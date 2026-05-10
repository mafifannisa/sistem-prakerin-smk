<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Jurusan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SiswaImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $importedCount = 0;
        $skippedCount = 0;

        foreach ($rows as $row) {
            // Bersihkan spasi
            $nisn = isset($row['nisn']) ? trim($row['nisn']) : '';
            $nama = isset($row['nama']) ? trim($row['nama']) : '';

            // Lewati baris kosong
            if (empty($nisn) || empty($nama)) {
                continue;
            }

            // Cek duplikat NISN
            if (Siswa::where('nisn', $nisn)->exists()) {
                $skippedCount++;
                continue;
            }

            // Cari jurusan berdasarkan kode_jurusan atau nama_jurusan
            $jurusanId = null;
            if (!empty($row['jurusan'])) {
                $kodeJurusan = trim($row['jurusan']);
                $jurusan = Jurusan::where('kode_jurusan', $kodeJurusan)
                    ->orWhere('nama_jurusan', 'like', "%{$kodeJurusan}%")
                    ->first();
                $jurusanId = $jurusan ? $jurusan->id : null;
            }

            // Tentukan email jika kosong
            $email = !empty($row['email']) ? trim($row['email']) : $nisn . '@siswa.com';
            
            // Cek duplikat email
            if (Siswa::where('email', $email)->exists()) {
                $skippedCount++;
                continue;
            }

            // Konversi tanggal lahir
            $tanggalLahir = $this->transformDate($row['tanggal_lahir'] ?? null);

            // Simpan siswa
            Siswa::create([
                'nisn'          => $nisn,
                'nama'          => $nama,
                'tempat_lahir'  => $row['tempat_lahir'] ?? null,
                'tanggal_lahir' => $tanggalLahir,
                'jurusan_id'    => $jurusanId,
                'kelas'         => $row['kelas'] ?? null,
                'no_wa'         => $row['no_wa'] ?? '-',
                'email'         => $email,
                'alamat'        => $row['alamat'] ?? null,
                'nama_wali'     => $row['nama_wali'] ?? null,
                'no_wa_wali'    => $row['no_wa_wali'] ?? null,
                'password'      => Hash::make($nisn), // Password default = NISN
                'is_active'     => true,
            ]);

            $importedCount++;
        }

        session()->flash('import_stats', [
            'success' => $importedCount,
            'skipped' => $skippedCount
        ]);
    }

    private function transformDate($value)
    {
        if (empty($value)) return null;
        
        try {
            // Jika format tanggal dari Excel (serial number)
            if (is_numeric($value)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
            }
            
            // Jika format teks (Y-m-d atau d/m/Y)
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}