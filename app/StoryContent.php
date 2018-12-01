<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Class StoryContent
 * @package App
 *
 * @OA\Schema(
 *   schema="StoryContent",
 *   type="object",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/StoryContent"),
 *      @OA\Schema(
 *          required={"type_id","name","gender_id","template_id","text"},
 *          @OA\Property(property="id", type="integer"),
 *          @OA\Property(property="story_id", type="integer"),
 *          @OA\Property(property="file", type="string"),
 *          @OA\Property(property="sort_order", type="integer"),
 *          @OA\Property(property="created_at", format="timestamp", type="string"),
 *          @OA\Property(property="updated_at", format="timestamp", type="string"),
 *          @OA\Property(property="viewed", type="boolean"),
 *      )
 *   }
 * )
 */
class StoryContent extends Model
{
    protected $fillable = ['name', 'file'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function users()
    {
        return $this->hasManyThrough(
            User::class,
            StoryContentToUser::class,
            'story_contents_id',
            'id',
            'id',
            'user_id'
        );
    }

    /**
     * @param User $user
     * @return bool
     */
    public function checkViewed(User $user)
    {
        return (bool) $this->users()->find($user->id);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function getFileAttribute($value)
    {
        return Storage::url($value);
    }
}
