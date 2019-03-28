<?php

namespace App;

/**
 * Class Question
 * @package App
 *
 * @OA\Schema(
 *   schema="Question",
 *   type="object",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/Question"),
 *      @OA\Schema(
 *          required={"test_id","text"},
 *          @OA\Property(property="id", type="integer"),
 *          @OA\Property(property="test_id", type="integer"),
 *          @OA\Property(property="text", type="string"),
 *          @OA\Property(property="required", type="boolean"),
 *          @OA\Property(property="sort_order", type="integer"),
 *          @OA\Property(property="created_at", format="timestamp", type="string"),
 *          @OA\Property(property="updated_at", format="timestamp", type="string"),
 *          @OA\Property(property="timecode", type="integer")
 *      )
 *   }
 * )
 */
class Question extends ModelExtender
{
    /**
     * @var array
     */
    protected $fillable = [
        'test_id',
        'text',
        'required',
        'sort_order',
    ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function passedTests()
    {
        return $this->hasMany(PassedTest::class);
    }

    /**
     * @return array
     */
    public function rules() :array
    {
        return [
            'test_id' => 'required|integer|min:1',
            'text' => 'required|string|min:1',
            'required' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeWithDefaultRelations($query)
    {
        return $query->with(['answers']);
    }
}
