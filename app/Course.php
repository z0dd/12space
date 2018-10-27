<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Course
 * @package App
 */
class Course extends Model implements ApiModelInterface
{
    protected $fillable = [
        'name',
        'price',
        'status_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function modules()
    {
        return $this->hasMany(Module::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(CourseStatus::class);
    }

    public function rules() :array
    {
        return [
            'name' => 'required|string|min:3|max:250',
            'price' => 'required|digits_between:0,2|min:0|',
            'status_id' => 'required|min:1|integer',
        ];
    }
}
