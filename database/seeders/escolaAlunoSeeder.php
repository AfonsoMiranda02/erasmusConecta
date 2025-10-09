<?php

namespace Database\Seeders;

use App\Models\escolaAluno;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class escolaAlunoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        escolaAluno::insert([
            ['escola_id' => 1, 'curso_id' => 1, 'aluno_id' => 3],
        ]);
    }
}
