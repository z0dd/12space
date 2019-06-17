<?php
/**
 * Created by PhpStorm.
 * User: z0dd
 * Date: 17.06.2019
 * Time: 8:18
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
            "username" => "required|string|max:255",
            "email" => "required|email|max:255",
            "phone" => "required|regex:/^[0-9]{4,15}$/",
        ];
    }
}

