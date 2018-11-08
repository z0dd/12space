<?php

namespace App;

/**
 * Class Module
 * @package App
 *
 * @OA\Schema(
 *   schema="Module",
 *   type="object",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/Module"),
 *      @OA\Schema(
 *          required={"name","course_id"},
 *          @OA\Property(property="id", type="integer"),
 *          @OA\Property(property="course_id", type="integer"),
 *          @OA\Property(property="name", type="string"),
 *          @OA\Property(property="sort_order", type="integer"),
 *          @OA\Property(property="created_at", format="timestamp", type="string"),
 *          @OA\Property(property="updated_at", format="timestamp", type="string")
 *      )
 *   }
 * )
 */
class Module extends ModelExtender
{
    /**
     * @var array
     */
    protected $fillable = [
        'course_id',
        'name',
        'sort_order'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function lessons()
    {
        return $this->hasManyThrough(Lesson::class,ModulesToLessons::class, 'module_id', 'id', 'id', 'lesson_id');
    }

    /**
     * @return array
     */
    public function rules() :array
    {
        return [
            'course_id' => 'required|integer|min:1',
            'name' => 'required|string|min:3|max:250',
            'sort_order' => 'integer|min:1'
        ];
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeWithDefaultRelations($query)
    {
        return $query->with(['lessons']);
    }
}
