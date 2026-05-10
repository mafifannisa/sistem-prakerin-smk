@extends('layouts.admin')

@section('title', 'Import Nilai Siswa')

@section('content')
<div class="p-8 max-w-6xl mx-auto font-sans">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Import Nilai Siswa</h1>
            <p class="text-slate-500 mt-2 text-sm leading-relaxed max-w-2xl">Unggah berkas Excel untuk memperbarui nilai siswa secara massal. Sistem akan otomatis memvalidasi NISN dengan data yang terdaftar di database.</p>
        </div>
        <a href="{{ route('admin.import-nilai.template') }}" 
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 text-slate-700 text-sm font-semibold rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all duration-200 shadow-sm group">
            <svg class="w-4 h-4 text-slate-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Download Template
        </a>
    </div>

    @if(session('success'))
        <div class="mb-8 bg-emerald-50/80 border border-emerald-200 rounded-2xl p-4 text-emerald-800 flex items-center gap-3 shadow-sm animate-fade-in-down">
            <div class="bg-emerald-100 p-1.5 rounded-lg">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <span class="font-medium text-sm">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-8 bg-rose-50/80 border border-rose-200 rounded-2xl p-4 text-rose-800 flex items-center gap-3 shadow-sm animate-fade-in-down">
            <div class="bg-rose-100 p-1.5 rounded-lg">
                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="font-medium text-sm">{{ session('error') }}</span>
        </div>
    @endif

    <form action="{{ route('admin.import-nilai') }}" method="POST" enctype="multipart/form-data" id="importForm">
        @csrf
        
        <div class="relative group">
            <div class="absolute inset-0 bg-blue-500/5 rounded-3xl scale-[0.98] group-hover:scale-100 transition-transform duration-300 ease-out"></div>
            <div class="relative bg-white border-2 border-dashed border-slate-200 hover:border-blue-400 rounded-3xl p-14 text-center transition-colors duration-300 cursor-pointer flex flex-col items-center justify-center" id="dropZone" onclick="document.getElementById('fileInput').click()">
                <input type="file" name="file_excel" id="fileInput" accept=".xls,.xlsx,.csv" class="hidden" required onchange="handleFileSelect(event)">
                
                <div class="w-20 h-20 bg-blue-50 rounded-2xl flex items-center justify-center mb-5 group-hover:-translate-y-1 transition-transform duration-300 shadow-sm border border-blue-100/50">
                    <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                </div>
                
                <h3 class="text-xl font-bold text-slate-800 mb-2" id="dropTitle">Klik untuk unggah atau seret berkas</h3>
                <p class="text-slate-400 text-sm font-medium" id="dropSubtitle">Format .xls, .xlsx, .csv didukung. Maksimal 10MB.</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mt-6">
            <div class="flex justify-between items-end mb-3">
                <div>
                    <span class="block text-sm font-bold text-slate-700">Progres Unggahan</span>
                    <p class="text-xs text-slate-400 mt-0.5 hidden" id="fileNameDisplay"></p>
                </div>
                <span class="text-sm font-black text-blue-600 bg-blue-50 px-2.5 py-1 rounded-lg" id="uploadProgressText">0%</span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                <div class="bg-blue-500 h-full rounded-full transition-all duration-500 ease-out relative" style="width: 0%" id="uploadProgressBar">
                    <div class="absolute top-0 left-0 bottom-0 right-0 bg-white/20 animate-pulse"></div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mt-8 overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center bg-slate-50/50 gap-4">
                <div class="flex items-center gap-3">
                    <div class="bg-white p-2 rounded-lg shadow-sm border border-slate-100">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg">Pratinjau Data</h3>
                </div>
                <div class="flex gap-4 bg-white px-4 py-2 rounded-xl border border-slate-100 shadow-sm">
                    <div class="flex items-center gap-2 text-xs font-bold text-slate-600">
                        <span class="relative flex h-2.5 w-2.5">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                        </span>
                        SIAP IMPOR
                    </div>
                    <div class="w-px h-4 bg-slate-200"></div>
                    <div class="flex items-center gap-2 text-xs font-bold text-slate-600">
                        <div class="w-2.5 h-2.5 rounded-full bg-rose-500"></div> PERIKSA NILAI
                    </div>
                </div>
            </div>
            
            <div class="p-20 text-center flex flex-col items-center justify-center bg-slate-50/30" id="previewEmpty">
                <div class="w-24 h-24 mb-6 relative">
                    <div class="absolute inset-0 bg-blue-100 rounded-full animate-ping opacity-20"></div>
                    <div class="relative w-full h-full bg-white rounded-full shadow-sm border border-slate-100 flex items-center justify-center">
                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                </div>
                <h4 class="text-slate-600 font-bold text-lg mb-2">Area Pratinjau Kosong</h4>
                <p class="text-slate-400 text-sm max-w-sm">Unggah file Excel Anda di atas, dan datanya akan muncul di sini sebelum diimpor secara permanen ke database.</p>
            </div>
            
            <div class="hidden overflow-x-auto" id="previewTableContainer">
                <table class="w-full text-sm text-left whitespace-nowrap">
                    <thead class="bg-slate-50/80 text-slate-500 text-xs uppercase tracking-wider font-semibold border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4">NISN</th>
                            <th class="px-6 py-4">Nama Lengkap</th>
                            <th class="px-6 py-4 text-center">Sikap</th>
                            <th class="px-6 py-4 text-center">Keterampilan</th>
                            <th class="px-6 py-4 text-center">Pengetahuan</th>
                            <th class="px-6 py-4">Catatan Industri</th>
                            <th class="px-6 py-4 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white" id="previewTableBody">
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-end gap-4 mt-8">
            <button type="button" onclick="resetForm()" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 font-bold text-sm rounded-xl hover:bg-slate-50 hover:text-slate-800 transition-colors shadow-sm focus:ring-4 focus:ring-slate-100">
                Batal & Reset
            </button>
            <button type="submit" id="submitBtn" disabled class="px-8 py-3 bg-slate-300 text-white font-bold text-sm rounded-xl transition-all flex items-center gap-2 cursor-not-allowed group">
                <svg class="w-5 h-5 transition-transform group-hover:-translate-y-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                Proses Import Data
            </button>
        </div>
    </form>
</div>

<script src="https://cdn.sheetjs.com/xlsx-0.20.0/package/dist/xlsx.full.min.js"></script>

<script>
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const submitBtn = document.getElementById('submitBtn');

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.classList.add('border-blue-400', 'bg-blue-50/50');
            dropZone.classList.remove('border-slate-200');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.classList.remove('border-blue-400', 'bg-blue-50/50');
            dropZone.classList.add('border-slate-200');
        }, false);
    });

    dropZone.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;
        if(files.length > 0) {
            fileInput.files = files;
            handleFileSelect({target: {files: files}});
        }
    }, false);

    async function handleFileSelect(e) {
        const file = e.target.files[0];
        if(!file) return;

        const allowedTypes = ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/csv'];
        if (!allowedTypes.includes(file.type) && !file.name.match(/\.(xls|xlsx|csv)$/i)) {
            alert('Hanya file Excel (.xls, .xlsx) yang didukung!');
            return;
        }

        if (file.size > 10 * 1024 * 1024) {
            alert('Ukuran file maksimal 10MB!');
            return;
        }

        document.getElementById('dropTitle').innerText = file.name;
        document.getElementById('dropSubtitle').innerText = (file.size / 1024 / 1024).toFixed(2) + ' MB';

        const bar = document.getElementById('uploadProgressBar');
        const text = document.getElementById('uploadProgressText');
        const display = document.getElementById('fileNameDisplay');

        display.classList.remove('hidden');
        display.innerText = "Membaca struktur file...";

        const reader = new FileReader();
        reader.onload = async function(e) {
            try {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, {type: 'array'});
                const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                const jsonData = XLSX.utils.sheet_to_json(firstSheet, {header: 1, defval: ''});

                if (jsonData.length < 2) {
                    display.innerText = "Error: File Excel kosong atau hanya berisi header.";
                    return;
                }

                const headers = jsonData[0].map(h => String(h).toLowerCase().trim());

                const nisnIdx = headers.findIndex(h => h === 'nisn');
                const sikapIdx = headers.findIndex(h => h === 'nilai_sikap');
                const keterampilanIdx = headers.findIndex(h => h === 'nilai_keterampilan');
                const pengetahuanIdx = headers.findIndex(h => h === 'nilai_pengetahuan');
                const catatanIdx = headers.findIndex(h => h === 'catatan');

                if (nisnIdx === -1) {
                    display.innerText = "Error: Kolom 'nisn' tidak ditemukan di file Excel.";
                    return;
                }

                const rows = [];
                const nisnSet = new Set();

                for (let i = 1; i < jsonData.length; i++) {
                    const row = jsonData[i];
                    const nisn = String(row[nisnIdx] || '').trim();
                    if (!nisn) continue;

                    const sikap = sikapIdx !== -1 ? parseFloat(row[sikapIdx]) || 0 : 0;
                    const keterampilan = keterampilanIdx !== -1 ? parseFloat(row[keterampilanIdx]) || 0 : 0;
                    const pengetahuan = pengetahuanIdx !== -1 ? parseFloat(row[pengetahuanIdx]) || 0 : 0;
                    const catatan = catatanIdx !== -1 ? String(row[catatanIdx] || '').trim() : '';

                    rows.push({ nisn, sikap, keterampilan, pengetahuan, catatan });
                    nisnSet.add(nisn);
                }

                if (rows.length === 0) {
                    display.innerText = "Tidak ada baris data ditemukan.";
                    return;
                }

                document.getElementById('previewEmpty').classList.add('hidden');
                document.getElementById('previewTableContainer').classList.remove('hidden');
                document.getElementById('previewTableBody').innerHTML = `
                    <tr><td colspan="7" class="px-6 py-12 text-center text-slate-500">
                        <div class="inline-flex items-center justify-center space-x-2">
                            <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce" style="animation-delay: -0.3s"></div>
                            <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce" style="animation-delay: -0.15s"></div>
                            <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce"></div>
                        </div>
                        <p class="mt-4 font-medium">Sinkronisasi NISN dengan database server...</p>
                    </td></tr>
                `;

                const checkResponse = await fetch('{{ route("admin.import-nilai.check-nisn") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ nisn: Array.from(nisnSet) })
                });

                const nisnData = await checkResponse.json();

                let validCount = 0;
                let invalidCount = 0;
                let rowsHtml = '';

                rows.forEach(row => {
                    const info = nisnData[row.nisn] || {};
                    const isRegistered = info.registered === true;
                    const nama = info.nama || '';
                    const nilaiValid = [row.sikap, row.keterampilan, row.pengetahuan].every(v => v >= 0 && v <= 100);
                    const isValid = isRegistered && nilaiValid;

                    if (isValid) validCount++;
                    else invalidCount++;

                    let statusBadge = '';
                    if (!isRegistered) {
                        statusBadge = `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-rose-50 text-rose-600 border border-rose-100"><span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>Tidak Terdaftar</span>`;
                    } else if (!nilaiValid) {
                        statusBadge = `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-600 border border-amber-100"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>Nilai Tidak Valid</span>`;
                    } else {
                        statusBadge = `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Valid</span>`;
                    }

                    rowsHtml += `
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-6 py-4 font-mono text-slate-500 font-medium">${row.nisn}</td>
                            <td class="px-6 py-4 text-slate-800 font-bold">${nama || '<span class="text-slate-300 italic">Unknown</span>'}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-slate-100 text-slate-700 px-2.5 py-1 rounded-md text-sm font-semibold">${row.sikap}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-slate-100 text-slate-700 px-2.5 py-1 rounded-md text-sm font-semibold">${row.keterampilan}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-slate-100 text-slate-700 px-2.5 py-1 rounded-md text-sm font-semibold">${row.pengetahuan}</span>
                            </td>
                            <td class="px-6 py-4 text-slate-500 truncate max-w-[200px]" title="${row.catatan}">${row.catatan || '-'}</td>
                            <td class="px-6 py-4 text-right">${statusBadge}</td>
                        </tr>
                    `;
                });

                document.getElementById('previewTableBody').innerHTML = rowsHtml;

                bar.style.width = '100%';
                text.innerText = '100%';
                display.innerHTML = `<span class="text-emerald-500 font-bold mr-1">Terselesaikan:</span> ${rows.length} baris data dipindai. <span class="font-bold text-slate-600 ml-2">${validCount} Siap Impor</span> / <span class="font-bold text-rose-500">${invalidCount} Perlu Dicek</span>`;

                submitBtn.disabled = validCount === 0;
                if (validCount > 0) {
                    submitBtn.classList.remove('bg-slate-300', 'cursor-not-allowed', 'text-white');
                    submitBtn.classList.add('bg-blue-600', 'hover:bg-blue-700', 'text-white', 'shadow-md', 'hover:shadow-lg');
                } else {
                    submitBtn.classList.add('bg-slate-300', 'cursor-not-allowed', 'text-white');
                    submitBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700', 'text-white', 'shadow-md', 'hover:shadow-lg');
                }

            } catch (error) {
                display.innerHTML = `<span class="text-rose-500 font-bold">Error:</span> ${error.message}`;
                console.error(error);
            }
        };

        reader.readAsArrayBuffer(file);
    }

    function resetForm() {
        document.getElementById('importForm').reset();
        document.getElementById('dropTitle').innerText = 'Klik untuk unggah atau seret berkas';
        document.getElementById('dropSubtitle').innerText = 'Format .xls, .xlsx, .csv didukung. Maksimal 10MB.';
        
        document.getElementById('uploadProgressBar').style.width = '0%';
        document.getElementById('uploadProgressText').innerText = '0%';
        document.getElementById('fileNameDisplay').classList.add('hidden');
        
        document.getElementById('previewEmpty').classList.remove('hidden');
        document.getElementById('previewTableContainer').classList.add('hidden');
        
        submitBtn.disabled = true;
        submitBtn.classList.add('bg-slate-300', 'cursor-not-allowed');
        submitBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700', 'shadow-md', 'hover:shadow-lg');
    }
</script>

<style>
    /* Tambahan animasi smooth untuk alert */
    @keyframes fadeInDown {
        0% { opacity: 0; transform: translateY(-10px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-down {
        animation: fadeInDown 0.4s ease-out forwards;
    }
</style>
@endsection