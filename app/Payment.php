<?php
/**
 * Created by PhpStorm.
 * User: z0dd
 * Date: 17.06.2019
 * Time: 8:01
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

/**
 * Class Payment
 *
 * @package App
 */
class Payment extends Model
{
    /**
     * @var string
     */
    protected $table = 'payments';
    /**
     * @var array
     */
    protected $fillable = ['username','phone','email','status','details','yandex_kassa_id'];

    const STATUS = [
        'error' => 0,
        'pending' => 1,
        'waiting_for_capture' => 2,
        'succeeded' => 3,
        'canceled' => 4
    ];
}
