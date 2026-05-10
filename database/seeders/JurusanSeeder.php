<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jurusan;

class JurusanSeeder extends Seeder
{
    public function run(): void
    {
        $jurusans = [
            [
                'kode_jurusan' => 'TPM',
                'nama_jurusan' => 'Teknik Pemesinan',
                'kepala_jurusan' => 'Budi Santoso, S.Pd, M.T',
            ],
            [
                'kode_jurusan' => 'TKI',
                'nama_jurusan' => 'Teknik Kimia Industri',
                'kepala_jurusan' => 'Dwi Hartono, S.T, M.Sc',
            ],
            [
                'kode_jurusan' => 'TKR',
                'nama_jurusan' => 'Teknik Kendaraan Ringan',
                'kepala_jurusan' => 'Ahmad Fauzi, S.Pd',
            ],
            [
                'kode_jurusan' => 'RPL',
                'nama_jurusan' => 'Rekayasa Perangkat Lunak',
                'kepala_jurusan' => 'Siti Nurhaliza, S.Kom, M.Kom',
            ],
            [
                'kode_jurusan' => 'TB',
                'nama_jurusan' => 'Tata Boga',
                'kepala_jurusan' => 'Dewi Lestari, S.Pd, M.M',
            ],
            [
                'kode_jurusan' => 'TPTU',
                'nama_jurusan' => 'Teknik Pendinginan dan Tata Udara',
                'kepala_jurusan' => 'Eko Prasetyo, S.T',
            ],
        ];

        foreach ($jurusans as $jurusan) {
            Jurusan::create([
                'kode_jurusan' => $jurusan['kode_jurusan'],
                'nama_jurusan' => $jurusan['nama_jurusan'],
                'kepala_jurusan' => $jurusan['kepala_jurusan'],
                'deskripsi' => "Program keahlian {$jurusan['nama_jurusan']} SMK Negeri 3 Tuban",
                'is_active' => true,
            ]);
        }
    }
}
