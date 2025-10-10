<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\users;
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
        ];
    }

    protected function addPermissions(): array
    {
        return [
            'show_eventos'                  => ['Administrador','Professor','Estudante','Intercambista'],
            'create_eventos'                => ['Administrador','Professor'],
            'edit_eventos'                  => ['Administrador','Professor'],
            'arquive_eventos'               => ['Administrador'],
            'validate_eventos'              => ['Administrador'],
            'convite_eventos'               => ['Administrador','Professor','Estudante'],
            'convite_accept'                => ['Administrador','Intercambista'],
            'convite_show'                  => ['Administrador','Intercambista'],
            'convite_arquive'               => ['Administrador','Professor','Estudante'],//Sendo que o Professor e o Estudante apenas podem ver os convites que ele fizeram e estão arquivados
        ];
    }
}
