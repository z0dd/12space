<?php
/**
 * Created by PhpStorm.
 * User: z0dd
 * Date: 11.08.2019
 * Time: 20:54
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

/**
 * Class PasswordReminder
 *
 * @package App
 */
class PasswordReminder extends Model
{
    /**
     * @var string
     */
    protected $table = 'password_resets';
    /**
     * @var string
     */
    protected $primaryKey = 'email';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class,'email','email');
    }
}
