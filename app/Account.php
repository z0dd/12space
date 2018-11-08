<?php

namespace App;

/**
 * Class Account
 * @package App
 *
 * @OA\Schema(
 *  schema="Account",
 *  type="object",
 *  allOf={
 *      @OA\Schema(ref="#/components/schemas/Account"),
 *      @OA\Schema(
 *          required={"name"},
 *          @OA\Property(property="id", type="integer"),
 *          @OA\Property(property="name", type="string"),
 *          @OA\Property(property="created_at", format="timestamp", type="string"),
 *          @OA\Property(property="updated_at", format="timestamp", type="string")
 *      )
 *   }
 * )
 */
class Account extends ModelExtender
{
    /**
     * @var array
     */
    protected $fillable = ['name'];

    protected $presentRelations = [];

    /**
     *
     */
    public function users()
    {
        $this->hasMany(User::class);
    }

    /**
     * @return array
     */
    public function rules() :array
    {
        return [
            'name' => 'string|required|min:3|max:250'
        ];
    }
}
