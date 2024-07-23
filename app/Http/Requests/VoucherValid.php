<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VoucherValid extends FormRequest
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
            'code' => 'required|string|max:50|unique:vouchers,code',
            'number' => 'required|numeric|min:1',
            'count_voucher' => 'required|integer|min:1',
            // 'status' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên voucher là bắt buộc.',
            'name.string' => 'Tên voucher phải là chuỗi ký tự.',
            'name.max' => 'Tên voucher không được vượt quá 255 ký tự.',
            'code.required' => 'Mã giảm giá là bắt buộc.',
            'code.string' => 'Mã giảm giá phải là chuỗi ký tự.',
            'code.max' => 'Mã giảm giá không được vượt quá 50 ký tự.',
            'code.unique' => 'Mã giảm giá này đã tồn tại.',
            'number.required' => 'Giảm giá là bắt buộc.',
            'number.numeric' => 'Giảm giá phải là số.',
            'number.min' => 'Giảm giá phải lớn hơn 0.',
            'count_voucher.required' => 'Số lượng là bắt buộc.',
            'count_voucher.integer' => 'Số lượng phải là số nguyên.',
            'count_voucher.min' => 'Số lượng phải lớn hơn 0.',
            // 'status.required' => 'Trạng thái là bắt buộc.',
            // 'status.boolean' => 'Trạng thái không hợp lệ.',
        ];
    }
}
