<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class LaratrustSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->truncateTables();
        // permission
        $roles = [
            'superadmin' => [
                'users' => ['create', 'read', 'update', 'delete'],
                'categories' => ['create', 'read', 'update', 'delete'],
                'products' => ['create', 'read', 'update', 'delete'],
                'transactions' => ['create', 'read', 'update'],
                'profile' => ['create','read','update'],
            ],
            'admin' => [
                'profile' => ['create','read','update'],
                'categories' => ['read'],
                'products' => ['read'],
                'transactions' => ['create', 'read'],
            ],
            'user' => [
                'profile' => ['create','read','update'],

            ],
        ];

        foreach($roles as $roleName => $modules){
            $role = Role::firstOrCreate([
                'name'  => $roleName,
                'display_name' => ucfirst($roleName),
                'description' => ucfirst($roleName),
            ]);

            $permissionIds = [];

            $this->command->info("Creating role {$roleName}");

            foreach($modules as $module => $actions){
                foreach($actions as $action){
                    $permission = Permission::firstOrCreate(
                        ['name' => "{$module}.{$action}"],
                        ['display_name' => ucfirst($action).' '.ucfirst($module),
                        'description' => ucfirst($action).' '.ucfirst($module),]
                    );

                    $permissionIds[] = $permission->id;
                    $this->command->info("Permission {$module}.{$action}");
                }
            }
            $role->syncPermissions($permissionIds);

            $user = User::firstOrCreate(
                [
                    'email' => "{$roleName}@app.com"
                ],
                [
                    'name' => ucfirst($roleName),
                    'password' => bcrypt('password')
                ]
            );

            $user->syncRoles([$role]);

        }
    }
    private function truncateTables(){
    Schema::disableForeignKeyConstraints();

    DB::table('permission_role')->truncate();
    DB::table('permission_user')->truncate();
    DB::table('role_user')->truncate();

    DB::table('permissions')->truncate();
    DB::table('roles')->truncate();

    Schema::enableForeignKeyConstraints();
    }
}
