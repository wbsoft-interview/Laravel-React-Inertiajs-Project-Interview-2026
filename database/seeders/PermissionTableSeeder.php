<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
          [
              'group_name' => 'user-access',
              'permissions' => [
                'user-list',
                'user-create',
                'user-edit',
                'user-delete',
              ]
            ],
            [
              'group_name' => 'role-access',
              'permissions' => [
                'role-list',
                'role-create',
                'role-edit',
                'role-delete',
              ]
            ],
            [
              'group_name' => 'account-access',
              'permissions' => [
                'account-list',
                'account-create',
                'account-edit',
                'account-delete',
              ]
            ],
            [
              'group_name' => 'account-category-access',
              'permissions' => [
                'account-category-list',
                'account-category-create',
                'account-category-edit',
                'account-category-delete',
              ]
            ],
            [
              'group_name' => 'package-access',
              'permissions' => [
                'package-list',
                'package-create',
                'package-edit',
                'package-delete',
              ]
            ],
            [
              'group_name' => 'package-category-access',
              'permissions' => [
                'package-category-list',
                'package-category-create',
                'package-category-edit',
                'package-category-delete',
              ]
            ],
            [
              'group_name' => 'sms-template-access',
              'permissions' => [
                'sms-template-list',
                'sms-template-create',
                'sms-template-edit',
                'sms-template-delete',
              ]
            ],
            [
              'group_name' => 'notice-sms-access',
              'permissions' => [
                'notice-sms-list',
                'notice-sms-create',
                'notice-sms-edit',
                'notice-sms-delete',
              ]
            ],
            [
              'group_name' => 'push-notification-access',
              'permissions' => [
                'push-notification-list',
                'push-notification-create',
                'push-notification-edit',
                'push-notification-delete',
              ]
            ],
            [
              'group_name' => 'ticket-support-access',
              'permissions' => [
                'ticket-support-list',
                'ticket-support-create',
                'ticket-support-edit',
                'ticket-support-delete',
              ]
            ],
            [
              'group_name' => 'payee-access',
              'permissions' => [
                'payee-list',
                'payee-create',
                'payee-edit',
                'payee-delete',
              ]
            ],
            [
              'group_name' => 'expense-access',
              'permissions' => [
                'expense-list',
                'expense-create',
                'expense-edit',
                'expense-delete',
              ]
            ],
            [
              'group_name' => 'expense-category-access',
              'permissions' => [
                'expense-category-list',
                'expense-category-create',
                'expense-category-edit',
                'expense-category-delete',
              ]
            ],
            [
              'group_name' => 'expense-receipt-access',
              'permissions' => [
                'expense-receipt-list',
                'expense-receipt-create',
                'expense-receipt-edit',
                'expense-receipt-delete',
              ]
            ],
            [
                'group_name' => 'receiver-access',
                'permissions' => [
                    'receiver-list',
                    'receiver-create',
                    'receiver-edit',
                    'receiver-delete',
                ]
            ],
            [
                'group_name' => 'income-access',
                'permissions' => [
                    'income-list',
                    'income-create',
                    'income-edit',
                    'income-delete',
                ]
            ],
            [
                'group_name' => 'income-category-access',
                'permissions' => [
                    'income-category-list',
                    'income-category-create',
                    'income-category-edit',
                    'income-category-delete',
                ]
            ],
            [
                'group_name' => 'income-receipt-access',
                'permissions' => [
                    'income-receipt-list',
                    'income-receipt-create',
                    'income-receipt-edit',
                    'income-receipt-delete',
                ]
            ],
            [
              'group_name' => 'blog-category-access',
              'permissions' => [
                'blog-category-list',
                'blog-category-create',
                'blog-category-edit',
                'blog-category-delete',
              ]
            ],
            [
              'group_name' => 'blog-access',
              'permissions' => [
                'blog-list',
                'blog-create',
                'blog-edit',
                'blog-delete',
              ]
            ],
            [
              'group_name' => 'documentation-category-access',
              'permissions' => [
                'documentation-category-list',
                'documentation-category-create',
                'documentation-category-edit',
                'documentation-category-delete',
              ]
            ],
            [
              'group_name' => 'documentation-access',
              'permissions' => [
                'documentation-list',
                'documentation-create',
                'documentation-edit',
                'documentation-delete',
              ]
            ],
            [
              'group_name' => 'documentation-tag-access',
              'permissions' => [
                'documentation-tag-list',
                'documentation-tag-create',
                'documentation-tag-edit',
                'documentation-tag-delete',
              ]
            ],
            [
              'group_name' => 'zone-access',
              'permissions' => [
                'zone-list',
                'zone-create',
                'zone-edit',
                'zone-delete',
              ]
            ],
            [
              'group_name' => 'report-access',
              'permissions' => [
                'user-report-access',
              ]
            ],
            [
              'group_name' => 'setting-access',
              'permissions' => [
                'general-setting-access',
                'logo-setting-access',
                'photo-gallery-access',
                'sms-count-view-access',
              ]
            ],
        ];

        // Create Permissions
        foreach ($permissions as $permissionGroup) {
            foreach ($permissionGroup['permissions'] as $permissionName) {
                // Check if the permission already exists, if not, create it
                $permission = Permission::firstOrCreate(
                    ['name' => $permissionName, 'group_name' => $permissionGroup['group_name']],
                    ['guard_name' => 'web']
                );
            }
        }

    }
}
