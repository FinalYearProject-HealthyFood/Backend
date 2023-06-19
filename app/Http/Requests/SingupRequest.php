<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SingupRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|same:password',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên là bắt buộc.',
            'name.string' => 'Tên phải là chuỗi.',
            'email.required' => 'Email là bắt buộc.',
            'email.unique' => 'Email này đã được sử dụng.',
            'email.email' => 'Email phải là email hợp lệ.',
            'password.required' => 'password là bắt buộc.',
            'password.string' => 'password phải là chuỗi.',
            'password.min' => 'password phải lớn hơn 6 ký tự.',
            'password_confirmation.required' => 'password confirmation là bắt buộc.',
            'password_confirmation.same' => 'password confirmation phải giống với password.',
        ];
    }
}
