<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Criar roles
        $roles = ['aluno', 'supervisor', 'diretor', 'administrador'];
        
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // Criar permissões
        $permissions = [
            'ver-dashboard',
            'analisar-evasao',
            'gerenciar-alertas',
            'gerenciar-usuarios',
            'gerenciar-turmas',
            'gerenciar-cursos',
            'ver-relatorios',
            'exportar-dados',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Atribuir permissões aos roles
        $roleAdmin = Role::findByName('administrador');
        $roleAdmin->givePermissionTo(Permission::all());

        $roleDiretor = Role::findByName('diretor');
        $roleDiretor->givePermissionTo([
            'ver-dashboard',
            'analisar-evasao',
            'gerenciar-alertas',
            'ver-relatorios',
        ]);

        $roleSupervisor = Role::findByName('supervisor');
        $roleSupervisor->givePermissionTo([
            'ver-dashboard',
            'ver-relatorios',
        ]);

        $roleAluno = Role::findByName('aluno');
        $roleAluno->givePermissionTo([
            'ver-dashboard',
            'exportar-dados',
        ]);
    }
}
