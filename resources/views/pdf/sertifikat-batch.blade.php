<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Batch Sertifikat Magang</title>
    <style>
        @page { size: A4 landscape; margin: 0; }
        body { font-family: 'Georgia', serif; color: #333; margin: 0; padding: 0; width: 100%; height: 100%; }

        .page { position: relative; width: 100%; height: 100%; page-break-after: always; overflow: hidden; }
        .page:last-child { page-break-after: auto; }

        .border-outer { position: absolute; top: 30px; left: 30px; right: 30px; bottom: 30px; border: 2px solid #e5e7eb; z-index: -2; }
        .border-inner { position: absolute; top: 40px; left: 40px; right: 40px; bottom: 40px; border: 3px solid #1e3a8a; z-index: -1; }

        .content { padding: 70px 80px; text-align: center; z-index: 1; }

        .kops { font-family: sans-serif; font-size: 11px; color: #6b7280; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 20px; }
        .title { font-size: 42px; font-weight: bold; margin-top: 40px; margin-bottom: 5px; color: #111827; }
        .nomor { font-size: 12px; color: #9ca3af; margin-bottom: 40px; }
        .diberikan { font-size: 14px; color: #4b5563; margin-bottom: 10px; }
        .nama { font-size: 34px; color: #2563eb; text-transform: uppercase; font-weight: bold; margin: 10px 0; }
        .nisn { font-size: 13px; color: #6b7280; margin-bottom: 40px; }
        .desc { font-size: 16px; color: #374151; line-height: 1.6; max-width: 90%; margin: 0 auto; font-style: italic; }

        .back-title { font-size: 20px; font-weight: bold; text-transform: uppercase; margin-bottom: 5px; color: #111827; }
        .back-subtitle { font-size: 13px; color: #6b7280; margin-bottom: 30px; }

        .table-nilai { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .table-nilai th, .table-nilai td { border: 1px solid #d1d5db; padding: 12px; font-family: sans-serif; font-size: 13px; text-align: left; }
        .table-nilai th { background-color: #f3f4f6; color: #4b5563; }
        .val-col { width: 130px; text-align: center; font-weight: bold; color: #111827; }
        .row-rata { background-color: #eff6ff; }
        .row-rata td { font-weight: bold; }
        .val-rata { color: #1d4ed8; font-size: 18px; }

        .footer-table { width: 100%; border: none; margin-top: 10px; }
        .footer-table td { border: none; padding: 0; vertical-align: top; }
    </style>
</head>
<body>

    @php
        $ttdPath = public_path('images/signature.png');
        $ttdBase64 = '';
        if(file_exists($ttdPath)) {
            $ttdBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($ttdPath));
        }
    @endphp

    @foreach($pengajuans as $penempatan)
        <div class="page">
            <div class="border-outer"></div>
            <div class="border-inner"></div>
            <div class="content">
                <div class="kops">Sekolah Menengah Kejuruan Negeri 3 Tuban</div>
                <div class="title">Sertifikat Kompetensi</div>
                <div class="nomor">Nomor: 421.5/129/SMKN3/{{ date('Y') }}</div>

                <div class="diberikan" style="margin-top: 40px;">Diberikan Kepada:</div>
                <div class="nama">{{ $penempatan->siswa->nama ?? 'Nama Siswa' }}</div>
                <div class="nisn">NISN: {{ $penempatan->siswa->nisn ?? '-' }}</div>

                <div class="desc" style="margin-top: 30px;">
                    Telah dinyatakan lulus mengikuti Praktik Kerja Lapangan (PKL) Industri di
                    <strong>{{ $penempatan->industri->nama_industri ?? '-' }}</strong> dengan predikat
                    <strong style="color: #111827;">{{ $penempatan->nilai->predikat ?? '-' }}</strong>.
                </div>
            </div>
        </div>

        <div class="page">
            <div class="border-outer"></div>
            <div class="border-inner"></div>
            <div class="content">
                <div class="back-title">Daftar Nilai Praktik Kerja Lapangan</div>
                <div class="back-subtitle">Nama: <span style="font-weight:bold; color:#111827;">{{ $penempatan->siswa->nama ?? '-' }}</span></div>

                <table class="table-nilai">
                    <thead>
                        <tr>
                            <th>Komponen Penilaian</th>
                            <th class="val-col">Nilai Angka</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1. Nilai Sikap & Kedisiplinan</td>
                            <td class="val-col">{{ $penempatan->nilai->nilai_sikap ?? '0' }}</td>
                        </tr>
                        <tr>
                            <td>2. Nilai Keterampilan & Kinerja</td>
                            <td class="val-col">{{ $penempatan->nilai->nilai_keterampilan ?? '0' }}</td>
                        </tr>
                        <tr>
                            <td>3. Nilai Pengetahuan Bidang</td>
                            <td class="val-col">{{ $penempatan->nilai->nilai_pengetahuan ?? '0' }}</td>
                        </tr>
                        <tr class="row-rata">
                            <td style="text-align: right;">Rata-Rata Nilai Akhir</td>
                            <td class="val-col val-rata">{{ $penempatan->nilai->nilai_akhir ?? '0' }}</td>
                        </tr>
                    </tbody>
                </table>

                <table class="footer-table">
                    <tr>
                        <td style="width: 50%; text-align: left;">
                            <div style="font-weight: bold; font-size: 12px; color: #374151; margin-bottom: 5px;">Keterangan Predikat:</div>
                            <div style="font-size: 11px; color: #6b7280; line-height: 1.6;">
                                90 - 100 : Sangat Memuaskan (A)<br>
                                80 - 89 : Memuaskan (B)<br>
                                70 - 79 : Cukup (C)<br>
                                < 70 : Kurang (D)
                            </div>
                        </td>
                        <td style="width: 50%; text-align: center; padding-left: 60px;">
                            <div style="font-size: 12px; color: #4b5563; margin-bottom: 5px;">Ditetapkan di Tuban, {{ \Carbon\Carbon::now()->format('d F Y') }}</div>
                            <div style="font-size: 12px; color: #111827; margin-bottom: 0;">Kepala Sekolah</div>

                            <div style="height: 75px; margin-top: 5px; margin-bottom: 5px;">
                                @if($ttdBase64)
                                    <img src="{{ $ttdBase64 }}" style="height: 80px; width: auto;">
                                @endif
                            </div>

                            <div style="border-top: 1px solid #111827; display: inline-block; padding-top: 5px; font-weight: bold; font-size: 13px; color: #111827; text-transform: uppercase;">
                                Bapak/Ibu Kepala Sekolah, S.Pd., M.Pd.
                            </div>
                            <div style="font-size: 11px; color: #111827; margin-top: 3px;">
                                NIP. 19801234 200501 1 001
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    @endforeach
</body>
</html>