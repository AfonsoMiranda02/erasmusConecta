<?php

namespace Database\Seeders;

use App\Models\notificacoes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class notificacaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        notificacoes::insert([
            [
                'user_id' => 4,
                'morph_type' => 'evento',
                'morph_id' => 1,
                'titulo' => 'Novo Evento Disponível',
                'mensagem' => 'Foi aprovado o evento "Passeio ao Centro Histórico".',
                'is_seen' => 0,
            ],
        ]);
    }
}
