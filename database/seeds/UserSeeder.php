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
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
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

        $user->assignRole('A');

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

        $roleEmployee = Role::create(['name' => 'E']);
        $roleEmployee->givePermissionTo('view dashboard');
        $roleEmployee->givePermissionTo('view category');
        $roleEmployee->givePermissionTo('add purchase');
        $roleEmployee->givePermissionTo('add sales');
        $roleEmployee->givePermissionTo('view sales');
        $roleEmployee->givePermissionTo('add stock');
    }
}
