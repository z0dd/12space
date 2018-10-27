<?php

namespace App;

/**
 * Class PassedTest
 * @package App
 */
class PassedTest extends ModelExtender
{
    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'test_id',
        'question_id',
        'answer_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    /**
     * @return array
     */
    public function rules() :array
    {
        return [
            'user_id' => 'required|integer|min:1',
            'test_id' => 'required|integer|min:1',
            'question_id' => 'required|integer|min:1',
            'answer_id' => 'required|integer|min:1',
        ];
    }
}
