<?php

namespace App;

/**
 * Class LessonType
 * @package App
 *
 * @OA\Schema(
 *   schema="LessonType",
 *   type="object",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/LessonType"),
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
class LessonType extends ModelExtender
{
    /**
     * @var array
     */
    protected $fillable = ['name'];

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
            'name' => 'required|string|min:1|max:250',
        ];
    }
}
