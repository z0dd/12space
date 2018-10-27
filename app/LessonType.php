<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class LessonType
 * @package App
 */
class LessonType extends Model implements ApiModelInterface
{
    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    /**
     * @return array
     */
    public function rules() :array
    {
        return [
            'name' => 'required|string|min:1|max:250',
        ];
    }
}
