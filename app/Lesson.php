<?php

namespace App;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * Class Lesson
 * @package App
 *
 * @OA\Schema(
 *   schema="Lesson",
 *   type="object",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/Lesson"),
 *      @OA\Schema(
 *          required={"type_id","name","gender_id","template_id","text"},
 *          @OA\Property(property="id", type="integer"),
 *          @OA\Property(property="type_id", type="integer"),
 *          @OA\Property(property="name", type="string"),
 *          @OA\Property(property="description", type="string"),
 *          @OA\Property(property="text", type="string"),
 *          @OA\Property(property="gender_id", type="integer"),
 *          @OA\Property(property="template_id", type="integer"),
 *          @OA\Property(property="duration", type="integer"),
 *          @OA\Property(property="image", type="string"),
 *          @OA\Property(property="status", type="integer", description="0-not_available, 1-available, 2-passed, 3-closed"),
 *          @OA\Property(property="sort_order", type="integer"),
 *          @OA\Property(property="published_at", type="string"),
 *          @OA\Property(property="created_at", format="timestamp", type="string"),
 *          @OA\Property(property="updated_at", format="timestamp", type="string"),
 *      )
 *   }
 * )
 */
class Lesson extends ModelExtender
{
    const LESSON_STATUSES = [
        'not_available' => 0,
        'available' => 1,
        'passed' => 2,
        'closed' => 3,
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'type_id',
        'name',
        'description',
        'gender_id',
        'template_id',
        'published_at',
        'status',
        'sort_order',
        'text',
        'image',
    ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function getImageAttribute($value)
    {
        return Storage::url($value);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function modules()
    {
        return $this->hasManyThrough(
            Module::class,
            ModulesToLessons::class,
            'lesson_id',
            'id',
            'id',
            'module_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return  $this->belongsTo(LessonType::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tests()
    {
        return $this->hasMany(Test::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contents()
    {
        return $this->hasMany(LessonContent::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function tags()
    {
        return $this->hasManyThrough(
            Tag::class,
            TagToLesson::class,
            'lesson_id',
            'id',
            'id',
            'tag_id'
        );
    }

    /**
     * @return array
     */
    public function rules() :array
    {
        return [
            'type_id' => 'required|integer|min:1',
            'name' => 'required|string|min:3|max:250',
            'description' => 'string|min:3|max:250',
            'gender_id' => 'required|integer|min:1',
            'template_id' => 'required|integer|min:1',
            'published_at' => 'date',
            'status' => 'integer|min:1',
            'sort_order' => 'integer|min:1',
            'text' => 'required|string',
            'duration' => 'integer|min:1',
            'image' => 'string',
        ];
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeWithDefaultRelations($query)
    {
        return $query->with(['tests','contents']);
    }

    public function getNextLesson()
    {
        $module = $this->modules->first();

        return $module
            ->lessons
            ->where('sort_order','>=',$this->sort_order)
            ->where('id', '!=', $this->id)
            ->sortBy('sort_order')
            ->first();
    }

    /**
     * @param User $user
     * @return $this
     */
    public function attachStatus(User $user)
    {
        $lesson_id = $this->id;
        if (false == $user->passedTests->isEmpty()) {
            // Если текущий урок уже пройден
            $filtered = $user->passedTests->filter(function ($passedTest) use ($lesson_id) {
                return $passedTest->test->lesson->id == $lesson_id;
            });

            if (false == $filtered->isEmpty()) {
                $this->status = self::LESSON_STATUSES['passed'];

                return $this;
            }

            // Если текущий урок должен быть следующим
            $passedTest = $user->passedTests->last();
            $nextLesson = $passedTest->nextLesson();

            if ($nextLesson && $nextLesson->id == $this->id) {
                $this->status = $passedTest->nextLessonConditionsSuccess()
                    ? self::LESSON_STATUSES['available']
                    : self::LESSON_STATUSES['not_available'];

                return $this;
            }

            // Если это не следующий урок, не пройденный и не закрытый админом,
            // то ставим ему статус - не доступен
            if ($nextLesson
                && $nextLesson->id != $this->id
                && $this->status != self::LESSON_STATUSES['passed']
                && $this->status != self::LESSON_STATUSES['closed']
            ) {
                $this->status = self::LESSON_STATUSES['not_available'];
            }
        }

        return $this;
    }

    /**
     * @return array|mixed
     */
    public function renderContents()
    {
        if($this->contents->isEmpty()) {
            return [];
        }

        foreach ($this->contents as $content) {
            $content->render();
        }

        return $this->contents;
    }

    /**
     * @param User $user
     * @return $this
     * @throws Exceptions\ApiException
     */
    public function attachPublish(User $user)
    {
        $publishDateFormat = 'Y-m-d 00:00';
        $prevLesson = $this->getPrevLessonByUser($user);

        // Рассчет количетва дней между текущим уроком и предыдущим.
        // Берется разница в значениях sort_order. Если разница меньше 0, то отдается значение по умолчанию (из конфига).
        // Если предыдущего урока нет, используется 0

        $daysToLesson = intval($this->sort_order) - ( $prevLesson ? intval($prevLesson->sort_order) : 0);
        if ($daysToLesson < 0) {
            $daysToLesson = config('settings.days_between_lessons');
        }

        // Если есть предыдущий урок
        if ($prevLesson) {
            $passedTest = $prevLesson->getPassedTestByUser($user);
            // Если по предшествующему уроку есть пройденный тест,
            // то дату публикации текущего урока рассчитываем от даты создания того теста
            if ($passedTest) {
                $this->published_at = $passedTest->created_at->addDays($daysToLesson)->format($publishDateFormat);
            } else {
                // Если предидущий урок не пройден, то рассчитываем дату публикации этого урока от даты публикации предидущего
                $this->published_at =
                    (new Carbon($prevLesson->attachPublish($user)->published_at))
                        ->addDays($daysToLesson)
                        ->format($publishDateFormat);
            }
        } else {
            // Если нет предыдущих уроков, рассчитываем дату публикации от созания пользователя
            // или связи пользователя с модулем если она есть
            $userModule = $user->getAttachedUserToModuleByLesson($this);
            if (is_null($userModule)) {
                $this->published_at = $user->created_at->format($publishDateFormat);
            } else {
                $this->published_at = $userModule->created_at->format($publishDateFormat);
            }
        }

        if ($this->status === self::LESSON_STATUSES['passed']) {
            foreach ($user->passedTests as $passedTest) {
                if ($passedTest->test->lesson_id == $this->id) {
                    $this->passed_days = $passedTest->updated_at->diff(Carbon::parse($this->published_at))->days;
                }
            }
        }

        return $this;
    }

    /**
     * @param User $user
     *
     * @return PassedTest|null
     */
    public function getPassedTestByUser(User $user) :? PassedTest
    {
        $passedTests = $user->passedTests()->with('test.lesson')->get();
        foreach ($passedTests as $passedTest) {
            if ($passedTest->test->lesson->id == $this->id) {
                return $passedTest;
            }
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function getPrevLesson()
    {
        $tag = $this->tags()->first();

        $lesson = Lesson::where('sort_order','<',$this->sort_order)
            ->orderBy('sort_order','desc');

        if (!is_null($tag)) {
            $lesson->join('tag_to_lessons', function ($q) use ($tag) {
                $q->on('tag_to_lessons.lesson_id','lessons.id')
                    ->where(function ($q) use ($tag) {
                        $q->where('tag_to_lessons.tag_id', $tag->id)
                            ->orWhereNull('tag_to_lessons.tag_id');
                    });

            })
                ->select('lessons.*');
        }

        return $lesson->first();
    }

    /**
     * @param User $user
     * @return Lesson|null
     * @throws Exceptions\ApiException
     */
    public function getPrevLessonByUser(User $user) :? Lesson
    {
        $prev = null;
        foreach ($user->lessons() as $lesson) {
            if ($lesson->id == $this->id){
                return $prev;
            }
            $prev = $lesson;
        }
        return $prev;
    }
}
