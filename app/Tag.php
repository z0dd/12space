<?php

namespace App;

/**
 * Class Tag
 * @package App
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
