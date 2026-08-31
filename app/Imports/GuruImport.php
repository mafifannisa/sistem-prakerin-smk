<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\User;
use App\Models\Jurusan;
use App\Models\Kelas;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GuruImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $importedCount = 0;
        $skippedCount = 0;

        foreach ($rows as $row) {
            $nama = isset($row['nama']) ? trim($row['nama']) : (isset($row['nama_lengkap']) ? trim($row['nama_lengkap']) : '');
            $nip = isset($row['nip']) ? trim($row['nip']) : null;

            // Lewati jika nama kosong
            if (empty($nama)) {
                continue;
            }

            // Normalisasi Jabatan / Role
            $jabatanRaw = strtolower(trim($row['jabatan'] ?? ($row['role'] ?? 'guru_pembimbing')));
            if (str_contains($jabatanRaw, 'kajur') || str_contains($jabatanRaw, 'kepala')) {
                $jabatan = 'kepala_jurusan';
            } elseif (str_contains($jabatanRaw, 'penguji')) {
                $jabatan = 'guru_penguji';
            } else {
                $jabatan = 'guru_pembimbing';
            }

            // Tentukan Username
            if (!empty($row['username'])) {
                $username = trim($row['username']);
            } elseif (!empty($nip)) {
                $username = $nip;
            } else {
                $slug = Str::slug($nama, '');
                $username = 'guru_' . ($slug ? substr($slug, 0, 12) : rand(1000, 9999));
            }

            // Tentukan Email
            if (!empty($row['email'])) {
                $email = trim($row['email']);
            } else {
                $email = $username . '@guru.com';
            }

            // Cek duplikasi User atau NIP Guru
            if (User::where('username', $username)->orWhere('email', $email)->exists()) {
                $skippedCount++;
                continue;
            }

            if (!empty($nip) && Guru::where('nip', $nip)->exists()) {
                $skippedCount++;
                continue;
            }

            // Cari Jurusan (opsional)
            $jurusanId = null;
            if (!empty($row['jurusan'])) {
                $kodeJurusan = trim($row['jurusan']);
                $jurusan = Jurusan::where('kode_jurusan', $kodeJurusan)
                    ->orWhere('nama_jurusan', 'like', "%{$kodeJurusan}%")
                    ->first();
                $jurusanId = $jurusan ? $jurusan->id : null;
            }

            // Cari / Buat Kelas (opsional)
            $kelasId = null;
            if (!empty($row['kelas'])) {
                $namaKelas = trim($row['kelas']);
                $kelas = Kelas::firstOrCreate([
                    'nama_kelas' => $namaKelas
                ]);
                $kelasId = $kelas->id;
            }

            // Password default: password dari excel, atau NIP, atau username, atau 'guru123'
            $rawPassword = !empty($row['password']) ? trim($row['password']) : (!empty($nip) ? $nip : 'guru123');

            $noTelp = !empty($row['no_telp']) ? trim($row['no_telp']) : (!empty($row['no_wa']) ? trim($row['no_wa']) : '-');

            DB::transaction(function () use ($username, $email, $rawPassword, $jabatan, $nama, $noTelp, $nip, $jurusanId, $kelasId) {
                $user = User::create([
                    'username'     => $username,
                    'email'        => $email,
                    'password'     => Hash::make($rawPassword),
                    'role'         => $jabatan,
                    'nama_lengkap' => $nama,
                    'no_wa'        => $noTelp,
                    'is_active'    => 1,
                ]);

                Guru::create([
                    'user_id'    => $user->id,
                    'nip'        => $nip,
                    'nama'       => $nama,
                    'jurusan_id' => $jurusanId,
                    'kelas_id'   => $kelasId,
                    'no_telp'    => $noTelp,
                    'jabatan'    => $jabatan,
                    'is_active'  => 1,
                ]);
            });

            $importedCount++;
        }

        session()->flash('import_stats', [
            'success' => $importedCount,
            'skipped' => $skippedCount
        ]);
    }
}
