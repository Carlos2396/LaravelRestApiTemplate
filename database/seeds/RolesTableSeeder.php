<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::create(['guard_name' => 'api', 'name' => 'admin']);
        $user = Role::create(['guard_name' => 'api', 'name' => 'user']);
    }
}
