<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
        $rules = [
            'nome' => ['required', 'string', 'max:255', 'unique:users,nome'],
            'email' => ['required', 'email', 'string', 'max:255', 'unique:users,email', 'ends_with:@ipvc.pt'],
            'num_processo' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'tipo' => ['required', 'string', Rule::in(['estudante', 'professor', 'intercambista'])],
        ];

        // Validação para intercambista (código ou documento)
        if ($this->input('tipo') === 'intercambista') {
            $rules['codigo_mobilidade'] = ['nullable', 'string', 'max:255'];
            $rules['documento'] = ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120']; // 5MB max
            
            // Pelo menos um dos dois deve ser preenchido
            $this->merge([
                'has_codigo_or_document' => !empty($this->input('codigo_mobilidade')) || $this->hasFile('documento')
            ]);
        }

        return $rules;
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->input('tipo') === 'intercambista') {
                if (empty($this->input('codigo_mobilidade')) && !$this->hasFile('documento')) {
                    $validator->errors()->add('codigo_mobilidade', 'Deve fornecer um código de mobilidade ou um documento comprovativo.');
                    $validator->errors()->add('documento', 'Deve fornecer um código de mobilidade ou um documento comprovativo.');
                }
            }
        });
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.unique' => 'Este nome já está em uso.',
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Por favor, insira um e-mail válido.',
            'email.unique' => 'Este e-mail já está registado.',
            'email.ends_with' => 'Deves usar um e-mail institucional (@ipvc.pt).',
            'num_processo.required' => 'O campo número de processo é obrigatório.',
            'password.required' => 'O campo palavra-passe é obrigatório.',
            'password.min' => 'A palavra-passe deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'A confirmação da palavra-passe não corresponde.',
            'tipo.required' => 'Deve selecionar um tipo de utilizador.',
            'tipo.in' => 'Tipo de utilizador inválido.',
            'documento.file' => 'O documento deve ser um ficheiro válido.',
            'documento.mimes' => 'O documento deve ser um ficheiro PDF, JPG, JPEG ou PNG.',
            'documento.max' => 'O documento não pode exceder 5MB.',
        ];
    }
}
