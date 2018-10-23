<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Test
 * @package App
 */
class Test extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function passed()
    {
        return $this->hasMany(PassedTest::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function answers()
    {
        return $this->hasManyThrough(Answer::class, Question::class);
    }
}
