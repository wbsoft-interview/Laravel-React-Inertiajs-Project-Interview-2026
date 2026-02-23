<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create(['name' => 'superadmin', 'display_name' => 'superadmin']);
        $role = Role::create(['name' => 'admin', 'display_name' => 'admin']); 
        
        // $role = Role::create(['name' => 'superadmin','guard_name'=>'api']);
        // $role = Role::create(['name' => 'admin','guard_name'=>'api']);   
        // $role = Role::create(['name' => 'manager','guard_name'=>'api']);   
        // $role = Role::create(['name' => 'staff','guard_name'=>'api']);   
        // $role = Role::create(['name' => 'user','guard_name'=>'api']);
    }
}
