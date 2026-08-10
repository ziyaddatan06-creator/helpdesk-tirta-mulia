<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Role
        $roleSuperAdmin = Role::create(['name' => 'Super Admin']);
        $roleAdmin = Role::create(['name' => 'Admin']);
        $roleTechnician = Role::create(['name' => 'Teknisi']);
        $roleCustomer = Role::create(['name' => 'Pelanggan']);

        // 2. Buat Akun Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'admin@tirtamulia.test',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $superAdmin->assignRole($roleSuperAdmin);

        // 3. Buat Akun Admin/CS
        $admin = User::create([
            'name' => 'Customer Service',
            'username' => 'admincs',
            'email' => 'cs@tirtamulia.test',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $admin->assignRole($roleAdmin);

        // 4. Buat Akun Teknisi
        $teknisi = User::create([
            'name' => 'Teknisi Lapangan 1',
            'username' => 'teknisi1',
            'email' => 'teknisi@tirtamulia.test',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $teknisi->assignRole($roleTechnician);

        // 5. Buat Akun Pelanggan (Data Anda)
        $pelanggan = User::create([
            'name' => 'Ziyaddatan Pratama Borneo',
            'username' => 'borneo',
            'customer_number' => 'PDAM-13182420037',
            'email' => 'pelanggan@tirtamulia.test',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $pelanggan->assignRole($roleCustomer);
    }
}