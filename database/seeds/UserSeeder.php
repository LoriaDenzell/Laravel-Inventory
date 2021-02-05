<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Facades\DB;
use Illuminate\Database\Str;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class UserSeeder extends Seeder
{
    public function run() //php artisan db:seed --class=UserSeeder
    {
        $user = User::create([
            'first_name'=>'Admin',
            'last_name'=>'Account',
            'gender'=>'M',
            'birthdate'=>'1997-09-18',
            'user_contact'=>'0955701405',
            'email'=>'automaticemailsystem2020@gmail.com',
            'status'=> 1,
            'password'=>Hash::make('admin123')
        ]);

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        //Create Permissions
        Permission::create(['name' => 'view dashboard']);
        Permission::create(['name' => 'add product']);
        Permission::create(['name' => 'edit product']);
        Permission::create(['name' => 'add category']);
        Permission::create(['name' => 'edit category']);
        Permission::create(['name' => 'view category']);
        Permission::create(['name' => 'add purchase']);
        Permission::create(['name' => 'edit purchase']);
        Permission::create(['name' => 'add sales']);
        Permission::create(['name' => 'edit sales']);
        Permission::create(['name' => 'view sales']);
        Permission::create(['name' => 'add stock']);
        Permission::create(['name' => 'edit stock']);
        Permission::create(['name' => 'add addon']);
        Permission::create(['name' => 'edit addon']);
        Permission::create(['name' => 'add cms']);
        Permission::create(['name' => 'edit cms']);

        //Create Role and give permissions to the Admin Role
        $roleAdmin = Role::create(['name' => 'A']);

        $roleAdmin->givePermissionTo('view dashboard');
        $roleAdmin->givePermissionTo('add product');
        $roleAdmin->givePermissionTo('edit product');
        $roleAdmin->givePermissionTo('add category');
        $roleAdmin->givePermissionTo('edit category');
        $roleAdmin->givePermissionTo('view category');
        $roleAdmin->givePermissionTo('add purchase');
        $roleAdmin->givePermissionTo('edit purchase');
        $roleAdmin->givePermissionTo('add sales');
        $roleAdmin->givePermissionTo('edit sales');
        $roleAdmin->givePermissionTo('view sales');
        $roleAdmin->givePermissionTo('add stock');
        $roleAdmin->givePermissionTo('edit stock');
        $roleAdmin->givePermissionTo('add addon');
        $roleAdmin->givePermissionTo('edit addon');
        $roleAdmin->givePermissionTo('add cms');
        $roleAdmin->givePermissionTo('edit cms');

        //Create Role and give permissions to the Employee Role
        $roleEmployee = Role::create(['name' => 'E']);
        $roleEmployee->givePermissionTo('view dashboard');
        $roleEmployee->givePermissionTo('view category');
        $roleEmployee->givePermissionTo('add purchase');
        $roleEmployee->givePermissionTo('add sales');
        $roleEmployee->givePermissionTo('view sales');
        $roleEmployee->givePermissionTo('add stock');

        $user->assignRole('A');
    }
}
