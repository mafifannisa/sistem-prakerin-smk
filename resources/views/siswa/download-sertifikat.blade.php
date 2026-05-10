@extends('layouts.siswa')

@section('title', 'Download Sertifikat')

@section('content')
<header class="bg-white border-b border-gray-200 px-8 py-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Unduh Sertifikat</h1>
            <p class="text-sm text-gray-500 mt-1">Unduh sertifikat resmi Prakerin Anda sebagai bukti kompetensi</p>
        </div>
        <div class="flex items-center gap-4">
            <button class="px-6 py-2 border-2 border-orange-500 text-orange-600 font-semibold rounded-xl hover:bg-orange-50 transition">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                </svg>
                Bagikan
            </button>
            @if($bolehDownload)
                <a href="{{ route('siswa.download.sertifikat.pdf') }}" 
                   class="px-6 py-2 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-xl transition">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download PDF
                </a>
            @endif
        </div>
    </div>
</header>

<div class="p-8">
    @if(!$bolehDownload)
        <!-- Belum Bisa Download -->
        <div class="bg-red-50 border-2 border-red-200 rounded-2xl p-8 mb-8">
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-red-800 mb-2">🔒 Sertifikat Belum Tersedia</h3>
                    <p class="text-red-700 mb-4">
                        Sertifikat hanya dapat diunduh setelah:
                    </p>
                    <ul class="text-sm text-red-600 space-y-2 list-disc list-inside">
                        <li>Menyelesaikan program magang (status: <strong>Selesai</strong>)</li>
                        <li>Upload laporan PKL dan disetujui</li>
                        <li>Semua jurnal harian disetujui pembimbing</li>
                    </ul>
                    <a href="{{ route('siswa.cek-status') }}" 
                       class="inline-block mt-6 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition">
                        📊 Cek Status Magang
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- Preview Sertifikat -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Preview Sertifikat (Kiri-Tengah) -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg border-4 border-orange-100 p-8">
                    <div class="bg-gradient-to-br from-orange-50 to-white rounded-xl p-12 border-2 border-orange-200">
                        <!-- Header Sertifikat -->
                        <div class="text-center mb-8">
                            <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <h2 class="text-3xl font-bold text-gray-800 mb-2">SERTIFIKAT PRAKERIN</h2>
                            <div class="w-24 h-1 bg-orange-500 mx-auto"></div>
                        </div>

                        <!-- Konten Sertifikat -->
                        <div class="text-center mb-8">
                            <p class="text-gray-600 mb-4">Diberikan kepada:</p>
                            <h1 class="text-4xl font-bold text-gray-800 mb-6">{{ strtoupper($siswa->nama) }}</h1>
                            <p class="text-gray-700 leading-relaxed max-w-2xl mx-auto">
                                Telah menyelesaikan Praktik Kerja Industri (Prakerin) di 
                                <strong>{{ $penempatan->industri->nama_industri ?? '-' }}</strong> 
                                pada periode Tahun Pelajaran 2023/2024 dengan hasil yang sangat memuaskan.
                            </p>
                        </div>

                        <!-- Tanda Tangan -->
                        <div class="flex justify-between mt-16 px-12">
                            <div class="text-center">
                                <div class="h-20 mb-2"></div>
                                <p class="font-bold text-gray-800">Kepala Sekolah</p>
                                <p class="text-sm text-gray-600 mt-1">Drs. HERU SUSANTO, M.Pd</p>
                                <p class="text-xs text-gray-500">NIP. 19680101 199003 1 001</p>
                            </div>
                            
                            <div class="text-center">
                                <div class="h-20 mb-2"></div>
                                <p class="font-bold text-gray-800">Pembimbing DUDI</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $penempatan->industri->nama_industri ?? '-' }}</p>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="mt-12 pt-6 border-t border-orange-200 text-center text-xs text-gray-500">
                            <p>Sertifikat ini diterbitkan secara elektronik oleh SMK Negeri 3 Tuban</p>
                            <p>Nomor: SKL/{{ $siswa->nisn }}/2026</p>
                        </div>
                    </div>
                </div>

                <!-- Opsi Berbagi -->
                <div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <svg class="w-6 h-6 inline mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                        </svg>
                        Opsi Berbagi
                    </h3>
                    <div class="flex flex-wrap gap-3">
                        <button onclick="copyLink()" 
                                class="flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                            Salin Tautan Verifikasi
                        </button>
                        
                        <button onclick="shareToLinkedIn()" 
                                class="flex items-center gap-2 px-4 py-2 bg-blue-100 hover:bg-blue-200 rounded-lg transition">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                            Posting ke LinkedIn
                        </button>
                        
                        <button onclick="sendEmail()" 
                                class="flex items-center gap-2 px-4 py-2 bg-green-100 hover:bg-green-200 rounded-lg transition">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Kirim Email
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sidebar (Kanan) -->
            <div class="space-y-6">
                <!-- Ringkasan Nilai -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Ringkasan Nilai</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-sm text-gray-600">Kedisiplinan</span>
                                <span class="text-sm font-bold text-orange-600">{{ $nilai['kedisiplinan'] }}/100</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-orange-500 h-2 rounded-full" style="width: {{ $nilai['kedisiplinan'] }}%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-sm text-gray-600">Kerja Sama</span>
                                <span class="text-sm font-bold text-orange-600">{{ $nilai['kerja_sama'] }}/100</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-orange-500 h-2 rounded-full" style="width: {{ $nilai['kerja_sama'] }}%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-sm text-gray-600">Inisiatif</span>
                                <span class="text-sm font-bold text-orange-600">{{ $nilai['inisiatif'] }}/100</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-orange-500 h-2 rounded-full" style="width: {{ $nilai['inisiatif'] }}%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-sm text-gray-600">Keahlian Kompetensi</span>
                                <span class="text-sm font-bold text-orange-600">{{ $nilai['keahlian_kompetensi'] }}/100</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-orange-500 h-2 rounded-full" style="width: {{ $nilai['keahlian_kompetensi'] }}%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-bold text-gray-800">Rata-rata Akhir</span>
                            <span class="text-2xl font-bold text-green-600">{{ $nilai['rata_rata'] }}</span>
                        </div>
                        <div class="text-center">
                            <span class="px-4 py-2 bg-green-100 text-green-700 text-sm font-bold rounded-full">
                                PREDIKAT: {{ strtoupper($nilai['predikat']) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Informasi Penting -->
                <div class="bg-orange-50 border border-orange-200 rounded-2xl p-6">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-orange-800 mb-2">Informasi Penting</h4>
                            <p class="text-sm text-orange-700 leading-relaxed">
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
function copyLink() {
    const dummyLink = '{{ route('siswa.download.sertifikat') }}';
    navigator.clipboard.writeText(dummyLink).then(() => {
        alert('Tautan verifikasi berhasil disalin!');
    });
}

function shareToLinkedIn() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent('Saya telah menyelesaikan Prakerin di {{ $penempatan->industri->nama_industri ?? "SMK Negeri 3 Tuban" }}');
    window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${url}&summary=${title}`, '_blank');
}

function sendEmail() {
    const subject = encodeURIComponent('Sertifikat Prakerin - {{ $siswa->nama }}');
    const body = encodeURIComponent('Saya telah menyelesaikan Praktik Kerja Industri (Prakerin). Lihat sertifikat saya di: ' + window.location.href);
    window.open(`mailto:?subject=${subject}&body=${body}`, '_self');
}
</script>
@endsection