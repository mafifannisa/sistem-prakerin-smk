<!DOCTYPE html>
<html>
<head>
    <title>Rekap Laporan Magang</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0; padding: 0; }
        .header p { margin: 5px 0 0 0; color: #555; }
        table { w-full; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; text-align: center; }
        .text-center { text-align: center; }
    </style>
</head>
<body>

    <div class="header">
        <h2>{{ $judul }}</h2>
        <p>SMK Negeri 3 Tuban - Dicetak pada: {{ date('d M Y H:i') }} WIB</p>
    </div>

    <table style="width: 100%;">
        <thead>
    <tr>
        <th>No</th>
        <th>Nama Siswa</th>
        <th>Absen (%)</th>
        <th>Jurnal (%)</th>
        <th>Laporan PKL</th>
        <th>Nilai Industri</th>
        <th>SKOR AKHIR</th>
    </tr>
</thead>
<tbody>
    @foreach($penempatans as $index => $row)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $row->siswa->nama }}</td>
        <td>{{ $row->nilai_absen }}%</td>
        <td>{{ $row->nilai_jurnal }}%</td>
        <td>{{ $row->nilai_laporan_pkl }}</td>
        <td>{{ $row->nilai_perusahaan }}</td>
        <td style="font-weight: bold;">{{ $row->skor_akhir }}</td>
    </tr>
    @endforeach
</tbody>
    </table>

</body>
</html>