<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Answer
 * @package App
 */
class Answer extends Model implements ApiModelInterface
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
}
