<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SickValid extends FormRequest
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
            'name' => 'required|string|max:255',
            'symptom' => 'required|string|max:255',
            'description' => 'required|string',
            // 'hot' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Bạn chưa nhập tên căn bệnh.',
            'name.max' => 'Quá ký tự cho phép.',
            'symptom.required' => 'Bạn chưa nhập triệu chứng.',
            'symptom.max' => 'Quá ký tự cho phép.',
            'description.required' => 'Bạn chưa nhập mô tả',
            // 'hot.required' => 'Bạn chưa chọn trạng thái nổi bật.',
            // 'hot.boolean' => 'Trạng thái nổi bật không hợp lệ.',
        ];
    }
}
