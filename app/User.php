<?php

namespace App;

use App\Exceptions\ApiException;
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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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
     * @return mixed
     * @throws ApiException
     */
    public function currentLesson()
    {
        $lastPassedTest = PassedTest::where('user_id', $this->id)
            ->orderBy('created_at', 'desc')
            ->first();

        if (false == $lastPassedTest) {
            // Возвращаем первый урок
            return Lesson::firstLesson();
        }

        if (false === $lastPassedTest->nextLessonConditionsSuccess()) {
            throw new ApiException('Доступ к следующему уроку пока закрыт',400);
        }

        $nextLesson = $lastPassedTest->nextLesson();

        if (is_null($nextLesson)) {
            throw new ApiException('Все уроки пройдены');
        }

        if (false === $nextLesson) {
            throw new ApiException('Ошибка при получении следующего урока');
        }

        return $nextLesson;
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
}
