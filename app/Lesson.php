<?php

namespace App;

/**
 * Class Lesson
 * @package App
 */
class Lesson extends ModelExtender
{
    /**
     * @var array
     */
    protected $fillable = [
        'type_id',
        'name',
        'description',
        'gender_id',
        'template_id',
        'published_at',
        'status',
        'sort_order',
        'text',
    ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function modules()
    {
        return $this->hasManyThrough(Module::class, ModulesToLessons::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return  $this->belongsTo(LessonType::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tests()
    {
        return $this->hasMany(Test::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contents()
    {
        return $this->hasMany(LessonContent::class);
    }

    /**
     * @return array
     */
    public function rules() :array
    {
        return [
            'type_id' => 'required|integer|min:1',
            'name' => 'required|string|min:3|max:250',
            'description' => 'string|min:3|max:250',
            'gender_id' => 'required|integer|min:1',
            'template_id' => 'required|integer|min:1',
            'published_at' => 'date',
            'status' => 'integer|min:1',
            'sort_order' => 'integer|min:1',
            'text' => 'required|string',
        ];
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeWithDefaultRelations($query)
    {
        return $query->with(['tests','contents']);
    }
}
