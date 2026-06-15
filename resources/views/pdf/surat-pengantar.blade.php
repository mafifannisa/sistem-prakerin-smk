<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Pengantar Prakerin - {{ $penempatan->siswa->nama }}</title>
    <style>
        @page {
            margin-top: 1cm;
            margin-right: 2cm;
            margin-bottom: 0.5cm;
            margin-left: 2.5cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10.5pt;
            line-height: 1.3;
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
        .meta-table {
            width: 100%;
            margin-bottom: 12px;
        }
        .meta-table td {
            vertical-align: top;
        }
        .student-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
            font-size: 10pt;
        }
        .student-table th, .student-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: middle;
        }
        .student-table th {
            text-align: center;
            font-weight: bold;
            background-color: #f8fafc;
        }
        .signature-wrapper {
            margin-top: 10px;
            width: 100%;
        }
        .signature {
            float: right;
            width: 230px;
            text-align: left;
            line-height: 1.3;
        }
        .signature p {
            margin: 1px 0;
        }
        .clear {
            clear: both;
        }
        
        /* Page Break Rules */
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    @php
        if (!isset($groupPlacements)) {
            $groupPlacements = collect([$penempatan]);
        }
    @endphp

    <!-- PAGE 1: SURAT PENGANTAR / PERMOHONAN -->
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

    <table class="meta-table" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width: 60%;">
                <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="70">Nomor</td>
                        <td width="15">:</td>
                        <td>{{ $nomor_surat }}</td>
                    </tr>
                    <tr>
                        <td>Lampiran</td>
                        <td>:</td>
                        <td>1 lembar</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;">Hal</td>
                        <td style="vertical-align: top;">:</td>
                        <td>Permohonan Praktik Kerja Industri<br>(PRAKERIN)</td>
                    </tr>
                </table>
            </td>
            <td style="width: 40%; text-align: right;">
                Tuban, {{ $tanggal_surat->translatedFormat('d F Y') }}
            </td>
        </tr>
    </table>

    <div style="margin-bottom: 12px; line-height: 1.3;">
        <p style="margin: 0 0 1px 0;"><strong>Yth. Pimpinan</strong></p>
        <p style="margin: 0 0 1px 0;"><strong>{{ $penempatan->industri->nama_industri ?? 'Perusahaan' }}</strong></p>
        <p style="margin: 0 0 1px 0;">{{ $penempatan->industri->alamat ?? '................................' }}</p>
        <p style="margin: 1px 0 0 0;">di</p>
        <p style="margin: 1px 0 0 20px;">Tempat</p>
    </div>

    <div style="text-align: justify; line-height: 1.5;">
        <p style="margin: 0 0 6px 0;">Dengan hormat,</p>
        <p style="text-indent: 40px; margin: 0 0 6px 0;">
            Dalam rangka meningkatkan kualitas Sumber Daya Manusia bidang teknologi industri, Kami mewajibkan kepada seluruh siswa untuk melaksanakan proses magang, agar terjadi keselarasan dan kesepadanan (link & match) antara pendidikan yang di dapat di sekolah dan pengalaman praktek di industri.
        </p>
        <p style="text-indent: 40px; margin: 0 0 6px 0;">
            Hal ini sesuai dengan kebijakan Menteri Pendidikan Nasional serta kurikulum yang berlaku. Untuk terlaksananya program tersebut, kami mohon bantuan dan partisipasi Bapak/Ibu dalam memberi kesempatan bagi siswa/siswi SMK Negeri 3 Tuban Tahun Ajaran {{ $penempatan->tahun_ajaran ?? '2025 / 2026' }}.
        </p>
        <p style="text-indent: 40px; margin: 0 0 6px 0;">
            Adapun pelaksanaan PRAKERIN direncanakan pada:
        </p>
    </div>

    <table class="student-table">
        <thead>
            <tr>
                <th width="8%">Periode</th>
                <th width="20%">Bulan</th>
                <th width="12%">Waktu</th>
                <th width="25%">Nama Siswa</th>
                <th width="15%">Nisn</th>
                <th width="8%">Jumlah Siswa</th>
                <th width="22%">Jurusan / Kelas</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;">1</td>
                <td style="text-align: center;">
                    {{ $penempatan->tanggal_mulai ? $penempatan->tanggal_mulai->translatedFormat('d M') : '................' }} s/d {{ $penempatan->tanggal_selesai ? $penempatan->tanggal_selesai->translatedFormat('d M Y') : '................' }}
                </td>
                <td style="text-align: center;">
                    @php
                        $mulai = $penempatan->tanggal_mulai;
                        $selesai = $penempatan->tanggal_selesai;
                        $diffInMonths = $mulai && $selesai ? round($mulai->diffInDays($selesai) / 30) : 2;
                    @endphp
                    {{ $diffInMonths }} Bulan
                </td>
                <td>
                    @foreach($groupPlacements as $gp)
                        {{ strtoupper($gp->siswa->nama) }}<br>
                    @endforeach
                </td>
                <td style="text-align: center;">
                    @foreach($groupPlacements as $gp)
                        {{ $gp->siswa->nisn }}<br>
                    @endforeach
                </td>
                <td style="text-align: center;">{{ count($groupPlacements) }}</td>
                <td style="text-align: center;">
                    @php
                        $jurusanKelas = [];
                        foreach($groupPlacements as $gp) {
                            $namaJurusan = $gp->siswa->jurusan->nama_jurusan ?? '-';
                            $kodeJurusan = $gp->siswa->jurusan->kode_jurusan ?? '';
                            $kelasNama = $gp->siswa->kelas->nama_kelas ?? '-';
                            
                            $formattedKelas = $kelasNama;
                            if ($kelasNama && $kelasNama !== '-' && $kodeJurusan) {
                                $parts = explode(' ', $kelasNama);
                                if (count($parts) >= 2) {
                                    $formattedKelas = $parts[0] . ' ' . $kodeJurusan . ' ' . implode(' ', array_slice($parts, 1));
                                } else {
                                    $formattedKelas = 'XII ' . $kodeJurusan . ' ' . $kelasNama;
                                }
                            }
                            
                            $jurusanKelas[] = $namaJurusan . ' / ' . $formattedKelas;
                        }
                        $jurusanKelas = array_unique($jurusanKelas);
                    @endphp
                    @foreach($jurusanKelas as $jk)
                        {{ $jk }}<br>
                    @endforeach
                </td>
            </tr>
        </tbody>
    </table>

    <div style="text-align: justify; line-height: 1.5; margin-top: 5px;">
        <p style="margin: 0 0 6px 0;">
            Demikian permohonan kami buat dengan harapan agar siswa/siswi kami dapat melaksanakan Prakerin di Perusahaan Bapak / Ibu, Apabila permohonan kami ini disetujui / tidak disetujui, kami mohon balasan dan dapat diberikan pada kami baik melalui, e-mail, telepon (0822 3703 8773, PANDHU PRAMAWATA, S.Pd.,Gr.) atau dikirim secara langsung.
        </p>
        <p style="margin: 0 0 10px 0; text-indent: 40px;">Atas perhatian dan kerjasama yang baik kami menyampaikan terima kasih.</p>
    </div>

    <div class="signature-wrapper">
        <div class="signature">
            <p>{{ $jabatan_pejabat ?? 'Kepala SMK Negeri 3 Tuban' }}</p>
            
            <div style="text-align: center; height: 75px; margin-top: 5px; margin-bottom: 5px;">
                @if(file_exists(public_path('images/signature.png')))
                    <img src="{{ public_path('images/signature.png') }}" style="height: 125px; width: auto; position: relative; top: -15px; margin-bottom: -15px; z-index: 1;">
                @endif
            </div>
            
            <p style="text-decoration: underline; font-weight: bold; text-transform: uppercase;">{{ $nama_pejabat ?? 'SHOLAHUDDIN, ST., M.SI' }}</p>
            <p>{{ $pangkat_pejabat ?? 'Pembina' }}</p>
            <p>NIP. {{ $nip_pejabat ?? '19680101 199003 1 001' }}</p>
        </div>
        <div class="clear"></div>
    </div>


    <!-- PAGE 2: SURAT BALASAN / JAWABAN DARI INDUSTRI -->
    <div class="page-break"></div>

    <div style="width: 100%; margin-top: 10px;">
        <div style="float: right; width: 320px; line-height: 1.3;">
            <p style="margin: 0;">Tuban, ............................................</p>
            <p style="margin: 10px 0 3px 0;">Kepada :</p>
            <p style="margin: 0;"><strong>Yth. Kepala SMK Negeri 3 Tuban</strong></p>
            <p style="margin: 0;">Jl. Bloso Temandang Merakurak Tuban.</p>
        </div>
        <div class="clear"></div>
    </div>

    <div style="margin-top: 25px; line-height: 1.5; text-align: justify;">
        <p style="margin: 0 0 10px 0;">Dengan hormat,</p>
        <p style="text-indent: 40px; margin: 0 0 15px 0;">
            Berdasarkan surat permohonan Praktek Kerja Industri (PRAKERIN) yang diajukan dengan nomor surat: <strong>{{ $nomor_surat }}</strong> Tanggal <strong>{{ $tanggal_surat->translatedFormat('d F Y') }}</strong>, maka kami menyatakan <strong>SANGGUP / TIDAK SANGGUP</strong>*) bekerjasama dengan SMK Negeri 3 Tuban untuk melaksanakan Prakerin di <strong>{{ $penempatan->industri->nama_industri }}</strong> - {{ $penempatan->industri->alamat }}, sesuai dengan jadwal yang telah direncanakan sekolah.
        </p>
        
        <p style="margin: 0 0 3px 0;">Catatan dari HRD :</p>
        <p style="border-bottom: 1px dotted #000; height: 20px; margin: 4px 0;"></p>
        <p style="border-bottom: 1px dotted #000; height: 20px; margin: 4px 0;"></p>
        <p style="border-bottom: 1px dotted #000; height: 20px; margin: 4px 0;"></p>
        
        <p style="text-indent: 40px; margin: 20px 0 20px 0;">
            Demikian surat ini dibuat untuk di tindak lanjuti dan dipergunakan sebagaimana mestinya. Atas perhatian dan kerjasamanya disampaikan terimakasih.
        </p>
    </div>

    <div style="margin-top: 30px; width: 100%;">
        <div style="float: right; width: 250px; text-align: left; line-height: 1.3;">
            <p style="margin: 0;">Direktur/Pimpinan</p>
            <p style="margin: 0;">Pembimbing DU/DI</p>
            <div style="height: 60px;"></div>
            <p style="margin: 0;">......................................................</p>
        </div>
        <div class="clear"></div>
    </div>

    <div style="margin-top: 60px; font-size: 9.5pt; line-height: 1.3; color: #555;">
        <p style="margin: 0;">*) Coret yang tidak perlu</p>
        <p style="margin: 0;">**) Di lengkapi dengan stempel DU/DI</p>
    </div>
</body>
</html>