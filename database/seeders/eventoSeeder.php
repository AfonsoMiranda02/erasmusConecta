<?php

namespace Database\Seeders;

use App\Models\evento;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class eventoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        evento::insert([
            [
                'titulo' => 'Passeio ao Centro Histórico',
                'tipo_id' => 1,
                'descricao' => 'Visita guiada pelo centro de Viana.',
                'data_hora' => '2525-11-01 10:00:00',
                'local' => 'Praça da República',
                'capacidade' => 30,
                'created_by' => 2,
                'aprovado_por' => 1,
                'status' => 'aprovado',
            ],
            [
                'titulo' => 'Workshop de Programação Web',
                'tipo_id' => 3,
                'descricao' => 'Sessão prática com Laravel e Tailwind.',
                'data_hora' => '2525-11-01 10:00:00',
                'local' => 'Sala 201 ESTG',
                'capacidade' => 25,
                'created_by' => 2,
                'aprovado_por' => 1,
                'status' => 'aprovado',
            ],
        ]);
    }
}
