<?php
/**
 * Created by PhpStorm.
 * User: z0dd
 * Date: 02.08.2019
 * Time: 11:17
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

/**
 * Class UserHashAuth
 *
 * @package App
 */
class UserHashAuth extends Model
{
    /**
     * @var string
     */
    protected $table = 'user_hash_auth';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'hash'];

    /**
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
