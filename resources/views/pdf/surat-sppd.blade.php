<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>SPPD & Surat Tugas - {{ $guru->nama }}</title>
    <style>
        @page {
            margin-top: 1.2cm;
            margin-right: 1.5cm;
            margin-bottom: 1.2cm;
            margin-left: 2cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .header-title {
            text-align: center;
        }
        .header-title p.kop-prov {
            margin: 0;
            font-size: 13pt;
            font-weight: normal;
            text-transform: uppercase;
            line-height: 1.25;
        }
        .header-title h2.kop-school {
            margin: 2px 0 0 0;
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.25;
        }
        .header-title p.kop-address {
            margin: 3px 0 0 0;
            font-size: 10pt;
            font-weight: normal;
            line-height: 1.2;
        }
        
        .double-line {
            border-top: 1px solid #000;
            border-bottom: 3.5px solid #000;
            height: 2px;
            line-height: 0;
            font-size: 0;
            margin-top: 5px;
            margin-bottom: 15px;
            width: 100%;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

    <!-- ============================================ -->
    <!-- HALAMAN 1: SURAT PERINTAH TUGAS              -->
    <!-- ============================================ -->
    <table class="header-table" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td width="12%" style="text-align: left; vertical-align: middle;">
                @if(file_exists(public_path('images/logoprovinsijawatimur.png')))
                    <img src="{{ public_path('images/logoprovinsijawatimur.png') }}" style="height: 100px; width: auto; display: block;">
                @endif
            </td>
            <td width="76%" class="header-title">
                <p class="kop-prov">Pemerintah Provinsi Jawa Timur</p>
                <p class="kop-prov">Dinas Pendidikan</p>
                <h2 class="kop-school">SMK NEGERI 3 TUBAN</h2>
                <p class="kop-address">Jalan Bloso Desa Temandang Kecamatan Merakurak Kabupaten Tuban</p>
                <p class="kop-address" style="margin-top: 1px;">Telepon (0356) 711974</p>
            </td>
            <td width="12%"></td>
        </tr>
    </table>

    <div class="double-line"></div>

    <div style="text-align: center; margin-bottom: 25px;">
        <h3 style="margin: 0; font-size: 13pt; font-weight: bold; text-decoration: underline; letter-spacing: 0.5px; text-transform: uppercase;">SURAT PERINTAH TUGAS</h3>
        <p style="margin: 3px 0 0 0; font-size: 11pt;">Nomor : {{ $nomor_surat }}</p>
    </div>

    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; font-size: 11pt; line-height: 1.5; margin-bottom: 15px;">
        <tr>
            <td width="12%" style="vertical-align: top; padding-bottom: 10px;">Dasar</td>
            <td width="3%" style="vertical-align: top; text-align: center;">:</td>
            <td width="85%" style="vertical-align: top; text-align: justify;">
                Jadwal prakerin jurusan {{ $penempatan->siswa->jurusan->kode_jurusan ?? 'TKR' }} tahun pelajaran {{ $penempatan->periodeMagang->nama_periode ?? '2025/2026' }}
            </td>
        </tr>
    </table>
    
    <div style="text-align: center; font-weight: bold; font-size: 12pt; margin: 15px 0; text-transform: uppercase;">MEMERINTAHKAN</div>

    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; font-size: 11pt; line-height: 1.5; margin-bottom: 15px;">
        <tr>
            <td width="12%" style="vertical-align: top; padding-bottom: 10px;">Kepada</td>
            <td width="3%" style="vertical-align: top; text-align: center;">:</td>
            <td width="85%">
                <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                    <tr>
                        <td width="20%" style="vertical-align: top; padding-bottom: 4px;">Nama</td>
                        <td width="3%" style="vertical-align: top; text-align: center;">:</td>
                        <td style="vertical-align: top; font-weight: bold;">{{ $guru->nama }}</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top; padding-bottom: 4px;">NIP/NIPPPK</td>
                        <td style="vertical-align: top; text-align: center;">:</td>
                        <td style="vertical-align: top;">{{ $guru->nip ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top; padding-bottom: 4px;">Pangkat/Gol</td>
                        <td style="vertical-align: top; text-align: center;">:</td>
                        <td style="vertical-align: top;">{{ $guru->pangkat_golongan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top; padding-bottom: 4px;">Jabatan</td>
                        <td style="vertical-align: top; text-align: center;">:</td>
                        <td style="vertical-align: top;">{{ strtoupper($guru->jabatan ?? 'GURU') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top; padding-top: 10px;">Untuk</td>
            <td style="vertical-align: top; text-align: center; padding-top: 10px;">:</td>
            <td style="vertical-align: top; padding-top: 10px;">
                <p style="margin: 0 0 8px 0; text-align: justify;">
                    Melaksanakan Tugas dalam rangka {{ $maksud_perjalanan }}, yang dilaksanakan pada :
                </p>
                <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                    <tr>
                        <td width="20%" style="vertical-align: top; padding-bottom: 4px;">Hari</td>
                        <td width="3%" style="vertical-align: top; text-align: center;">:</td>
                        <td style="vertical-align: top;">{{ $tanggal_perjalanan->translatedFormat('l') }}</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top; padding-bottom: 4px;">Tanggal</td>
                        <td style="vertical-align: top; text-align: center;">:</td>
                        <td style="vertical-align: top;">{{ $tanggal_perjalanan->translatedFormat('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top; padding-bottom: 4px;">Tempat</td>
                        <td style="vertical-align: top; text-align: center;">:</td>
                        <td style="vertical-align: top;">
                            <strong style="text-transform: uppercase;">{{ $tempat_tujuan }}</strong>
                            @if($penempatan->industri->alamat)
                                <br><span style="font-size: 10.5pt; font-weight: normal; line-height: 1.3; display: inline-block;">{{ $penempatan->industri->alamat }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top; padding-bottom: 4px;">Pukul</td>
                        <td style="vertical-align: top; text-align: center;">:</td>
                        <td style="vertical-align: top;">07.30 WIB - selesai</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <p style="font-size: 11pt; line-height: 1.5; text-align: justify; margin-top: 15px; margin-bottom: 25px;">
        Demikian surat tugas ini dibuat untuk dapat dilaksanakan dengan penuh tanggung jawab.
    </p>

    <!-- Signature Page 1 -->
    <div style="float: right; width: 230px; font-size: 11pt; line-height: 1.4; margin-top: 10px;">
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
            <tr>
                <td width="40%">Ditetapkan Di</td>
                <td width="10%">:</td>
                <td>Tuban</td>
            </tr>
            <tr>
                <td>Pada Tanggal</td>
                <td>:</td>
                <td>{{ $tanggal_surat->translatedFormat('d F Y') }}</td>
            </tr>
        </table>
        <p style="margin: 8px 0 0 0; font-weight: normal;">Kepala SMK Negeri 3 Tuban</p>
        
        <div style="height: 65px; margin-top: 5px; margin-bottom: 5px; position: relative;">
            @if(file_exists(public_path('images/signature.png')))
                <img src="{{ public_path('images/signature.png') }}" style="height: 110px; width: auto; position: absolute; top: -15px; left: -20px; z-index: 1;">
            @endif
        </div>
        
        <p style="font-weight: bold; text-transform: uppercase; margin: 0; white-space: nowrap;">{{ $nama_pejabat }}</p>
        <p style="margin: 0; white-space: nowrap;">NIP. {{ $nip_pejabat }}</p>
    </div>
    <div style="clear: both;"></div>


    <div class="page-break"></div>

    <!-- ============================================ -->
    <!-- HALAMAN 2: SURAT PERINTAH PERJALANAN DINAS   -->
    <!-- ============================================ -->
    <table class="header-table" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td width="12%" style="text-align: left; vertical-align: middle;">
                @if(file_exists(public_path('images/logoprovinsijawatimur.png')))
                    <img src="{{ public_path('images/logoprovinsijawatimur.png') }}" style="height: 100px; width: auto; display: block;">
                @endif
            </td>
            <td width="76%" class="header-title">
                <p class="kop-prov">Pemerintah Provinsi Jawa Timur</p>
                <p class="kop-prov">Dinas Pendidikan</p>
                <h2 class="kop-school">SMK NEGERI 3 TUBAN</h2>
                <p class="kop-address">Jalan Bloso Desa Temandang Kecamatan Merakurak Kabupaten Tuban</p>
                <p class="kop-address" style="margin-top: 1px;">Telepon (0356) 711974</p>
            </td>
            <td width="12%"></td>
        </tr>
    </table>

    <div class="double-line"></div>

    <!-- Top Right Meta Box -->
    <div style="float: right; width: 300px; font-size: 10.5pt; line-height: 1.3; margin-bottom: 10px;">
        <table border="0" cellpadding="2" cellspacing="0" style="width: 100%;">
            <tr>
                <td width="35%">Lembar Ke</td>
                <td width="5%">:</td>
                <td></td>
            </tr>
            <tr>
                <td>Kode Nomor</td>
                <td>:</td>
                <td></td>
            </tr>
            <tr>
                <td>Nomor</td>
                <td>:</td>
                <td>{{ $nomor_surat }}</td>
            </tr>
        </table>
    </div>
    <div style="clear: both;"></div>

    <div style="text-align: center; margin-bottom: 15px; margin-top: 5px;">
        <h3 style="margin: 0; font-size: 13pt; font-weight: bold; letter-spacing: 0.5px; text-transform: uppercase;">SURAT PERINTAH PERJALANAN DINAS</h3>
    </div>

    <!-- Main Table -->
    <table border="1" cellspacing="0" cellpadding="5" style="width: 100%; border-collapse: collapse; font-size: 10.5pt; line-height: 1.4; border: 1.5px solid #000;">
        <tr>
            <td width="4%" style="text-align: center; vertical-align: top; border: 1px solid #000;">1</td>
            <td width="36%" style="vertical-align: top; border: 1px solid #000;">Kuasa Pengguna Anggaran</td>
            <td width="60%" style="vertical-align: top; border: 1px solid #000; font-weight: bold;">{{ $nama_pejabat }}</td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: top; border: 1px solid #000;">2</td>
            <td style="vertical-align: top; border: 1px solid #000;">Nama/NIP Pegawai Yang Diperintah</td>
            <td style="vertical-align: top; border: 1px solid #000;">
                <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                    <tr>
                        <td width="15%">a. Nama</td>
                        <td width="5%">:</td>
                        <td style="font-weight: bold;">{{ $guru->nama }}</td>
                    </tr>
                    <tr>
                        <td>b. NIP</td>
                        <td>:</td>
                        <td>{{ $guru->nip ?? '-' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: top; border: 1px solid #000;">3</td>
            <td style="vertical-align: top; border: 1px solid #000;">
                a. Pangkat dan Golongan<br>
                b. Jabatan / Instansi<br>
                c. Tingkat Biaya Perjalanan Dinas
            </td>
            <td style="vertical-align: top; border: 1px solid #000;">
                a. {{ $guru->pangkat_golongan ?? '-' }}<br>
                b. {{ strtoupper($guru->jabatan ?? 'GURU') }} / SMK Negeri 3 Tuban<br>
                c. -
            </td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: top; border: 1px solid #000;">4</td>
            <td style="vertical-align: top; border: 1px solid #000;">Maksud Perjalanan Dinas</td>
            <td style="vertical-align: top; border: 1px solid #000;">{{ $maksud_perjalanan }}</td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: top; border: 1px solid #000;">5</td>
            <td style="vertical-align: top; border: 1px solid #000;">Alat Angkutan Yang Dipergunakan</td>
            <td style="vertical-align: top; border: 1px solid #000;">{{ $alat_angkutan }}</td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: top; border: 1px solid #000;">6</td>
            <td style="vertical-align: top; border: 1px solid #000;">
                a. Tempat Berangkat<br>
                b. Tempat Tujuan
            </td>
            <td style="vertical-align: top; border: 1px solid #000;">
                a. {{ $tempat_berangkat }}<br>
                b. {{ $tempat_tujuan }}
            </td>
        </tr>
        @php
            $daysCount = $tanggal_perjalanan->diffInDays($tanggal_kembali) + 1;
            $words = [1 => 'Satu', 2 => 'Dua', 3 => 'Tiga', 4 => 'Empat', 5 => 'Lima', 6 => 'Enam', 7 => 'Tujuh', 8 => 'Delapan', 9 => 'Sembilan', 10 => 'Sepuluh'];
            $daysWord = $words[$daysCount] ?? '';
        @endphp
        <tr>
            <td style="text-align: center; vertical-align: top; border: 1px solid #000;">7</td>
            <td style="vertical-align: top; border: 1px solid #000;">
                a. Lamanya Perjalanan Dinas<br>
                b. Tanggal Berangkat<br>
                c. Tanggal harus kembali
            </td>
            <td style="vertical-align: top; border: 1px solid #000;">
                a. Selama {{ $daysCount }} {{ $daysWord ? '(' . $daysWord . ')' : '' }} Hari<br>
                b. {{ $tanggal_perjalanan->translatedFormat('d F Y') }}<br>
                c. {{ $tanggal_kembali->translatedFormat('d F Y') }}
            </td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: top; border: 1px solid #000;">8</td>
            <td style="vertical-align: top; border: 1px solid #000;">Pengikut :</td>
            <td style="vertical-align: top; border: 1px solid #000; padding: 0;">
                <table border="0" cellpadding="4" cellspacing="0" style="width: 100%;">
                    <tr>
                        <td width="50%" style="border-right: 1px solid #000; border-bottom: 1px solid #000; vertical-align: top;">a. Pangkat : -</td>
                        <td width="50%" style="border-bottom: 1px solid #000; vertical-align: top;">Keterangan :</td>
                    </tr>
                    <tr>
                        <td style="border-right: 1px solid #000; vertical-align: top;">b. Gol : -</td>
                        <td style="vertical-align: top;"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: top; border: 1px solid #000;">9</td>
            <td style="vertical-align: top; border: 1px solid #000;">Pembebanan Anggaran</td>
            <td style="vertical-align: top; border: 1px solid #000; padding: 0;">
                <table border="0" cellpadding="4" cellspacing="0" style="width: 100%;">
                    <tr>
                        <td colspan="2" style="border-bottom: 1px solid #000;">a. SKPD : SMK Negeri 3 Tuban</td>
                    </tr>
                    <tr>
                        <td width="50%" style="border-right: 1px solid #000; vertical-align: top;">b. Akun : -</td>
                        <td width="50%" style="vertical-align: top;">b. Prog : - &nbsp;&nbsp;&nbsp; Keg: - <br> Kode Rekening: -</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: top; border: 1px solid #000;">10</td>
            <td style="vertical-align: top; border: 1px solid #000;">Keterangan Lain</td>
            <td style="vertical-align: top; border: 1px solid #000;">-</td>
        </tr>
    </table>

    <!-- Signature Page 2 -->
    <div style="float: right; width: 230px; font-size: 10.5pt; line-height: 1.4; margin-top: 20px;">
        <p style="margin: 0;">Dikeluarkan Di : Tuban</p>
        <p style="margin: 0;">Tanggal : {{ $tanggal_surat->translatedFormat('d F Y') }}</p>
        <p style="margin: 8px 0 0 0; font-weight: normal;">Kepala SMK Negeri 3 Tuban</p>
        
        <div style="height: 65px; margin-top: 5px; margin-bottom: 5px; position: relative;">
            @if(file_exists(public_path('images/signature.png')))
                <img src="{{ public_path('images/signature.png') }}" style="height: 110px; width: auto; position: absolute; top: -15px; left: -20px; z-index: 1;">
            @endif
        </div>
        
        <p style="font-weight: bold; text-transform: uppercase; margin: 0; white-space: nowrap;">{{ $nama_pejabat }}</p>
        <p style="margin: 0; white-space: nowrap;">NIP. {{ $nip_pejabat }}</p>
    </div>
    <div style="clear: both;"></div>


    <div class="page-break"></div>

    <!-- ============================================ -->
    <!-- HALAMAN 3: LOG KEBERANGKATAN & KEDATANGAN    -->
    <!-- ============================================ -->
    <div style="font-size: 10pt; line-height: 1.4; margin-bottom: 15px;">
        <div style="float: left; width: 45%;">
        </div>
        <div style="float: right; width: 50%;">
            <table border="0" cellpadding="2" cellspacing="0" style="width: 100%;">
                <tr>
                    <td width="35%">Berangkat Dari</td>
                    <td width="5%">:</td>
                    <td>SMK NEGERI 3 TUBAN</td>
                </tr>
                <tr>
                    <td>(Tempat Kedudukan)</td>
                    <td>:</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Ke</td>
                    <td>:</td>
                    <td>{{ $tempat_tujuan }}</td>
                </tr>
                <tr>
                    <td>Pada Tanggal</td>
                    <td>:</td>
                    <td>{{ $tanggal_perjalanan->translatedFormat('d F Y') }}</td>
                </tr>
            </table>
            <p style="margin: 10px 0 0 0;">Kepala Sekolah</p>
            <div style="height: 40px;"></div>
            <p style="font-weight: bold; text-transform: uppercase; margin: 0;">{{ $nama_pejabat }}</p>
            <p style="margin: 0;">NIP. {{ $nip_pejabat }}</p>
        </div>
        <div style="clear: both;"></div>
    </div>

    <!-- Log Table -->
    <table border="1" cellspacing="0" cellpadding="4" style="width: 100%; border-collapse: collapse; font-size: 9.5pt; line-height: 1.3; border: 1.5px solid #000; margin-bottom: 8px;">
        <!-- Row I -->
        <tr>
            <td width="50%" style="vertical-align: top; border: 1px solid #000; height: 75px;">
                <table border="0" cellpadding="1" cellspacing="0" style="width: 100%;">
                    <tr><td width="30%">I. Tiba di</td><td width="5%">:</td><td>......................................</td></tr>
                    <tr><td>Pada Tanggal</td><td>:</td><td>......................................</td></tr>
                    <tr><td>Kepala</td><td>:</td><td>......................................</td></tr>
                </table>
                <div style="text-align: center; margin-top: 2px; font-size: 14pt; color: #777; line-height: 1;">&radic;</div>
                <div style="margin-top: 2px;">
                    (......................................................)<br>
                    NIP. .................................................
                </div>
            </td>
            <td width="50%" style="vertical-align: top; border: 1px solid #000; height: 75px;">
                <table border="0" cellpadding="1" cellspacing="0" style="width: 100%;">
                    <tr><td width="30%">Berangkat Dari</td><td width="5%">:</td><td>......................................</td></tr>
                    <tr><td>Ke</td><td>:</td><td>......................................</td></tr>
                    <tr><td>Pada Tanggal</td><td>:</td><td>......................................</td></tr>
                    <tr><td>Kepala</td><td>:</td><td>......................................</td></tr>
                </table>
                <div style="text-align: center; margin-top: 2px; font-size: 14pt; color: #777; line-height: 1;">&radic;</div>
                <div style="margin-top: 2px;">
                    (......................................................)<br>
                    NIP. .................................................
                </div>
            </td>
        </tr>
        <!-- Row II -->
        <tr>
            <td style="vertical-align: top; border: 1px solid #000; height: 75px;">
                <table border="0" cellpadding="1" cellspacing="0" style="width: 100%;">
                    <tr><td width="30%">II. Tiba di</td><td width="5%">:</td><td>......................................</td></tr>
                    <tr><td>Pada Tanggal</td><td>:</td><td>......................................</td></tr>
                    <tr><td>Kepala</td><td>:</td><td>......................................</td></tr>
                </table>
                <div style="margin-top: 5px;">
                    (......................................................)<br>
                    NIP. .................................................
                </div>
            </td>
            <td style="vertical-align: top; border: 1px solid #000; height: 75px;">
                <table border="0" cellpadding="1" cellspacing="0" style="width: 100%;">
                    <tr><td width="30%">Berangkat Dari</td><td width="5%">:</td><td>......................................</td></tr>
                    <tr><td>Ke</td><td>:</td><td>......................................</td></tr>
                    <tr><td>Pada Tanggal</td><td>:</td><td>......................................</td></tr>
                    <tr><td>Kepala</td><td>:</td><td>......................................</td></tr>
                </table>
                <div style="margin-top: 5px;">
                    (......................................................)<br>
                    NIP. .................................................
                </div>
            </td>
        </tr>
        <!-- Row III -->
        <tr>
            <td style="vertical-align: top; border: 1px solid #000; height: 75px;">
                <table border="0" cellpadding="1" cellspacing="0" style="width: 100%;">
                    <tr><td width="30%">III. Tiba di</td><td width="5%">:</td><td>......................................</td></tr>
                    <tr><td>Pada Tanggal</td><td>:</td><td>......................................</td></tr>
                    <tr><td>Kepala</td><td>:</td><td>......................................</td></tr>
                </table>
                <div style="margin-top: 5px;">
                    (......................................................)<br>
                    NIP. .................................................
                </div>
            </td>
            <td style="vertical-align: top; border: 1px solid #000; height: 75px;">
                <table border="0" cellpadding="1" cellspacing="0" style="width: 100%;">
                    <tr><td width="30%">Berangkat Dari</td><td width="5%">:</td><td>......................................</td></tr>
                    <tr><td>Ke</td><td>:</td><td>......................................</td></tr>
                    <tr><td>Pada Tanggal</td><td>:</td><td>......................................</td></tr>
                    <tr><td>Kepala</td><td>:</td><td>......................................</td></tr>
                </table>
                <div style="margin-top: 5px;">
                    (......................................................)<br>
                    NIP. .................................................
                </div>
            </td>
        </tr>
        <!-- Row IV -->
        <tr>
            <td style="vertical-align: top; border: 1px solid #000; padding-bottom: 5px;">
                <table border="0" cellpadding="1" cellspacing="0" style="width: 100%;">
                    <tr><td width="30%">IV. Tiba di</td><td width="5%">:</td><td>SMK Negeri 3 Tuban</td></tr>
                    <tr><td>Pada Tanggal</td><td>:</td><td>{{ $tanggal_kembali->translatedFormat('d F Y') }}</td></tr>
                </table>
                <p style="margin: 6px 0 0 0;">Kepala SMK Negeri 3 Tuban</p>
                
                <div style="height: 35px; margin-top: 2px; margin-bottom: 2px; position: relative;">
                    @if(file_exists(public_path('images/signature.png')))
                        <img src="{{ public_path('images/signature.png') }}" style="height: 65px; width: auto; position: absolute; top: -15px; left: -20px; z-index: 1;">
                    @endif
                </div>
                
                <p style="font-weight: bold; text-transform: uppercase; margin: 0;">{{ $nama_pejabat }}</p>
                <p style="margin: 0;">NIP. {{ $nip_pejabat }}</p>
            </td>
            <td style="vertical-align: middle; border: 1px solid #000; text-align: justify; padding: 8px;">
                Telah diperiksa dengan keterangan bahwa perjalanan tersebut di atas benar di lakukan atas perintahnya dan semata-mata untuk kepentingan jabatan dalam waktu yang sesingkat-singkatnya.
            </td>
        </tr>
        <!-- Row V -->
        <tr>
            <td colspan="2" style="border: 1px solid #000; padding: 4px;">
                <strong>V. Catatan Lain-Lain:</strong>
            </td>
        </tr>
        <!-- Row VI -->
        <tr>
            <td colspan="2" style="border: 1px solid #000; padding: 6px; text-align: justify; font-size: 8pt; line-height: 1.25;">
                <strong>VI. PERHATIAN:</strong><br>
                Pengguna Anggaran/Kuasa Pengguna Anggaran/Pejabat Pembuat Komitmen yang menerbitkan Surat Perjalanan Dinas (SPD), Pegawai yang melakukan perjalanan Dinas, para pejabat yang mengesahkan tanggal berangkat/tiba, serta bendahara pengeluaran bertanggung jawab berdasarkan peraturan-peraturan Keuangan Negara apabila negara menderita rugi akibat kesalahan, kelalaian, dan kealpaannya.
            </td>
        </tr>
    </table>

    <!-- Signature Page 3 -->
    <div style="float: right; width: 230px; font-size: 10pt; line-height: 1.3; margin-top: 5px;">
        <p style="margin: 0;">Kepala SMK Negeri 3 Tuban</p>
        
        <div style="height: 40px; margin-top: 2px; margin-bottom: 2px; position: relative;">
            @if(file_exists(public_path('images/signature.png')))
                <img src="{{ public_path('images/signature.png') }}" style="height: 75px; width: auto; position: absolute; top: -15px; left: -20px; z-index: 1;">
            @endif
        </div>
        
        <p style="font-weight: bold; text-transform: uppercase; margin: 0;">{{ $nama_pejabat }}</p>
        <p style="margin: 0;">NIP. {{ $nip_pejabat }}</p>
    </div>
    <div style="clear: both;"></div>


    <div class="page-break"></div>

    <!-- ============================================ -->
    <!-- HALAMAN 4: LAPORAN PERJALANAN DINAS          -->
    <!-- ============================================ -->
    <div style="text-align: center; margin-top: 15px; margin-bottom: 25px;">
        <h3 style="margin: 0; font-size: 13pt; font-weight: bold; text-decoration: underline; letter-spacing: 0.5px; text-transform: uppercase;">LAPORAN PERJALANAN DINAS</h3>
    </div>

    <!-- Report Table -->
    <table border="1" cellspacing="0" cellpadding="6" style="width: 100%; border-collapse: collapse; font-size: 10.5pt; line-height: 1.5; border: 1.5px solid #000; margin-bottom: 20px;">
        <tr>
            <td width="4%" style="text-align: center; vertical-align: top; border: 1px solid #000;">I</td>
            <td width="36%" style="vertical-align: top; border: 1px solid #000;">DASAR</td>
            <td width="60%" style="vertical-align: top; border: 1px solid #000;">
                Surat Perintah Perjalanan Dinas :<br>
                {{ $nomor_surat }}<br>
                Tanggal : {{ $tanggal_surat->translatedFormat('d F Y') }}
            </td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: top; border: 1px solid #000;">II</td>
            <td style="vertical-align: top; border: 1px solid #000;">MAKSUD DAN TUJUAN</td>
            <td style="vertical-align: top; border: 1px solid #000;">{{ $maksud_perjalanan }}</td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: top; border: 1px solid #000;">III</td>
            <td style="vertical-align: top; border: 1px solid #000;">WAKTU PERJALANAN</td>
            <td style="vertical-align: top; border: 1px solid #000;">
                {{ $tanggal_perjalanan->translatedFormat('d F Y') }} s.d. {{ $tanggal_kembali->translatedFormat('d F Y') }}
            </td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: top; border: 1px solid #000;">IV</td>
            <td style="vertical-align: top; border: 1px solid #000;">NAMA PETUGAS</td>
            <td style="vertical-align: top; border: 1px solid #000; font-weight: bold;">{{ $guru->nama }}</td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: top; border: 1px solid #000;">V</td>
            <td style="vertical-align: top; border: 1px solid #000;">DAERAH TUJUAN / PERTEMUAN</td>
            <td style="vertical-align: top; border: 1px solid #000;">{{ $tempat_tujuan }}</td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: top; border: 1px solid #000; height: 45px;">VI</td>
            <td style="vertical-align: top; border: 1px solid #000;">PETUNJUK/ARAHAN YANG DIBERIKAN</td>
            <td style="vertical-align: top; border: 1px solid #000;"></td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: top; border: 1px solid #000; height: 45px;">VII</td>
            <td style="vertical-align: top; border: 1px solid #000;">TUJUAN</td>
            <td style="vertical-align: top; border: 1px solid #000;"></td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: top; border: 1px solid #000; height: 45px;">VIII</td>
            <td style="vertical-align: top; border: 1px solid #000;">SARAN / TINDAKAN</td>
            <td style="vertical-align: top; border: 1px solid #000;"></td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: top; border: 1px solid #000; height: 45px;">IX</td>
            <td style="vertical-align: top; border: 1px solid #000;">LAIN-LAIN</td>
            <td style="vertical-align: top; border: 1px solid #000;"></td>
        </tr>
    </table>

    <!-- Signature Page 4 -->
    <div style="float: right; width: 250px; font-size: 10.5pt; line-height: 1.4; margin-top: 15px;">
        <p style="margin: 0;">Tuban, {{ $tanggal_kembali->translatedFormat('d F Y') }}</p>
        <p style="margin: 0; font-weight: bold;">Yang Bertanggungjawab,</p>
        <div style="height: 65px;"></div>
        <p style="font-weight: bold; margin: 0;">{{ $guru->nama }}</p>
        <p style="margin: 0;">NIP. {{ $guru->nip ?? '-' }}</p>
    </div>
    <div style="clear: both;"></div>

    <div style="margin-top: 25px; font-size: 9pt; line-height: 1.3; color: #000;">
        <p style="margin: 0; font-style: italic;">
            catatan :<br>
            Bila petugas lebih dari satu orang, maka yang menandatangani laporan perjalanan dinas ini adalah petugas yang dianggap paling bertanggung jawab
        </p>
    </div>

</body>
</html>
