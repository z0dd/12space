<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Template
 * @package App
 */
class Template extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}
