<?php

namespace App;

/**
 * Class Answer
 * @package App
 */
/**
 * Class Answer
 * @package App
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
}
