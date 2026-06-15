@extends('layouts.admin')

@section('title', 'Generate Sertifikat')

@section('content')
<div class="p-8 max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Generate Sertifikat Magang</h1>
            <p class="text-gray-500 mt-2 text-sm">Pilih siswa dan tinjau sertifikat kompetensi industri secara otomatis.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <button type="button" id="btnSelectAll" onclick="toggleSelectAll()" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-bold rounded-lg transition shadow-sm border border-gray-300">
                ☑️ Pilih Semua
            </button>

            <form id="formBatchZip" action="{{ route('admin.generate-sertifikat.batch-zip') }}" method="POST" class="m-0">
                @csrf
                <input type="hidden" name="selected_ids" id="selectedIdsInput" value="">
                <button type="button" onclick="submitBatchZip()" class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Cetak Masal (ZIP)
                </button>
            </form>
        </div>
    </div>

    <div class="mb-8">
        <div class="flex items-center justify-between relative">
            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-0.5 bg-gray-200 -z-10"></div>
            
            <div class="flex items-center gap-2 bg-gray-50 pr-4">
                <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm transition-colors duration-300">1</div>
                <span class="font-semibold text-blue-600 text-sm transition-colors duration-300">Pilih Siswa</span>
            </div>
            
            <div class="flex items-center gap-2 bg-gray-50 px-4">
                <div id="step2-circle" class="w-8 h-8 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center font-bold text-sm transition-colors duration-300">2</div>
                <span id="step2-text" class="font-medium text-gray-500 text-sm transition-colors duration-300">Preview Depan & Belakang</span>
            </div>
            
            <div class="flex items-center gap-2 bg-gray-50 pl-4">
                <div id="step3-circle" class="w-8 h-8 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center font-bold text-sm transition-colors duration-300">3</div>
                <span id="step3-text" class="font-medium text-gray-500 text-sm transition-colors duration-300">Selesai & Download</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <div class="lg:col-span-4 space-y-4">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-[600px]">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="font-bold text-gray-800 text-sm">Daftar Siswa</h3>
                    <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-md">{{ $pengajuans->total() ?? count($pengajuans) }} Siswa</span>
                </div>
                
                <div class="p-4 border-b border-gray-100">
                    <div class="relative">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" id="searchInput" placeholder="Cari nama atau NISN..." class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
                    </div>
                </div>

                <div class="overflow-y-auto flex-1 p-2 space-y-1" id="studentList">
                    @forelse($pengajuans as $item)
                        
                        <div class="student-item flex items-center p-2 rounded-xl transition border border-transparent hover:bg-gray-50 cursor-pointer"
                             data-id="{{ $item->id }}"
                             data-nama="{{ $item->siswa->nama }}"
                             data-nisn="{{ $item->siswa->nisn }}"
                             data-industri="{{ $item->industri->nama_industri ?? '-' }}"
                             data-predikat="{{ $item->nilai->predikat ?? '-' }}"
                             data-sikap="{{ round($item->nilai->nilai_sikap ?? 0) }}"
                             data-keterampilan="{{ round($item->nilai->nilai_keterampilan ?? 0) }}"
                             data-pengetahuan="{{ round($item->nilai->nilai_pengetahuan ?? 0) }}"
                             data-akhir="{{ round($item->nilai->nilai_akhir ?? 0) }}"
                             data-kegiatan1="{{ $item->nilai->kegiatan_1 ?? 'Membuat Table Disposisi di Excel' }}"
                             data-nilai1="{{ round($item->nilai->nilai_1 ?? 0) }}"
                             data-kegiatan2="{{ $item->nilai->kegiatan_2 ?? 'Membuat Table foto di Word' }}"
                             data-nilai2="{{ round($item->nilai->nilai_2 ?? 0) }}"
                             data-kegiatan3="{{ $item->nilai->kegiatan_3 ?? 'Memproses dan Remove Background Foto' }}"
                             data-nilai3="{{ round($item->nilai->nilai_3 ?? 0) }}"
                             data-jurusan="{{ $item->siswa->jurusan->nama_jurusan ?? '-' }}"
                             data-ttl="{{ $item->siswa->tempat_lahir ?? 'Tuban' }}, {{ $item->siswa->tanggal_lahir ? \Carbon\Carbon::parse($item->siswa->tanggal_lahir)->isoFormat('DD MMMM YYYY') : '-' }}"
                             data-pimpinan="{{ $item->industri->nama_hr ?? 'Pimpinan DU/DI' }}"
                             data-pembimbing="{{ $item->industri->pembimbing_magang ?? 'Pembimbing Industri' }}"
                             data-selesai="{{ $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai)->isoFormat('DD MMMM YYYY') : \Carbon\Carbon::now()->isoFormat('DD MMMM YYYY') }}"
                             data-alamat="{{ $item->industri->alamat ?? '-' }}"
                             data-mulai="{{ $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai)->isoFormat('DD MMMM YYYY') : '-' }}"
                             onclick="previewStudent(this)">
                            
                            <div class="pl-2 pr-4 border-r border-gray-100" onclick="event.stopPropagation();">
                                <input type="checkbox" class="student-checkbox w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer" value="{{ $item->id }}" onclick="checkSelection();">
                            </div>

                            <div class="flex-1 flex items-center gap-3 pl-3 py-1" title="Klik untuk lihat preview Sertifikat">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm flex-shrink-0 avatar-circle bg-gray-200 text-gray-700 transition-colors">
                                    {{ substr($item->siswa->nama, 0, 2) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-800 text-sm truncate student-name">{{ $item->siswa->nama }}</h4>
                                    <p class="text-xs text-gray-500 truncate student-nisn">NISN: {{ $item->siswa->nisn }}</p>
                                </div>
                                <div class="check-icon text-blue-500 hidden transition-opacity pr-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </div>
                            </div>
                        </div>

                    @empty
                        <div class="p-8 text-center text-gray-500">
                            <p class="text-sm">Belum ada siswa yang memiliki nilai lengkap.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="lg:col-span-8 flex flex-col">
            
            <div id="step2Container" class="flex flex-col h-full opacity-50 pointer-events-none transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex gap-2">
                        <button id="tabDepan" onclick="switchTab('depan')" class="px-4 py-2 bg-white border border-gray-200 rounded-t-lg font-bold text-sm text-gray-800 shadow-sm border-b-0 transition-all">Sisi Depan</button>
                        <button id="tabBelakang" onclick="switchTab('belakang')" class="px-4 py-2 bg-gray-50 border border-gray-200 rounded-t-lg font-medium text-sm text-gray-500 hover:bg-white hover:text-gray-800 transition-all border-b-gray-200">Daftar Nilai (Belakang)</button>
                    </div>
                </div>

                <div class="bg-gray-100 border border-gray-200 rounded-b-2xl rounded-tr-2xl p-8 flex-1 flex items-center justify-center min-h-[500px] overflow-hidden relative shadow-inner">
                                     <div id="canvasDepan" class="bg-white shadow-2xl w-full max-w-2xl aspect-[1.414/1] relative p-12 flex flex-col transform transition duration-500 mx-auto">
                        <img id="customBorderDepan" class="absolute inset-0 w-full h-full object-fill pointer-events-none hidden" style="z-index:0;" src="" />
                        <div class="absolute inset-0 border-[16px] border-white pointer-events-none" style="z-index:1;"></div>
                        <div class="absolute inset-[20px] border border-white pointer-events-none" style="z-index:1;"></div>
                        
                        <h2 class="text-lg font-bold text-gray-800 text-center uppercase underline mt-4 mb-1" style="position:relative;z-index:2;">Surat Keterangan Praktik Kerja Industri</h2>
                        <h3 class="text-sm font-bold text-gray-800 text-center mb-6" style="position:relative;z-index:2;">(P R A K E R I N)</h3>
                        
                        <table class="w-10/12 mx-auto text-[11px] mb-4 text-left" style="position:relative;z-index:2;">
                            <tr><td class="py-0.5 w-32">Nama</td><td class="w-2">:</td><td class="font-bold uppercase" id="previewNama">NAMA SISWA</td></tr>
                            <tr><td class="py-0.5">Tempat/Tgl Lahir</td><td>:</td><td id="previewTtl">Tuban, 01 Januari 2000</td></tr>
                            <tr><td class="py-0.5">Nomor Induk Siswa</td><td>:</td><td id="previewNisn">00000000</td></tr>
                            <tr><td class="py-0.5">Kompetensi Keahlian</td><td>:</td><td id="previewJurusan">Rekayasa Perangkat Lunak</td></tr>
                        </table>
                        
                        <p class="text-[11px] text-center text-gray-800 px-6 mb-6 leading-relaxed" style="position:relative;z-index:2;">
                            Adalah Siswa Sekolah Menengah Kejuruan (SMK) Negeri 3 Tuban, yang telah melakukan Praktik Kerja Industri di :<br>
                            <strong id="previewIndustri">Perusahaan</strong><br>
                            <strong id="previewAlamat">Alamat Industri</strong><br>
                            Pada tanggal &nbsp; <span id="previewTglMulai">01 Januari 2024</span> &nbsp; sampai dengan &nbsp; <span id="previewTglSelesaiText">30 Juni 2024</span><br>
                            Pada Bidang Studi Keahlian : <span id="previewJurusanParagraph">Jurusan</span>, dengan perolehan predikat : <strong id="previewPredikat" class="uppercase">-</strong>
                        </p>
                        
                        <div class="flex justify-between items-end px-6 text-[11px] text-center mt-auto mb-2" style="position:relative;z-index:2;">
                            <div class="w-1/3">
                                <p class="mb-10">Mengetahui,<br>Kepala DU/DI</p>
                                <p class="font-bold border-b border-black inline-block px-2" id="previewPimpinan">Pimpinan DU/DI</p>
                            </div>
                            <div class="w-1/3 flex justify-center">
                                <div class="w-12 h-16 border border-gray-400 flex items-center justify-center text-gray-400 text-[10px]">3x4</div>
                            </div>
                             <div class="w-1/3">
                                 <p class="mb-10"><span id="previewTglSelesai">Tuban, {{ date('d F Y') }}</span><br>Pembimbing DU/DI,</p>
                                 <p class="font-bold border-b border-black inline-block px-2" id="previewPembimbing">Pembimbing Industri</p>
                             </div>
                        </div>
                    </div>

                    <div id="canvasBelakang" class="hidden bg-white shadow-2xl w-full max-w-2xl aspect-[1.414/1] relative p-8 flex flex-col transform transition duration-500 mx-auto">
                        <img id="customBorderBelakang" class="absolute inset-0 w-full h-full object-fill pointer-events-none hidden" style="z-index:0;" src="" />
                        <div class="absolute inset-4 border border-gray-200 pointer-events-none" style="z-index:1;"></div>
                        
                        <h3 class="text-center font-bold text-xs text-gray-800 mb-1 uppercase" style="position:relative;z-index:2;">Daftar Nilai Hasil Praktik Kerja Industri</h3>
                        <p class="text-center text-[11px] text-gray-600 mb-4 font-bold" id="previewNamaBelakang" style="position:relative;z-index:2;">Nama Siswa: -</p>

                        <div class="grid grid-cols-2 gap-4 mt-1" style="position:relative;z-index:2;">
                            <!-- Kolom Kiri -->
                            <div>
                                <table class="w-full text-[10px] text-left border-collapse border border-gray-400 mb-4">
                                    <tr><th colspan="4" class="bg-gray-100 p-1 border border-gray-400">A. NILAI TEKNIS</th></tr>
                                    <tr>
                                        <th class="border border-gray-400 text-center w-8 p-1">No</th>
                                        <th class="border border-gray-400 text-center p-1">Komponen Yang Dinilai</th>
                                        <th class="border border-gray-400 text-center w-12 p-1">Angka</th>
                                        <th class="border border-gray-400 text-center w-20 p-1">Huruf</th>
                                    </tr>
                                    <tr><td class="border border-gray-400 text-center p-1">1</td><td class="border border-gray-400 p-1" id="previewKegiatan1">Membuat Table Disposisi di Excel</td><td class="border border-gray-400 text-center p-1 font-bold" id="previewNilai1">0</td><td class="border border-gray-400 p-1 text-[8px]" id="previewHuruf1">-</td></tr>
                                    <tr><td class="border border-gray-400 text-center p-1">2</td><td class="border border-gray-400 p-1" id="previewKegiatan2">Membuat Table foto di World</td><td class="border border-gray-400 text-center p-1 font-bold" id="previewNilai2">0</td><td class="border border-gray-400 p-1 text-[8px]" id="previewHuruf2">-</td></tr>
                                    <tr><td class="border border-gray-400 text-center p-1">3</td><td class="border border-gray-400 p-1" id="previewKegiatan3">Memproses dan Remove Background Foto</td><td class="border border-gray-400 text-center p-1 font-bold" id="previewNilai3">0</td><td class="border border-gray-400 p-1 text-[8px]" id="previewHuruf3">-</td></tr>
                                </table>

                                <table class="w-full text-[10px] text-left border-collapse border border-gray-850 border-2">
                                    <tr>
                                        <th class="border border-gray-400 text-center p-1 w-2/3">Rata-rata A+B</th>
                                        <th class="border border-gray-400 text-center p-1 w-1/3 font-bold text-xs" id="previewAkhir">0</th>
                                    </tr>
                                    <tr>
                                        <th class="border border-gray-400 text-center p-1">Predikat</th>
                                        <th class="border border-gray-400 text-center p-1 font-bold uppercase text-xs" id="previewPredikatB">BAIK</th>
                                    </tr>
                                </table>
                            </div>

                            <!-- Kolom Kanan -->
                            <div>
                                <table class="w-full text-[10px] text-left border-collapse border border-gray-400 mb-4">
                                    <tr><th colspan="4" class="bg-gray-100 p-1 border border-gray-400">B. NILAI NON TEKNIS</th></tr>
                                    <tr>
                                        <th class="border border-gray-400 text-center w-8 p-1">No</th>
                                        <th class="border border-gray-400 text-center p-1">Komponen Yang Dinilai</th>
                                        <th class="border border-gray-400 text-center w-12 p-1">Angka</th>
                                        <th class="border border-gray-400 text-center w-20 p-1">Huruf</th>
                                    </tr>
                                    <tr><td class="border border-gray-400 text-center p-1">1</td><td class="border border-gray-400 p-1">Kedisiplinan</td><td class="border border-gray-400 text-center p-1 font-bold" id="previewSikap">0</td><td class="border border-gray-400 p-1 text-[8px]" id="previewHurufSikap">-</td></tr>
                                    <tr><td class="border border-gray-400 text-center p-1">2</td><td class="border border-gray-400 p-1">Kerjasama</td><td class="border border-gray-400 text-center p-1 font-bold" id="previewKeterampilan">0</td><td class="border border-gray-400 p-1 text-[8px]" id="previewHurufKeterampilan">-</td></tr>
                                    <tr><td class="border border-gray-400 text-center p-1">3</td><td class="border border-gray-400 p-1">Inisiatif dan Kreativitas</td><td class="border border-gray-400 text-center p-1 font-bold" id="previewPengetahuan">0</td><td class="border border-gray-400 p-1 text-[8px]" id="previewHurufPengetahuan">-</td></tr>
                                </table>

                                <div class="text-[9px] text-gray-800 font-bold p-1">
                                    SKALA RENTANG NILAI :
                                    <table class="w-full font-normal text-[8px] mt-1 text-gray-700">
                                        <tr>
                                            <td>- 86 - 100 = Baik Sekali</td>
                                            <td>- 56 - 69 = Cukup</td>
                                        </tr>
                                        <tr>
                                            <td>- 70 - 85 = Baik</td>
                                            <td>- 40 - 55 = Kurang</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION TEMPLATE BORDER CUSTOM (OPSIONAL) -->
                <div class="bg-white border border-gray-150/60 rounded-2xl shadow-sm p-6 mt-6">
                    <h3 class="text-sm font-black text-gray-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                        🎨 Template Border Custom (Opsional)
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Upload Section -->
                        <div class="md:col-span-1 border-2 border-dashed border-gray-200 hover:border-blue-400 rounded-xl p-4 transition-all flex flex-col items-center justify-center text-center relative bg-gray-50/50">
                            <form id="formUploadBorder" enctype="multipart/form-data" class="m-0 flex flex-col items-center justify-center">
                                @csrf
                                <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-xs font-bold text-gray-600 mb-1">Unggah Gambar Border</span>
                                <span class="text-[10px] text-gray-400 mb-3 block">PNG, JPG atau JPEG (Maks. 5MB)</span>
                                
                                <input type="file" id="borderFileInput" name="border_image" accept="image/*" class="hidden" onchange="handleBorderUpload(this)">
                                <button type="button" onclick="document.getElementById('borderFileInput').click()" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-[11px] font-bold rounded-lg transition shadow-sm">
                                    Pilih File
                                </button>
                            </form>
                            <div id="uploadLoader" class="absolute inset-0 bg-white/80 rounded-xl flex items-center justify-center flex-col hidden">
                                <div class="w-8 h-8 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                                <span class="text-[10px] font-bold text-blue-700 mt-2">Mengunggah...</span>
                            </div>
                        </div>

                        <!-- Thumbnail Grid & Customization -->
                        <div class="md:col-span-2 flex flex-col justify-between">
                            <div>
                                <span class="text-xs font-bold text-gray-500 block mb-2">Pilih Template Border:</span>
                                <div class="flex flex-wrap gap-3 max-h-[120px] overflow-y-auto pr-1" id="borderGridContainer">
                                    <!-- No Border -->
                                    <div class="border-card border-2 border-blue-600 rounded-lg p-1.5 cursor-pointer relative flex flex-col items-center justify-center w-16 h-16 bg-white transition select-none" onclick="selectBorder(null)" id="borderCardNone">
                                        <div class="w-full h-full border border-dashed border-gray-300 rounded flex items-center justify-center text-[9px] font-black text-gray-400 uppercase">
                                            Polos
                                        </div>
                                        <div class="absolute -top-1.5 -right-1.5 bg-blue-600 text-white w-4 h-4 rounded-full flex items-center justify-center text-[9px] font-black shadow" id="badgeNone">✓</div>
                                    </div>
                                    
                                    <!-- Dynamic Borders from Controller -->
                                    @foreach($borders as $border)
                                        <div class="border-card border-2 border-transparent hover:border-gray-300 rounded-lg p-1 cursor-pointer relative flex flex-col items-center justify-center w-16 h-16 bg-white transition select-none group" onclick="selectBorder({{ $border->id }}, '{{ asset($border->image_path) }}')" id="borderCard_{{ $border->id }}">
                                            <img src="{{ asset($border->image_path) }}" class="w-full h-full object-cover rounded border border-gray-150" />
                                            <div class="absolute -top-1.5 -right-1.5 bg-blue-600 text-white w-4 h-4 rounded-full flex items-center justify-center text-[9px] font-black shadow hidden select-badge">✓</div>
                                            <button type="button" onclick="event.stopPropagation(); deleteBorder({{ $border->id }})" class="absolute -bottom-1 -right-1 bg-red-500 hover:bg-red-600 text-white rounded w-4 h-4 flex items-center justify-center shadow opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Target Placement Side Toggles -->
                            <div class="mt-4 pt-3 border-t border-gray-100 flex flex-wrap items-center justify-between gap-4">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-gray-650">Sisi Penerapan Border:</span>
                                    <div class="flex bg-gray-100 p-0.5 rounded-lg border border-gray-200">
                                        <button type="button" onclick="updateBorderSide('depan')" class="px-2.5 py-1 rounded-md text-[11px] font-bold cursor-pointer transition bg-white text-gray-800 shadow-sm border border-gray-200/40" id="btnSideDepan">
                                            Sisi Depan
                                        </button>
                                        <button type="button" onclick="updateBorderSide('belakang')" class="px-2.5 py-1 rounded-md text-[11px] font-bold cursor-pointer transition text-gray-500 hover:text-gray-850" id="btnSideBelakang">
                                            Sisi Belakang
                                        </button>
                                        <button type="button" onclick="updateBorderSide('semua')" class="px-2.5 py-1 rounded-md text-[11px] font-bold cursor-pointer transition text-gray-500 hover:text-gray-850" id="btnSideSemua">
                                            Semua Sisi
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-4 mt-6 flex items-center justify-between">
                    <div class="flex gap-8">
                        <div>
                            <p class="text-xs font-bold text-gray-500 mb-2">Format Output:</p>
                            <div class="flex items-center gap-4 text-sm font-medium">
                                <label class="flex items-center gap-2 text-blue-600 bg-blue-50 px-3 py-1.5 rounded-lg cursor-pointer">
                                    <input type="radio" checked class="text-blue-600 border-gray-300 focus:ring-blue-500"> PDF (2 Halaman)
                                </label>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="btnLanjut" onclick="goToStep3()" disabled class="px-8 py-2.5 bg-gray-300 text-white text-sm font-bold rounded-lg cursor-not-allowed flex items-center gap-2 transition-all">
                        Lanjut ke Download
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>

            <div id="step3Container" class="hidden bg-white border border-gray-200 rounded-2xl shadow-sm p-12 flex-1 flex flex-col items-center justify-center text-center">
                <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mb-6 border-4 border-green-50">
                    <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Sertifikat Siap Diunduh!</h2>
                <p class="text-gray-500 mb-8 max-w-sm">Data nilai dan preview sertifikat untuk <strong id="finalNamaSiswa" class="text-gray-700">-</strong> telah divalidasi dan siap dicetak.</p>
                
                <div class="flex flex-col gap-4 w-full max-w-md">
                    <div class="flex gap-4 w-full">
                        <button type="button" onclick="backToStep2()" class="flex-1 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-bold rounded-xl transition-all">
                            Kembali Preview
                        </button>
                        <a href="#" id="btnDownloadFinal" class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-md transition flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Download PDF
                        </a>
                    </div>
                    
                    <form action="#" id="formKirimSertifikat" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="w-full py-3 bg-green-500 hover:bg-green-600 text-white text-sm font-bold rounded-xl shadow-md transition flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            Kirim Sertifikat ke Siswa
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    // JS UNTUK BATCH SELECT ALL
    let isAllSelected = false;

    function toggleSelectAll() {
        isAllSelected = !isAllSelected;
        const checkboxes = document.querySelectorAll('.student-checkbox');
        
        checkboxes.forEach(cb => {
            if (cb.closest('.student-item').style.display !== 'none') {
                cb.checked = isAllSelected;
            }
        });
        updateBtnSelectAllText();
    }

    function checkSelection() {
        const visibleItems = Array.from(document.querySelectorAll('.student-checkbox')).filter(cb => cb.closest('.student-item').style.display !== 'none');
        const allChecked = visibleItems.every(cb => cb.checked);
        
        isAllSelected = allChecked && visibleItems.length > 0;
        updateBtnSelectAllText();
    }

    function updateBtnSelectAllText() {
        document.getElementById('btnSelectAll').innerText = isAllSelected ? '❌ Batal Pilih Semua' : '☑️ Pilih Semua';
    }

    function submitBatchZip() {
        const selectedIds = Array.from(document.querySelectorAll('.student-checkbox:checked')).map(cb => cb.value);
        
        if (selectedIds.length === 0) {
            alert('Silakan centang minimal satu kotak siswa untuk dicetak masal!');
            return;
        }

        document.getElementById('selectedIdsInput').value = selectedIds.join(',');
        document.getElementById('formBatchZip').submit();
    }

    // Pencarian Siswa
    document.getElementById('searchInput').addEventListener('input', function(e) {
        let filter = e.target.value.toLowerCase();
        let items = document.querySelectorAll('.student-item');
        
        items.forEach(item => {
            let name = item.dataset.nama.toLowerCase();
            let nisn = item.dataset.nisn.toLowerCase();
            if(name.includes(filter) || nisn.includes(filter)) item.style.display = 'flex';
            else item.style.display = 'none';
        });
    });

    // Pindah Tab
    function switchTab(tabName) {
        const btnDepan = document.getElementById('tabDepan');
        const btnBelakang = document.getElementById('tabBelakang');
        const canvasDepan = document.getElementById('canvasDepan');
        const canvasBelakang = document.getElementById('canvasBelakang');

        if(tabName === 'depan') {
            btnDepan.classList.replace('bg-gray-50', 'bg-white');
            btnDepan.classList.replace('text-gray-500', 'text-gray-800');
            btnDepan.classList.add('border-b-0', 'shadow-sm');
            
            btnBelakang.classList.replace('bg-white', 'bg-gray-50');
            btnBelakang.classList.replace('text-gray-800', 'text-gray-500');
            btnBelakang.classList.remove('border-b-0', 'shadow-sm');

            canvasDepan.classList.remove('hidden');
            canvasBelakang.classList.add('hidden');
        } else {
            btnBelakang.classList.replace('bg-gray-50', 'bg-white');
            btnBelakang.classList.replace('text-gray-500', 'text-gray-800');
            btnBelakang.classList.add('border-b-0', 'shadow-sm');
            
            btnDepan.classList.replace('bg-white', 'bg-gray-50');
            btnDepan.classList.replace('text-gray-800', 'text-gray-500');
            btnDepan.classList.remove('border-b-0', 'shadow-sm');

            canvasBelakang.classList.remove('hidden');
            canvasDepan.classList.add('hidden');
        }
    }

    let currentSelectedId = null;

    function bacaAngkaJS(angka) {
        const huruf = ['Nol', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan'];
        let str = Math.round(parseFloat(angka) || 0).toString();
        let hasil = [];
        for(let i = 0; i < str.length; i++) {
            let digit = parseInt(str[i]);
            if (!isNaN(digit) && huruf[digit]) {
                hasil.push(huruf[digit]);
            }
        }
        return hasil.join(' ');
    }

    // FUNGSI UTAMA PREVIEW (Sekarang mengambil data dari Dataset HTMl)
    function previewStudent(element) {
        const id = element.dataset.id;
        const nama = element.dataset.nama;
        const nisn = element.dataset.nisn;
        const industri = element.dataset.industri;
        const predikat = element.dataset.predikat;
        const sikap = element.dataset.sikap;
        const keterampilan = element.dataset.keterampilan;
        const pengetahuan = element.dataset.pengetahuan;
        const akhir = element.dataset.akhir;
        const kegiatan1 = element.dataset.kegiatan1;
        const nilai1 = element.dataset.nilai1;
        const kegiatan2 = element.dataset.kegiatan2;
        const nilai2 = element.dataset.nilai2;
        const kegiatan3 = element.dataset.kegiatan3;
        const nilai3 = element.dataset.nilai3;
        const jurusan = element.dataset.jurusan;
        const ttl = element.dataset.ttl;
        const pimpinan = element.dataset.pimpinan;
        const pembimbing = element.dataset.pembimbing;
        const selesai = element.dataset.selesai;
        const alamat = element.dataset.alamat;
        const mulai = element.dataset.mulai;

        if (currentSelectedId === id) {
            currentSelectedId = null;
            resetPreview();
            return;
        }

        currentSelectedId = id;

        // Reset class dari list lainnya
        document.querySelectorAll('.student-item').forEach(item => {
            item.classList.remove('bg-blue-50', 'border-blue-200');
            item.classList.add('border-transparent');
            item.querySelector('.avatar-circle').classList.remove('bg-blue-200', 'text-blue-700');
            item.querySelector('.avatar-circle').classList.add('bg-gray-200', 'text-gray-700');
            item.querySelector('.check-icon').classList.add('hidden');
        });

        // Set class aktif
        element.classList.remove('border-transparent', 'hover:bg-gray-50');
        element.classList.add('bg-blue-50', 'border-blue-200');
        element.querySelector('.avatar-circle').classList.remove('bg-gray-200', 'text-gray-700');
        element.querySelector('.avatar-circle').classList.add('bg-blue-200', 'text-blue-700');
        element.querySelector('.check-icon').classList.remove('hidden');

        // Munculkan kontainer preview
        document.getElementById('step2Container').classList.remove('opacity-50', 'pointer-events-none');
        document.getElementById('step3Container').classList.add('hidden'); 

        // Timpakan tulisan ke Canvas HTML
        document.getElementById('previewNama').innerText = nama;
        document.getElementById('previewNamaBelakang').innerText = 'Nama Siswa: ' + nama;
        document.getElementById('finalNamaSiswa').innerText = nama; 
        document.getElementById('previewNisn').innerText = nisn;
        document.getElementById('previewTtl').innerText = ttl;
        document.getElementById('previewJurusan').innerText = jurusan;
        document.getElementById('previewIndustri').innerText = industri;
        document.getElementById('previewAlamat').innerText = alamat;
        document.getElementById('previewTglMulai').innerText = mulai;
        document.getElementById('previewTglSelesaiText').innerText = selesai;
        document.getElementById('previewJurusanParagraph').innerText = jurusan;
        document.getElementById('previewPimpinan').innerText = pimpinan;
        document.getElementById('previewPembimbing').innerText = pembimbing;
        document.getElementById('previewTglSelesai').innerText = 'Tuban, ' + selesai;
        
        let predikatText = 'KURANG';
        if(predikat === 'A') predikatText = 'BAIK SEKALI';
        else if (predikat === 'B') predikatText = 'BAIK';
        else if (predikat === 'C') predikatText = 'CUKUP';
        else if (predikat === 'D' || predikat === 'E') predikatText = 'KURANG';
        
        document.getElementById('previewPredikat').innerText = predikatText;
        if(document.getElementById('previewPredikatB')) {
            document.getElementById('previewPredikatB').innerText = predikatText;
        }

        // technical
        document.getElementById('previewKegiatan1').innerText = kegiatan1;
        document.getElementById('previewNilai1').innerText = nilai1;
        document.getElementById('previewHuruf1').innerText = bacaAngkaJS(nilai1);

        document.getElementById('previewKegiatan2').innerText = kegiatan2;
        document.getElementById('previewNilai2').innerText = nilai2;
        document.getElementById('previewHuruf2').innerText = bacaAngkaJS(nilai2);

        document.getElementById('previewKegiatan3').innerText = kegiatan3;
        document.getElementById('previewNilai3').innerText = nilai3;
        document.getElementById('previewHuruf3').innerText = bacaAngkaJS(nilai3);

        // non-technical
        document.getElementById('previewSikap').innerText = sikap;
        document.getElementById('previewHurufSikap').innerText = bacaAngkaJS(sikap);

        document.getElementById('previewKeterampilan').innerText = keterampilan;
        document.getElementById('previewHurufKeterampilan').innerText = bacaAngkaJS(keterampilan);

        document.getElementById('previewPengetahuan').innerText = pengetahuan;
        document.getElementById('previewHurufPengetahuan').innerText = bacaAngkaJS(pengetahuan);

        document.getElementById('previewAkhir').innerText = akhir;

        setStepper(2);

        // Nyalakan Tombol Lanjut
        const btnLanjut = document.getElementById('btnLanjut');
        btnLanjut.disabled = false;
        btnLanjut.classList.replace('bg-gray-300', 'bg-blue-600');
        btnLanjut.classList.replace('cursor-not-allowed', 'hover:bg-blue-700');

        // Ganti URL aksi untuk tombol download satuan dan kirim satuan
        document.getElementById('btnDownloadFinal').href = `/admin/generate-sertifikat/${id}`;
        document.getElementById('formKirimSertifikat').action = `/admin/generate-sertifikat/kirim/${id}`;
    }

    function resetPreview() {
        document.querySelectorAll('.student-item').forEach(item => {
            item.classList.remove('bg-blue-50', 'border-blue-200');
            item.classList.add('border-transparent');
            item.querySelector('.avatar-circle').classList.remove('bg-blue-200', 'text-blue-700');
            item.querySelector('.avatar-circle').classList.add('bg-gray-200', 'text-gray-700');
            item.querySelector('.check-icon').classList.add('hidden');
        });

        document.getElementById('step2Container').classList.add('opacity-50', 'pointer-events-none');
        document.getElementById('step2Container').classList.remove('hidden');
        document.getElementById('step3Container').classList.add('hidden');

        document.getElementById('previewNama').innerText = 'NAMA SISWA';
        document.getElementById('previewNamaBelakang').innerText = 'Nama Siswa: -';
        document.getElementById('previewNisn').innerText = '-';
        document.getElementById('previewTtl').innerText = 'Tuban, 01 Januari 2000';
        document.getElementById('previewJurusan').innerText = 'Rekayasa Perangkat Lunak';
        document.getElementById('previewIndustri').innerText = 'Perusahaan';
        document.getElementById('previewAlamat').innerText = 'Alamat Industri';
        document.getElementById('previewTglMulai').innerText = '01 Januari 2024';
        document.getElementById('previewTglSelesaiText').innerText = '30 Juni 2024';
        document.getElementById('previewJurusanParagraph').innerText = 'Jurusan';
        document.getElementById('previewPimpinan').innerText = 'Pimpinan DU/DI';
        document.getElementById('previewPembimbing').innerText = 'Pembimbing Industri';
        document.getElementById('previewTglSelesai').innerText = 'Tuban, ' + '{{ date("d F Y") }}';
        
        document.getElementById('previewPredikat').innerText = '-';
        if(document.getElementById('previewPredikatB')) {
            document.getElementById('previewPredikatB').innerText = '-';
        }
        
        document.getElementById('previewKegiatan1').innerText = 'Membuat Table Disposisi di Excel';
        document.getElementById('previewNilai1').innerText = '0';
        document.getElementById('previewHuruf1').innerText = '-';
        document.getElementById('previewKegiatan2').innerText = 'Membuat Table foto di Word';
        document.getElementById('previewNilai2').innerText = '0';
        document.getElementById('previewHuruf2').innerText = '-';
        document.getElementById('previewKegiatan3').innerText = 'Memproses dan Remove Background Foto';
        document.getElementById('previewNilai3').innerText = '0';
        document.getElementById('previewHuruf3').innerText = '-';
        
        document.getElementById('previewSikap').innerText = '0';
        document.getElementById('previewHurufSikap').innerText = '-';
        document.getElementById('previewKeterampilan').innerText = '0';
        document.getElementById('previewHurufKeterampilan').innerText = '-';
        document.getElementById('previewPengetahuan').innerText = '0';
        document.getElementById('previewHurufPengetahuan').innerText = '-';
        document.getElementById('previewAkhir').innerText = '0';

        setStepper(1);

        const btnLanjut = document.getElementById('btnLanjut');
        btnLanjut.disabled = true;
        btnLanjut.classList.replace('bg-blue-600', 'bg-gray-300');
        btnLanjut.classList.replace('hover:bg-blue-700', 'cursor-not-allowed');
    }

    function goToStep3() {
        document.getElementById('step2Container').classList.add('hidden');
        document.getElementById('step3Container').classList.remove('hidden');
        setStepper(3);
    }

    function backToStep2() {
        document.getElementById('step3Container').classList.add('hidden');
        document.getElementById('step2Container').classList.remove('hidden');
        setStepper(2);
    }

    function setStepper(step) {
        const circle2 = document.getElementById('step2-circle');
        const text2 = document.getElementById('step2-text');
        const circle3 = document.getElementById('step3-circle');
        const text3 = document.getElementById('step3-text');
        
        circle2.className = "w-8 h-8 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center font-bold text-sm transition-colors duration-300";
        text2.className = "font-medium text-gray-500 text-sm transition-colors duration-300";
        circle3.className = "w-8 h-8 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center font-bold text-sm transition-colors duration-300";
        text3.className = "font-medium text-gray-500 text-sm transition-colors duration-300";

        if(step === 2) {
            circle2.classList.replace('bg-gray-200', 'bg-blue-100');
            circle2.classList.replace('text-gray-500', 'text-blue-600');
            text2.classList.replace('text-gray-500', 'text-blue-600');
            text2.classList.replace('font-medium', 'font-bold');
        } 
        else if (step === 3) {
            circle2.classList.replace('bg-gray-200', 'bg-blue-600');
            circle2.classList.replace('text-gray-500', 'text-white');
            text2.classList.replace('text-gray-500', 'text-gray-800');
            text2.classList.replace('font-medium', 'font-bold');
            
            circle3.classList.replace('bg-gray-200', 'bg-blue-100');
            circle3.classList.replace('text-gray-500', 'text-blue-600');
            text3.classList.replace('text-gray-500', 'text-blue-600');
            text3.classList.replace('font-medium', 'font-bold');
        }
    }
    // ==================== BORDER TEMPLATE FUNCTIONS ====================
    let selectedBorderId = null;
    let selectedBorderUrl = null;
    let currentBorderSide = 'depan';

    function selectBorder(id, url) {
        selectedBorderId = id;
        selectedBorderUrl = url || null;

        // Reset all border cards
        document.querySelectorAll('.border-card').forEach(card => {
            card.classList.remove('border-blue-600');
            card.classList.add('border-transparent');
            const badge = card.querySelector('.select-badge') || card.querySelector('#badgeNone');
            if (badge) badge.classList.add('hidden');
        });

        // Activate selected card
        if (id === null) {
            const noneCard = document.getElementById('borderCardNone');
            noneCard.classList.remove('border-transparent');
            noneCard.classList.add('border-blue-600');
            document.getElementById('badgeNone').classList.remove('hidden');
        } else {
            const card = document.getElementById('borderCard_' + id);
            if (card) {
                card.classList.remove('border-transparent');
                card.classList.add('border-blue-600');
                const badge = card.querySelector('.select-badge');
                if (badge) badge.classList.remove('hidden');
            }
        }

        // Update preview overlays
        applyBorderPreview();
    }

    function applyBorderPreview() {
        const imgDepan = document.getElementById('customBorderDepan');
        const imgBelakang = document.getElementById('customBorderBelakang');

        // Reset both
        imgDepan.classList.add('hidden');
        imgBelakang.classList.add('hidden');

        if (!selectedBorderUrl) return;

        if (currentBorderSide === 'depan' || currentBorderSide === 'semua') {
            imgDepan.src = selectedBorderUrl;
            imgDepan.classList.remove('hidden');
        }
        if (currentBorderSide === 'belakang' || currentBorderSide === 'semua') {
            imgBelakang.src = selectedBorderUrl;
            imgBelakang.classList.remove('hidden');
        }
    }

    function updateBorderSide(side) {
        currentBorderSide = side;

        // Update button styles
        ['Depan', 'Belakang', 'Semua'].forEach(s => {
            const btn = document.getElementById('btnSide' + s);
            btn.className = 'px-2.5 py-1 rounded-md text-[11px] font-bold cursor-pointer transition text-gray-500 hover:text-gray-850';
        });

        const activeBtn = document.getElementById('btnSide' + side.charAt(0).toUpperCase() + side.slice(1));
        activeBtn.className = 'px-2.5 py-1 rounded-md text-[11px] font-bold cursor-pointer transition bg-white text-gray-800 shadow-sm border border-gray-200/40';

        applyBorderPreview();
    }

    function handleBorderUpload(input) {
        if (!input.files || !input.files[0]) return;

        const file = input.files[0];
        if (file.size > 5 * 1024 * 1024) {
            alert('Ukuran file melebihi 5MB!');
            input.value = '';
            return;
        }

        const formData = new FormData();
        formData.append('border_image', file);
        formData.append('_token', '{{ csrf_token() }}');

        document.getElementById('uploadLoader').classList.remove('hidden');

        fetch('{{ route("admin.generate-sertifikat.upload-border") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('uploadLoader').classList.add('hidden');
            input.value = '';

            if (data.success) {
                const border = data.border;
                const container = document.getElementById('borderGridContainer');
                const imgUrl = '/'+border.image_path;

                const newCard = document.createElement('div');
                newCard.className = 'border-card border-2 border-transparent hover:border-gray-300 rounded-lg p-1 cursor-pointer relative flex flex-col items-center justify-center w-16 h-16 bg-white transition select-none group';
                newCard.id = 'borderCard_' + border.id;
                newCard.onclick = function() { selectBorder(border.id, imgUrl); };
                newCard.innerHTML = `
                    <img src="${imgUrl}" class="w-full h-full object-cover rounded border border-gray-150" />
                    <div class="absolute -top-1.5 -right-1.5 bg-blue-600 text-white w-4 h-4 rounded-full flex items-center justify-center text-[9px] font-black shadow hidden select-badge">✓</div>
                    <button type="button" onclick="event.stopPropagation(); deleteBorder(${border.id})" class="absolute -bottom-1 -right-1 bg-red-500 hover:bg-red-600 text-white rounded w-4 h-4 flex items-center justify-center shadow opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                `;
                container.appendChild(newCard);

                // Auto-select the newly uploaded border
                selectBorder(border.id, imgUrl);
            } else {
                alert(data.message || 'Gagal mengunggah.');
            }
        })
        .catch(err => {
            document.getElementById('uploadLoader').classList.add('hidden');
            input.value = '';
            alert('Terjadi kesalahan saat mengunggah.');
            console.error(err);
        });
    }

    function deleteBorder(id) {
        if (!confirm('Hapus template border ini?')) return;

        fetch(`/admin/generate-sertifikat/border/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const card = document.getElementById('borderCard_' + id);
                if (card) card.remove();

                // If the deleted border was selected, revert to "Polos"
                if (selectedBorderId === id) {
                    selectBorder(null);
                }
            } else {
                alert(data.message || 'Gagal menghapus.');
            }
        })
        .catch(err => {
            alert('Terjadi kesalahan saat menghapus.');
            console.error(err);
        });
    }

    // Override download URL and form action to include border params
    const originalPreviewStudent = previewStudent;
    // Patch: update download link & kirim form with border params
    const _origGoToStep3 = goToStep3;
    goToStep3 = function() {
        _origGoToStep3();

        // Append border params to download link
        let downloadBtn = document.getElementById('btnDownloadFinal');
        let baseUrl = downloadBtn.href.split('?')[0];
        if (selectedBorderId) {
            downloadBtn.href = baseUrl + '?border_id=' + selectedBorderId + '&border_side=' + currentBorderSide;
        } else {
            downloadBtn.href = baseUrl;
        }

        // Append hidden inputs to kirim form
        let kirimForm = document.getElementById('formKirimSertifikat');
        // Remove old hidden inputs
        kirimForm.querySelectorAll('.border-param').forEach(el => el.remove());

        if (selectedBorderId) {
            let inp1 = document.createElement('input');
            inp1.type = 'hidden'; inp1.name = 'border_id'; inp1.value = selectedBorderId; inp1.className = 'border-param';
            kirimForm.appendChild(inp1);

            let inp2 = document.createElement('input');
            inp2.type = 'hidden'; inp2.name = 'border_side'; inp2.value = currentBorderSide; inp2.className = 'border-param';
            kirimForm.appendChild(inp2);
        }
    };

    // Patch submitBatchZip to include border params
    const _origSubmitBatchZip = submitBatchZip;
    submitBatchZip = function() {
        let form = document.getElementById('formBatchZip');
        // Remove old border params
        form.querySelectorAll('.border-param').forEach(el => el.remove());

        if (selectedBorderId) {
            let inp1 = document.createElement('input');
            inp1.type = 'hidden'; inp1.name = 'border_id'; inp1.value = selectedBorderId; inp1.className = 'border-param';
            form.appendChild(inp1);

            let inp2 = document.createElement('input');
            inp2.type = 'hidden'; inp2.name = 'border_side'; inp2.value = currentBorderSide; inp2.className = 'border-param';
            form.appendChild(inp2);
        }

        _origSubmitBatchZip();
    };

</script>
@endpush
@endsection