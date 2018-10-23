<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FileTypes
 * @package App
 */
class FileTypes extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lessonContent()
    {
        return $this->hasMany(LessonContent::class);
    }
}
