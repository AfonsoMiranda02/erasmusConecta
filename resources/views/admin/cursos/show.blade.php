@extends('layouts.admin')

@section('title', 'Detalhes do Curso')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <a 
                href="{{ route('admin.cursos.index') }}" 
                class="text-sm text-gray-500 hover:text-gray-700 mb-2 inline-flex items-center"
            >
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Voltar aos cursos
            </a>
            <h1 class="text-2xl font-semibold text-gray-800 mt-2">{{ $curso->nome }}</h1>
            <p class="mt-1 text-sm text-gray-500">Detalhes do curso</p>
        </div>
        <div class="flex items-center space-x-2">
            <a 
                href="{{ route('admin.cursos.edit', $curso->id) }}" 
                class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
            >
                Editar
            </a>
        </div>
    </div>

    <!-- Informações do Curso -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h2 class="text-base font-semibold text-gray-800 mb-4">Informações</h2>
        <div>
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Nome</p>
            <p class="mt-1 text-sm text-gray-900">{{ $curso->nome }}</p>
        </div>
    </div>

    <!-- Disciplinas -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h2 class="text-base font-semibold text-gray-800 mb-4">Disciplinas</h2>
        @if($curso->disciplinas->isEmpty())
            <p class="text-sm text-gray-500">Não há disciplinas associadas a este curso.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($curso->disciplinas as $disciplina)
                <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <p class="text-sm font-medium text-gray-900">{{ $disciplina->nome }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $disciplina->sigla }}</p>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Alunos -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h2 class="text-base font-semibold text-gray-800 mb-4">Alunos</h2>
        @if($curso->alunos->isEmpty())
            <p class="text-sm text-gray-500">Não há alunos associados a este curso.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aluno</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Escola</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($curso->alunos as $alunoCurso)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $alunoCurso->aluno->nome ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $alunoCurso->escola->nome ?? 'N/A' }}
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

