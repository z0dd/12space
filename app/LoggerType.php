<?php

namespace App;

/**
 * Class LoggerType
 * @package App
 */
class LoggerType extends ModelExtender
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs()
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
        ];
    }
}
