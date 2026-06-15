<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Dispensasi Prakerin - {{ $penempatan->siswa->nama }}</title>
    <style>
        @page {
            margin-top: 1.2cm;
            margin-right: 2cm;
            margin-bottom: 1.2cm;
            margin-left: 2.5cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11.5pt;
            line-height: 1.5;
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
        
        .info-table {
            width: 100%;
            margin-bottom: 15px;
        }
        .info-table td {
            vertical-align: top;
        }
        .details-table td {
            vertical-align: top;
            padding: 2px 0;
            font-size: 11.5pt;
        }
        
        .recipient {
            margin-bottom: 18px;
            line-height: 1.4;
            font-size: 11.5pt;
        }
        
        .body-content {
            text-align: justify;
            line-height: 1.5;
            font-size: 11.5pt;
        }
        .indent {
            text-indent: 45px;
        }
        
        .student-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 11.5pt;
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
    </style>
</head>
<body>
    <!-- KOP SURAT -->
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

    <!-- INFO SURAT & TANGGAL -->
    <table class="info-table" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td width="65%">
                <table border="0" cellpadding="0" cellspacing="0" class="details-table">
                    <tr>
                        <td width="75">Nomor</td>
                        <td width="15" style="text-align: center;">:</td>
                        <td>{{ $nomor_surat }}</td>
                    </tr>
                    <tr>
                        <td>Lampiran</td>
                        <td style="text-align: center;">:</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;">Hal</td>
                        <td style="vertical-align: top; text-align: center;">:</td>
                        <td style="vertical-align: top;">Dispensasi Praktek Kerja Industri<br>(PRAKERIN)</td>
                    </tr>
                </table>
            </td>
            <td width="35%" style="text-align: right; vertical-align: top; padding-top: 2px; font-size: 11.5pt;">
                Tuban, {{ $tanggal_surat->translatedFormat('d F Y') }}
            </td>
        </tr>
    </table>

    <!-- PENERIMA SURAT -->
    <div class="recipient">
        <p style="margin: 0 0 2px 0;"><strong>Yth. Pimpinan</strong></p>
        <p style="margin: 0 0 2px 0;"><strong>{{ strtoupper($penempatan->industri->nama_industri ?? 'Perusahaan') }}</strong></p>
        <p style="margin: 0 0 2px 0; max-width: 380px;">{{ $penempatan->industri->alamat ?? '................................' }}</p>
        <p style="margin: 2px 0 2px 0;">Di</p>
        <p style="margin: 0 0 0 35px;">Tempat</p>
    </div>

    <!-- ISI SURAT -->
    <div class="body-content">
        <p style="margin: 0 0 10px 0;">Dengan hormat,</p>
        
        @if($tipe_surat === 'kegiatan')
            <p class="indent" style="text-align: justify; margin: 0 0 12px 0;">
                Dikarenakan ada kegiatan <strong>{{ $nama_kegiatan }}</strong> di <strong>{{ $tempat_kegiatan }}</strong> tanggal <strong>{{ $tanggal_kegiatan }}</strong> , maka kami selaku pihak sekolah memohon agar Bapak/Ibu Pimpinan DU/DI memberikan ijin kepada siswa tersebut yang sedang prakerin untuk mengikuti kegiatan tersebut.
            </p>
            <p class="indent" style="margin: 0 0 8px 0;">
                Adapun nama siswa yang melaksanakan PRAKERIN yaitu:
            </p>
            
            <table class="student-table">
                <thead>
                    <tr>
                        <th width="6%" style="white-space: nowrap;"><strong>NO.</strong></th>
                        <th width="40%" style="white-space: nowrap;"><strong>NAMA</strong></th>
                        <th width="12%" style="white-space: nowrap;"><strong>KELAS</strong></th>
                        <th width="42%" style="white-space: nowrap;"><strong>KOMPETENSI KEAHLIAN</strong></th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach($groupPlacements as $gp)
                        <tr>
                            <td style="text-align: center;">{{ $no++ }}.</td>
                            <td style="padding-left: 8px;">{{ strtoupper($gp->siswa->nama) }}</td>
                            <td style="text-align: center; white-space: nowrap;">{{ strtoupper($gp->siswa->kelas->nama_kelas ?? '-') }}</td>
                            <td style="text-align: center; white-space: nowrap;">{{ strtoupper($gp->siswa->jurusan->nama_jurusan ?? '-') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="indent" style="text-align: justify; margin: 0 0 12px 0;">
                Sehubungan dengan akan adanya kegiatan <strong>{{ $nama_kegiatan_sas }}</strong>, maka kami selaku pihak sekolah memohon agar Bapak/Ibu Pimpinan memberikan ijin tanggal <strong>{{ $tanggal_izin_sas }}</strong> kepada siswa/siswi <strong>{{ $kelas_sas }}</strong> SMK Negeri 3 Tuban yang sedang prakerin untuk hadir ke sekolah. Demikian juga nanti penjemputan siswa prakerin akan kami lakukan lebih awal dari jadwal penjemputan. Yaitu tanggal <strong>{{ $tanggal_penjemputan_sas }}</strong> adapun agenda kegiatan terlampir.
            </p>
        @endif
        
        <p class="indent" style="text-align: justify; margin: 15px 0 0 0;">
            Demikian surat dispensasi ini dibuat, atas perhatian dan kerjasama yang baik kami ucapkan terima kasih.
        </p>
    </div>

    <!-- TANDA TANGAN (SIGNATURE BLOCK) -->
    <div style="margin-top: 35px; width: 100%;">
        <div style="float: right; width: 200px; text-align: left; line-height: 1.4; font-size: 11.5pt;">
            <p style="margin: 0; white-space: nowrap;">{{ $jabatan_pejabat }}</p>
            
            <div style="height: 65px; margin-top: 5px; margin-bottom: 5px; position: relative;">
                @if(file_exists(public_path('images/signature.png')))
                    <img src="{{ public_path('images/signature.png') }}" style="height: 110px; width: auto; position: absolute; top: -15px; left: -20px; z-index: 1;">
                @endif
            </div>
            
            <p style="font-weight: bold; text-transform: uppercase; margin: 0; white-space: nowrap;">{{ $nama_pejabat }}</p>
            <p style="margin: 0; white-space: nowrap;">{{ $pangkat_pejabat }}</p>
            <p style="margin: 0; white-space: nowrap;">NIP. {{ $nip_pejabat }}</p>
        </div>
        <div style="clear: both;"></div>
    </div>
</body>
</html>
