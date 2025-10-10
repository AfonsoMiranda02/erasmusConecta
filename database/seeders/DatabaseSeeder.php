<?php

namespace Database\Seeders;

use App\Models\codigoMobilidade;
use App\Models\disciplina;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Model::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // call the seeders
        $this->call([
            UserSeeder::class,
            escolasSeeder::class,
            disciplinaSeeder::class,
            cursoSeeder::class,
            cursoDisciplinaSeeder::class,
            escolaProfessorSeeder::class,
            escolaAlunoSeeder::class,
            codigoMobilidadeSeeder::class,
            tipoSeeder::class,
            eventoSeeder::class,
            inscricaoSeeder::class,
            conviteSeeder::class,
            documentoSeeder::class,
            logSeeder::class,
            notificacaoSeeder::class,
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        Model::reguard();
    }
}
