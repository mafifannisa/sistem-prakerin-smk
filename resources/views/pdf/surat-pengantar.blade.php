<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Pengantar Prakerin - {{ $penempatan->siswa->nama }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.3; /* Spasi diperkecil agar muat 1 halaman */
            margin: 2cm 2.5cm; /* Margin standar surat resmi */
        }
        .header {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 16pt;
            text-transform: uppercase;
            font-weight: bold;
        }
        .header p {
            margin: 0;
            font-size: 11pt;
        }
        .content {
            text-align: justify;
        }
        /* Style untuk bagian Nomor dan Hal */
        .meta-surat {
            margin-bottom: 20px;
        }
        .meta-surat table {
            width: 100%;
        }
        .meta-surat td {
            vertical-align: top;
            padding-bottom: 3px;
        }
        /* Style untuk Biodata Siswa */
        .student-info {
            margin: 15px 0 15px 40px;
        }
        .student-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .student-info td {
            vertical-align: top;
            padding: 3px 0;
        }
        /* Mengunci lebar kolom agar titik dua (:) sejajar sempurna */
        .student-info .label { width: 120px; }
        .student-info .colon { width: 15px; text-align: center; }
        
        /* Style untuk Tanda Tangan dikanan bawah */
        .signature-wrapper {
            margin-top: 40px;
            width: 100%;
        }
        .signature {
            float: right; /* Mendorong ke sebelah kanan */
            width: 300px;
            text-align: left;
            padding-left: 30px;
        }
        .signature p {
            margin: 2px 0;
        }
        .signature-image {
            margin: 10px 0;
            height: 70px;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>SMK NEGERI 3 TUBAN</h2>
        <p>Jl. Doktor Wahidin Sudiro Husodo No. 123, Tuban, Jawa Timur</p>
        <p>Telp: (0356) 123456 Email: info@smkn3tuban.sch.id</p>
    </div>

    <div class="meta-surat">
        <table border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td width="70">Nomor</td>
                <td width="15">:</td>
                <td>{{ $nomor_surat }}</td>
            </tr>
            <tr>
                <td>Hal</td>
                <td>:</td>
                <td><strong>Permohonan Praktik Kerja Industri (Prakerin)</strong></td>
            </tr>
        </table>
    </div>

    <div class="content">
        <p style="margin-bottom: 15px;">
            Yth. Pimpinan <strong>{{ $penempatan->industri->nama_industri ?? 'Perusahaan' }}</strong><br>
            Di Tempat
        </p>

        <p>Dengan hormat,</p>
        <p>Melalui surat ini kami mengajukan permohonan agar siswa kami atas nama:</p>

        <div class="student-info">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="label">Nama</td>
                    <td class="colon">:</td>
                    <td><strong>{{ $penempatan->siswa->nama }}</strong></td>
                </tr>
                <tr>
                    <td class="label">NISN</td>
                    <td class="colon">:</td>
                    <td>{{ $penempatan->siswa->nisn }}</td>
                </tr>
                <tr>
                    <td class="label">Jurusan</td>
                    <td class="colon">:</td>
                    <td>{{ $penempatan->siswa->jurusan->nama_jurusan ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Posisi</td>
                    <td class="colon">:</td>
                    <td>{{ $penempatan->posisi_magang ?? 'Sesuai Jurusan' }}</td>
                </tr>
            </table>
        </div>

        <p>dapat melaksanakan Praktik Kerja Industri di perusahaan Bapak/Ibu pada periode 
            <strong>{{ $penempatan->tanggal_mulai ? \Carbon\Carbon::parse($penempatan->tanggal_mulai)->format('d F Y') : '................................' }}</strong> 
            s.d. 
            <strong>{{ $penempatan->tanggal_selesai ? \Carbon\Carbon::parse($penempatan->tanggal_selesai)->format('d F Y') : '................................' }}</strong>.
        </p>

        @php
            $kode = $penempatan->siswa->jurusan->kode_jurusan ?? '';
            $kompetensi = match($kode) {
                'RPL' => 'pengembangan perangkat lunak, pemrograman web, dan basis data',
                'TKJ' => 'administrasi jaringan, infrastruktur IT, dan keamanan komputer',
                'TEI' => 'elektronika industri, mikrokontroler, dan sistem otomasi',
                'TKR' => 'perawatan dan perbaikan mesin otomotif kendaraan ringan',
                'TSM' => 'perawatan dan perbaikan mesin sepeda motor',
                'DPIB' => 'desain pemodelan, informasi bangunan, dan arsitektur',
                'TB' => 'tata busana, pembuatan pola, dan desain pakaian',
                default => 'bidang kejuruan yang relevan sesuai kompetensi'
            };
        @endphp

        <p>Adapun siswa bersangkutan telah dibekali kompetensi dasar pada bidang <strong>{{ $kompetensi }}</strong>, sehingga diharapkan dapat mengaplikasikan serta meningkatkan pengetahuannya pada perusahaan yang Bapak/Ibu pimpin.</p>

        <p>Demikian surat permohonan ini kami sampaikan. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.</p>
    </div>
    
    <div class="signature-wrapper">
        <div class="signature">
            <p>Tuban, {{ $tanggal_surat->format('d F Y') }}</p>
            <p>Kepala SMK Negeri 3 Tuban</p>
            
            @if(file_exists(public_path('images/signature.png')))
            <div class="signature-image">
                <img src="{{ public_path('images/signature.png') }}" style="height: 70px;">
            </div>
            @else
            <div style="height: 70px;"></div> 
            @endif
            
            <p style="text-decoration: underline; font-weight: bold;">Drs. HERU SUSANTO, M.Pd</p>
            <p>NIP. 19680101 199003 1 001</p>
        </div>
        <div class="clear"></div>
    </div>
</body>
</html>