<?php

namespace Database\Seeders;
  
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
  
class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
           'role-list',
           'role-create',
           'role-edit',
           'role-delete',
           'permission-list',
           'permission-create',
           'permission-edit',
           'permission-delete',
           'product-list',
           'product-create',
           'product-edit',
           'product-delete',
           'Order-Item',
           'Manage Order',
           'user-list',
           'Manage Order Create',
           'Manage Order Delete',
           'Manage Order Edit',
           'Manage Order status',
           'Dashboard',
           'Manage Countries',
           'Manage Cities',
           'Demo Excel',
           'Import',
        ];
        
        foreach ($permissions as $permission) {
             Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
    }
}