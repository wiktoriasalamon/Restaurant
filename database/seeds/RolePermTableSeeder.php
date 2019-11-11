<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rolePerm = config('rolePerm');
        $roles = config('roles');
        foreach ($roles as $roleName) {
            $role = Role::create(['name' => $roleName]);
            foreach ($rolePerm[$roleName] as $permissionName) {
                try {
                    Permission::create(['name' => $permissionName]);
                } catch (PermissionAlreadyExists $e) {

                }
                $role->givePermissionTo(Permission::findByName($permissionName));
            }
        }
    }
}
