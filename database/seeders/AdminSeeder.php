<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Account;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create or update the Super Admin user
        $user = User::updateOrCreate(
            ['email' => 'mywbpanel@gmail.com'],
            [
                'name' => 'superadmin',
                'mobile' => '01774444000',
                'login_mobile' => '01774444000',
                'password' => Hash::make('Dinajpur@2021'),
                'role' => 'superadmin',
                'status' => 1,
            ]
        );

        // Create or update the Admin user
        $user1 = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'admin',
                'mobile' => '01774444001',
                'login_mobile' => '01774444001',
                'password' => Hash::make('admin#123'),
                'role' => 'admin',
                'status' => 1,
                'admin_id' => 1,
            ]
        );
        
        // Create or update the Admin user
        Account::updateOrCreate(
            ['account_number' => '01774444000'],
            [
                'account_name' => 'Main Account',
                'account_holder_name' => 'Admin',
                'account_number' => '01774444000',
                'account_balance' => 0,
                'status' => 1,
                'user_id' => 1,
            ]
        );

        // Fetch or create roles
        $superadminRole = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        
        // Fetch all permissions
        $allPermissions = Permission::where('guard_name', 'web')->get();

        // Assign all permissions to superadmin
        $superadminRole->syncPermissions($allPermissions);
        $user->syncRoles($superadminRole);

        // Assign permissions to admin (you can customize the permissions assigned to admin if needed)
        $adminPermissions = Permission::where('guard_name', 'web')->get();
        $adminRole->syncPermissions($adminPermissions);
        $user1->syncRoles($adminRole);
    }
}
