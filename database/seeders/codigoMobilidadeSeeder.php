<?php

namespace Database\Seeders;

use App\Models\codigoMobilidade;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class codigoMobilidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        codigoMobilidade::insert([
            ['user_id' => 4, 'cod_mobilidade' => 'MOB2025-INTL-001', 'is_validado' => 1],
        ]);
    }
}
