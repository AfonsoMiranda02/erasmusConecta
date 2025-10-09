<?php

namespace Database\Seeders;

use App\Models\escolas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class escolasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        escolas::insert([
            [
                'nome' => 'Escola Superior de Tecnologia e Gestão',
                'morada' => 'Viana do Castelo',
                'codigo_postal' => '4900-000',
                'instituicao' => 'IPVC',
                'abreviacao' => 'ESTG',
            ],
            [
                'nome' => 'Escola Superior de Educação',
                'morada' => 'Viana do Castelo',
                'codigo_postal' => '4900-001',
                'instituicao' => 'IPVC',
                'abreviacao' => 'ESE',
            ],
        ]);
    }
}
