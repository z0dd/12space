<?php
/**
 * Created by PhpStorm.
 * User: z0dd
 * Date: 30.07.2019
 * Time: 7:17
 */

namespace App\Http\Requests;


use App\User;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UserRegisterRequest
 *
 * @package App\Http\Requests
 */
class UserRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return (new User)->rules();
    }
}
