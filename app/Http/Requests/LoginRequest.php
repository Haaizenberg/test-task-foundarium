<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Определить, уполномочен ли пользователь выполнить этот запрос.
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'email' => [ 'bail', 'required', 'email:rfc,dns', 'max:255' ],
            'password' => [ 'bail', 'required', 'string', 'max:255' ],
        ];
    }
}
