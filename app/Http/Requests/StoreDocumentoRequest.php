<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        // Apenas estudantes em mobilidade podem submeter documentos
        return in_array($user->cargo, ['estudante', 'intercambista']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tipo_documento' => ['required', 'string', 'max:100'],
            'documento' => ['required', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:10240'], // 10MB max
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
            'tipo_documento.required' => 'O tipo de documento é obrigatório.',
            'documento.required' => 'É necessário selecionar um ficheiro.',
            'documento.file' => 'O ficheiro enviado não é válido.',
            'documento.mimes' => 'O ficheiro deve ser PDF, DOC, DOCX, JPG, JPEG ou PNG.',
            'documento.max' => 'O ficheiro não pode exceder 10MB.',
        ];
    }
}
