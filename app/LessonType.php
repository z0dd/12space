<?php

namespace App;

/**
 * Class LessonType
 * @package App
 */
class LessonType extends ModelExtender
{
    /**
     * @var array
     */
    protected $fillable = ['name'];

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
