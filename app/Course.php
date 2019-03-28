<?php

namespace App;

/**
 * Class Course
 * @package App
 *
 * @OA\Schema(
 *   schema="Course",
 *   type="object",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/Course"),
 *      @OA\Schema(
 *          required={"name", "price", "status_id"},
 *          @OA\Property(property="id", type="integer"),
 *          @OA\Property(property="name", type="string"),
 *          @OA\Property(property="price", format="double", type="number"),
 *          @OA\Property(property="status_id", type="integer"),
 *          @OA\Property(property="image", type="string"),
 *          @OA\Property(property="created_at", format="timestamp", type="string"),
 *          @OA\Property(property="updated_at", format="timestamp", type="string")
 *      )
 *   }
 * )
 */
class Course extends ModelExtender
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'price',
        'status_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function modules()
    {
        return $this->hasMany(Module::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(CourseStatus::class);
    }

    /**
     * @return array
     */
    public function rules() :array
    {
        return [
            'name' => 'required|string|min:3|max:250',
            'price' => 'required|digits_between:0,2|min:0|',
            'status_id' => 'required|min:1|integer',
            'image' => 'string',
        ];
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeWithDefaultRelations($query)
    {
        return $query->with(['modules.lessons']);
    }
}
