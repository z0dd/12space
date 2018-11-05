<?php

namespace App;

/**
 * Class Module
 * @package App
 */
class Module extends ModelExtender
{
    /**
     * @var array
     */
    protected $fillable = [
        'course_id',
        'name',
        'sort_order'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function lessons()
    {
        return $this->hasManyThrough(Lesson::class,ModulesToLessons::class, 'module_id', 'id', 'id', 'lesson_id');
    }

    /**
     * @return array
     */
    public function rules() :array
    {
        return [
            'course_id' => 'required|integer|min:1',
            'name' => 'required|string|min:3|max:250',
            'sort_order' => 'integer|min:1'
        ];
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeWithDefaultRelations($query)
    {
        return $query->with(['lessons']);
    }
}
