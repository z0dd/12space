<?php

namespace App;

use App\Exceptions\ApiException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\Messages\MailMessage;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 * @package App
 *
 * @OA\Schema(
 *   schema="User",
 *   type="object",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/User"),
 *      @OA\Schema(
 *          required={"name","last_name", "email", "phone", "gender_id","account_id"},
 *          @OA\Property(property="id", type="integer"),
 *          @OA\Property(property="name", type="string"),
 *          @OA\Property(property="last_name", type="string"),
 *          @OA\Property(property="email", format="email", type="string"),
 *          @OA\Property(property="password", type="password", writeOnly="true"),
 *          @OA\Property(property="phone", type="string"),
 *          @OA\Property(property="gender_id", type="integer"),
 *          @OA\Property(property="account_id", type="integer"),
 *          @OA\Property(property="created_at", format="timestamp", type="string"),
 *          @OA\Property(property="updated_at", format="timestamp", type="string")
 *      )
 *   }
 * )
 */
class User extends \TCG\Voyager\Models\User implements ApiModelInterface
{
    use HasApiTokens, Notifiable;

    /**
     * @var
     */
    private $lessons;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','last_name','phone','gender_id','account_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
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
    public function passedTests()
    {
        return $this->hasMany(PassedTest::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function history()
    {
        return $this->hasMany(Logger::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function courses()
    {
        return $this->hasManyThrough(
            Course::class,
            UserToCourse::class,
            'user_id',
            'id',
            'id',
            'course_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userCourses()
    {
        return $this->hasMany(UserToCourse::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userModules()
    {
        return $this->hasMany(UserToModule::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function modules()
    {
        return $this->hasManyThrough(
            Module::class,
            UserToModule::class,
            'user_id',
            'id',
            'id',
            'module_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userHash()
    {
        return $this->hasOne(UserHashAuth::class);
    }

    /**
     * @return array
     */
    public function rules() :array
    {
        return [
            'name' => 'required|string|min:3|max:250',
            'last_name' => 'required|string|min:3|max:250',
            'email' => 'required|email|min:3|max:250',
            'password' => 'confirmed',
            'phone' => 'required|regex:/^((7)+([0-9]){10})$/i',
            'gender_id' => 'required|integer|min:1',
            'account_id' =>  'required|integer|min:1',
        ];
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeWithDefaultRelations($query)
    {
        return $query->with(['gender','account']);
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->name} {$this->last_name}";
    }

    /**
     * @return mixed
     * @throws ApiException
     */
    public function currentLesson()
    {
        $lastPassedTest = $this->getLastPassedTest();

        if (false == $lastPassedTest) {
            // Возвращаем первый урок
            return $this->activeModule()->lessons->first();
        }

        $nextLesson = $lastPassedTest->nextLesson();

        if (false === $lastPassedTest->nextLessonConditionsSuccess()) {
            $nextLesson->status = Lesson::LESSON_STATUSES['not_available'];
        }

        if (is_null($nextLesson)) {
            throw new ApiException('Все уроки пройдены');
        }

        if (false === $nextLesson) {
            throw new ApiException('Ошибка при получении следующего урока');
        }

//        $nextLesson->published_at = $lastPassedTest->created_at->addDays(config('settings.days_between_lessons'))->format('Y-m-d H:i:s');

        $nextLesson->attachStatus($this)->attachPublish($this);

        return $nextLesson;
    }

    /**
     * @return array
     * @throws ApiException
     */
    public function parsedCourses()
    {
        $courses = [];

        if ($this->courses->isEmpty()) {
            throw new ApiException('Нет доступных курсов');
        }

        $userLessons = $this->lessons();

        foreach ($this->userCourses as $userCourse) {
            $courseStatus = array_search($userCourse->status, UserToCourse::STATUSES);

            $userCourse->course->load('modules.lessons');

            if ($userCourse->course && false == $userCourse->course->modules->isEmpty()){
                // Перебираем модули вложенные в курс
                foreach ($userCourse->course->modules as &$module) {
                    if ($module->lessons->isEmpty()) {
                        continue;
                    }

                    $lessons = [];

                    // Перебираем уроки вложенные в модуль
                    foreach ($module->lessons as $key => &$lesson) {
                        // Фильтруем уроки которые есть в курсе. Оставляем только те, что могут быть показаны пльзоватетелю
                        if ($userLessons->filter(function ($userLesson) use ($lesson) {
                            return $userLesson->id == $lesson->id;
                        })->isNotEmpty()) {
                            // Собираем ID всех уроков которые должны быть возвращены, что бы потом перезапросить их из БД.
                            $lessons[] = $lesson->id;
                        }
                    }

                    $module->load(['lessons' => function ($q) use ($lessons) {$q->whereIn('lessons.id', $lessons);}]);
                    foreach ($module->lessons as &$lesson) {
                        $lesson->attachStatus($this)->attachPublish($this);
                    }
                }
            }

            if (empty($courses[$courseStatus])) {
                $courses[$courseStatus] = $userCourse->course;
            } else {
                $courses[$courseStatus][] = $userCourse->course;
            }
        }
        return $courses;
    }

    /**
     * @return Course
     * @throws ApiException
     */
    public function activeCourse()
    {
        $userToCourse = UserToCourse::findCourseInCollection($this->userCourses, UserToCourse::STATUSES['in_progress']);

        if (empty($userToCourse)) {
            $userToCourse = UserToCourse::findCourseInCollection($this->userCourses, UserToCourse::STATUSES['available']);
        }

        if (empty($userToCourse)) {
            throw new ApiException('Нет доступных курсов');
        }

        return $userToCourse->course;
    }

    /**
     * @return Module
     * @throws ApiException
     */
    public function activeModule()
    {
        $userToModule = UserToModule::findModuleInCollection($this->userModules, UserToModule::STATUSES['in_progress']);

        if (empty($userToModule)) {
            $userToModule = UserToModule::findModuleInCollection($this->userModules, UserToModule::STATUSES['available']);
        }

        if (empty($userToModule)) {
            throw new ApiException('Нет доступных модулей');
        }

        return $userToModule->module;
    }

    /**
     * @return mixed
     * @throws ApiException
     */
    public function lessons()
    {
        if (empty($this->lessons)) {

            $tags = $this->getPassedTags();

            $this->lessons = $this->activeModule()->lessons->filter(function ($lesson) use ($tags) {
                if (false == $lesson->relationLoaded('tags')) {
                    $lesson->load('tags');
                }

                // Если нет пройденных тестов то берем только уроки без тега
                if (empty($tags)) {
                    return $lesson->tags->isEmpty();
                }

                // Уроки без тегов возвращаем всегда
                if ($lesson->tags->isEmpty())
                    return true;

                // Перебираем теги ответов и уроков, возвращаем совпадающие
                if ($lesson->tags->isNotEmpty()) {
                    foreach ($tags as $answerTag) {
                        foreach ($lesson->tags as $lessonTag) {
                            if ($answerTag['id'] == $lessonTag->id) {
                                return true;
                            }
                        }
                    }
                }

                return false;
            });
        }

        return $this->lessons;
    }

    /**
     * @return mixed
     */
    public function getLastPassedTest()
    {
        return PassedTest::where('user_id', $this->id)
            ->orderBy('id', 'desc')
            ->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function AauthAcessToken(){
        return $this->hasMany(OauthAccessToken::class);
    }

    /**
     * @return \Illuminate\Support\Collection|null
     */
    public function getPassedTags()
    {
        if (empty($this->passedTests) || $this->passedTests->isEmpty())
            return null;

        $tags = [];

        foreach ($this->passedTests as $passedTest) {
            if ($passedTest->answer->tags->isNotEmpty())
                $tags = array_merge($tags, $passedTest->answer->tags->toArray());
        }

        return collect($tags);
    }

    public function getAttachedUserToModuleByLesson(Lesson $lesson)
    {
        $module = null;
        foreach ($this->modules as $userModule) {
            foreach ($lesson->modules as $lessonModule) {
                if ($userModule->id == $lessonModule->id)
                    $module = $lessonModule;
            }
        }
        return UserToModule::where('user_id', $this->id)
            ->where('module_id',$module->id)->first();
    }
}
