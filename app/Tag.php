<?php

namespace App;

/**
 * Class Tag
 * @package App
 *
 * @OA\Schema(
 *   schema="Tag",
 *   type="object",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/Tag"),
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
class Tag extends ModelExtender
{
    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function answers()
    {
        return $this->hasManyThrough(
            Answer::class,
            TagToAnswer::class,
            'tag_id',
            'id',
            'id',
            'answer_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function lessons()
    {
        return $this->hasManyThrough(
            Lesson::class,
            TagToLesson::class,
            'tag_id',
            'id',
            'id',
            'lesson_id'
        );
    }
}
