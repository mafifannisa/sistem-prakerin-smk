<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Tugas Prakerin - {{ $penempatan->siswa->nama }}</title>
    <style>
        @page {
            margin-top: 1cm;
            margin-right: 2cm;
            margin-bottom: 0.5cm;
            margin-left: 2.5cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.4;
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
        .title {
            text-align: center;
            margin-bottom: 20px;
        }
        .title h4 {
            margin: 0;
            font-size: 12pt;
            text-decoration: underline;
            font-weight: bold;
            text-transform: uppercase;
        }
        .title p {
            margin: 2px 0 0 0;
            font-size: 12pt;
        }
        .content {
            text-align: justify;
            line-height: 1.5;
            font-size: 12pt;
        }
        .meta-table {
            width: 100%;
            margin: 10px 0;
            font-size: 12pt;
            line-height: 1.5;
        }
        .meta-table td {
            vertical-align: top;
            padding: 2px 0;
        }
        .meta-table .label {
            width: 160px;
        }
        .meta-table .colon {
            width: 15px;
            text-align: center;
        }
        .student-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 11pt;
            line-height: 1.5;
        }
        .student-table th, .student-table td {
            border: 1px solid #000;
            padding: 5px 8px;
            vertical-align: middle;
        }
        .student-table th {
            text-align: center;
            font-weight: bold;
        }
        .signature-wrapper {
            margin-top: 30px;
            width: 100%;
        }
        .signature {
            float: right;
            width: 230px;
            text-align: left;
            line-height: 1.3;
            font-size: 12pt;
        }
        .signature p {
            margin: 1px 0;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>
    @php
        if (!isset($groupPlacements)) {
            $groupPlacements = collect([$penempatan]);
        }
    @endphp

    <!-- HEADER -->
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
                <p class="kop-address">Jl. Bloso Ds. Temandang Kec. Merakurak Kab. Tuban</p>
                <p class="kop-address" style="margin-top: 1px;">Telp (0356) 711974, 7131506</p>
            </td>
            <td width="12%"></td>
        </tr>
    </table>

    <!-- DOUBLE LINE UNDER HEADER -->
    <div class="double-line"></div>

    <!-- TITLE -->
    <div class="title">
        <h4>SURAT TUGAS SISWA</h4>
        <p>Nomor : {{ $nomor_surat }}</p>
    </div>

    <!-- CONTENT -->
    <div class="content">
        <p style="margin: 0 0 5px 0;">Yang bertanda tangan di bawah ini :</p>
        
        <table class="meta-table" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td class="label">Nama</td>
                <td class="colon">:</td>
                <td><strong>{{ $nama_pemberi }}</strong></td>
            </tr>
            <tr>
                <td class="label">NIP</td>
                <td class="colon">:</td>
                <td>{{ $nip_pemberi }}</td>
            </tr>
            <tr>
                <td class="label">Pangkat / golongan</td>
                <td class="colon">:</td>
                <td>{{ $pangkat_pemberi }}</td>
            </tr>
            <tr>
                <td class="label">Jabatan</td>
                <td class="colon">:</td>
                <td>{{ $jabatan_pemberi }}</td>
            </tr>
            <tr>
                <td class="label">Alamat sekolah</td>
                <td class="colon">:</td>
                <td>{{ $alamat_sekolah }}</td>
            </tr>
        </table>

        <p style="margin: 15px 0 5px 0; font-weight: bold; text-align: center; text-decoration: underline;">MEMBERI TUGAS KEPADA :</p>

        <!-- STUDENT TABLE -->
        <table class="student-table">
            <thead>
                <tr>
                    <th width="8%">No</th>
                    <th width="42%">Nama Siswa</th>
                    <th width="15%">Kelas</th>
                    <th width="35%">Jurusan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groupPlacements as $index => $gp)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}.</td>
                        <td>{{ strtoupper($gp->siswa->nama) }}</td>
                        <td style="text-align: center;">
                            @php
                                $kelasNama = $gp->siswa->kelas->nama_kelas ?? '-';
                                $parts = explode(' ', $kelasNama);
                                $grade = count($parts) > 0 ? $parts[0] : $kelasNama;
                            @endphp
                            {{ strtoupper($grade) }}
                        </td>
                        <td>{{ strtoupper($gp->siswa->jurusan->nama_jurusan ?? '-') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- DETAILS -->
        <table class="meta-table" border="0" cellpadding="0" cellspacing="0" style="margin-top: 15px;">
            <tr>
                <td class="label">Tugas</td>
                <td class="colon">:</td>
                <td>{{ $keterangan_tugas }} <strong>{{ strtoupper($penempatan->industri->nama_industri) }}</strong></td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td class="colon">:</td>
                <td>{{ $penempatan->industri->alamat }}</td>
            </tr>
            <tr>
                <td class="label">Waktu</td>
                <td class="colon">:</td>
                <td>
                    @php
                        if ($penempatan->tanggal_mulai && $penempatan->tanggal_selesai) {
                            $mulai = $penempatan->tanggal_mulai;
                            $selesai = $penempatan->tanggal_selesai;
                            if ($mulai->format('Y') === $selesai->format('Y')) {
                                if ($mulai->format('m') === $selesai->format('m')) {
                                    $waktu = $mulai->translatedFormat('d') . ' s/d ' . $selesai->translatedFormat('d F Y');
                                } else {
                                    $waktu = $mulai->translatedFormat('d F') . ' s/d ' . $selesai->translatedFormat('d F Y');
                                }
                            } else {
                                $waktu = $mulai->translatedFormat('d F Y') . ' s/d ' . $selesai->translatedFormat('d F Y');
                            }
                        } else {
                            $waktu = '..........................................';
                        }
                    @endphp
                    {{ $waktu }}
                </td>
            </tr>
        </table>

        <p style="margin-top: 15px; text-indent: 40px; text-align: justify;">
            Demikian surat tugas ini dibuat untuk dilaksanakan dengan penuh tanggung jawab.
        </p>
    </div>

    <!-- SIGNATURE -->
    <div class="signature-wrapper">
        <div class="signature">
            <p>Tuban, {{ $tanggal_surat->translatedFormat('d F Y') }}</p>
            <p>{{ $jabatan_pemberi }}</p>
            
            <div style="text-align: center; height: 75px; margin-top: 5px; margin-bottom: 5px;">
                @if(file_exists(public_path('images/signature.png')))
                    <img src="{{ public_path('images/signature.png') }}" style="height: 125px; width: auto; position: relative; top: -15px; margin-bottom: -15px; z-index: 1;">
                @endif
            </div>
            
            <p style="text-decoration: underline; font-weight: bold; text-transform: uppercase;">{{ $nama_pemberi }}</p>
            <p>{{ $pangkat_pemberi }}</p>
            <p>NIP. {{ $nip_pemberi }}</p>
        </div>
        <div class="clear"></div>
    </div>
</body>
</html>
