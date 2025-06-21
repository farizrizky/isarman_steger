<?php

namespace Database\Seeders;

use App\Models\Cash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class InitialSetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // Clear existing roles and permissions
        DB::table('model_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('permissions')->truncate();
        DB::table('roles')->truncate();
        DB::table('users')->truncate();

        // Create roles and permissions
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
            'approve_rent'
        ];

        foreach ($permission as $perm){
            Permission::create(['name' => $perm]);
        }

        $rolePermissions = [
            'Owner' => ['user', 'item', 'purchase', 'stock', 'rent', 'approve_rent','finance', 'repair'],
            'Direktur' => ['user', 'item', 'purchase', 'stock', 'rent', 'approve_rent', 'finance', 'repair'],
            'Staff' => ['rent']
        ];

        foreach($rolePermissions as $role => $permissions) {
            $roleInstance = Role::findByName($role);
            $roleInstance->givePermissionTo($permissions);
        }

        // Create the initial user with Owner role
        User::factory()->create([
            'name' => 'isarman',
            'fullname' => 'Isarman',
            'phone' => '081234567890',
            'email' => 'owner@isarmansteger.com',
            'password' => bcrypt('steger2025#')
        ])->assignRole('Owner');
        
        // Create the initial cash
        Cash::create([
            'cash_initial_balance' => 0,
            'cash_balance' => 0,
        ]);
    }
}
