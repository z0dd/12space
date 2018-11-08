<?php

namespace App;

/**
 * Class FileTypes
 * @package App
 *
 * @OA\Schema(
 *   schema="FileTypes",
 *   type="object",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/FileTypes"),
 *      @OA\Schema(
 *          required={"name", "mimes"},
 *          @OA\Property(property="id", type="integer"),
 *          @OA\Property(property="name", type="string"),
 *          @OA\Property(property="mimes", type="string"),
 *          @OA\Property(property="created_at", format="timestamp", type="string"),
 *          @OA\Property(property="updated_at", format="timestamp", type="string")
 *      )
 *   }
 * )
 */
class FileTypes extends ModelExtender
{
    /**
     * @var array
     */
    protected $fillable = [
        'name', 'mimes',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lessonContent()
    {
        return $this->hasMany(LessonContent::class);
    }

    /**
     * @return array
     */
    public function rules() :array
    {
        return [
            'name' => 'required|string|min:3|max:250',
            'mimes' => 'required|string',
        ];
    }
}
