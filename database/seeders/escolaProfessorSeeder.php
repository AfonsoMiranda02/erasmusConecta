<?php

namespace Database\Seeders;

use App\Models\escolaProfessor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class escolaProfessorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        escolaProfessor::insert([
            ['escola_id' => 1, 'professor_id' => 2, 'disciplina_id' => 1],
            ['escola_id' => 1, 'professor_id' => 2, 'disciplina_id' => 2],
        ]);
    }
}
