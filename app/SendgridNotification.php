<?php
/**
 * Created by PhpStorm.
 * User: z0dd
 * Date: 18.06.2019
 * Time: 16:35
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

/**
 * Class SendgridNotification
 *
 * @package App
 */
class SendgridNotification extends Model
{
    /**
     * @var string
     */
    protected $table = 'sendgrid_notifications';
    /**
     * @var array
     */
    protected $fillable = ['email','x_message_id','status','user_id','template_id'];
}
