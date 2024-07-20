<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginValid extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|ends_with:.com',
            'password' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'email.required' => 'Bạn chưa nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'email.ends_with' => 'Email phải có đuôi .com.',
            'password.required' => 'Bạn chưa nhập mật khẩu.',
        ];
    }
}
