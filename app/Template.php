<?php

namespace App;

/**
 * Class Template
 * @package App
 */
class Template extends ModelExtender
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'path',
        'data',
    ];
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
            'name' => 'required|string|min:3|max:250',
            'path' => 'required|string',
            'data' => 'string',
        ];
    }
}
