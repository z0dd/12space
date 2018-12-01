<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Story
 * @package App
 *
 * @OA\Schema(
 *   schema="Story",
 *   type="object",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/Story"),
 *      @OA\Schema(
 *          required={"type_id","name","gender_id","template_id","text"},
 *          @OA\Property(property="id", type="integer"),
 *          @OA\Property(property="name", type="string"),
 *          @OA\Property(property="sort_order", type="integer"),
 *          @OA\Property(property="content", type="array", @OA\Items(ref="#/components/schemas/StoryContent")),
 *          @OA\Property(property="created_at", format="timestamp", type="string"),
 *          @OA\Property(property="updated_at", format="timestamp", type="string"),
 *      )
 *   }
 * )
 */
class Story extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function content()
    {
        return $this->hasMany(StoryContent::class);
    }
}
