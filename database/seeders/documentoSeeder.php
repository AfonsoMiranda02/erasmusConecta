<?php

namespace Database\Seeders;

use App\Models\documentos;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class documentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        documentos::insert([
            [
                'morph_type' => 'evento',
                'morph_id' => 1,
                'file_name' => 'programa_evento.pdf',
                'file_path' => '/docs/eventos/programa_evento.pdf',
                'dh_entrega' => '2025-10-10 12:00:00',
                'entregue_por_user_id' => 2,
            ],
        ]);
    }
}
