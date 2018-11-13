<?php

namespace App;

/**
 * Class LessonContent
 * @package App
 *
 * @OA\Schema(
 *   schema="LessonContent",
 *   type="object",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/LessonContent"),
 *      @OA\Schema(
 *          required={"lesson_id","name","mime","file_type_id","gender_id"},
 *          @OA\Property(property="id", type="integer"),
 *          @OA\Property(property="lesson_id", type="integer"),
 *          @OA\Property(property="name", type="string"),
 *          @OA\Property(property="file", format="binary", type="string"),
 *          @OA\Property(property="mime", type="string"),
 *          @OA\Property(property="file_type_id", type="integer"),
 *          @OA\Property(property="gender_id", type="integer"),
 *          @OA\Property(property="link", type="string"),
 *          @OA\Property(property="created_at", format="timestamp", type="string"),
 *          @OA\Property(property="updated_at", format="timestamp", type="string")
 *      )
 *   }
 * )
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
        'link',
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
            'link' => 'string|min:1',
        ];
    }
}
