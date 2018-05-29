<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_admin = Role::where('name', 'Admin')->first();
        //$role_manager  = Role::where('name', ‘manager’)->first();

        $admin = new User();
        $admin->name = 'Alcides Teixeira';
        $admin->email = 'alcides.mn.teixeira@gmail.com';
        $admin->password = bcrypt('tiptop');
        $admin->save();
        $admin->roles()->attach($role_admin);

        /*$manager = new User();
        $manager->name = ‘Manager Name’;
        $manager->email = ‘manager@example.com’;
        $manager->password = bcrypt(‘secret’);
        $manager->save();
        $manager->roles()->attach($role_manager);*/
    }
}
