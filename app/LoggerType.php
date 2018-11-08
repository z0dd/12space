<?php

namespace App;

/**
 * Class LoggerType
 * @package App
 *
 * @OA\Schema(
 *   schema="LoggerType",
 *   type="object",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/LoggerType"),
 *      @OA\Schema(
 *          required={"name"},
 *          @OA\Property(property="id", type="integer"),
 *          @OA\Property(property="name", type="string"),
 *          @OA\Property(property="created_at", format="timestamp", type="string"),
 *          @OA\Property(property="updated_at", format="timestamp", type="string")
 *      )
 *   }
 * )
 */
class LoggerType extends ModelExtender
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs()
    {
        return $this->hasMany(Logger::class);
    }

    /**
     * @return array
     */
    public function rules() :array
    {
        return [
            'name' => 'required|string|min:3|max:250',
        ];
    }
}
