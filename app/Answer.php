<?php

namespace App;

/**
 * Class Answer
 * @package App
 *
 * @OA\Schema(
 *   schema="Answer",
 *   type="object",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/Answer"),
 *      @OA\Schema(
 *          required={"question_id", "text"},
 *          @OA\Property(property="id", type="integer"),
 *          @OA\Property(property="question_id", type="integer"),
 *          @OA\Property(property="text", type="string"),
 *          @OA\Property(property="sort_order", type="integer"),
 *          @OA\Property(property="created_at", format="timestamp", type="string"),
 *          @OA\Property(property="updated_at", format="timestamp", type="string")
 *      )
 *   }
 * )
 */
class Answer extends ModelExtender
{
    /**
     * @var array
     */
    protected $fillable = [
        'question_id',
        'text',
        'sort_order',
    ];

    /**
     * @var array
     */
    protected $presentRelations = ['question'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
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
            'question_id' => 'required|integer|min:1',
            'text' => 'required|string|min:1',
            'sort_order' => 'integer|min:1|max:1000',
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function tags()
    {
        return $this->hasManyThrough(
            Tag::class,
            TagToAnswer::class,
            'answer_id',
            'id',
            'id',
            'tag_id'
        );
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeWithDefaultRelations($query)
    {
        return $query->with(['question']);
    }
}
