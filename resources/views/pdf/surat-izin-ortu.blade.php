<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Izin Orang Tua - {{ $siswa->nama }}</title>
    <style>
        body { font-family: 'Times New Roman', serif; font-size: 12pt; line-height: 1.5; margin: 2cm; }
        .header { text-align: center; font-weight: bold; text-decoration: underline; margin-bottom: 30px; }
        .content table { width: 100%; margin-bottom: 20px; }
        .footer { float: right; width: 200px; margin-top: 50px; }
    </style>
</head>
<body>
    <div class="header">
        <h3>SURAT IZIN ORANG TUA / WALI</h3>
    </div>

    <div class="content">
        <p>Yang bertanda tangan di bawah ini:</p>
        <table>
            <tr><td width="150">Nama Orang Tua/Wali</td><td width="10">:</td><td><strong>{{ $siswa->nama_wali ?? '................................' }}</strong></td></tr>
            <tr><td>Alamat</td><td>:</td><td>{{ $siswa->alamat }}</td></tr>
        </table>

        <p>Adalah benar orang tua / wali dari siswa:</p>
        <table>
            <tr><td width="150">Nama Siswa</td><td width="10">:</td><td><strong>{{ $siswa->nama }}</strong></td></tr>
            <tr><td>NISN</td><td>:</td><td>{{ $siswa->nisn }}</td></tr>
            <tr><td>Kelas / Jurusan</td><td>:</td><td>{{ $siswa->kelas->nama_kelas ?? '-' }} / {{ $siswa->jurusan->nama_jurusan }}</td></tr>
        </table>

        <p>Menyatakan **MEMBERIKAN IZIN** kepada putra/putri kami untuk melaksanakan Praktik Kerja Industri (Prakerin) di <strong>{{ $penempatan->industri->nama_industri ?? '................................' }}</strong> selama periode yang telah ditentukan oleh sekolah.</p>
        
        <p>Demikian surat izin ini dibuat dengan sadar tanpa paksaan dari pihak manapun untuk dipergunakan sebagaimana mestinya.</p>
    </div>

    <div class="footer">
        <p>Tuban, {{ date('d F Y') }}</p>
        <p>Hormat Kami,</p>
        <div style="height: 80px;"></div>
        <p>( ........................................ )</p>
        <p style="font-size: 10pt; font-style: italic;">Nama Terang & Tanda Tangan</p>
    </div>
</body>
</html>