<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Account
 * @package App
 */
class Account extends Model implements ApiModelInterface
{
    /**
     * @var array
     */
    protected $fillable = ['name'];

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
