<?php

namespace App;

/**
 * Class CourseStatus
 * @package App
 */
class CourseStatus extends ModelExtender
{
    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    /**
     * @return array
     */
    public function rules() :array
    {
        return [
            'name' => 'required|string|min:3|max:250',
        ];
    }
}
