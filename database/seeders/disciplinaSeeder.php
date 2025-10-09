<?php

namespace Database\Seeders;

use App\Models\disciplina;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class disciplinaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        disciplina::insert([
            ['nome' => 'Programação Web', 'sigla' => 'PW'],
            ['nome' => 'Bases de Dados', 'sigla' => 'BD'],
        ]);
    }
}
