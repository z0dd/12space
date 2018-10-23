<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ModulesToLessons
 * @package App
 */
class ModulesToLessons extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lessons()
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function modules()
    {
        return $this->belongsTo(Module::class);
    }
}
