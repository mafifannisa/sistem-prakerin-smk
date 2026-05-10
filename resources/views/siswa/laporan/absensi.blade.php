@extends('layouts.siswa')

@section('title', 'Absensi Harian')

@section('content')
<header class="bg-white border-b border-gray-200 px-8 py-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Absensi Harian</h1>
            <p class="text-sm text-gray-500 mt-1">Isi presensi kehadiran harian dengan foto bukti</p>
        </div>
        <div class="text-sm text-gray-600">{{ tanggal_indonesia() }}</div>
    </div>
</header>

<div class="p-8">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 flex items-center gap-3">
            <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-green-700">{{ session('success') }}</span>
        </div>
    @endif
    
    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-center gap-3">
            <svg class="w-6 h-6 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-red-700">{{ session('error') }}</span>
        </div>
    @endif

    <!-- LOCK MESSAGE: Jika Belum Boleh Absen -->
    @if(!isset($bolehAbsen) || !$bolehAbsen)
        <div class="bg-red-50 border-2 border-red-200 rounded-2xl p-8 mb-8">
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-red-800 mb-2">🔒 Absensi Terkunci</h3>
                    <p class="text-red-700 mb-4">{{ $pesanLock ?? 'Anda belum dapat mengakses absensi.' }}</p>
                    
                    @if(!$penempatan)
                        <div class="bg-white rounded-xl p-4 border border-red-200">
                            <p class="text-sm text-red-600 font-semibold mb-2">📌 Yang Perlu Dilakukan:</p>
                            <ol class="text-sm text-red-700 space-y-1 list-decimal list-inside">
                                <li>Buka menu <strong>Cek Status Magang</strong></li>
                                <li>Pilih mitra magang yang diinginkan</li>
                                <li>Ajukan pengajuan tempat magang</li>
                                <li>Tunggu approval dari TU dan Pimpinan</li>
                                <li>Setelah disetujui, absensi akan terbuka otomatis</li>
                            </ol>
                            <a href="{{ route('siswa.cek-status') }}" 
                               class="inline-block mt-4 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition">
                                📍 Buka Cek Status Magang
                            </a>
                        </div>
                    @elseif($penempatan->status === 'pending')
                        <div class="bg-white rounded-xl p-4 border border-yellow-200">
                            <p class="text-sm text-yellow-700 font-semibold mb-2">⏳ Status Pengajuan:</p>
                            <div class="flex items-center gap-3 mb-3">
                                <span class="px-4 py-2 bg-yellow-100 text-yellow-700 text-sm font-bold rounded-full">
                                    {{ ucfirst($penempatan->status) }}
                                </span>
                                <span class="text-sm text-yellow-600">
                                    {{ $penempatan->industri->nama_industri ?? '-' }}
                                </span>
                            </div>
                            <p class="text-sm text-yellow-700">
                                Pengajuan Anda sedang diverifikasi. Absensi akan terbuka setelah mendapat approval.
                            </p>
                        </div>
                    @elseif($penempatan->status === 'rejected')
                        <div class="bg-white rounded-xl p-4 border border-red-200">
                            <p class="text-sm text-red-700 font-semibold mb-2">❌ Pengajuan Ditolak</p>
                            <p class="text-sm text-red-600 mb-3">
                                {{ $penempatan->catatan_industri ?? 'Silakan hubungi TU untuk informasi lebih lanjut.' }}
                            </p>
                            <a href="{{ route('siswa.cek-status') }}" 
                               class="inline-block px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition">
                                🔄 Ajukan Ulang
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @else
        <!-- FORM ABSENSI NORMAL (Jika Sudah Boleh Absen) -->
        <!-- Info Card -->
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 mb-8">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 mb-2">Informasi Absensi</h3>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Isi absensi setiap hari setelah hadir di tempat magang</li>
                        <li>• Upload foto selfie sebagai bukti kehadiran</li>
                        <li>• Pilih status: Hadir, Izin, Sakit, atau Alpha</li>
                        <li>• ⚠️ <strong class="text-red-600">Jika tidak mengisi absensi 1 hari, otomatis tercatat Alpha</strong></li>
                        <li>• Tanggal absensi otomatis hari ini (tidak dapat diubah)</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Form Absensi -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">📝 Isi Absensi Hari Ini</h2>
                    
                    @if($sudahAbsenHariIni)
                        <div class="text-center py-8">
                            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="font-bold text-gray-800 mb-2">Anda Sudah Absen Hari Ini</h3>
                            <p class="text-sm text-gray-500">Terima kasih, absensi Anda sudah tercatat</p>
                        </div>
                    @else
                        <form action="{{ route('siswa.laporan.absensi') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            
                            <!-- Tanggal (Read-Only) -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Tanggal Absensi *
                                </label>
                                <div class="relative">
                                    <input type="date" name="tanggal" required 
                                           value="{{ date('Y-m-d') }}"
                                           readonly
                                           class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-xl text-gray-500 cursor-not-allowed">
                                    <div class="absolute right-4 top-1/2 transform -translate-y-1/2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">📅 Tanggal otomatis hari ini (tidak dapat diubah)</p>
                            </div>
                            
                            <!-- Status Kehadiran -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Status Kehadiran *
                                </label>
                                <select name="status" required 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">-- Pilih Status --</option>
                                    <option value="hadir">✅ Hadir</option>
                                    <option value="izin">📋 Izin</option>
                                    <option value="sakit">🤒 Sakit</option>
                                    <option value="alpha">❌ Alpha</option>
                                </select>
                            </div>
                            
                            <!-- Jam Masuk -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Jam Masuk
                                </label>
                                <input type="time" name="jam_masuk" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <!-- Jam Pulang -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Jam Pulang
                                </label>
                                <input type="time" name="jam_pulang" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <!-- Keterangan -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Keterangan (Opsional)
                                </label>
                                <textarea name="keterangan" rows="3" 
                                          placeholder="Contoh: Hadir tepat waktu, atau alasan izin/sakit..."
                                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            </div>
                            
                            <!-- Foto Bukti -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Foto Bukti Kehadiran *
                                </label>
                                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-500 transition">
                                    <input type="file" name="bukti_foto" accept="image/*" required 
                                           id="fotoPreview" 
                                           class="hidden" 
                                           onchange="previewImage(this)">
                                    <label for="fotoPreview" class="cursor-pointer">
                                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <p class="text-sm text-gray-600 mb-1">Klik untuk upload foto</p>
                                        <p class="text-xs text-gray-400">Max 2MB (JPG, PNG)</p>
                                    </label>
                                    <img id="preview" class="hidden mt-4 max-h-48 mx-auto rounded-lg shadow-md">
                                </div>
                            </div>
                            
                            <!-- Submit Button -->
                            <button type="submit" 
                                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-4 rounded-xl transition duration-200">
                                📤 Kirim Absensi
                            </button>
                            
                            <p class="text-xs text-gray-500 text-center">
                                Pastikan data yang diisi sudah benar
                            </p>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Tabel Riwayat Absensi -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-800">📊 Riwayat Absensi</h2>
                        <div class="flex gap-2">
                            <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                                Hadir: {{ $absensis->where('status', 'hadir')->count() }}
                            </span>
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full">
                                Izin: {{ $absensis->where('status', 'izin')->count() }}
                            </span>
                            <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">
                                Alpha: {{ $absensis->where('status', 'alpha')->count() }}
                            </span>
                        </div>
                    </div>

                    @if($absensis->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Jam Masuk</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Jam Pulang</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Keterangan</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Bukti Foto</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($absensis as $absen)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 text-sm text-gray-800">
                                                {{ $absen->tanggal->format('d M Y') }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full
                                                    @if($absen->status === 'hadir') bg-green-100 text-green-700
                                                    @elseif($absen->status === 'izin') bg-yellow-100 text-yellow-700
                                                    @elseif($absen->status === 'sakit') bg-blue-100 text-blue-700
                                                    @else bg-red-100 text-red-700 @endif">
                                                    {{ ucfirst($absen->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
    {{ $absen->jam_masuk ? \Carbon\Carbon::parse($absen->jam_masuk)->format('H:i') : '-' }}
</td>
<td class="px-6 py-4 text-sm text-gray-600">
    {{ $absen->jam_pulang ? \Carbon\Carbon::parse($absen->jam_pulang)->format('H:i') : '-' }}
</td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ Str::limit($absen->keterangan, 30) ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4">
    @if($absen->bukti_foto)
        <a href="{{ asset('storage/' . $absen->bukti_foto) }}" target="_blank" class="block inline-block">
            <img src="{{ asset('storage/' . $absen->bukti_foto) }}" alt="Bukti Absen" 
                 class="w-16 h-16 object-cover rounded-lg border border-gray-200 hover:opacity-80 transition shadow-sm">
        </a>
    @else
        <span class="text-gray-400 text-sm font-medium bg-gray-100 px-3 py-1 rounded-full">Kosong</span>
    @endif
</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $absensis->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-20 h-20 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="text-gray-500 font-medium">Belum ada riwayat absensi</p>
                            <p class="text-sm text-gray-400 mt-2">Silakan isi absensi harian Anda</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Script Preview Image -->
<script>
function previewImage(input) {
    const preview = document.getElementById('preview');
    const file = input.files[0];
    
    if (file) {
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran foto maksimal 2MB!');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    } else {
        preview.src = '';
        preview.classList.add('hidden');
    }
}
</script>
@endsection