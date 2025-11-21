<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\PermissionRegistrar;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = ['Administrador','Professor','Estudante','Intercambista'];

        $roleMap = [
            'A' => 'Administrador',
            'P' => 'Professor',
            'E' => 'Estudante',
            'I' => 'Intercambista',
        ];      

        foreach ($roles as $roleName) {
            SpatieRole::firstOrCreate(['name' => $roleName]);
        }

        foreach ($this->addUsers() as $userData) {

            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'nome'         => $userData['nome'],
                    'num_processo' => $userData['num_processo'],
                    'password'     => $userData['password'],
                    'is_active'    => $userData['is_active'],
                    'is_aprovado'  => $userData['is_aprovado'],
                ]
            );
        
            $prefix = strtoupper(substr($user->num_processo, 0, 1));
        
            if (isset($roleMap[$prefix])) {
                $user->assignRole($roleMap[$prefix]);
            }
        }

        foreach ($this->addPermissions() as $permName => $rolesAllowed) {
            $perm = Permission::firstOrCreate(['name' => $permName]);

            foreach ($rolesAllowed as $roleName) {
                $role = SpatieRole::where('name', $roleName)->first();
                if ($role) {
                    $role->givePermissionTo($perm);
                }
            }
        }
    }

    protected function addUsers(): array
    {
        return [
            ['nome' => 'Admin','email' => 'admin@ipvc.pt','num_processo' => 'A001','password' => Hash::make('009'),'is_active' => 1,'is_aprovado' => 1],
            ['nome' => 'João','email' => 'joao.silva@ipvc.pt','num_processo' => 'P001','password' => Hash::make('009'),'is_active' => 1,'is_aprovado' => 1],
            ['nome' => 'Maria','email' => 'maria.sousa@ipvc.pt','num_processo' => 'E001','password' => Hash::make('009'),'is_active' => 1,'is_aprovado' => 1],
            ['nome' => 'Ana','email' => 'ana.intl@gmail.com','num_processo' => 'I001','password' => Hash::make('009'),'is_active' => 1,'is_aprovado' => 1],
            ['nome' => 'Jaime','email' => 'jaimito@gmail.com','num_processo' => 'P002','password' => Hash::make('009'),'is_active' => 1,'is_aprovado' => 1],
        ];
    }

    protected function addPermissions(): array
    {
        return [
            'Codigo-Mobilidade_Submit'              =>['Intercambista'],
            'Validar_Intercambistas'                =>['Administrador'],
            //Users
            'Users_Create'                          =>['Administrador'],
            'Users_Edit'                            =>['Administrador'],
            'Users_View'                            =>['Administrador'],
            'Users_Active-Deactive'                 =>['Administrador'],
            'Users_Arquive'                         =>['Administrador'],
            'Users_Arquive_View'                    =>['Administrador'],
            'Users_Aprove'                          =>['Administrador'],
            //PDFs
            'PDF_Create'                            =>['Administrador'],
            'PDF_Submit'                            =>['Administrador','Intercambista'],
            'PDF_View'                              =>['Administrador'],
            //Logs
            'Logs_View'                             =>['Administrador'],
            //Documents
            'Docs_View'                             =>['Administrador'],
            //Notificações
            'Notifs_View'                           =>['Administrador'],
            //Atividades
            'Atividades_Create'                     =>['Administrador','Professor','Estudante'],
            'Atividades_Edit'                       =>['Administrador','Professor','Estudante'],
            'Atividades_View'                       =>['Administrador','Professor','Estudante','Intercambista'],
            'Atividades_Arquive'                    =>['Administrador','Professor','Estudante'],
            'Atividades_Arquive_View'               =>['Administrador','Professor','Estudante'],
            'Atividades_Aprove'                     =>['Administrador'],
            //Convites
            'Convites_Create'                       =>['Administrador','Professor','Estudante'],
            'Convites_Edit'                         =>['Administrador','Professor','Estudante'],
            'Convites_View'                         =>['Administrador','Professor','Estudante','Intercambista'],
            'Convites_Arquive'                      =>['Administrador','Professor','Estudante'],
            'Convites_Arquive_View'                 =>['Administrador','Professor','Estudante'],
            'Convites_Aprove'                       =>['Administrador'],
            //Inscrições
            'Inscricao_Create'                      =>['Intercambista'],
            'Inscricao_Edit'                        =>['Administrador','Professor','Estudante'],
            'Inscricao_View'                        =>['Administrador','Professor','Estudante','Intercambista'],
            'Inscricao_Arquive'                     =>['Administrador','Professor','Estudante'],
            'Inscricao_Arquive_View'                =>['Administrador','Professor','Estudante'],
            'Inscricao_Aprove'                      =>['Administrador'],
            'Inscricaoes_Cancelar'                  =>['Intercambista'],
            //Arquivos
            'Arquivo_Edit'                          =>['Administrador'],
            'Arquivo_View'                          =>['Administrador'],
            //Escolas
            'Escola_Create'                         =>['Administrador'],
            'Escola_Edit'                           =>['Administrador'],
            'Escola_View'                           =>['Administrador'],
            'Escola_Arquive'                        =>['Administrador'],
            //Cursos
            'Curso_Create'                          =>['Administrador'],
            'Curso_Edit'                            =>['Administrador'],
            'Curso_View'                            =>['Administrador'],
            'Curso_Arquive'                         =>['Administrador'],
            //Disciplinas   
            'Disciplina_Create'                     =>['Administrador'],
            'Disciplina_Edit'                       =>['Administrador'],
            'Disciplina_View'                       =>['Administrador'],
            'Disciplina_Arquive'                    =>['Administrador'],
        ];
    }
}
