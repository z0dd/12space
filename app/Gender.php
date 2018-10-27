<?php

namespace App;

/**
 * Class Gender
 * @package App
 */
class Gender extends ModelExtender
{
    /**
     * @var array
     */
    protected $fillable = ['name'];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lessonContents()
    {
        return $this->hasMany(LessonContent::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    /**
     * @return array
     */
    public function rules() :array
    {
        return [
            'name' => 'required|string|min:1|max:250',
        ];
    }
}
