<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class LoggerType
 * @package App
 */
class LoggerType extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs()
    {
        return $this->hasMany(Logger::class);
    }
}
