<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Module
 * @package App
 */
class Module extends Model
{
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
        return $this->hasManyThrough(Lesson::class,ModulesToLessons::class);
    }
}
