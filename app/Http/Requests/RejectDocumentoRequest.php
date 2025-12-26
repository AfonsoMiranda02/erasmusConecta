<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejectDocumentoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Apenas administradores podem rejeitar documentos
        return $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'mensagem_rejeicao' => ['required', 'string', 'min:10', 'max:1000'],
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
            'mensagem_rejeicao.required' => 'A mensagem de rejeição é obrigatória.',
            'mensagem_rejeicao.min' => 'A mensagem deve ter pelo menos 10 caracteres.',
            'mensagem_rejeicao.max' => 'A mensagem não pode exceder 1000 caracteres.',
        ];
    }
}
