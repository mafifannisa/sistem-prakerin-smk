@extends('layouts.siswa')

@section('title', 'Download Sertifikat')

@section('header_breadcrumb', 'DOWNLOAD SERTIFIKAT')
@section('header_title', 'UNDUH SERTIFIKAT')

@section('content')

<div class="p-0">
    @if(!$bolehDownload)
        <!-- Belum Bisa Download -->
        <div class="bg-red-50/50 backdrop-blur-md border border-red-200/50 rounded-2xl p-8 mb-8 shadow-[0_8px_30px_rgb(0,0,0,0.02)]">
            <div class="flex flex-col sm:flex-row items-start gap-4">
                <div class="w-16 h-16 bg-red-100/60 rounded-2xl flex items-center justify-center flex-shrink-0 border border-red-200/20 shadow-inner">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-extrabold text-red-850 mb-2">🔒 Sertifikat Belum Tersedia</h3>
                    <p class="text-red-755 font-semibold text-sm mb-4">
                        Sertifikat hanya dapat diunduh setelah Anda memenuhi persyaratan berikut:
                    </p>
                    <ul class="text-sm text-red-700 space-y-2.5 list-none font-semibold">
                        <li class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                            Menyelesaikan program magang (status: <span class="text-red-900 font-extrabold">Selesai</span>)
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                            Upload laporan PKL dan disetujui
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                            Semua jurnal harian disetujui pembimbing
                        </li>
                    </ul>
                    <a href="{{ route('siswa.cek-status') }}" 
                       class="inline-block mt-6 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-extrabold rounded-xl shadow-md transition duration-200 transform hover:-translate-y-0.5">
                        📊 Cek Status Magang
                     </a>
                </div>
            </div>
        </div>
    @else
        @php
            $predikatDb = $penempatan->nilai->predikat ?? 'E';
            $predikatTeks = 'KURANG';
            if ($predikatDb == 'A') $predikatTeks = 'BAIK SEKALI';
            elseif ($predikatDb == 'B') $predikatTeks = 'BAIK';
            elseif ($predikatDb == 'C') $predikatTeks = 'CUKUP';
            elseif ($predikatDb == 'D' || $predikatDb == 'E') $predikatTeks = 'KURANG';
        @endphp

        <!-- Preview Sertifikat -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Preview Sertifikat (Kiri-Tengah) -->
            <div class="lg:col-span-2">
                <div class="bg-white/40 border border-white/50 backdrop-blur-md rounded-2xl p-8 flex justify-center overflow-hidden shadow-inner">
                    <div class="w-full max-w-2xl aspect-[1.414/1] relative p-12 flex flex-col transform transition duration-500 bg-white shadow-xl">
                        <div class="absolute inset-0 border-[16px] border-white pointer-events-none"></div>
                        <div class="absolute inset-[20px] border border-white pointer-events-none"></div>
                        
                        <h2 class="text-lg font-bold text-gray-800 text-center uppercase underline mt-4 mb-1">Surat Keterangan Praktik Kerja Industri</h2>
                        <h3 class="text-sm font-bold text-gray-800 text-center mb-6">(P R A K E R I N)</h3>
                        
                        <table class="w-10/12 mx-auto text-[11px] mb-4 text-left">
                            <tr><td class="py-0.5 w-32 text-gray-700 font-bold uppercase">Nama</td><td class="w-2">:</td><td class="font-bold uppercase text-gray-900">{{ $siswa->nama }}</td></tr>
                            <tr><td class="py-0.5 text-gray-700">Tempat/Tgl Lahir</td><td>:</td><td class="text-gray-900">{{ $siswa->tempat_lahir ?? 'Tuban' }}, {{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->isoFormat('DD MMMM YYYY') }}</td></tr>
                            <tr><td class="py-0.5 text-gray-700">Nomor Induk Siswa</td><td>:</td><td class="text-gray-900">{{ $siswa->nisn }}</td></tr>
                            <tr><td class="py-0.5 text-gray-700">Kompetensi Keahlian</td><td>:</td><td class="text-gray-900">{{ $siswa->jurusan->nama_jurusan ?? '-' }}</td></tr>
                        </table>
                        
                        <p class="text-[11px] text-center text-gray-800 px-6 mb-6 leading-relaxed">
                            Adalah Siswa Sekolah Menengah Kejuruan (SMK) Negeri 3 Tuban, yang telah melakukan Praktik Kerja Industri di :<br>
                            <strong>{{ $penempatan->industri->nama_industri ?? '-' }}</strong><br>
                            <strong>{{ $penempatan->industri->alamat ?? '-' }}</strong><br>
                            Pada tanggal {{ \Carbon\Carbon::parse($penempatan->tanggal_mulai ?? now())->isoFormat('DD MMMM YYYY') }} sampai dengan {{ \Carbon\Carbon::parse($penempatan->tanggal_selesai ?? now())->isoFormat('DD MMMM YYYY') }}<br>
                            Pada Bidang Studi Keahlian : {{ $siswa->jurusan->nama_jurusan ?? '-' }}, dengan perolehan predikat : <strong class="uppercase">{{ $predikatTeks }}</strong>
                        </p>
                        
                        <div class="flex justify-between items-end px-6 text-[11px] text-center mt-auto mb-2 text-gray-800">
                            <div class="w-1/3">
                                <p class="mb-10">Mengetahui,<br>Kepala DU/DI</p>
                                <p class="font-bold border-b border-black inline-block px-2">{{ $penempatan->industri->nama_hr ?? 'Pimpinan DU/DI' }}</p>
                            </div>
                            <div class="w-1/3 flex justify-center">
                                <div class="w-12 h-16 border border-gray-400 flex items-center justify-center text-gray-400 text-[10px]">3x4</div>
                            </div>
                            <div class="w-1/3">
                                <p class="mb-10">Tuban, {{ \Carbon\Carbon::parse($penempatan->tanggal_selesai ?? now())->isoFormat('DD MMMM YYYY') }}<br>Pembimbing DU/DI,</p>
                                <p class="font-bold border-b border-black inline-block px-2">{{ $penempatan->industri->pembimbing_magang ?? 'Pembimbing Industri' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Opsi Berbagi -->
                <div class="mt-6 bg-white/65 backdrop-blur-md border border-white/50 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.02)] p-6">
                    <h3 class="text-base font-extrabold text-gray-800 mb-4 tracking-wide flex items-center gap-2">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                        </svg>
                        Bagikan Sertifikat
                    </h3>
                    <div class="flex flex-wrap gap-3">
                        <button onclick="sendEmail()" 
                                class="flex items-center gap-2 px-4 py-2.5 bg-green-50/10 hover:bg-green-50/25 border border-green-50/20 hover:border-green-50/30 rounded-xl transition text-xs font-bold text-green-700 duration-200 font-bold">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Kirim Email
                        </button>

                        @if($bolehDownload)
                            <a href="{{ route('siswa.download.sertifikat.pdf') }}" 
                               class="flex items-center gap-2 px-4 py-2.5 bg-orange-500 hover:bg-orange-600 text-white rounded-xl shadow-md transition text-xs font-bold duration-200 font-bold">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Download PDF
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar (Kanan) -->
            <div class="space-y-6">
                <!-- Ringkasan Nilai -->
                <div class="bg-white/65 backdrop-blur-md border border-white/50 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.02)] p-6">
                    <h3 class="text-base font-extrabold text-gray-800 mb-5 tracking-wide">Ringkasan Nilai</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between mb-1.5">
                                <span class="text-xs text-gray-500 font-bold uppercase tracking-wider">Sikap & Kedisiplinan</span>
                                <span class="text-xs font-extrabold text-orange-600">{{ $penempatan->nilai->nilai_sikap ?? 0 }}/100</span>
                            </div>
                            <div class="w-full bg-gray-200/60 rounded-full h-2 border border-gray-150/10">
                                <div class="bg-orange-500 h-2 rounded-full transition-all duration-500" style="width: {{ $penempatan->nilai->nilai_sikap ?? 0 }}%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between mb-1.5">
                                <span class="text-xs text-gray-500 font-bold uppercase tracking-wider">Keterampilan Bidang</span>
                                <span class="text-xs font-extrabold text-orange-600">{{ $penempatan->nilai->nilai_keterampilan ?? 0 }}/100</span>
                            </div>
                            <div class="w-full bg-gray-200/60 rounded-full h-2 border border-gray-150/10">
                                <div class="bg-orange-500 h-2 rounded-full transition-all duration-500" style="width: {{ $penempatan->nilai->nilai_keterampilan ?? 0 }}%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between mb-1.5">
                                <span class="text-xs text-gray-500 font-bold uppercase tracking-wider">Pengetahuan Bidang</span>
                                <span class="text-xs font-extrabold text-orange-655">{{ $penempatan->nilai->nilai_pengetahuan ?? 0 }}/100</span>
                            </div>
                            <div class="w-full bg-gray-200/60 rounded-full h-2 border border-gray-150/10">
                                <div class="bg-orange-500 h-2 rounded-full transition-all duration-500" style="width: {{ $penempatan->nilai->nilai_pengetahuan ?? 0 }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between mb-1.5">
                                <span class="text-xs text-gray-500 font-bold uppercase tracking-wider">{{ $penempatan->nilai->kegiatan_1 ?? 'Kegiatan Teknis 1' }}</span>
                                <span class="text-xs font-extrabold text-orange-600">{{ $penempatan->nilai->nilai_1 ?? 0 }}/100</span>
                            </div>
                            <div class="w-full bg-gray-200/60 rounded-full h-2 border border-gray-150/10">
                                <div class="bg-orange-500 h-2 rounded-full transition-all duration-500" style="width: {{ $penempatan->nilai->nilai_1 ?? 0 }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between mb-1.5">
                                <span class="text-xs text-gray-500 font-bold uppercase tracking-wider">{{ $penempatan->nilai->kegiatan_2 ?? 'Kegiatan Teknis 2' }}</span>
                                <span class="text-xs font-extrabold text-orange-600">{{ $penempatan->nilai->nilai_2 ?? 0 }}/100</span>
                            </div>
                            <div class="w-full bg-gray-200/60 rounded-full h-2 border border-gray-150/10">
                                <div class="bg-orange-500 h-2 rounded-full transition-all duration-500" style="width: {{ $penempatan->nilai->nilai_2 ?? 0 }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between mb-1.5">
                                <span class="text-xs text-gray-500 font-bold uppercase tracking-wider">{{ $penempatan->nilai->kegiatan_3 ?? 'Kegiatan Teknis 3' }}</span>
                                <span class="text-xs font-extrabold text-orange-600">{{ $penempatan->nilai->nilai_3 ?? 0 }}/100</span>
                            </div>
                            <div class="w-full bg-gray-200/60 rounded-full h-2 border border-gray-150/10">
                                <div class="bg-orange-500 h-2 rounded-full transition-all duration-500" style="width: {{ $penempatan->nilai->nilai_3 ?? 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-5 border-t border-gray-150/50">
                        <div class="flex justify-between items-center mb-3">
                            <span class="font-extrabold text-gray-700 text-sm font-bold">Rata-rata Akhir</span>
                            <span class="text-2xl font-black text-green-600">{{ $penempatan->nilai->nilai_akhir ?? 0 }}</span>
                        </div>
                        <div class="text-center">
                            <span class="inline-flex px-4 py-1.5 bg-green-50 text-green-700 text-xs font-extrabold rounded-full uppercase tracking-wider border border-green-200/50 font-bold">
                                PREDIKAT: {{ $predikatTeks }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Informasi Penting -->
                <div class="bg-orange-50/50 backdrop-blur-md border border-orange-200/50 rounded-2xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.01)]">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-orange-100/60 rounded-xl flex items-center justify-center flex-shrink-0 border border-orange-200/20">
                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-orange-850 text-sm mb-1 tracking-wide">Informasi Penting</h4>
                            <p class="text-xs text-orange-700 leading-relaxed font-semibold">
                                Sertifikat digital ini sah dan dapat diverifikasi melalui QR Code yang tertera pada dokumen asli PDF. Jika terdapat kesalahan data, harap hubungi bagian kurikulum atau admin Prakerin.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
function sendEmail() {
    const subject = encodeURIComponent('Sertifikat Prakerin - {{ $siswa->nama }}');
    const body = encodeURIComponent('Saya telah menyelesaikan Praktik Kerja Industri (Prakerin).');
    window.open(`https://mail.google.com/mail/?view=cm&fs=1&su=${subject}&body=${body}`, '_blank');
}
</script>
@endsection