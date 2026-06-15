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
        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'nama_lengkap' => 'Administrator Sistem',
                'email' => 'admin@smk3tuban.sch.id',
                'no_wa' => '081234567890',
                'is_active' => true,
            ]
        );

        // Pimpinan Default
        User::firstOrCreate(
            ['username' => 'pimpinan'],
            [
                'password' => Hash::make('pimpinan123'),
                'role' => 'pimpinan',
                'nama_lengkap' => 'Kepala SMK Negeri 3 Tuban',
                'email' => 'kepala@smk3tuban.sch.id',
                'no_wa' => '081234567891',
                'is_active' => true,
            ]
        );

        // Admin TU (Tata Usaha)
        User::firstOrCreate(
            ['username' => 'tu'],
            [
                'password' => Hash::make('tu123'),
                'role' => 'admin',
                'nama_lengkap' => 'Staff Tata Usaha',
                'email' => 'tu@smk3tuban.sch.id',
                'no_wa' => '081234567892',
                'is_active' => true,
            ]
        );

        // Guru Pembimbing Default
        $guruUser = User::firstOrCreate(
            ['username' => 'guru'],
            [
                'password' => Hash::make('guru123'),
                'role' => 'guru_pembimbing',
                'nama_lengkap' => 'Guru Pembimbing Prakerin',
                'email' => 'guru@smk3tuban.sch.id',
                'no_wa' => '081234567893',
                'is_active' => true,
            ]
        );
        \App\Models\Guru::firstOrCreate(
            ['user_id' => $guruUser->id],
            [
                'nip' => '198001012010011001',
                'nama' => 'Guru Pembimbing Prakerin',
                'jurusan_id' => 4, // RPL
                'no_telp' => '081234567893',
                'jabatan' => 'guru_pembimbing',
                'is_active' => true,
            ]
        );

        // Kepala Jurusan Default
        $kajurUser = User::firstOrCreate(
            ['username' => 'kajur'],
            [
                'password' => Hash::make('kajur123'),
                'role' => 'kepala_jurusan',
                'nama_lengkap' => 'Kepala Jurusan RPL',
                'email' => 'kajur@smk3tuban.sch.id',
                'no_wa' => '081234567894',
                'is_active' => true,
            ]
        );
        \App\Models\Guru::firstOrCreate(
            ['user_id' => $kajurUser->id],
            [
                'nip' => '198001022010011002',
                'nama' => 'Kepala Jurusan RPL',
                'jurusan_id' => 4, // RPL
                'no_telp' => '081234567894',
                'jabatan' => 'kepala_jurusan',
                'is_active' => true,
            ]
        );

        // Guru Penguji Default
        $pengujiUser = User::firstOrCreate(
            ['username' => 'penguji'],
            [
                'password' => Hash::make('penguji123'),
                'role' => 'guru_penguji',
                'nama_lengkap' => 'Guru Penguji Prakerin',
                'email' => 'penguji@smk3tuban.sch.id',
                'no_wa' => '081234567895',
                'is_active' => true,
            ]
        );
        \App\Models\Guru::firstOrCreate(
            ['user_id' => $pengujiUser->id],
            [
                'nip' => '198001032010011003',
                'nama' => 'Guru Penguji Prakerin',
                'jurusan_id' => null,
                'no_telp' => '081234567895',
                'jabatan' => 'guru_penguji',
                'is_active' => true,
            ]
        );
    }
}
