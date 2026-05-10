<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faq;

class FAQSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'Kapan bisa download surat pengantar?',
                'answer' => 'Surat pengantar bisa didownload setelah status pengajuan magang Anda "Disetujui" oleh Pimpinan. Cek status di menu "Cek Status Magang".',
                'category' => 'surat',
                'keywords' => 'download,surat,pengantar,kapan,status',
                'priority' => 10,
            ],
            [
                'question' => 'Bagaimana cara cek status magang?',
                'answer' => 'Login ke dashboard siswa, lalu klik menu "Cek Status Magang" di sidebar. Anda akan melihat timeline status pengajuan Anda.',
                'category' => 'status',
                'keywords' => 'cek,status,magang,timeline',
                'priority' => 9,
            ],
            [
                'question' => 'Berapa nilai minimal untuk dapat sertifikat?',
                'answer' => 'Nilai minimal untuk mendapatkan sertifikat adalah 70. Jika nilai Anda di bawah 70, Anda harus mengulang program magang.',
                'category' => 'sertifikat',
                'keywords' => 'nilai,minimal,sertifikat,lulus',
                'priority' => 8,
            ],
            [
                'question' => 'Kapan sertifikat bisa didownload?',
                'answer' => 'Sertifikat bisa didownload setelah nilai Anda diinput oleh Admin dan status magang Anda "Completed".',
                'category' => 'sertifikat',
                'keywords' => 'kapan,sertifikat,download',
                'priority' => 7,
            ],
            [
                'question' => 'Bagaimana cara import nilai?',
                'answer' => 'Fitur import nilai hanya untuk Admin. Admin dapat mengupload file Excel template yang sudah disediakan di menu "Import Nilai".',
                'category' => 'nilai',
                'keywords' => 'import,nilai,excel,admin',
                'priority' => 6,
            ],
            [
                'question' => 'Apa saja 6 jurusan di SMK 3 Tuban?',
                'answer' => '6 jurusan di SMK Negeri 3 Tuban: TPM (Teknik Pemesinan), TKI (Teknik Kimia Industri), TKR (Teknik Kendaraan Ringan), RPL (Rekayasa Perangkat Lunak), TB (Tata Boga), dan TPTU (Teknik Pendinginan dan Tata Udara).',
                'category' => 'umum',
                'keywords' => 'jurusan,6,TPM,TKI,TKR,RPL,TB,TPTU',
                'priority' => 5,
            ],
            [
                'question' => 'Berapa lama program magang?',
                'answer' => 'Program magang/prakerin di SMK Negeri 3 Tuban berlangsung selama 3-6 bulan tergantung jurusan dan industri.',
                'category' => 'umum',
                'keywords' => 'lama,durasi,magang,prakerin',
                'priority' => 4,
            ],
            [
                'question' => 'Bagaimana cara hubungi admin?',
                'answer' => 'Anda bisa hubungi Admin TU melalui WhatsApp di nomor 081234567890 atau datang langsung ke ruang Tata Usaha.',
                'category' => 'kontak',
                'keywords' => 'hubungi,admin,kontak,WA,telepon',
                'priority' => 3,
            ],
            [
                'question' => 'Apa itu sistem prakerin digital?',
                'answer' => 'Sistem Administrasi Prakerin Digital adalah platform online untuk mengelola seluruh proses praktik kerja industri di SMK Negeri 3 Tuban secara terintegrasi.',
                'category' => 'umum',
                'keywords' => 'sistem,prakerin,digital,online',
                'priority' => 2,
            ],
            [
                'question' => 'Lupa password login, apa yang harus dilakukan?',
                'answer' => 'Untuk siswa, password default adalah NISN Anda. Untuk admin/pimpinan, hubungi Administrator Sistem untuk reset password.',
                'category' => 'akun',
                'keywords' => 'lupa,password,login,reset',
                'priority' => 1,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create([
                'question' => $faq['question'],
                'answer' => $faq['answer'],
                'category' => $faq['category'],
                'keywords' => $faq['keywords'],
                'is_active' => true,
                'priority' => $faq['priority'],
            ]);
        }
    }
}