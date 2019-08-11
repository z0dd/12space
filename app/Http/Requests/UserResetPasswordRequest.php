<?php
/**
 * Created by PhpStorm.
 * User: z0dd
 * Date: 11.08.2019
 * Time: 20:28
 */

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UserResetPasswordRequest
 *
 * @package App\Http\Requests
 */
class UserResetPasswordRequest extends FormRequest
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
        return [
            'token' => 'required|regex:/[^0-9a-A]/i',
            'email' => 'required|email|min:3|max:250',
            'password' => 'required|confirmed',
        ];
    }
}
