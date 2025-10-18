<?php

namespace Database\Seeders;

use App\Models\presencas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class presencasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        presencas::insert([
            [
                'user_id'               => 1,
                'evento_id'             =>1,
                'is_user_presente'      =>1,
                'obs'                   =>'Apareceu',
                'submited_by'            =>1,
                'data_fim_atividade'    =>carbon::now()
            ],

            [   
                'user_id'               =>1,
                'evento_id'             =>1,
                'is_user_presente'      =>0,
                'obs'                   =>'Não Apareceu',
                'submited_by'            =>1,
                'data_fim_atividade'    =>carbon::now()
            ],
        ]);
    }
}
