<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Logger
 * @package App
 */
class Logger extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(LoggerType::class);
    }
}
