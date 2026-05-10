<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Siswa;
use App\Models\Jurusan;
use Illuminate\Support\Facades\Hash;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        $siswas = [
            ['nisn' => '0051234567', 'nama' => 'Rofiqul Wahyu Romadhani', 'tempat_lahir' => 'Tuban', 'tanggal_lahir' => '2007-05-15', 'jurusan' => 'RPL', 'no_wa' => '081234567801'],
            ['nisn' => '0051234568', 'nama' => 'Ahmad Maulana', 'tempat_lahir' => 'Bojonegoro', 'tanggal_lahir' => '2007-06-20', 'jurusan' => 'RPL', 'no_wa' => '081234567802'],
            ['nisn' => '0051234569', 'nama' => 'Siti Nurhaliza', 'tempat_lahir' => 'Tuban', 'tanggal_lahir' => '2007-07-10', 'jurusan' => 'TB', 'no_wa' => '081234567803'],
            ['nisn' => '0051234570', 'nama' => 'Budi Pratama', 'tempat_lahir' => 'Lamongan', 'tanggal_lahir' => '2007-08-05', 'jurusan' => 'TKR', 'no_wa' => '081234567804'],
            ['nisn' => '0051234571', 'nama' => 'Dewi Sartika', 'tempat_lahir' => 'Tuban', 'tanggal_lahir' => '2007-09-12', 'jurusan' => 'TB', 'no_wa' => '081234567805'],
            ['nisn' => '0051234572', 'nama' => 'Eko Prasetyo', 'tempat_lahir' => 'Gresik', 'tanggal_lahir' => '2007-10-18', 'jurusan' => 'TPM', 'no_wa' => '081234567806'],
            ['nisn' => '0051234573', 'nama' => 'Fitri Rahmawati', 'tempat_lahir' => 'Tuban', 'tanggal_lahir' => '2007-11-22', 'jurusan' => 'TKI', 'no_wa' => '081234567807'],
            ['nisn' => '0051234574', 'nama' => 'Gunawan Wijaya', 'tempat_lahir' => 'Surabaya', 'tanggal_lahir' => '2007-12-08', 'jurusan' => 'TPTU', 'no_wa' => '081234567808'],
            ['nisn' => '0051234575', 'nama' => 'Hana Pertiwi', 'tempat_lahir' => 'Tuban', 'tanggal_lahir' => '2008-01-14', 'jurusan' => 'TKI', 'no_wa' => '081234567809'],
            ['nisn' => '0051234576', 'nama' => 'Indra Kusuma', 'tempat_lahir' => 'Tuban', 'tanggal_lahir' => '2008-02-20', 'jurusan' => 'RPL', 'no_wa' => '081234567810'],
        ];

        foreach ($siswas as $siswa) {
            $jurusan = Jurusan::where('kode_jurusan', $siswa['jurusan'])->first();
            
            if ($jurusan) {
                Siswa::create([
                    'nisn' => $siswa['nisn'],
                    'nama' => $siswa['nama'],
                    'tempat_lahir' => $siswa['tempat_lahir'],
                    'tanggal_lahir' => $siswa['tanggal_lahir'],
                    'jurusan_id' => $jurusan->id,
                    'no_wa' => $siswa['no_wa'],
                    'email' => strtolower(str_replace(' ', '.', $siswa['nama'])) . '@student.smk3tuban.sch.id',
                    'alamat' => 'Jl. Raya Tuban No. ' . rand(1, 100),
                    'nama_wali' => 'Orang Tua ' . $siswa['nama'],
                    'no_wa_wali' => '081' . rand(10000000, 99999999),
                    'password' => Hash::make($siswa['nisn']),
                    'is_active' => true,
                ]);
            }
        }
    }
}