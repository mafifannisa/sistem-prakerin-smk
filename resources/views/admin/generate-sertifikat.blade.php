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
                             data-sikap="{{ $item->nilai->nilai_sikap ?? 0 }}"
                             data-keterampilan="{{ $item->nilai->nilai_keterampilan ?? 0 }}"
                             data-pengetahuan="{{ $item->nilai->nilai_pengetahuan ?? 0 }}"
                             data-akhir="{{ $item->nilai->nilai_akhir ?? 0 }}"
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
                            <p class="text-sm">Belum ada siswa yang memiliki nilai.</p>
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
                    
                    <div id="canvasDepan" class="bg-white shadow-2xl w-full max-w-2xl aspect-[1.414/1] relative p-12 flex flex-col items-center justify-center text-center transform transition duration-500">
                        <div class="absolute inset-4 border border-gray-200 pointer-events-none"></div>
                        <div class="absolute inset-6 border-2 border-blue-900 pointer-events-none opacity-10"></div>
                        
                        <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                        </div>
                        
                        <p class="text-[10px] tracking-widest text-gray-500 uppercase mb-2">Sekolah Menengah Kejuruan Negeri 3 Tuban</p>
                        <h2 class="text-4xl font-serif font-bold text-gray-800 mb-2" style="font-family: Georgia, serif;">Sertifikat Kompetensi</h2>
                        <p class="text-[10px] text-gray-400 mb-8">Nomor: 421.5/129/SMKN3/{{ date('Y') }}</p>
                        
                        <p class="text-sm text-gray-600 mb-2">Diberikan Kepada:</p>
                        <h1 class="text-3xl font-bold text-blue-600 uppercase tracking-wide mb-1" id="previewNama">NAMA SISWA</h1>
                        <p class="text-xs text-gray-500 mb-8" id="previewNisn">NISN: -</p>
                        
                        <p class="text-sm text-gray-700 italic max-w-md leading-relaxed">
                            Telah dinyatakan lulus mengikuti Praktik Kerja Lapangan (PKL) Industri di <strong id="previewIndustri">Perusahaan</strong> dengan predikat <strong id="previewPredikat" class="text-gray-900">-</strong>.
                        </p>
                    </div>

                    <div id="canvasBelakang" class="hidden bg-white shadow-2xl w-full max-w-2xl aspect-[1.414/1] relative p-12 flex flex-col transform transition duration-500">
                        <div class="absolute inset-4 border border-gray-200 pointer-events-none"></div>
                        
                        <h3 class="text-center font-bold text-lg text-gray-800 mb-1 uppercase tracking-wider">Daftar Nilai Praktik Kerja Lapangan</h3>
                        <p class="text-center text-xs text-gray-500 mb-8">Nama: <span id="previewNamaBelakang" class="font-bold">NAMA SISWA</span></p>

                        <table class="w-full text-sm text-left border-collapse border border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border border-gray-300 px-4 py-2 font-semibold">Komponen Penilaian</th>
                                    <th class="border border-gray-300 px-4 py-2 font-semibold text-center w-32">Nilai Angka</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border border-gray-300 px-4 py-3 text-gray-700">1. Nilai Sikap & Kedisiplinan</td>
                                    <td class="border border-gray-300 px-4 py-3 text-center font-bold text-gray-800" id="previewSikap">0</td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-300 px-4 py-3 text-gray-700">2. Nilai Keterampilan & Kinerja</td>
                                    <td class="border border-gray-300 px-4 py-3 text-center font-bold text-gray-800" id="previewKeterampilan">0</td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-300 px-4 py-3 text-gray-700">3. Nilai Pengetahuan Bidang</td>
                                    <td class="border border-gray-300 px-4 py-3 text-center font-bold text-gray-800" id="previewPengetahuan">0</td>
                                </tr>
                                <tr class="bg-blue-50">
                                    <td class="border border-gray-300 px-4 py-3 font-bold text-right text-gray-800">Rata-Rata Nilai Akhir</td>
                                    <td class="border border-gray-300 px-4 py-3 text-center font-bold text-blue-700 text-lg" id="previewAkhir">0</td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="flex justify-between items-end mt-auto pt-6">
                            <div class="text-[10px] text-gray-500 leading-relaxed w-1/2">
                                <p class="font-bold mb-1 text-gray-700">Keterangan Predikat:</p>
                                <p>90 - 100 : Sangat Memuaskan (A)<br>80 - 89 : Memuaskan (B)<br>70 - 79 : Cukup (C)<br>< 70 : Kurang (D)</p>
                            </div>
                            <div class="w-1/2 flex flex-col items-center justify-end pl-12 pr-4 text-center">
                                <p class="text-[11px] text-gray-800 mb-1">Ditetapkan di Tuban, {{ date('d F Y') }}</p>
                                <p class="text-[11px] text-gray-800 mb-0">Kepala Sekolah</p>
                                
                                <div class="h-16 flex items-center justify-center my-1 relative w-full">
                                    <img src="{{ asset('images/signature.png') }}" alt="TTD Kepala Sekolah" class="max-h-20 object-contain scale-110 origin-center mix-blend-multiply" onerror="this.style.display='none'">
                                </div>
                                
                                <p class="text-[11px] font-bold text-gray-900 uppercase underline decoration-gray-900 underline-offset-2">Bapak/Ibu Kepala Sekolah, S.Pd., M.Pd.</p>
                                <p class="text-[10px] text-gray-800 mt-0.5">NIP. 19801234 200501 1 001</p>
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
        document.getElementById('previewNamaBelakang').innerText = nama;
        document.getElementById('finalNamaSiswa').innerText = nama; 
        document.getElementById('previewNisn').innerText = 'NISN: ' + nisn;
        document.getElementById('previewIndustri').innerText = industri;
        
        let predikatText = 'Memuaskan';
        if(predikat === 'A') predikatText = 'Sangat Memuaskan';
        else if (predikat === 'B') predikatText = 'Memuaskan';
        else if (predikat === 'C') predikatText = 'Cukup';
        else predikatText = predikat;
        
        document.getElementById('previewPredikat').innerText = predikatText;
        document.getElementById('previewSikap').innerText = sikap;
        document.getElementById('previewKeterampilan').innerText = keterampilan;
        document.getElementById('previewPengetahuan').innerText = pengetahuan;
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
        document.getElementById('previewNamaBelakang').innerText = 'NAMA SISWA';
        document.getElementById('previewNisn').innerText = 'NISN: -';
        document.getElementById('previewIndustri').innerText = 'Perusahaan';
        document.getElementById('previewPredikat').innerText = '-';
        document.getElementById('previewSikap').innerText = '0';
        document.getElementById('previewKeterampilan').innerText = '0';
        document.getElementById('previewPengetahuan').innerText = '0';
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
</script>
@endpush
@endsection