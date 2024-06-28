<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostValid extends FormRequest
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
            'title' => ['required'],
            'id_cate' => ['required'],
        ];
    }
    public function messages() {
        return [
            'title.required' => 'Bạn phải nhập tiêu đề',
            'id_cate.required' => 'Bạn phải chọn chuyên mục'
        ];
    }
}
