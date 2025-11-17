@extends('layouts.admin')

@section('title', 'Detalhes da Escola')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <a 
                href="{{ route('admin.escolas.index') }}" 
                class="text-sm text-gray-500 hover:text-gray-700 mb-2 inline-flex items-center"
            >
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Voltar às escolas
            </a>
            <h1 class="text-2xl font-semibold text-gray-800 mt-2">{{ $escola->nome }}</h1>
            <p class="mt-1 text-sm text-gray-500">Detalhes da escola</p>
        </div>
        <div class="flex items-center space-x-2">
            <a 
                href="{{ route('admin.escolas.edit', $escola->id) }}" 
                class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
            >
                Editar
            </a>
        </div>
    </div>

    <!-- Informações da Escola -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h2 class="text-base font-semibold text-gray-800 mb-4">Informações</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Nome</p>
                <p class="mt-1 text-sm text-gray-900">{{ $escola->nome }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Instituição</p>
                <p class="mt-1 text-sm text-gray-900">{{ $escola->instituicao }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Morada</p>
                <p class="mt-1 text-sm text-gray-900">{{ $escola->morada }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Código Postal</p>
                <p class="mt-1 text-sm text-gray-900">{{ $escola->codigo_postal }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Abreviação</p>
                <p class="mt-1 text-sm text-gray-900">{{ $escola->abreviacao }}</p>
            </div>
        </div>
    </div>

    <!-- Professores -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h2 class="text-base font-semibold text-gray-800 mb-4">Professores</h2>
        @if($escola->professores->isEmpty())
            <p class="text-sm text-gray-500">Não há professores associados a esta escola.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Professor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Disciplina</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($escola->professores as $professorEscola)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $professorEscola->professor->nome ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $professorEscola->disciplina->nome ?? 'N/A' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Alunos -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h2 class="text-base font-semibold text-gray-800 mb-4">Alunos</h2>
        @if($escola->alunos->isEmpty())
            <p class="text-sm text-gray-500">Não há alunos associados a esta escola.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aluno</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Curso</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($escola->alunos as $alunoEscola)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $alunoEscola->aluno->nome ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $alunoEscola->curso->nome ?? 'N/A' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

