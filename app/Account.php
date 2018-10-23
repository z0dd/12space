<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Account
 * @package App
 */
class Account extends Model
{
    public function users()
    {
        $this->hasMany(User::class);
    }
}
