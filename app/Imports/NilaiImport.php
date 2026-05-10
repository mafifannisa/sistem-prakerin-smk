<?php

namespace App\Imports;

use App\Models\PenempatanMagang;
use App\Models\Nilai;
use App\Models\Siswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class NilaiImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $nisn = trim($row['nisn'] ?? '');
            $nilaiSikap = floatval($row['nilai_sikap'] ?? 0);
            $nilaiKeterampilan = floatval($row['nilai_keterampilan'] ?? 0);
            $nilaiPengetahuan = floatval($row['nilai_pengetahuan'] ?? 0);
            $catatan = $row['catatan'] ?? null;

            // Abaikan jika NISN kosong
            if (empty($nisn)) continue;

            // Cari siswa berdasarkan NISN
            $siswa = Siswa::where('nisn', $nisn)->first();
            if (!$siswa) continue; // NISN tidak terdaftar → lewati baris ini

            // Cari penempatan magang aktif
            $penempatan = PenempatanMagang::where('siswa_id', $siswa->id)
                ->whereIn('status', ['approved', 'ongoing', 'completed'])
                ->latest()
                ->first();

            if (!$penempatan) continue; // Tidak ada penempatan valid → lewati

            // Hitung nilai akhir dan predikat
            $nilaiAkhir = round(($nilaiSikap + $nilaiKeterampilan + $nilaiPengetahuan) / 3, 2);

            if ($nilaiAkhir >= 90) $predikat = 'A';
            elseif ($nilaiAkhir >= 80) $predikat = 'B';
            elseif ($nilaiAkhir >= 70) $predikat = 'C';
            elseif ($nilaiAkhir >= 60) $predikat = 'D';
            else $predikat = 'E';

            // Simpan atau update nilai
            Nilai::updateOrCreate(
                ['penempatan_magang_id' => $penempatan->id],
                [
                    'nilai_sikap' => $nilaiSikap,
                    'nilai_keterampilan' => $nilaiKeterampilan,
                    'nilai_pengetahuan' => $nilaiPengetahuan,
                    'nilai_akhir' => $nilaiAkhir,
                    'predikat' => $predikat,
                    'catatan_penguji' => $catatan,
                    'tanggal_input' => Carbon::now(),
                    'input_by' => auth()->id(),
                ]
            );
        }
    }
}