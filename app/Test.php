<?php

namespace App;

/**
 * Class Test
 * @package App
 *
 * @OA\Schema(
 *   schema="Test",
 *   type="object",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/Test"),
 *      @OA\Schema(
 *          required={"name","lesson_id"},
 *          @OA\Property(property="id", type="integer"),
 *          @OA\Property(property="lesson_id", type="integer"),
 *          @OA\Property(property="sort_order", type="integer"),
 *          @OA\Property(property="name", type="string"),
 *          @OA\Property(property="created_at", format="timestamp", type="string"),
 *          @OA\Property(property="updated_at", format="timestamp", type="string")
 *      )
 *   }
 * )
 */
class Test extends ModelExtender
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'lesson_id',
        'sort_order'
    ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function passed()
    {
        return $this->hasMany(PassedTest::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function answers()
    {
        return $this->hasManyThrough(Answer::class, Question::class);
    }

    /**
     * @return array
     */
    public function rules() :array
    {
        return [
            'name' => 'required|string|min:3|max:250',
            'lesson_id' => 'required|integer|min:1',
            'sort_order' => 'integer',
        ];
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeWithDefaultRelations($query)
    {
        return $query->with(['questions']);
    }
}
