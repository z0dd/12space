<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CourseStatus
 * @package App
 */
class CourseStatus extends Model implements ApiModelInterface
{
    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    /**
     * @return array
     */
    public function rules() :array
    {
        return [
            'name' => 'required|string|min:3|max:250',
        ];
    }
}
