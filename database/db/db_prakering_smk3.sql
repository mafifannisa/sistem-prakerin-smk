-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 27 Apr 2026 pada 18.25
-- Versi server: 11.7.2-MariaDB
-- Versi PHP: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Basis data: `db_prakering_smk3`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `absensis`
--

CREATE TABLE `absensis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `siswa_id` bigint(20) UNSIGNED NOT NULL,
  `penempatan_magang_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `status` enum('hadir','izin','sakit','alpha') NOT NULL DEFAULT 'hadir',
  `jam_masuk` time DEFAULT NULL,
  `jam_pulang` time DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `bukti_foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `faqs`
--

CREATE TABLE `faqs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `priority` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `category`, `keywords`, `is_active`, `priority`, `created_at`, `updated_at`) VALUES
(1, 'Kapan bisa download surat pengantar?', 'Surat pengantar bisa didownload setelah status pengajuan magang Anda \"Disetujui\" oleh Pimpinan. Cek status di menu \"Cek Status Magang\".', 'surat', 'download,surat,pengantar,kapan,status', 1, 10, '2026-04-01 06:47:32', '2026-04-01 06:47:32'),
(2, 'Bagaimana cara cek status magang?', 'Login ke dashboard siswa, lalu klik menu \"Cek Status Magang\" di sidebar. Anda akan melihat timeline status pengajuan Anda.', 'status', 'cek,status,magang,timeline', 1, 9, '2026-04-01 06:47:32', '2026-04-01 06:47:32'),
(3, 'Berapa nilai minimal untuk dapat sertifikat?', 'Nilai minimal untuk mendapatkan sertifikat adalah 70. Jika nilai Anda di bawah 70, Anda harus mengulang program magang.', 'sertifikat', 'nilai,minimal,sertifikat,lulus', 1, 8, '2026-04-01 06:47:32', '2026-04-01 06:47:32'),
(4, 'Kapan sertifikat bisa didownload?', 'Sertifikat bisa didownload setelah nilai Anda diinput oleh Admin dan status magang Anda \"Completed\".', 'sertifikat', 'kapan,sertifikat,download', 1, 7, '2026-04-01 06:47:32', '2026-04-01 06:47:32'),
(5, 'Bagaimana cara import nilai?', 'Fitur import nilai hanya untuk Admin. Admin dapat mengupload file Excel template yang sudah disediakan di menu \"Import Nilai\".', 'nilai', 'import,nilai,excel,admin', 1, 6, '2026-04-01 06:47:32', '2026-04-01 06:47:32'),
(6, 'Apa saja 6 jurusan di SMK 3 Tuban?', '6 jurusan di SMK Negeri 3 Tuban: TPM (Teknik Pemesinan), TKI (Teknik Kimia Industri), TKR (Teknik Kendaraan Ringan), RPL (Rekayasa Perangkat Lunak), TB (Tata Boga), dan TPTU (Teknik Pendinginan dan Tata Udara).', 'umum', 'jurusan,6,TPM,TKI,TKR,RPL,TB,TPTU', 1, 5, '2026-04-01 06:47:32', '2026-04-01 06:47:32'),
(7, 'Berapa lama program magang?', 'Program magang/prakerin di SMK Negeri 3 Tuban berlangsung selama 3-6 bulan tergantung jurusan dan industri.', 'umum', 'lama,durasi,magang,prakerin', 1, 4, '2026-04-01 06:47:32', '2026-04-01 06:47:32'),
(8, 'Bagaimana cara hubungi admin?', 'Anda bisa hubungi Admin TU melalui WhatsApp di nomor 081234567890 atau datang langsung ke ruang Tata Usaha.', 'kontak', 'hubungi,admin,kontak,WA,telepon', 1, 3, '2026-04-01 06:47:32', '2026-04-01 06:47:32'),
(9, 'Apa itu sistem prakerin digital?', 'Sistem Administrasi Prakerin Digital adalah platform online untuk mengelola seluruh proses praktik kerja industri di SMK Negeri 3 Tuban secara terintegrasi.', 'umum', 'sistem,prakerin,digital,online', 1, 2, '2026-04-01 06:47:32', '2026-04-01 06:47:32'),
(10, 'Lupa password login, apa yang harus dilakukan?', 'Untuk siswa, password default adalah NISN Anda. Untuk admin/pimpinan, hubungi Administrator Sistem untuk reset password.', 'akun', 'lupa,password,login,reset', 1, 1, '2026-04-01 06:47:32', '2026-04-01 06:47:32');

-- --------------------------------------------------------

--
-- Struktur dari tabel `industris`
--

CREATE TABLE `industris` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_industri` varchar(255) NOT NULL,
  `nib` varchar(255) DEFAULT NULL,
  `alamat` text NOT NULL,
  `kelurahan` varchar(255) DEFAULT NULL,
  `kecamatan` varchar(255) DEFAULT NULL,
  `kota` varchar(255) NOT NULL,
  `provinsi` varchar(255) NOT NULL DEFAULT 'Jawa Timur',
  `kode_pos` varchar(255) DEFAULT NULL,
  `no_telp` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `nama_hr` varchar(255) DEFAULT NULL,
  `no_wa_hr` varchar(255) DEFAULT NULL,
  `pembimbing_magang` varchar(255) DEFAULT NULL,
  `kategori` varchar(255) DEFAULT NULL,
  `kapasitas_magang` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `industris`
--

INSERT INTO `industris` (`id`, `nama_industri`, `nib`, `alamat`, `kelurahan`, `kecamatan`, `kota`, `provinsi`, `kode_pos`, `no_telp`, `email`, `website`, `nama_hr`, `no_wa_hr`, `pembimbing_magang`, `kategori`, `kapasitas_magang`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'PT. Teknologi Nusantara Abadi', '1234567890124', 'Jl. Gatot Subroto No. 12', 'Latsari', 'Tuban', 'Surabaya', 'Jawa Timur', '62319', '0356-234567', 'recruitment@teknusantara.co.id', 'www.teknusantara.co.id', 'Ibu Sari', '081234567902', NULL, 'IT', 30, 1, '2026-04-01 06:47:32', '2026-04-06 18:47:03'),
(3, 'CV. Baruna Jaya', '1234567890125', 'Jl. Basuki Rahmat No. 45', 'Doromukti', 'Tuban', 'Tuban', 'Jawa Timur', '62315', '0356-345678', 'info@barunajaya.com', 'www.barunajaya.com', 'Bapak Joko', '081234567903', NULL, 'Otomotif', 40, 1, '2026-04-01 06:47:32', '2026-04-01 06:47:32'),
(4, 'PT. Bank Jatim Cabang Tuban', '1234567890126', 'Jl. Pemuda No. 88', 'Ronggomulyo', 'Tuban', 'Tuban', 'Jawa Timur', '62311', '0356-456789', 'cabang.tuban@bankjatim.co.id', 'www.bankjatim.co.id', 'Ibu Wulan', '081234567904', NULL, 'Keuangan', 20, 1, '2026-04-01 06:47:32', '2026-04-01 06:47:32'),
(5, 'Hotel Tuban Graha', '1234567890127', 'Jl. Sunan Bonang No. 99', 'Penambangan', 'Tuban', 'Tuban', 'Jawa Timur', '62316', '0356-567890', 'hr@tubangraha.com', 'www.tubangraha.com', 'Bapak Agus', '081234567905', NULL, 'Pariwisata', 25, 1, '2026-04-01 06:47:32', '2026-04-01 06:47:32'),
(7, 'PT. Gacor', '123456789112', 'Jl. Industri Raya No. 1', 'Karangrejo', 'Kerek', 'Surabaya Utara', 'Jawa Timur', '62354', '0356123456', 'hrd@sukamakmur.co.id', NULL, 'Bapak Hendraa', '081234567902', NULL, 'Tech', 0, 1, '2026-04-07 14:03:21', '2026-04-07 14:03:21'),
(8, 'PT. Gacor', '123456789112', 'Jl. Industri Raya No. 1', 'Karangrejo', 'Kerek', 'Surabaya Utara', 'Jawa Timur', '62354', '0356123456', 'hrd@sukamakmur.co.id', NULL, 'Bapak Hendraa', '081234567902', NULL, 'Tech', 0, 1, '2026-04-07 14:13:54', '2026-04-07 14:13:54'),
(9, 'PT. Gacor', '123456789112', 'Jl. Industri Raya No. 1', 'Karangrejo', 'Kerek', 'Surabaya Utara', 'Jawa Timur', '62354', '0356123456', 'hrd@sukamakmur.co.id', 'www.sukamakmur.co.id', 'Bapak Hendraa', '081234567902', NULL, 'Tech', 0, 1, '2026-04-07 15:03:26', '2026-04-07 15:03:26'),
(10, 'PT. Gacor', '123456789112', 'Jl. Industri Raya No. 1', 'Karangrejo', 'Kerek', 'Surabaya Utara', 'Jawa Timur', '62354', '0356123456', 'hrd@sukamakmur.co.id', 'www.sukamakmur.co.id', 'Bapak Hendraa', '081234567902', NULL, 'Tech', 0, 1, '2026-04-07 15:23:14', '2026-04-07 15:23:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jurnal_harians`
--

CREATE TABLE `jurnal_harians` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `siswa_id` bigint(20) UNSIGNED NOT NULL,
  `penempatan_magang_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `minggu_ke` int(11) NOT NULL,
  `kegiatan` text NOT NULL,
  `durasi_jam` int(11) NOT NULL DEFAULT 8,
  `catatan_pembimbing` text DEFAULT NULL,
  `status` enum('pending','disetujui','ditolak') NOT NULL DEFAULT 'pending',
  `disetujui_oleh` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jurusans`
--

CREATE TABLE `jurusans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_jurusan` varchar(255) NOT NULL,
  `nama_jurusan` varchar(255) NOT NULL,
  `kepala_jurusan` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `jurusans`
--

INSERT INTO `jurusans` (`id`, `kode_jurusan`, `nama_jurusan`, `kepala_jurusan`, `deskripsi`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'TPM', 'Teknik Pemesinan', 'Budi Santoso, S.Pd, M.T', 'Program keahlian Teknik Pemesinan SMK Negeri 3 Tuban', 1, '2026-04-01 06:47:27', '2026-04-01 06:47:27'),
(2, 'TKI', 'Teknik Kimia Industri', 'Dwi Hartono, S.T, M.Sc', 'Program keahlian Teknik Kimia Industri SMK Negeri 3 Tuban', 1, '2026-04-01 06:47:27', '2026-04-01 06:47:27'),
(3, 'TKR', 'Teknik Kendaraan Ringan', 'Ahmad Fauzi, S.Pd', 'Program keahlian Teknik Kendaraan Ringan SMK Negeri 3 Tuban', 1, '2026-04-01 06:47:27', '2026-04-01 06:47:27'),
(4, 'RPL', 'Rekayasa Perangkat Lunak', 'Siti Nurhaliza, S.Kom, M.Kom', 'Program keahlian Rekayasa Perangkat Lunak SMK Negeri 3 Tuban', 1, '2026-04-01 06:47:27', '2026-04-01 06:47:27'),
(5, 'TB', 'Tata Boga', 'Dewi Lestari, S.Pd, M.M', 'Program keahlian Tata Boga SMK Negeri 3 Tuban', 1, '2026-04-01 06:47:27', '2026-04-01 06:47:27'),
(6, 'TPTU', 'Teknik Pendinginan dan Tata Udara', 'Eko Prasetyo, S.T', 'Program keahlian Teknik Pendinginan dan Tata Udara SMK Negeri 3 Tuban', 1, '2026-04-01 06:47:27', '2026-04-01 06:47:27'),
(7, 'APL', 'Analisis Pengujiian Laboratorium', 'Ahmad Syauqi', 'ANALISIS PENGUJIAN LABORATORIUM SMKN 3 TUBAN', 1, '2026-04-05 17:22:28', '2026-04-05 17:22:28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan_pkls`
--

CREATE TABLE `laporan_pkls` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `siswa_id` bigint(20) UNSIGNED NOT NULL,
  `penempatan_magang_id` bigint(20) UNSIGNED NOT NULL,
  `judul_laporan` varchar(255) NOT NULL,
  `abstrak` text DEFAULT NULL,
  `jenis` enum('draft','submit','revisi','approved') NOT NULL DEFAULT 'draft',
  `file_path` varchar(255) DEFAULT NULL,
  `tanggal_submit` date DEFAULT NULL,
  `catatan_pembimbing` text DEFAULT NULL,
  `status` enum('pending','disetujui','perlu_revisi') NOT NULL DEFAULT 'pending',
  `disetujui_oleh` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_was`
--

CREATE TABLE `log_was` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `siswa_id` bigint(20) UNSIGNED DEFAULT NULL,
  `no_wa_tujuan` varchar(255) NOT NULL,
  `pesan` text NOT NULL,
  `jenis` enum('blast','individual','chatbot_reply') NOT NULL DEFAULT 'individual',
  `status` enum('pending','sent','failed','delivered') NOT NULL DEFAULT 'pending',
  `message_id` varchar(255) DEFAULT NULL,
  `response` text DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 1),
(3, '2026_04_01_105520_create_users_table', 1),
(4, '2026_04_01_105534_create_jurusans_table', 1),
(5, '2026_04_01_105547_create_siswas_table', 1),
(6, '2026_04_01_105602_create_industris_table', 1),
(7, '2026_04_01_105610_create_penempatan_magangs_table', 1),
(8, '2026_04_01_105615_create_template_surats_table', 1),
(9, '2026_04_01_105627_create_surat_masuks_table', 1),
(10, '2026_04_01_105641_create_nilais_table', 1),
(11, '2026_04_01_105648_create_sertifikats_table', 1),
(12, '2026_04_01_105650_create_surat_keluars_table', 1),
(13, '2026_04_01_105658_create_log_was_table', 1),
(14, '2026_04_01_105703_create_faqs_table', 1),
(15, '2026_04_01_134803_create_sessions_table', 2),
(16, '2026_04_01_185034_create_absensis_table', 3),
(17, '2026_04_01_185042_create_jurnal_harians_table', 3),
(18, '2026_04_01_185047_create_laporan_pkls_table', 3),
(19, '2026_04_02_032752_create_notifikasis_table', 4),
(20, '2026_04_02_032801_create_pengumumans_table', 4),
(21, '2026_04_06_225452_add_kelas_to_siswas_table', 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `nilais`
--

CREATE TABLE `nilais` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `penempatan_magang_id` bigint(20) UNSIGNED NOT NULL,
  `nilai_sikap` decimal(5,2) DEFAULT NULL,
  `nilai_keterampilan` decimal(5,2) DEFAULT NULL,
  `nilai_pengetahuan` decimal(5,2) DEFAULT NULL,
  `nilai_penguji` decimal(5,2) DEFAULT NULL,
  `kegiatan_1` varchar(255) DEFAULT NULL,
  `nilai_1` decimal(5,2) DEFAULT NULL,
  `kegiatan_2` varchar(255) DEFAULT NULL,
  `nilai_2` decimal(5,2) DEFAULT NULL,
  `kegiatan_3` varchar(255) DEFAULT NULL,
  `nilai_3` decimal(5,2) DEFAULT NULL,
  `foto_nilai` varchar(255) DEFAULT NULL,
  `nilai_akhir` decimal(5,2) DEFAULT NULL,
  `predikat` enum('A','B','C','D','E') DEFAULT NULL,
  `catatan_penguji` text DEFAULT NULL,
  `tanggal_input` datetime DEFAULT NULL,
  `input_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifikasis`
--

CREATE TABLE `notifikasis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `siswa_id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `pesan` text NOT NULL,
  `jenis` enum('info','warning','success','error') NOT NULL DEFAULT 'info',
  `tipe` enum('umum','pengumuman','approval','app','lainnya') DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `notifikasis`
--

INSERT INTO `notifikasis` (`id`, `siswa_id`, `judul`, `pesan`, `jenis`, `tipe`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 1, 'Pengajuan Magang Dikirim', 'Pengajuan tempat magang Anda telah dikirim. Tunggu verifikasi dari TU.', 'info', 'umum', 0, '2026-04-01 22:06:00', '2026-04-01 22:06:00'),
(2, 6, 'Pengajuan Magang Disetujui', 'Pengajuan magang Anda di PT. Suka Makmur telah disetujui. Surat pengantar sudah tersedia untuk diunduh.', 'success', 'app', 0, '2026-04-05 21:05:10', '2026-04-05 21:05:10'),
(3, 6, 'Pengajuan Magang Disetujui', 'Pengajuan magang Anda di PT. Suka Makmur telah disetujui. Surat pengantar sudah tersedia untuk diunduh.', 'success', 'app', 0, '2026-04-05 21:13:30', '2026-04-05 21:13:30'),
(4, 1, 'Pengajuan Magang Disetujui', 'Pengajuan magang Anda di Hotel Tuban Graha telah disetujui. Silakan download Surat Pengantar Anda di Dashboard.', 'success', 'umum', 0, '2026-04-05 21:23:22', '2026-04-05 21:23:22'),
(5, 2, 'Pengajuan Magang Disetujui', 'Pengajuan magang Anda di PT. Bank Jatim Cabang Tuban telah disetujui. Silakan download Surat Pengantar Anda di Dashboard.', 'success', 'umum', 0, '2026-04-05 21:24:00', '2026-04-05 21:24:00'),
(6, 6, 'Pengajuan Magang Ditolak', 'Pengajuan magang Anda di PT. Suka Makmur ditolak. Silakan hubungi TU untuk informasi lebih lanjut.', 'error', 'app', 0, '2026-04-05 21:41:29', '2026-04-05 21:41:29'),
(7, 4, 'Pengajuan Diverifikasi Admin', 'Pengajuan Anda telah diverifikasi dan menunggu persetujuan Kepala Sekolah.', 'info', 'app', 0, '2026-04-06 21:49:23', '2026-04-06 21:49:23'),
(8, 4, 'Pengajuan Magang Disetujui', 'Pengajuan magang Anda di PT. Teknologi Nusantara Abadi telah disetujui oleh Kepala Sekolah. Silakan download Surat Pengantar Anda di Dashboard.', 'success', 'umum', 0, '2026-04-06 21:52:02', '2026-04-06 21:52:02'),
(9, 7, 'Pengajuan Diverifikasi Admin', 'Pengajuan Anda telah diverifikasi dan menunggu persetujuan Kepala Sekolah.', 'info', 'app', 0, '2026-04-07 13:00:28', '2026-04-07 13:00:28'),
(10, 7, 'Pengajuan Magang Disetujui', 'Pengajuan magang Anda di PT. Bank Jatim Cabang Tuban telah disetujui oleh Kepala Sekolah. Silakan download Surat Pengantar Anda di Dashboard.', 'success', 'umum', 0, '2026-04-07 13:01:53', '2026-04-07 13:01:53'),
(11, 8, 'Pengajuan Ditolak Admin', 'Pengajuan magang Anda ditolak oleh Admin.', 'error', 'app', 0, '2026-04-07 14:15:37', '2026-04-07 14:15:37'),
(12, 8, 'Pengajuan Ditolak Admin', 'Pengajuan Anda ditolak. Alasan: PT tidak di temukan njir', 'error', 'app', 0, '2026-04-07 15:10:36', '2026-04-07 15:10:36'),
(13, 8, 'Pengajuan Diverifikasi Admin', 'Pengajuan Anda telah diverifikasi dan menunggu persetujuan Kepala Sekolah.', 'info', 'app', 0, '2026-04-07 15:24:16', '2026-04-07 15:24:16'),
(14, 8, 'Pengajuan Ditolak Pimpinan', 'Pengajuan Anda ditolak oleh Kepala Sekolah. Alasan: lu gajelas njir', 'error', 'app', 0, '2026-04-07 15:25:12', '2026-04-07 15:25:12');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penempatan_magangs`
--

CREATE TABLE `penempatan_magangs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `siswa_id` bigint(20) UNSIGNED NOT NULL,
  `industri_id` bigint(20) UNSIGNED NOT NULL,
  `tahun_ajaran` varchar(255) NOT NULL,
  `semester` varchar(255) NOT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `status` enum('pending','verified','approved','rejected','ongoing','completed') NOT NULL DEFAULT 'pending',
  `alasan_penolakan` text DEFAULT NULL,
  `posisi_magang` varchar(255) DEFAULT NULL,
  `catatan_industri` text DEFAULT NULL,
  `tanggal_approval` date DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `penempatan_magangs`
--

INSERT INTO `penempatan_magangs` (`id`, `siswa_id`, `industri_id`, `tahun_ajaran`, `semester`, `tanggal_mulai`, `tanggal_selesai`, `status`, `alasan_penolakan`, `posisi_magang`, `catatan_industri`, `tanggal_approval`, `approved_by`, `created_at`, `updated_at`) VALUES
(1, 1, 5, '2025/2026', 'Ganjil', '2026-05-02', '2026-07-01', 'approved', NULL, 'Manager', 'Dapat uang saku', NULL, NULL, '2026-04-01 22:06:00', '2026-04-05 21:23:22'),
(2, 2, 4, '2025/2026', 'Ganjil', '2026-05-06', '2026-07-05', 'approved', NULL, 'Ceo', 'Gaji Besar', NULL, NULL, '2026-04-05 20:11:30', '2026-04-05 21:23:59'),
(4, 4, 2, '2025/2026', 'Ganjil', '2026-05-07', '2026-07-06', 'approved', NULL, 'Ceo', 'Gaji Tinggi', NULL, NULL, '2026-04-06 20:32:54', '2026-04-06 21:51:58'),
(5, 7, 4, '2025/2026', 'Ganjil', '2026-05-07', '2026-07-06', 'approved', NULL, 'Manager', 'Baik', NULL, NULL, '2026-04-07 12:15:00', '2026-04-07 13:01:47'),
(8, 8, 10, '2026/2027', 'Genap', NULL, NULL, 'rejected', 'lu gajelas njir', 'Ceo', 'cor mulai', NULL, NULL, '2026-04-07 15:23:14', '2026-04-07 15:25:12');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengumumans`
--

CREATE TABLE `pengumumans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `prioritas` enum('rendah','sedang','tinggi') NOT NULL DEFAULT 'sedang',
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `dibuat_oleh` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sertifikats`
--

CREATE TABLE `sertifikats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `penempatan_magang_id` bigint(20) UNSIGNED NOT NULL,
  `nilai_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nomor_sertifikat` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `tanggal_terbit` date DEFAULT NULL,
  `status` enum('draft','generated','issued') NOT NULL DEFAULT 'draft',
  `catatan` text DEFAULT NULL,
  `generated_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('7SglryT8MQBFlzq1BJomJkW9DZxnInLej1iqc5rX', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVzRrVGhORHhQd0tMRnlSZHlaM1hUUzNCTXhjT21pM0l3R0tXMU02TCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fX0=', 1777301701),
('Aqlbmw47e7Rh9tGEkEXu48djCSQohGhhsC2E3SFM', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaUFyQ1dmUGNHb3lUbTl5VGhSVDRBRXZhWE9TVk03alRkQk1MYjlyTyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1775574260),
('NffYugN0s1kNsf8IL32AYJCXeOtbltdPd5OTWwMh', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiT1p1cFVKclpLSkJoYkdkZUFBbXZGTnlGRWpIT0NJTmV0Slg5cHozcyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXN3YS9jZWstc3RhdHVzIjt9czo4OiJzaXN3YV9pZCI7aTo4O3M6MTA6InNpc3dhX25pc24iO3M6MTA6IjAwNTEyMzQ1NzQiO3M6MTA6InNpc3dhX25hbWEiO3M6MTQ6Ikd1bmF3YW4gV2lqYXlhIjtzOjEzOiJzaXN3YV9qdXJ1c2FuIjtzOjMzOiJUZWtuaWsgUGVuZGluZ2luYW4gZGFuIFRhdGEgVWRhcmEiO30=', 1775575533);

-- --------------------------------------------------------

--
-- Struktur dari tabel `siswas`
--

-- Struktur dari tabel `kelas`
--

CREATE TABLE `kelas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_kelas` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `siswas`
--

CREATE TABLE `siswas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nisn` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `tempat_lahir` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jurusan_id` bigint(20) UNSIGNED NOT NULL,
  `kelas_id` bigint(20) UNSIGNED DEFAULT NULL,
  `no_wa` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `nama_wali` varchar(255) DEFAULT NULL,
  `no_wa_wali` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `siswas`
--

INSERT INTO `siswas` (`id`, `nisn`, `nama`, `tempat_lahir`, `tanggal_lahir`, `jurusan_id`, `kelas_id`, `no_wa`, `email`, `alamat`, `nama_wali`, `no_wa_wali`, `password`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '0051234567', 'Rofiqul Wahyu Romadhani', 'Tuban', '2007-05-15', 4, NULL, '081234567801', 'rofiqul.wahyu.romadhani@student.smk3tuban.sch.id', 'Jl. Raya Tuban No. 91', 'Orang Tua Rofiqul Wahyu Romadhani', '08184700518', '$2y$12$pWHMG5W5E/2gXh3Su5n1GujT4gRX8upiydM/NphU7WqGui/tpx/na', 1, '2026-04-01 06:47:29', '2026-04-06 16:22:45'),
(2, '0051234568', 'Ahmad Maulana', 'Bojonegoro', '2007-06-20', 4, NULL, '081234567802', 'ahmad.maulana@student.smk3tuban.sch.id', 'Jl. Raya Tuban No. 49', 'Orang Tua Ahmad Maulana', '08148052630', '$2y$12$zXvDqhFWac0SvoEysz6VTuiah0.x0ZJUuoDRTaVuwreYhrkpYCfIy', 1, '2026-04-01 06:47:30', '2026-04-06 16:22:07'),
(3, '0051234569', 'Siti Nurhaliza', 'Tuban', '2007-07-10', 5, NULL, '081234567803', 'siti.nurhaliza@student.smk3tuban.sch.id', 'Jl. Raya Tuban No. 41', 'Orang Tua Siti Nurhaliza', '08170680733', '$2y$12$MXVKdr2KfWXVIwFr/.BpyOJhc/5.WKj1wkp4gStSnOlK9O7Tdlxmq', 1, '2026-04-01 06:47:30', '2026-04-06 16:22:18'),
(4, '0051234570', 'Budi Pratama', 'Lamongan', '2007-08-05', 3, NULL, '081234567804', 'budi.pratama@student.smk3tuban.sch.id', 'Jl. Raya Tuban No. 56', 'Orang Tua Budi Pratama', '08121706496', '$2y$12$iluTnUc/oyMPmgo6MhKTbOIDFrfzelhi2xWwMzvDHqrRtobjndOTu', 1, '2026-04-01 06:47:30', '2026-04-06 16:22:30'),
(5, '0051234571', 'Dewi Sartika', 'Tuban', '2007-09-12', 5, NULL, '081234567805', 'dewi.sartika@student.smk3tuban.sch.id', 'Jl. Raya Tuban No. 25', 'Orang Tua Dewi Sartika', '08168021392', '$2y$12$CG65f885C8KNyBfDQIxtSenU.wU0W66LGwS6mutDl6rjJR74tRuhm', 1, '2026-04-01 06:47:31', '2026-04-06 16:21:12'),
(6, '0051234572', 'Eko Prasetyo', 'Gresik', '2007-10-18', 1, NULL, '081234567806', 'eko.prasetyo@student.smk3tuban.sch.id', 'Jl. Raya Tuban No. 90', 'Orang Tua Eko Prasetyo', '08134287555', '$2y$12$qA0tEjYeqTCyWL60a5NzMuQRDBJYl8DAVOb8rqPvCnhOWQH.MnT/W', 1, '2026-04-01 06:47:31', '2026-04-06 16:21:33'),
(7, '0051234573', 'Fitri Rahmawati', 'Tuban', '2007-11-22', 2, NULL, '081234567807', 'fitri.rahmawati@student.smk3tuban.sch.id', 'Jl. Raya Tuban No. 1', 'Orang Tua Fitri Rahmawati', '08177766490', '$2y$12$mAAuBgu.0npoWTExfZqvyeu87J58sMo1NZ4hIrSXxQsae31HeVJUO', 1, '2026-04-01 06:47:31', '2026-04-06 16:21:48'),
(8, '0051234574', 'Gunawan Wijaya', 'Surabaya', '2007-12-08', 6, NULL, '081234567808', 'gunawan.wijaya@student.smk3tuban.sch.id', 'Jl. Raya Tuban No. 38', 'Orang Tua Gunawan Wijaya', '08163615514', '$2y$12$grump92PMvaiBRantSK1DeC0KuChZqRquXpUUOVZ1r9Hubo.iPxnq', 1, '2026-04-01 06:47:32', '2026-04-06 16:20:17'),
(9, '0051234575', 'Hana Pertiwi', 'Tuban', '2008-01-14', 2, NULL, '081234567809', 'hana.pertiwi@student.smk3tuban.sch.id', 'Jl. Raya Tuban No. 94', 'Orang Tua Hana Pertiwi', '08188745105', '$2y$12$xKzK5cfA.DKM7GWPHEciAeSIlhoRf..venQn36wb8xeMic.rrpgzu', 1, '2026-04-01 06:47:32', '2026-04-06 16:20:36'),
(10, '0051234576', 'Indra Kusuma', 'Tuban', '2008-02-20', 4, NULL, '081234567810', 'indra.kusuma@student.smk3tuban.sch.id', 'Jl. Raya Tuban No. 86', 'Orang Tua Indra Kusuma', '08156740054', '$2y$12$MnWJlQlJUVxQhIr8LoMcPuFFIuEkT9RxLd9WIaOj9ZgDrbUc9cDs2', 1, '2026-04-01 06:47:32', '2026-04-06 16:20:47'),
(11, '123456789112', 'Freya Gracelinaa', 'Surabayaa', '2008-06-07', 7, NULL, '0856954789511', 'Gracelina@gmail.comm', 'Jl. Raya Surabaya No. 911', 'Anggelina Jollyy', '0856954785622', '123456', 1, '2026-04-05 17:26:10', '2026-04-07 12:59:39');

-- --------------------------------------------------------

--
-- Struktur dari tabel `surat_keluars`
--

CREATE TABLE `surat_keluars` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nomor_surat` varchar(255) NOT NULL,
  `penempatan_magang_id` bigint(20) UNSIGNED NOT NULL,
  `template_surat_id` bigint(20) UNSIGNED DEFAULT NULL,
  `jenis_surat` enum('pengantar','permohonan','balasan','lainnya') NOT NULL DEFAULT 'pengantar',
  `file_path` varchar(255) DEFAULT NULL,
  `status` enum('draft','approved','rejected','sent') NOT NULL DEFAULT 'draft',
  `tanggal_kirim` date DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `surat_keluars`
--

INSERT INTO `surat_keluars` (`id`, `nomor_surat`, `penempatan_magang_id`, `template_surat_id`, `jenis_surat`, `file_path`, `status`, `tanggal_kirim`, `catatan`, `created_by`, `created_at`, `updated_at`) VALUES
(2, '421/SMK.3-TUBAN/5030/04/2026', 1, NULL, 'pengantar', 'surat/Surat_Pengantar_Rofiqul_Wahyu_Romadhani_20260406042322.pdf', 'approved', '2026-04-06', NULL, 2, '2026-04-05 21:23:22', '2026-04-05 21:23:22'),
(3, '421/SMK.3-TUBAN/9857/04/2026', 2, NULL, 'pengantar', 'surat/Surat_Pengantar_Ahmad_Maulana_20260406042359.pdf', 'approved', '2026-04-06', NULL, 2, '2026-04-05 21:24:00', '2026-04-05 21:24:00'),
(4, '421/SMK.3-TUBAN/7005/04/2026', 4, NULL, 'pengantar', 'surat/Surat_Pengantar_Budi_Pratama_20260407045200.pdf', 'approved', '2026-04-07', NULL, 2, '2026-04-06 21:52:02', '2026-04-06 21:52:02'),
(5, '421/SMK.3-TUBAN/6852/04/2026', 5, NULL, 'pengantar', 'surat/Surat_Pengantar_Fitri_Rahmawati_20260407200149.pdf', 'approved', '2026-04-07', NULL, 2, '2026-04-07 13:01:53', '2026-04-07 13:01:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `surat_masuks`
--

CREATE TABLE `surat_masuks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nomor_surat` varchar(255) NOT NULL,
  `pengirim` varchar(255) NOT NULL,
  `tanggal_terima` date NOT NULL,
  `perihal` varchar(255) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `status` enum('baru','diproses','selesai') NOT NULL DEFAULT 'baru',
  `catatan` text DEFAULT NULL,
  `penempatan_magang_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `template_surats`
--

CREATE TABLE `template_surats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `jurusan_id` bigint(20) UNSIGNED NOT NULL,
  `nama_template` varchar(255) NOT NULL,
  `jenis_surat` enum('pengantar','permohonan','balasan') NOT NULL DEFAULT 'pengantar',
  `file_path` varchar(255) DEFAULT NULL,
  `konten_template` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','pimpinan','guru_pembimbing','kepala_jurusan','guru_penguji') NOT NULL DEFAULT 'admin',
  `nama_lengkap` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `no_wa` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `nama_lengkap`, `email`, `no_wa`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$12$.olVNaRaFKLYVAI3N6XL8uqcS3r61.cciKqAi4erY694jmZVVimdu', 'admin', 'Administrator Sistem', 'admin@smk3tuban.sch.id', '081234567890', 1, NULL, '2026-04-01 06:47:28', '2026-04-01 06:47:28'),
(2, 'pimpinan', '$2y$12$bCaDw13dCjU86u9.ENtmB.KUgYCkAe7BoN8wmvzrF8s6iLegzyk92', 'pimpinan', 'Kepala SMK Negeri 3 Tuban', 'kepala@smk3tuban.sch.id', '081234567891', 1, NULL, '2026-04-01 06:47:28', '2026-04-01 06:47:28'),
(3, 'tu', '$2y$12$2znGara3TEdG.g9nsykJleVNU9s6lqxLweAbqCsh0KsyxtLB2gkXG', 'admin', 'Staff Tata Usaha', 'tu@smk3tuban.sch.id', '081234567892', 1, NULL, '2026-04-01 06:47:29', '2026-04-01 06:47:29');

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `absensis`
--
ALTER TABLE `absensis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `absensis_siswa_id_foreign` (`siswa_id`),
  ADD KEY `absensis_penempatan_magang_id_foreign` (`penempatan_magang_id`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `industris`
--
ALTER TABLE `industris`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jurnal_harians`
--
ALTER TABLE `jurnal_harians`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jurnal_harians_siswa_id_foreign` (`siswa_id`),
  ADD KEY `jurnal_harians_penempatan_magang_id_foreign` (`penempatan_magang_id`),
  ADD KEY `jurnal_harians_disetujui_oleh_foreign` (`disetujui_oleh`);

--
-- Indeks untuk tabel `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jurusans`
--
ALTER TABLE `jurusans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `jurusans_kode_jurusan_unique` (`kode_jurusan`);

--
-- Indeks untuk tabel `laporan_pkls`
--
ALTER TABLE `laporan_pkls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `laporan_pkls_siswa_id_foreign` (`siswa_id`),
  ADD KEY `laporan_pkls_penempatan_magang_id_foreign` (`penempatan_magang_id`),
  ADD KEY `laporan_pkls_disetujui_oleh_foreign` (`disetujui_oleh`);

--
-- Indeks untuk tabel `log_was`
--
ALTER TABLE `log_was`
  ADD PRIMARY KEY (`id`),
  ADD KEY `log_was_siswa_id_foreign` (`siswa_id`),
  ADD KEY `log_was_created_by_foreign` (`created_by`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `nilais`
--
ALTER TABLE `nilais`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nilais_penempatan_magang_id_foreign` (`penempatan_magang_id`),
  ADD KEY `nilais_input_by_foreign` (`input_by`);

--
-- Indeks untuk tabel `notifikasis`
--
ALTER TABLE `notifikasis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifikasis_siswa_id_foreign` (`siswa_id`);

--
-- Indeks untuk tabel `penempatan_magangs`
--
ALTER TABLE `penempatan_magangs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penempatan_magangs_siswa_id_foreign` (`siswa_id`),
  ADD KEY `penempatan_magangs_industri_id_foreign` (`industri_id`),
  ADD KEY `penempatan_magangs_approved_by_foreign` (`approved_by`);

--
-- Indeks untuk tabel `pengumumans`
--
ALTER TABLE `pengumumans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengumumans_dibuat_oleh_foreign` (`dibuat_oleh`);

--
-- Indeks untuk tabel `sertifikats`
--
ALTER TABLE `sertifikats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sertifikats_nomor_sertifikat_unique` (`nomor_sertifikat`),
  ADD KEY `sertifikats_penempatan_magang_id_foreign` (`penempatan_magang_id`),
  ADD KEY `sertifikats_nilai_id_foreign` (`nilai_id`),
  ADD KEY `sertifikats_generated_by_foreign` (`generated_by`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `siswas`
--
ALTER TABLE `siswas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `siswas_nisn_unique` (`nisn`),
  ADD KEY `siswas_jurusan_id_foreign` (`jurusan_id`),
  ADD KEY `siswas_kelas_id_foreign` (`kelas_id`);

--
-- Indeks untuk tabel `surat_keluars`
--
ALTER TABLE `surat_keluars`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `surat_keluars_nomor_surat_unique` (`nomor_surat`),
  ADD KEY `surat_keluars_penempatan_magang_id_foreign` (`penempatan_magang_id`),
  ADD KEY `surat_keluars_template_surat_id_foreign` (`template_surat_id`),
  ADD KEY `surat_keluars_created_by_foreign` (`created_by`);

--
-- Indeks untuk tabel `surat_masuks`
--
ALTER TABLE `surat_masuks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `surat_masuks_penempatan_magang_id_foreign` (`penempatan_magang_id`),
  ADD KEY `surat_masuks_created_by_foreign` (`created_by`);

--
-- Indeks untuk tabel `template_surats`
--
ALTER TABLE `template_surats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `template_surats_jurusan_id_foreign` (`jurusan_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `absensis`
--
ALTER TABLE `absensis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `industris`
--
ALTER TABLE `industris`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jurnal_harians`
--
ALTER TABLE `jurnal_harians`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jurusans`
--
ALTER TABLE `jurusans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `laporan_pkls`
--
ALTER TABLE `laporan_pkls`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `log_was`
--
ALTER TABLE `log_was`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `nilais`
--
ALTER TABLE `nilais`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `notifikasis`
--
ALTER TABLE `notifikasis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `penempatan_magangs`
--
ALTER TABLE `penempatan_magangs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `pengumumans`
--
ALTER TABLE `pengumumans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `sertifikats`
--
ALTER TABLE `sertifikats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `siswas`
--
ALTER TABLE `siswas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `surat_keluars`
--
ALTER TABLE `surat_keluars`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `surat_masuks`
--
ALTER TABLE `surat_masuks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `template_surats`
--
ALTER TABLE `template_surats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `absensis`
--
ALTER TABLE `absensis`
  ADD CONSTRAINT `absensis_penempatan_magang_id_foreign` FOREIGN KEY (`penempatan_magang_id`) REFERENCES `penempatan_magangs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `absensis_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswas` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `jurnal_harians`
--
ALTER TABLE `jurnal_harians`
  ADD CONSTRAINT `jurnal_harians_disetujui_oleh_foreign` FOREIGN KEY (`disetujui_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `jurnal_harians_penempatan_magang_id_foreign` FOREIGN KEY (`penempatan_magang_id`) REFERENCES `penempatan_magangs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jurnal_harians_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswas` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `laporan_pkls`
--
ALTER TABLE `laporan_pkls`
  ADD CONSTRAINT `laporan_pkls_disetujui_oleh_foreign` FOREIGN KEY (`disetujui_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `laporan_pkls_penempatan_magang_id_foreign` FOREIGN KEY (`penempatan_magang_id`) REFERENCES `penempatan_magangs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `laporan_pkls_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswas` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `log_was`
--
ALTER TABLE `log_was`
  ADD CONSTRAINT `log_was_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `log_was_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswas` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `nilais`
--
ALTER TABLE `nilais`
  ADD CONSTRAINT `nilais_input_by_foreign` FOREIGN KEY (`input_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nilais_penempatan_magang_id_foreign` FOREIGN KEY (`penempatan_magang_id`) REFERENCES `penempatan_magangs` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `notifikasis`
--
ALTER TABLE `notifikasis`
  ADD CONSTRAINT `notifikasis_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswas` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `penempatan_magangs`
--
ALTER TABLE `penempatan_magangs`
  ADD CONSTRAINT `penempatan_magangs_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `penempatan_magangs_industri_id_foreign` FOREIGN KEY (`industri_id`) REFERENCES `industris` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `penempatan_magangs_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswas` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengumumans`
--
ALTER TABLE `pengumumans`
  ADD CONSTRAINT `pengumumans_dibuat_oleh_foreign` FOREIGN KEY (`dibuat_oleh`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `sertifikats`
--
ALTER TABLE `sertifikats`
  ADD CONSTRAINT `sertifikats_generated_by_foreign` FOREIGN KEY (`generated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sertifikats_nilai_id_foreign` FOREIGN KEY (`nilai_id`) REFERENCES `nilais` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sertifikats_penempatan_magang_id_foreign` FOREIGN KEY (`penempatan_magang_id`) REFERENCES `penempatan_magangs` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `siswas`
--
ALTER TABLE `siswas`
  ADD CONSTRAINT `siswas_jurusan_id_foreign` FOREIGN KEY (`jurusan_id`) REFERENCES `jurusans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `siswas_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `surat_keluars`
--
ALTER TABLE `surat_keluars`
  ADD CONSTRAINT `surat_keluars_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `surat_keluars_penempatan_magang_id_foreign` FOREIGN KEY (`penempatan_magang_id`) REFERENCES `penempatan_magangs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `surat_keluars_template_surat_id_foreign` FOREIGN KEY (`template_surat_id`) REFERENCES `template_surats` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `surat_masuks`
--
ALTER TABLE `surat_masuks`
  ADD CONSTRAINT `surat_masuks_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `surat_masuks_penempatan_magang_id_foreign` FOREIGN KEY (`penempatan_magang_id`) REFERENCES `penempatan_magangs` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `template_surats`
--
ALTER TABLE `template_surats`
  ADD CONSTRAINT `template_surats_jurusan_id_foreign` FOREIGN KEY (`jurusan_id`) REFERENCES `jurusans` (`id`) ON DELETE CASCADE;

-- --------------------------------------------------------

--
-- Struktur dari tabel `gurus`
--

CREATE TABLE IF NOT EXISTS `gurus` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nip` varchar(50) DEFAULT NULL,
  `nama` varchar(255) NOT NULL,
  `jurusan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `kelas_id` bigint(20) UNSIGNED DEFAULT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `jabatan` enum('guru_pembimbing','kepala_jurusan','guru_penguji') NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gurus_user_id_foreign` (`user_id`),
  KEY `gurus_jurusan_id_foreign` (`jurusan_id`),
  KEY `gurus_kelas_id_foreign` (`kelas_id`),
  CONSTRAINT `gurus_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `gurus_jurusan_id_foreign` FOREIGN KEY (`jurusan_id`) REFERENCES `jurusans` (`id`) ON DELETE SET NULL,
  CONSTRAINT `gurus_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan_masalah_magangs`
--

CREATE TABLE IF NOT EXISTS `laporan_masalah_magangs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `siswa_id` bigint(20) UNSIGNED NOT NULL,
  `industri_id` bigint(20) UNSIGNED NOT NULL,
  `pelapor_id` bigint(20) UNSIGNED NOT NULL,
  `permasalahan` text NOT NULL,
  `solusi` text DEFAULT NULL,
  `tanggal_lapor` date NOT NULL,
  `status` enum('pending','ditinjau','selesai') NOT NULL DEFAULT 'pending',
  `catatan_kajur` text DEFAULT NULL,
  `ditinjau_oleh` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `laporan_masalah_siswa_id_foreign` (`siswa_id`),
  KEY `laporan_masalah_industri_id_foreign` (`industri_id`),
  KEY `laporan_masalah_pelapor_id_foreign` (`pelapor_id`),
  CONSTRAINT `laporan_masalah_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `laporan_masalah_industri_id_foreign` FOREIGN KEY (`industri_id`) REFERENCES `industris` (`id`) ON DELETE CASCADE,
  CONSTRAINT `laporan_masalah_pelapor_id_foreign` FOREIGN KEY (`pelapor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Modifikasi `penempatan_magangs` untuk menambahkan `guru_pembimbing_id`
ALTER TABLE `penempatan_magangs` ADD COLUMN IF NOT EXISTS `guru_pembimbing_id` bigint(20) UNSIGNED DEFAULT NULL AFTER `approved_by`;
ALTER TABLE `penempatan_magangs` ADD CONSTRAINT `penempatan_magangs_guru_pembimbing_id_foreign` FOREIGN KEY (`guru_pembimbing_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

-- Insert data default user & guru baru
INSERT INTO `users` (`username`, `password`, `role`, `nama_lengkap`, `email`, `no_wa`, `is_active`, `created_at`, `updated_at`) VALUES
('kajur_rpl', '$2y$12$.olVNaRaFKLYVAI3N6XL8uqcS3r61.cciKqAi4erY694jmZVVimdu', 'kepala_jurusan', 'Siti Nurhaliza, S.Kom, M.Kom', 'kajur.rpl@smk3tuban.sch.id', '081234500001', 1, NOW(), NOW()),
('kajur_tkr', '$2y$12$.olVNaRaFKLYVAI3N6XL8uqcS3r61.cciKqAi4erY694jmZVVimdu', 'kepala_jurusan', 'Ahmad Fauzi, S.Pd', 'kajur.tkr@smk3tuban.sch.id', '081234500002', 1, NOW(), NOW()),
('guru_bimbing1', '$2y$12$.olVNaRaFKLYVAI3N6XL8uqcS3r61.cciKqAi4erY694jmZVVimdu', 'guru_pembimbing', 'Drs. Haryanto, M.Pd', 'haryanto@smk3tuban.sch.id', '081234500003', 1, NOW(), NOW()),
('guru_bimbing2', '$2y$12$.olVNaRaFKLYVAI3N6XL8uqcS3r61.cciKqAi4erY694jmZVVimdu', 'guru_pembimbing', 'Ir. Sulistyowati, M.T', 'sulistyowati@smk3tuban.sch.id', '081234500004', 1, NOW(), NOW()),
('guru_uji1', '$2y$12$.olVNaRaFKLYVAI3N6XL8uqcS3r61.cciKqAi4erY694jmZVVimdu', 'guru_penguji', 'Dr. Bambang Sutrisno, M.Pd', 'bambang@smk3tuban.sch.id', '081234500005', 1, NOW(), NOW());

INSERT INTO `gurus` (`user_id`, `nip`, `nama`, `jurusan_id`, `kelas_id`, `no_telp`, `jabatan`, `is_active`, `created_at`, `updated_at`) VALUES
((SELECT id FROM users WHERE username = 'kajur_rpl'), '198501152010011001', 'Siti Nurhaliza, S.Kom, M.Kom', 4, NULL, '081234500001', 'kepala_jurusan', 1, NOW(), NOW()),
((SELECT id FROM users WHERE username = 'kajur_tkr'), '198703202011011002', 'Ahmad Fauzi, S.Pd', 3, NULL, '081234500002', 'kepala_jurusan', 1, NOW(), NOW()),
((SELECT id FROM users WHERE username = 'guru_bimbing1'), '197812102005011003', 'Drs. Haryanto, M.Pd', 4, NULL, '081234500003', 'guru_pembimbing', 1, NOW(), NOW()),
((SELECT id FROM users WHERE username = 'guru_bimbing2'), '198206152008012004', 'Ir. Sulistyowati, M.T', 3, NULL, '081234500004', 'guru_pembimbing', 1, NOW(), NOW()),
((SELECT id FROM users WHERE username = 'guru_uji1'), '197505202003011005', 'Dr. Bambang Sutrisno, M.Pd', NULL, NULL, '081234500005', 'guru_penguji', 1, NOW(), NOW());
--
-- Table structure for table `periode_magangs`
--

CREATE TABLE IF NOT EXISTS `periode_magangs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) DEFAULT NULL,
  `tahun_ajaran` varchar(255) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Altering `penempatan_magangs` to add `periode_magang_id`
--

ALTER TABLE `penempatan_magangs` ADD COLUMN IF NOT EXISTS `periode_magang_id` bigint(20) UNSIGNED DEFAULT NULL AFTER `industri_id`;
ALTER TABLE `penempatan_magangs` ADD CONSTRAINT `penempatan_magangs_periode_magang_id_foreign` FOREIGN KEY (`periode_magang_id`) REFERENCES `periode_magangs` (`id`) ON DELETE SET NULL;

ALTER TABLE `penempatan_magangs` ADD COLUMN IF NOT EXISTS `guru_penguji_id` bigint(20) UNSIGNED DEFAULT NULL AFTER `guru_pembimbing_id`;
ALTER TABLE `penempatan_magangs` ADD CONSTRAINT `penempatan_magangs_guru_penguji_id_foreign` FOREIGN KEY (`guru_penguji_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

-- --------------------------------------------------------

--
-- Struktur dari tabel `border_templates`
--

CREATE TABLE IF NOT EXISTS `border_templates` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
