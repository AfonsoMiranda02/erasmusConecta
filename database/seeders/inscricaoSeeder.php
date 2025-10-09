<?php

namespace Database\Seeders;

use App\Models\inscricao;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class inscricaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        inscricao::insert([
            [
                'evento_id' => 1,
                'user_id' => 4,
                'estado' => 'aprovada',
                'date_until_cancelation' => '2025-10-14 23:59:59',
                'is_active' => 1,
            ],
        ]);
    }
}
