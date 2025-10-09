<?php

namespace Database\Seeders;

use App\Models\tipo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class tipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        tipo::insert([
            ['nome' => 'Cultural'],
            ['nome' => 'Social'],
            ['nome' => 'Académico'],
        ]);
    }
}
