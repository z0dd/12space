<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Question
 * @package App
 */
class Question extends Model implements ApiModelInterface
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
}
