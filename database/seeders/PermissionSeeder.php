<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

// database/seeders/PermissionSeeder.php
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Company permissions
        Permission::firstOrCreate(['name' => 'create company']);
        Permission::firstOrCreate(['name' => 'update company']);
        Permission::firstOrCreate(['name' => 'delete company']);
        Permission::firstOrCreate(['name' => 'view company']);

        // User permissions
        Permission::firstOrCreate(['name' => 'create user']);
        Permission::firstOrCreate(['name' => 'update user']);
        Permission::firstOrCreate(['name' => 'delete user']);
        Permission::firstOrCreate(['name' => 'view user']);
    }
}

