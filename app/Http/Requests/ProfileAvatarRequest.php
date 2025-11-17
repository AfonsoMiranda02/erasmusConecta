<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileAvatarRequest extends FormRequest
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
            'avatar' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:2048'], // 2MB max
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'avatar.required' => 'Deve selecionar uma imagem.',
            'avatar.image' => 'O ficheiro deve ser uma imagem.',
            'avatar.mimes' => 'A imagem deve ser JPEG, JPG ou PNG.',
            'avatar.max' => 'A imagem não pode exceder 2MB.',
        ];
    }
}
