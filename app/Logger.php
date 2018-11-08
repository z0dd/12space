<?php

namespace App;

/**
 * Class Logger
 * @package App
 *
 * @OA\Schema(
 *   schema="History",
 *   type="object",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/History"),
 *      @OA\Schema(
 *          required={"user_id","log_type_id","message"},
 *          @OA\Property(property="id", type="integer"),
 *          @OA\Property(property="user_id", type="integer"),
 *          @OA\Property(property="status", type="integer"),
 *          @OA\Property(property="log_type_id", type="integer"),
 *          @OA\Property(property="message", type="string"),
 *          @OA\Property(property="created_at", format="timestamp", type="string"),
 *          @OA\Property(property="updated_at", format="timestamp", type="string")
 *      )
 *   }
 * )
 */
class Logger extends ModelExtender
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
