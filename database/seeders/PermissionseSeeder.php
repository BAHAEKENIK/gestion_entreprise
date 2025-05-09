<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()

    {

        $permissions = [

           'role-list',

           'role-create',

           'role-edit',

           'role-delete',
            'user-list',
            'user-create',
             'user-edit',
            'user-delete',
        ];



        foreach ($permissions as $permission) {

             Permission::create(['name' => $permission]);

        }
        $user = User::create([

            'name' => 'Bahae Kenikssi',

            'email' => 'bahaekenik@gmail.com',

            'password' => bcrypt('123456')

        ]);



        $role = Role::create(['name' => 'directeur']);
        Role::create(['name'=>'employe']);

        $permissions = Permission::pluck('id','id')->all();



        $role->syncPermissions($permissions);



        $user->assignRole([$role->id]);


    }





}
