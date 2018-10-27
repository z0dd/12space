<?php

namespace App;

/**
 * Class Account
 * @package App
 */
class Account extends ModelExtender
{
    /**
     * @var array
     */
    protected $fillable = ['name'];

    protected $presentRelations = [];

    /**
     *
     */
    public function users()
    {
        $this->hasMany(User::class);
    }

    /**
     * @return array
     */
    public function rules() :array
    {
        return [
            'name' => 'string|required|min:3|max:250'
        ];
    }
}
