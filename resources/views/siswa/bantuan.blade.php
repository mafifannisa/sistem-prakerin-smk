@extends('layouts.siswa')

@section('title', 'Bantuan & FAQ')

@section('content')
<header class="bg-white border-b border-gray-200 px-8 py-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Bantuan & FAQ</h1>
            <p class="text-sm text-gray-500 mt-1">Cari solusi cepat untuk pertanyaan yang sering diajukan</p>
        </div>
        <div class="text-sm text-gray-600">{{ tanggal_indonesia() }}</div>
    </div>
</header>

<div class="p-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- FAQ Section (Kiri-Tengah) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Pertanyaan Sering Diajukan</h2>
                
                <div class="space-y-4">
                    @foreach($faqs as $faq)
                        <div class="border border-gray-200 rounded-xl overflow-hidden">
                            <button onclick="toggleFaq({{ $faq['id'] }})" 
                                    class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition">
                                <span class="font-semibold text-gray-800">{{ $faq['pertanyaan'] }}</span>
                                <svg id="icon-{{ $faq['id'] }}" class="w-5 h-5 text-gray-400 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div id="faq-{{ $faq['id'] }}" class="hidden px-6 pb-4">
                                <div class="bg-gray-50 rounded-xl p-4 text-gray-700">
                                    {{ $faq['jawaban'] }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Contact Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">Kirim Email</h3>
                    <p class="text-sm text-gray-500 mb-3">hubungi kami via email resmi sekolah</p>
                    <a href="mailto:prakerin@smkn3tuban.sch.id" class="text-orange-600 font-semibold hover:underline">
                        prakerin@smkn3tuban.sch.id
                    </a>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">Hubungi Langsung</h3>
                    <p class="text-sm text-gray-500 mb-3">Tersedia via WhatsApp Admin</p>
                    <a href="https://wa.me/6281234567890" target="_blank" class="text-green-600 font-semibold hover:underline">
                        +62 812 3456 7890
                    </a>
                </div>
            </div>
        </div>

        <!-- Chatbot Section (Kanan) -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden sticky top-8">
                <!-- Header Chatbot -->
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-6 text-white">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg">Asisten Virtual SMKN 3</h3>
                            <div class="flex items-center gap-2 text-sm text-white/80">
                                <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                                <span>Online | Siap Membantu</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chat Messages -->
                <div id="chatMessages" class="h-96 overflow-y-auto p-4 space-y-4 bg-gray-50">
                    <!-- Bot Message -->
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="bg-white rounded-2xl rounded-tl-sm p-3 shadow-sm max-w-[85%]">
                            <p class="text-sm text-gray-700">
                                Halo! Saya adalah asisten virtual SMKN 3 Tuban. Ada yang bisa saya bantu terkait administrasi Prakerin?
                            </p>
                            <span class="text-xs text-gray-400 mt-1 block">09:41 AM</span>
                        </div>
                    </div>

                    <!-- User Message (Example) -->
                    <div class="flex items-start gap-3 justify-end">
                        <div class="bg-orange-500 rounded-2xl rounded-tr-sm p-3 shadow-sm max-w-[85%]">
                            <p class="text-sm text-white">
                                Saya ingin menanyakan status surat permohonan saya yang belum divalidasi.
                            </p>
                            <span class="text-xs text-white/80 mt-1 block text-right">09:42 AM</span>
                        </div>
                    </div>

                    <!-- Bot Reply (Example) -->
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="bg-white rounded-2xl rounded-tl-sm p-3 shadow-sm max-w-[85%]">
                            <p class="text-sm text-gray-700">
                                Tentu, boleh sebutkan nomor induk siswa (NISN) Anda untuk pengecekan lebih lanjut?
                            </p>
                            <span class="text-xs text-gray-400 mt-1 block">09:42 AM</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Questions -->
                <div class="p-4 border-t border-gray-200 bg-white">
                    <p class="text-xs text-gray-500 mb-3">Pertanyaan Cepat:</p>
                    <div class="flex flex-wrap gap-2">
                        <button onclick="sendQuickQuestion('Cara mengajukan magang?')" 
                                class="px-3 py-2 bg-gray-100 hover:bg-orange-100 text-gray-700 hover:text-orange-700 text-xs rounded-lg transition">
                            Cara mengajukan magang?
                        </button>
                        <button onclick="sendQuickQuestion('Status pengajuan saya')" 
                                class="px-3 py-2 bg-gray-100 hover:bg-orange-100 text-gray-700 hover:text-orange-700 text-xs rounded-lg transition">
                            Status pengajuan saya
                        </button>
                        <button onclick="sendQuickQuestion('Download sertifikat')" 
                                class="px-3 py-2 bg-gray-100 hover:bg-orange-100 text-gray-700 hover:text-orange-700 text-xs rounded-lg transition">
                            Download sertifikat
                        </button>
                    </div>
                </div>

                <!-- Input Chat -->
                <div class="p-4 border-t border-gray-200 bg-white">
                    <form onsubmit="sendMessage(event)" class="flex items-center gap-2">
                        <input type="text" 
                               id="chatInput"
                               placeholder="Ketik pesan..." 
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm">
                        <button type="submit" 
                                class="w-10 h-10 bg-orange-500 hover:bg-orange-600 text-white rounded-xl flex items-center justify-center transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle FAQ
function toggleFaq(id) {
    const faq = document.getElementById(`faq-${id}`);
    const icon = document.getElementById(`icon-${id}`);
    
    if (faq.classList.contains('hidden')) {
        faq.classList.remove('hidden');
        icon.classList.add('rotate-180');
    } else {
        faq.classList.add('hidden');
        icon.classList.remove('rotate-180');
    }
}

// Send Message
function sendMessage(event) {
    event.preventDefault();
    const input = document.getElementById('chatInput');
    const message = input.value.trim();
    
    if (message) {
        addMessage(message, 'user');
        input.value = '';
        
        // Simulate bot response
        setTimeout(() => {
            const response = getBotResponse(message);
            addMessage(response, 'bot');
        }, 1000);
    }
}

// Send Quick Question
function sendQuickQuestion(question) {
    addMessage(question, 'user');
    
    setTimeout(() => {
        const response = getBotResponse(question);
        addMessage(response, 'bot');
    }, 1000);
}

// Add Message to Chat
function addMessage(text, sender) {
    const chatMessages = document.getElementById('chatMessages');
    const time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    
    if (sender === 'user') {
        const messageHtml = `
            <div class="flex items-start gap-3 justify-end">
                <div class="bg-orange-500 rounded-2xl rounded-tr-sm p-3 shadow-sm max-w-[85%]">
                    <p class="text-sm text-white">${text}</p>
                    <span class="text-xs text-white/80 mt-1 block text-right">${time}</span>
                </div>
            </div>
        `;
        chatMessages.insertAdjacentHTML('beforeend', messageHtml);
    } else {
        const messageHtml = `
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="bg-white rounded-2xl rounded-tl-sm p-3 shadow-sm max-w-[85%]">
                    <p class="text-sm text-gray-700">${text}</p>
                    <span class="text-xs text-gray-400 mt-1 block">${time}</span>
                </div>
            </div>
        `;
        chatMessages.insertAdjacentHTML('beforeend', messageHtml);
    }
    
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Simple Bot Response Logic
function getBotResponse(message) {
    message = message.toLowerCase();
    
    if (message.includes('ajukan') || message.includes('magang') || message.includes('pengajuan')) {
        return 'Untuk mengajukan tempat magang, buka menu "Cek Status Magang", pilih industri, isi form, dan klik "Ajukan Pengajuan". Proses approval biasanya 3-5 hari kerja.';
    } else if (message.includes('status')) {
        return 'Untuk cek status pengajuan, buka menu "Cek Status Magang". Di sana Anda bisa melihat timeline progress pengajuan Anda.';
    } else if (message.includes('sertifikat') || message.includes('download')) {
        return 'Sertifikat dapat diunduh setelah magang selesai dan laporan disetujui. Buka menu "Download Sertifikat" untuk mengunduh.';
    } else if (message.includes('jurnal')) {
        return 'Jurnal harian diisi setiap hari di menu "Laporan" > "Jurnal Harian". Jangan lupa upload foto wajah dan deskripsi minimal 50 karakter.';
    } else if (message.includes('absen') || message.includes('presensi')) {
        return 'Absensi diisi setiap hari di menu "Laporan" > "Absensi Harian". Upload foto selfie sebagai bukti kehadiran.';
    } else {
        return 'Terima kasih atas pertanyaannya. Untuk informasi lebih lanjut, silakan hubungi admin melalui WhatsApp atau email yang tersedia di halaman ini.';
    }
}
</script>
@endsection