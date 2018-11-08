<?php

namespace App;

/**
 * Class Template
 * @package App
 *
 * @OA\Schema(
 *   schema="Template",
 *   type="object",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/Template"),
 *      @OA\Schema(
 *          required={"name","path"},
 *          @OA\Property(property="id", type="integer"),
 *          @OA\Property(property="name", type="string"),
 *          @OA\Property(property="path", type="string"),
 *          @OA\Property(property="data", type="string"),
 *          @OA\Property(property="created_at", format="timestamp", type="string"),
 *          @OA\Property(property="updated_at", format="timestamp", type="string")
 *      )
 *   }
 * )
 */
class Template extends ModelExtender
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'path',
        'data',
    ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    /**
     * @return array
     */
    public function rules() :array
    {
        return [
            'name' => 'required|string|min:3|max:250',
            'path' => 'required|string',
            'data' => 'string',
        ];
    }
}
