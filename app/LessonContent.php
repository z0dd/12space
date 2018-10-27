<?php

namespace App;

/**
 * Class LessonContent
 * @package App
 */
class LessonContent extends ModelExtender
{
    /**
     * @var array
     */
    protected $fillable = [
        'lesson_id',
        'file',
        'name',
        'mime',
        'file_type_id',
        'gender_id',
    ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(FileTypes::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    /**
     * @return array
     */
    public function rules() :array
    {
        return [
            'lesson_id' => 'required|min:1|integer',
            'file' => 'file',
            'name' => 'required|string|min:3|max:250',
            'mime' => 'required|string|min:1|max:250',
            'file_type_id' => 'required|min:1|integer',
            'gender_id' => 'required|min:1|integer',
        ];
    }
}
