<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class StoryContentToUser
 * @package App
 *
 * @OA\Schema(
 *   schema="StoryContentToUser",
 *   type="object",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/StoryContentToUser"),
 *      @OA\Schema(
 *          required={"type_id","name","gender_id","template_id","text"},
 *          @OA\Property(property="id", type="integer"),
 *          @OA\Property(property="user_id", type="integer"),
 *          @OA\Property(property="story_contents_id", type="integer"),
 *          @OA\Property(property="created_at", format="timestamp", type="string"),
 *          @OA\Property(property="updated_at", format="timestamp", type="string"),
 *      )
 *   }
 * )
 */
class StoryContentToUser extends Model
{
    protected $fillable = ['user_id','story_contents_id'];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contents()
    {
        return $this->belongsTo(StoryContent::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
