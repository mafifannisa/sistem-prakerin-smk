<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Batch Sertifikat Prakerin</title>
    <style>
        @page { size: A4 landscape; margin: 0; }
        body { 
            font-family: 'Times New Roman', Times, serif; 
            color: #000; 
            margin: 0; 
            padding: 0; 
            width: 100%; 
            height: 100%; 
        }

        .page {
            position: relative;
            width: 297mm;
            height: 210mm;
            page-break-after: always;
            box-sizing: border-box;
            overflow: hidden;
        }

        /* Border untuk Halaman Depan */
        .border-red {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border: 16px solid #ffffff; /* Warna putih */
            z-index: 1;
        }

        .border-inner-thin {
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            bottom: 20px;
            border: 1px solid #ffffff; /* Warna putih */
            z-index: 1;
        }

        .custom-border-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        .content-front {
            position: absolute;
            top: 15mm;
            left: 20mm;
            width: 257mm;
            height: 180mm;
            z-index: 10;
        }

        .content-back {
            position: absolute;
            top: 12mm;
            left: 16mm;
            width: 265mm;
            height: 186mm;
            z-index: 10;
        }

        /* Judul */
        .title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }
        .subtitle {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 30px;
            letter-spacing: 2px;
        }

        /* Tabel Data Diri */
        .biodata {
            width: 55%;
            margin: 0 auto;
            font-size: 15px;
            border-collapse: collapse;
        }
        .biodata td {
            padding: 4px 0;
            vertical-align: top;
        }
        
        .paragraph {
            text-align: center;
            font-size: 15px;
            line-height: 1.6;
            margin-top: 25px;
            padding: 0 40px;
        }

        .signatures {
            width: 100%;
            margin-top: 35px;
            text-align: center;
            font-size: 14px;
            border-collapse: collapse;
        }

        .signatures td {
            vertical-align: bottom;
        }

        .photo-box {
            width: 2.2cm;
            height: 3cm;
            border: 1px solid #000;
            margin: 0 auto;
            line-height: 3cm;
            text-align: center;
            font-size: 12px;
        }

        /* Bagian Belakang (Nilai) */
        .back-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 30px;
            letter-spacing: 1px;
        }

        .split-container {
            width: 100%;
            border: none;
            border-collapse: collapse;
        }
        .split-container td.col-val {
            vertical-align: top;
        }

        .table-nilai {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .table-nilai th, .table-nilai td {
            border: 1px solid #000;
            padding: 6px 8px;
        }
        .table-nilai th {
            text-align: left;
            background: #fff;
        }
        .table-nilai th.center, .table-nilai td.center {
            text-align: center;
        }

        .skala-nilai {
            margin-top: 15px;
            font-size: 12px;
            font-weight: bold;
        }
        .skala-table {
            width: 100%;
            margin-top: 5px;
            font-weight: normal;
        }
        .skala-table td {
            padding: 2px 0;
        }

    </style>
</head>
<body>

    @php
        if (!function_exists('bacaAngka')) {
            function bacaAngka($angka) {
                $huruf = ['Nol', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan'];
                $str = (string) round($angka);
                $hasil = [];
                for($i = 0; $i < strlen($str); $i++) {
                    if (isset($huruf[(int)$str[$i]])) {
                        $hasil[] = $huruf[(int)$str[$i]];
                    }
                }
                return implode(' ', $hasil);
            }
        }
    @endphp

    @foreach($pengajuans as $penempatan)
        @php
            $predikatDb = $penempatan->nilai->predikat ?? 'E';
            $predikatTeks = 'KURANG';
            if ($predikatDb == 'A') $predikatTeks = 'BAIK SEKALI';
            elseif ($predikatDb == 'B') $predikatTeks = 'BAIK';
            elseif ($predikatDb == 'C') $predikatTeks = 'CUKUP';
            elseif ($predikatDb == 'D' || $predikatDb == 'E') $predikatTeks = 'KURANG';
        @endphp

        <!-- HALAMAN DEPAN -->
        <div class="page">
            @if(isset($borderPath) && $borderPath && ($borderSide == 'depan' || $borderSide == 'semua'))
                <img class="custom-border-overlay" src="{{ $borderPath }}" />
            @endif
            <div class="border-red"></div>
            <div class="border-inner-thin"></div>
            <div class="content-front">
                <div class="title">SURAT KETERANGAN PRAKTIK KERJA INDUSTRI</div>
                <div class="subtitle">(P R A K E R I N)</div>

                <table class="biodata">
                    <tr>
                        <td width="180">Nama</td>
                        <td width="20">:</td>
                        <td><strong>{{ $penempatan->siswa->nama ?? '-' }}</strong></td>
                    </tr>
                    <tr>
                        <td>Tempat dan Tanggal Lahir</td>
                        <td>:</td>
                        <td>
                            {{ $penempatan->siswa->tempat_lahir ?? 'Tuban' }}, 
                            {{ \Carbon\Carbon::parse($penempatan->siswa->tanggal_lahir)->isoFormat('DD MMMM YYYY') }}
                        </td>
                    </tr>
                    <tr>
                        <td>Nomor Induk Siswa</td>
                        <td>:</td>
                        <td>{{ $penempatan->siswa->nisn ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Kompetensi Keahlian</td>
                        <td>:</td>
                        <td>{{ $penempatan->siswa->jurusan->nama_jurusan ?? '-' }}</td>
                    </tr>
                </table>

                <div class="paragraph">
                    Adalah Siswa Sekolah Menengah Kejuruan (SMK) Negeri 3 Tuban, yang telah melakukan Praktik Kerja Industri di :<br>
                    <strong>{{ $penempatan->industri->nama_industri ?? '-' }}</strong><br>
                    <strong>{{ $penempatan->industri->alamat ?? '-' }}</strong><br>
                    Pada tanggal &nbsp; {{ \Carbon\Carbon::parse($penempatan->tanggal_mulai)->isoFormat('DD MMMM YYYY') }} &nbsp; sampai dengan &nbsp; {{ \Carbon\Carbon::parse($penempatan->tanggal_selesai)->isoFormat('DD MMMM YYYY') }}<br>
                    Pada Bidang Studi Keahlian : {{ $penempatan->siswa->jurusan->nama_jurusan ?? '-' }}, dengan perolehan predikat : <strong>{{ $predikatTeks }}</strong>
                </div>

                <table class="signatures">
                    <tr>
                        <td width="35%">
                            Mengetahui,<br>
                            Kepala {{ $penempatan->industri->nama_industri ?? 'DU/DI' }}<br>
                            <br><br><br><br>
                            <strong><u>{{ $penempatan->industri->nama_hr ?? 'Pimpinan DU/DI' }}</u></strong>
                        </td>
                        <td width="30%" style="vertical-align: middle;">
                            <div class="photo-box">3x4</div>
                        </td>
                        <td width="35%">
                            Tuban, {{ \Carbon\Carbon::parse($penempatan->tanggal_selesai ?? now())->isoFormat('DD MMMM YYYY') }}<br>
                            Pembimbing DU/DI,<br>
                            <br><br><br><br>
                            <strong><u>Pembimbing Industri</u></strong>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- HALAMAN BELAKANG (NILAI) -->
        <div class="page" style="page-break-after: {{ !$loop->last ? 'always' : 'auto' }};">
            @if(isset($borderPath) && $borderPath && ($borderSide == 'belakang' || $borderSide == 'semua'))
                <img class="custom-border-overlay" src="{{ $borderPath }}" />
            @endif
            <div class="content-back">
                <div class="back-title">DAFTAR NILAI HASIL PRAKTIK KERJA INDUSTRI</div>

                <table class="split-container">
                    <tr>
                        <!-- KOLOM KIRI (NILAI TEKNIS) -->
                        <td class="col-val" width="48%">
                            <table class="table-nilai">
                                <tr>
                                    <th colspan="4" style="background: #f3f4f6; text-align: left;">A. NILAI TEKNIS</th>
                                </tr>
                                <tr>
                                    <th class="center" width="8%">No</th>
                                    <th class="center" width="52%">Komponen Yang Dinilai</th>
                                    <th class="center" width="15%">Angka</th>
                                    <th class="center" width="25%">Huruf</th>
                                </tr>
                                <tr>
                                    <td class="center">1</td>
                                    <td>{{ $penempatan->nilai->kegiatan_1 ?? 'Membuat Table Disposisi di Excel' }}</td>
                                    <td class="center">{{ round($penempatan->nilai->nilai_1 ?? 0) }}</td>
                                    <td>{{ bacaAngka($penempatan->nilai->nilai_1 ?? 0) }}</td>
                                </tr>
                                <tr>
                                    <td class="center">2</td>
                                    <td>{{ $penempatan->nilai->kegiatan_2 ?? 'Membuat Table foto di Word' }}</td>
                                    <td class="center">{{ round($penempatan->nilai->nilai_2 ?? 0) }}</td>
                                    <td>{{ bacaAngka($penempatan->nilai->nilai_2 ?? 0) }}</td>
                                </tr>
                                <tr>
                                    <td class="center">3</td>
                                    <td>{{ $penempatan->nilai->kegiatan_3 ?? 'Memproses dan Remove Background Foto' }}</td>
                                    <td class="center">{{ round($penempatan->nilai->nilai_3 ?? 0) }}</td>
                                    <td>{{ bacaAngka($penempatan->nilai->nilai_3 ?? 0) }}</td>
                                </tr>
                            </table>

                            <table class="table-nilai" style="margin-top: 15px; border: 1.5px solid #000;">
                                <tr>
                                    <td class="font-bold" width="60%"><strong>Rata-rata A+B</strong></td>
                                    <td class="center font-bold" width="40%"><strong>{{ round($penempatan->nilai->nilai_akhir ?? 0) }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="font-bold"><strong>Predikat</strong></td>
                                    <td class="center font-bold"><strong>{{ $predikatTeks }}</strong></td>
                                </tr>
                            </table>
                        </td>

                        <!-- SPACER -->
                        <td width="4%"></td>

                        <!-- KOLOM KANAN (NILAI NON TEKNIS & SKALA) -->
                        <td class="col-val" width="48%">
                            <table class="table-nilai">
                                <tr>
                                    <th colspan="4" style="background: #f3f4f6; text-align: left;">B. NILAI NON TEKNIS</th>
                                </tr>
                                <tr>
                                    <th class="center" width="8%">No</th>
                                    <th class="center" width="52%">Komponen Yang Dinilai</th>
                                    <th class="center" width="15%">Angka</th>
                                    <th class="center" width="25%">Huruf</th>
                                </tr>
                                <tr>
                                    <td class="center">1</td>
                                    <td>Kedisiplinan</td>
                                    <td class="center">{{ round($penempatan->nilai->nilai_sikap ?? 0) }}</td>
                                    <td>{{ bacaAngka($penempatan->nilai->nilai_sikap ?? 0) }}</td>
                                </tr>
                                <tr>
                                    <td class="center">2</td>
                                    <td>Kerjasama</td>
                                    <td class="center">{{ round($penempatan->nilai->nilai_keterampilan ?? 0) }}</td>
                                    <td>{{ bacaAngka($penempatan->nilai->nilai_keterampilan ?? 0) }}</td>
                                </tr>
                                <tr>
                                    <td class="center">3</td>
                                    <td>Inisiatif dan Kreativitas</td>
                                    <td class="center">{{ round($penempatan->nilai->nilai_pengetahuan ?? 0) }}</td>
                                    <td>{{ bacaAngka($penempatan->nilai->nilai_pengetahuan ?? 0) }}</td>
                                </tr>
                            </table>

                            <div class="skala-nilai">
                                SKALA RENTANG NILAI :
                                <table class="skala-table">
                                    <tr>
                                        <td width="50%">- 86 - 100 = Baik Sekali</td>
                                        <td width="50%">- 56 - 69  = Cukup</td>
                                    </tr>
                                    <tr>
                                        <td>- 70 - 85  = Baik</td>
                                        <td>- 40 - 55  = Kurang</td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    @endforeach

</body>
</html>