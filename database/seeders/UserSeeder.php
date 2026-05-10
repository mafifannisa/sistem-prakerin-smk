<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin Default
        User::create([
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'nama_lengkap' => 'Administrator Sistem',
            'email' => 'admin@smk3tuban.sch.id',
            'no_wa' => '081234567890',
            'is_active' => true,
        ]);

        // Pimpinan Default
        User::create([
            'username' => 'pimpinan',
            'password' => Hash::make('pimpinan123'),
            'role' => 'pimpinan',
            'nama_lengkap' => 'Kepala SMK Negeri 3 Tuban',
            'email' => 'kepala@smk3tuban.sch.id',
            'no_wa' => '081234567891',
            'is_active' => true,
        ]);

        // Admin TU (Tata Usaha)
        User::create([
            'username' => 'tu',
            'password' => Hash::make('tu123'),
            'role' => 'admin',
            'nama_lengkap' => 'Staff Tata Usaha',
            'email' => 'tu@smk3tuban.sch.id',
            'no_wa' => '081234567892',
            'is_active' => true,
        ]);
    }
}
