<?php

namespace Database\Seeders;

use App\Models\logs;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class logSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        logs::insert([
            [
                'log_name' => 'Criação de evento',
                'user_id' => 2,
                'morph_type' => 'evento',
                'morph_id' => 1,
                'action' => 'create',
            ],
        ]);
    }
}
