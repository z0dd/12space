<?php

namespace App;
use function foo\func;

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
 *          @OA\Property(property="status", type="integer", description="0-not_available, 1-available, 2-passed, 3-closed"),
 *          @OA\Property(property="sort_order", type="integer"),
 *          @OA\Property(property="published_at", type="string"),
 *          @OA\Property(property="created_at", format="timestamp", type="string"),
 *          @OA\Property(property="updated_at", format="timestamp", type="string")
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
    ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function template()
    {
        return $this->belongsTo(Template::class);
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
     * @return mixed
     */
    public static function firstLesson()
    {
        return Lesson::withDefaultRelations()->first();
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
        }



        return $this;
    }
}
