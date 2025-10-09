<?php

namespace Database\Seeders;

use App\Models\cursoDisciplina;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class cursoDisciplinaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        cursoDisciplina::insert([
            ['curso_id' => 1, 'disciplina_id' => 1],
            ['curso_id' => 1, 'disciplina_id' => 2],
            ['curso_id' => 2, 'disciplina_id' => 1],
        ]);
    }
}
