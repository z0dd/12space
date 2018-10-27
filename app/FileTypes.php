<?php

namespace App;

/**
 * Class FileTypes
 * @package App
 */
class FileTypes extends ModelExtender
{
    /**
     * @var array
     */
    protected $fillable = [
        'name', 'mimes',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lessonContent()
    {
        return $this->hasMany(LessonContent::class);
    }

    /**
     * @return array
     */
    public function rules() :array
    {
        return [
            'name' => 'required|string|min:3|max:250',
            'mimes' => 'required|string',
        ];
    }
}
