<?php

namespace Database\Seeders;

use App\Models\roles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class roleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        roles::insert([
            ['nome' => 'Administrador'],
            ['nome' => 'Professor'],
            ['nome' => 'Estudante'],
            ['nome' => 'Intercambista'],
        ]);
    }
}
