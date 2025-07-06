<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('role_has_permissions')->truncate();
        DB::table('permissions')->truncate();
        DB::table('roles')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $roles = [
            'Owner',
            'Direktur',
            'Staff'
        ];
        
        foreach($roles as $role){
            Role::create(['name' => $role]);
        }

        $permission = [
            'user',
            'item',
            'purchase',
            'stock',
            'repair',
            'rent',
            'finance',
            'approve_rent',
            'price_list'
        ];

        foreach ($permission as $perm){
            Permission::create(['name' => $perm]);
        }

        $rolePermissions = [
            'Owner' => ['user', 'item', 'purchase', 'stock', 'rent', 'approve_rent','finance', 'repair', 'price_list'],
            'Direktur' => ['user', 'item', 'purchase', 'stock', 'rent', 'approve_rent', 'finance', 'repair', 'price_list'],
            'Staff' => ['rent', 'price_list']
        ];

        foreach($rolePermissions as $role => $permissions) {
            $roleInstance = Role::findByName($role);
            $roleInstance->givePermissionTo($permissions);
        }

    }
}
