<?php

namespace Database\Seeders;

use App\Models\MasterAdmin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1) buat profil master_admin
        $adminProfile = MasterAdmin::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'           => 'Super Admin',
                'phone_wa'       => '082126357645',
                'jabatan'        => 'Super Admin',
                'is_super_admin' => true,
                'is_active'      => true,
            ]
        );

        // 2) buat user login di sys_user
        $user = User::firstOrCreate(
            ['email' => $adminProfile->email],
            [
                'name'            => $adminProfile->name,
                'password'        => 'R4md4nltz!', // auto hash via casts
                'role'            => 'super_admin',
                'master_admin_id' => $adminProfile->id,
                'is_active'       => true,
            ]
        );

        $roles = ['super_admin', 'agen', 'mitra_brankas', 'customer'];
        if (class_exists(\Spatie\Permission\Models\Role::class)) {
            foreach ($roles as $r) {
                \Spatie\Permission\Models\Role::firstOrCreate(['name' => $r, 'guard_name' => 'web']);
            }
            $user->assignRole('super_admin');
        } elseif (class_exists(\App\Models\SysRole::class)) {
            foreach ($roles as $r) {
                \App\Models\SysRole::firstOrCreate(['name' => $r, 'guard_name' => 'web']);
            }
        }
    }
}
