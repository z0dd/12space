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
     * @var array
     */
    protected $fillable = [
        'user_id',
        'status',
        'log_type_id',
        'message',
    ];

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

    /**
     * @return array
     */
    public function rules() :array
    {
        return [
            'user_id' => 'required|integer|min:1',
            'status' => 'integer|min:1',
            'log_type_id' => 'required|integer|min:1',
            'message' => 'required|string|min:1|max:250',
        ];
    }
}
