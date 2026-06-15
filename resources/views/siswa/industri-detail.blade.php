@extends('layouts.siswa')

@section('title', 'Detail Industri Tempat Magang')

@section('header_breadcrumb', 'Mitra / Detail')
@section('header_title', 'DETAIL MITRA')

@section('header_actions')
<a href="{{ route('siswa.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 hover:border-orange-500 hover:text-orange-655 bg-white/60 hover:bg-orange-50/40 backdrop-blur-md transition text-xs font-extrabold rounded-xl text-gray-700 duration-200 uppercase tracking-wider">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
    Kembali
</a>
@endsection

@section('content')

<div class="p-0">
    <div class="bg-white/65 backdrop-blur-md border border-white/50 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.02)] overflow-hidden mb-6">
        <div class="p-8">
            <div class="flex flex-col lg:flex-row lg:items-center gap-6 justify-between">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                    <div class="w-24 h-24 bg-orange-500/10 backdrop-blur-sm rounded-2xl flex items-center justify-center text-orange-600 font-black text-4xl border border-orange-200/20 shrink-0 shadow-inner">
                        {{ substr($industri->nama_industri, 0, 1) }}
                    </div>
                    <div>
                        <div class="flex flex-col sm:flex-row items-center gap-3 mb-2">
                            <h2 class="text-2xl sm:text-3xl font-black text-gray-800 tracking-wide">{{ $industri->nama_industri }}</h2>
                            @if($industri->kategori)
                                <span class="inline-flex px-3 py-1 bg-orange-500/10 text-orange-655 border border-orange-200/30 text-[10px] font-extrabold uppercase tracking-wider rounded-full">{{ $industri->kategori }}</span>
                            @endif
                        </div>
                        <p class="text-gray-600 font-semibold flex items-center justify-center sm:justify-start gap-1.5 text-sm">
                            <svg class="w-4 h-4 text-orange-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                            {{ $industri->kota }}, {{ $industri->provinsi }}
                        </p>
                    </div>
                </div>
                
                <div class="flex justify-center gap-4">
                    <div class="bg-white/40 p-4 rounded-xl shadow-sm text-center min-w-[120px] border border-white/50 backdrop-blur-sm">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Kapasitas</p>
                        <p class="text-xl font-black text-gray-850">{{ $industri->kapasitas_magang }} <span class="text-xs font-bold text-gray-500">Orang</span></p>
                    </div>
                    <div class="{{ $sisaKuota > 0 ? 'bg-green-50/40 border-green-200/50' : 'bg-red-50/40 border-red-200/50' }} p-4 rounded-xl shadow-sm border text-center min-w-[120px] backdrop-blur-sm">
                        <p class="text-[10px] font-bold {{ $sisaKuota > 0 ? 'text-green-600' : 'text-red-600' }} uppercase tracking-wider mb-1">Sisa Kuota</p>
                        <p class="text-xl font-black {{ $sisaKuota > 0 ? 'text-green-700' : 'text-red-700' }}">{{ $sisaKuota }} <span class="text-xs font-bold {{ $sisaKuota > 0 ? 'text-green-500' : 'text-red-500' }}">Slot</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-1 space-y-6">
            
            @if($penempatanAktif && $penempatanAktif->industri_id == $industri->id)
                <div class="bg-white/65 backdrop-blur-md border border-white/50 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.02)] p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Status Pengajuan Anda</h3>
                    
                    @php
                        $statusColors = [
                            'pending' => ['bg' => 'bg-yellow-50/50', 'border' => 'border-yellow-200/60', 'text' => 'text-yellow-750', 'icon' => 'text-yellow-500', 'label' => 'Menunggu Verifikasi TU (Pending)'],
                            'verified' => ['bg' => 'bg-blue-50/50', 'border' => 'border-blue-200/60', 'text' => 'text-blue-750', 'icon' => 'text-blue-500', 'label' => 'Menunggu Approval Pimpinan'],
                            'approved' => ['bg' => 'bg-green-50/50', 'border' => 'border-green-200/60', 'text' => 'text-green-750', 'icon' => 'text-green-500', 'label' => 'Disetujui - Siap Magang'],
                            'ongoing' => ['bg' => 'bg-indigo-50/50', 'border' => 'border-indigo-200/60', 'text' => 'text-indigo-750', 'icon' => 'text-indigo-500', 'label' => 'Sedang Magang Aktif'],
                        ];
                        $c = $statusColors[$penempatanAktif->status] ?? $statusColors['pending'];
                    @endphp

                    <div class="p-4 rounded-xl border {{ $c['bg'] }} {{ $c['border'] }} text-center mb-6">
                        <svg class="w-10 h-10 {{ $c['icon'] }} mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <p class="font-bold {{ $c['text'] }} text-sm">{{ $c['label'] }}</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase">Posisi yang Anda Lamar</p>
                            <p class="text-sm font-semibold text-gray-800 bg-white/40 p-3 rounded-lg border border-white/60 mt-1 shadow-sm">{{ $penempatanAktif->posisi_magang }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase">Alasan Pemilihan</p>
                            <p class="text-sm text-gray-700 bg-white/40 p-3 rounded-lg border border-white/60 mt-1 italic shadow-sm">{{ $penempatanAktif->catatan_industri ?? 'Tidak menyertakan alasan.' }}</p>
                        </div>
                    </div>
                </div>

            @elseif($penempatanAktif && $penempatanAktif->industri_id != $industri->id)
                <div class="bg-white/65 backdrop-blur-md border border-white/50 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.02)] p-6 text-center">
                    <div class="w-16 h-16 bg-gray-100/60 border border-gray-200/50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-450" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">Formulir Terkunci</h3>
                    <p class="text-sm text-gray-500 mb-4">Anda sudah memiliki pengajuan magang yang sedang aktif di <span class="font-bold text-gray-700">{{ $penempatanAktif->industri->nama_industri ?? 'perusahaan lain' }}</span>.</p>
                    <a href="{{ route('siswa.cek-status') }}" class="inline-block w-full px-4 py-3 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl transition shadow-md">Lihat Status Pengajuan</a>
                </div>

            @else
                <div class="bg-white/65 backdrop-blur-md border border-white/50 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.02)] p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Formulir Pengajuan Magang</h3>
                    
                    @if($sisaKuota > 0)
                        <form action="{{ route('siswa.ajukan-mitra') }}" method="POST" class="space-y-5">
                            @csrf
                            <input type="hidden" name="industri_id" value="{{ $industri->id }}">
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Posisi yang Diinginkan <span class="text-red-500">*</span></label>
                                <input type="text" name="posisi_magang" required placeholder="Contoh: Web Developer" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none text-sm bg-white/50 focus:bg-white transition">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Alasan Memilih Mitra Ini</label>
                                <textarea name="alasan" rows="3" placeholder="Mengapa Anda tertarik magang di sini?" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none text-sm resize-none bg-white/50 focus:bg-white transition"></textarea>
                            </div>

                            <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3.5 rounded-xl shadow-md transition flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Ajukan Sekarang
                            </button>
                        </form>
                    @else
                        <div class="bg-red-50/50 backdrop-blur-sm border border-red-200/60 text-red-700 p-6 rounded-2xl text-center">
                            <div class="flex items-center justify-center gap-2 mb-2">
                                <svg class="w-8 h-8 text-red-650" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            </div>
                            <p class="font-black text-lg mb-1">KUOTA PENUH</p>
                            <p class="text-sm text-red-600">Maaf, kapasitas magang di industri ini sudah terisi penuh oleh siswa lain.</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white/65 backdrop-blur-md border border-white/50 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.02)] p-8">
                <h3 class="text-base font-extrabold text-gray-805 mb-6 border-b border-gray-150/50 pb-4 tracking-wide uppercase">Informasi Lengkap Perusahaan</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Nomor Induk Berusaha (NIB)</p>
                        <p class="text-sm font-extrabold text-gray-800">{{ $industri->nib ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Website Perusahaan</p>
                        @if($industri->website)
                            <a href="{{ str_starts_with($industri->website, 'http') ? $industri->website : 'https://'.$industri->website }}" target="_blank" class="text-sm font-extrabold text-orange-600 hover:text-orange-700 hover:underline transition">{{ $industri->website }}</a>
                        @else
                            <p class="text-sm font-extrabold text-gray-800">-</p>
                        @endif
                    </div>

                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Nomor Telepon Kantor</p>
                        <p class="text-sm font-extrabold text-gray-800">{{ $industri->no_telp ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Email Resmi</p>
                        <p class="text-sm font-extrabold text-gray-800">{{ $industri->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Pembimbing Lapangan / HRD</p>
                        <p class="text-sm font-extrabold text-gray-800">{{ $industri->nama_hr ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">WhatsApp HRD</p>
                        <p class="text-sm font-extrabold text-gray-800">{{ $industri->no_wa_hr ?? '-' }}</p>
                    </div>

                    <div class="md:col-span-2 bg-white/40 p-5 rounded-2xl border border-white/50 shadow-[0_8px_30px_rgb(0,0,0,0.01)]">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Alamat Detail</p>
                        <p class="text-sm font-semibold text-gray-700 leading-relaxed">
                            {{ $industri->alamat }}<br>
                            @if($industri->kelurahan || $industri->kecamatan)
                                Kel. {{ $industri->kelurahan ?? '-' }}, Kec. {{ $industri->kecamatan ?? '-' }}<br>
                            @endif
                            {{ $industri->kota }}, {{ $industri->provinsi }} - {{ $industri->kode_pos ?? '' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection