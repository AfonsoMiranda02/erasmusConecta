<?php

namespace Database\Seeders;

use App\Models\convite;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class conviteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        convite::insert([
            [
                'sent_by' => 2,
                'evento_id' => 1,
                'for_user' => 4,
                'titulo' => 'Convite para Passeio Histórico',
                'descricao' => 'Gostaria de convidá-la para o passeio cultural.',
                'data_hora' => '2025-10-15 14:00:00',
                'estado' => 'pendente',
                'time_until_expire' => '2025-10-14 23:59:59',
            ],
        ]);
    }
}
