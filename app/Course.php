<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Course
 * @package App
 */
class Course extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function modules()
    {
        return $this->hasMany(Module::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(CourseStatus::class);
    }
}
