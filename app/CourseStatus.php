<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CourseStatus
 * @package App
 */
class CourseStatus extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
