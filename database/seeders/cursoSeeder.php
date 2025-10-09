<?php

namespace Database\Seeders;

use App\Models\curso;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class cursoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        curso::insert([
            ['nome' => 'CTeSP TPSI'],
            ['nome' => 'Engenharia Informática'],
        ]);
    }
}
