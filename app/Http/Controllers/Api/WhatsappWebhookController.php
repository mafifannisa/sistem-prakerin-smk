<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\LogWa;
use App\Models\Siswa;

class WhatsappWebhookController extends Controller
{
    public function webhook(Request $request)
    {
        // Tangkap data dari Fonnte
        $sender = $request->sender;     // Nomor pengirim (siswa)
        $message = $request->message;   // Isi pesan

        // Abaikan jika pesan kosong atau bukan teks
        if (!$message || empty($sender)) {
            return response()->json(['status' => 'ignored']);
        }

        // 1. INSTRUKSI SISTEM UNTUK GEMINI (Prompt Engineering Berdasarkan Sistemmu)
        $systemPrompt = "Kamu adalah 'PrakerinBot', Asisten AI Customer Service resmi untuk Sistem Administrasi Prakerin (Magang) SMK Negeri 3 Tuban. Gunakan bahasa yang ramah, sopan, dan asyik layaknya admin sekolah untuk anak SMK.

        INFORMASI SISTEM YANG WAJIB KAMU KETAHUI UNTUK MENJAWAB:
        1. Jurusan di SMK 3 Tuban: TPM (Pemesinan), TKI (Kimia Industri), TKR (Kendaraan Ringan), RPL (Rekayasa Perangkat Lunak), TB (Tata Boga), TPTU (Tata Udara), dan APL (Analisis Pengujian Lab).
        2. Alur Pengajuan: Siswa login -> Menu Cek Status -> Ajukan Mitra/Mandiri -> Verifikasi TU -> Approval Pimpinan -> Cetak Surat Pengantar.
        3. Kewajiban Siswa Magang: Wajib Absensi harian, mengisi minimal 60 Jurnal Harian, dan upload Laporan PKL di website.
        4. Syarat Lulus & Sertifikat: Harus menyelesaikan absen, jurnal, laporan, dan mendapat nilai akhir minimal 70 dari perusahaan. Sertifikat bisa diunduh di menu 'Download Sertifikat'.
        5. Lupa Password: Password default siswa adalah NISN. Jika masih gagal login, hubungi TU sekolah.

        ATURAN KETAT (WAJIB DIPATUHI):
        - Jawab dengan singkat dan jelas (maksimal 2-3 paragraf pendek). Jangan bertele-tele.
        - Jika siswa bertanya DI LUAR TOPIK magang/prakerin SMK 3 Tuban (misalnya: minta tolong kerjakan PR matematika, tanya soal politik, game, coding rumit, atau curhat), TOLAK dengan halus dan ingatkan bahwa kamu hanya melayani pertanyaan terkait Magang/Prakerin SMK 3 Tuban.
        - Jangan pernah memberikan janji palsu, arahkan siswa untuk mengecek website atau menghubungi Tata Usaha (TU) jika masalahnya di luar kemampuanmu.";

        // 2. TEMBAK KE API GEMINI (Model 1.5 Flash - Super Cepat)
        // $geminiApiKey = env('GEMINI_API_KEY'); 
        
        // // (Atau kalau kamu mau tembak langsung tanpa .env, hapus kode di atas dan pakai ini:)
        $geminiApiKey = 'AIzaSyBC4WS_hQBF1N9AUOipmY_kMTWyGK-PAAk';
        
        try {
            $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key=" . $geminiApiKey, [
                "contents" => [
                    [
                        "parts" => [
                            ["text" => $systemPrompt . "\n\nSekarang, jawablah pertanyaan siswa berikut: \"" . $message . "\""]
                        ]
                    ]
                ]
            ]);

            // Cek apakah sukses atau error
            if ($response->successful()) {
                $result = $response->json();
                $botReply = $result['candidates'][0]['content']['parts'][0]['text'] ?? "Maaf, respon AI kosong.";
            } else {
                // Tampilkan pesan error asli dari Google Gemini
                $botReply = "ERROR GEMINI: " . $response->body();
            }

            // 3. BALAS KE WHATSAPP SISWA VIA FONNTE
            Http::withHeaders([
                'Authorization' => env('FONNTE_TOKEN')
            ])->post('https://api.fonnte.com/send', [
                'target' => $sender,
                'message' => $botReply
            ]);

            // 4. CATAT LOG KE DATABASE
            $siswa = Siswa::where('no_wa', $sender)->orWhere('no_wa', 'like', '%' . substr($sender, 2) . '%')->first();
            LogWa::create([
                'siswa_id' => $siswa ? $siswa->id : null,
                'no_wa_tujuan' => $sender,
                'pesan' => "AI REPLY: \n" . $botReply,
                'jenis' => 'chatbot_reply',
                'status' => 'sent',
                'created_by' => 1 // ID Default Admin
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }

        return response()->json(['status' => 'ok']);
    }
}