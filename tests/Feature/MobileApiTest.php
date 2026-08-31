<?php

namespace Tests\Feature;

use App\Models\Industri;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\PenempatanMagang;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MobileApiTest extends TestCase
{
    use RefreshDatabase;

    protected Siswa $siswa;
    protected Industri $industri;
    protected PenempatanMagang $penempatan;
    protected User $pembimbing;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        $jurusan = Jurusan::create(['nama_jurusan' => 'Rekayasa Perangkat Lunak', 'kode_jurusan' => 'RPL']);
        $kelas = Kelas::create(['nama_kelas' => 'XII RPL 1', 'jurusan_id' => $jurusan->id]);

        $this->pembimbing = User::create([
            'username' => 'pembimbing1',
            'password' => Hash::make('password123'),
            'nama_lengkap' => 'Guru Pembimbing M.Pd',
            'role' => 'guru_pembimbing',
            'is_active' => true,
        ]);

        $this->industri = Industri::create([
            'nama_industri' => 'PT. Teknologi Nusantara Abadi',
            'nib' => '1234567890124',
            'alamat' => 'Jl. Gatot Subroto No. 12',
            'no_telp' => '081234567890',
            'kota' => 'Tuban',
            'latitude' => -6.89452000,
            'longitude' => 112.05834000,
            'radius_toleransi_meter' => 300,
            'is_active' => true,
        ]);

        $this->siswa = Siswa::create([
            'nisn' => '0051234567',
            'nama' => 'Rofiqul Wahyu',
            'tempat_lahir' => 'Tuban',
            'tanggal_lahir' => '2008-05-12',
            'no_wa' => '081234567899',
            'jurusan_id' => $jurusan->id,
            'kelas_id' => $kelas->id,
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        $this->penempatan = PenempatanMagang::create([
            'siswa_id' => $this->siswa->id,
            'industri_id' => $this->industri->id,
            'guru_pembimbing_id' => $this->pembimbing->id,
            'tahun_ajaran' => '2026/2027',
            'semester' => 'Ganjil',
            'status' => 'ongoing',
            'tanggal_mulai' => now()->subMonth(),
            'tanggal_selesai' => now()->addMonths(2),
        ]);
    }

    public function test_siswa_can_login_and_bind_device(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'nisn' => '0051234567',
            'password' => 'password123',
            'device_id' => 'device-uuid-12345',
            'device_model' => 'Samsung Galaxy A54',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => ['token', 'user', 'penempatan'],
            ]);

        $this->assertDatabaseHas('siswas', [
            'id' => $this->siswa->id,
            'device_id' => 'device-uuid-12345',
        ]);
    }

    public function test_siswa_cannot_login_with_different_device(): void
    {
        // Bind to device 1
        $this->siswa->update(['device_id' => 'device-uuid-12345']);

        // Try login with device 2
        $response = $this->postJson('/api/v1/auth/login', [
            'nisn' => '0051234567',
            'password' => 'password123',
            'device_id' => 'device-uuid-DIFFERENT',
        ]);

        $response->assertStatus(403)
            ->assertJsonPath('error_code', 'DEVICE_MISMATCH');
    }

    public function test_check_location_inside_radius(): void
    {
        $token = $this->siswa->createToken('test')->plainTextToken;

        // Position 33 meters from office (-6.894310, 112.058120)
        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/absensi/check-location', [
                'latitude' => -6.894310,
                'longitude' => 112.058120,
                'accuracy' => 10.0,
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.is_within_radius', true)
            ->assertJsonPath('data.can_check_in', true);
    }

    public function test_check_in_blocked_when_outside_radius(): void
    {
        $token = $this->siswa->createToken('test')->plainTextToken;
        $file = UploadedFile::fake()->image('selfie.jpg');

        // Point 5km away
        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/absensi/check-in', [
                'latitude' => -6.950000,
                'longitude' => 112.100000,
                'foto_selfie' => $file,
            ]);

        $response->assertStatus(422)
            ->assertJsonPath('error_code', 'OUT_OF_GEOFENCE_RADIUS');
    }

    public function test_check_in_blocked_when_mock_location_detected(): void
    {
        $token = $this->siswa->createToken('test')->plainTextToken;
        $file = UploadedFile::fake()->image('selfie.jpg');

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/absensi/check-in', [
                'latitude' => -6.894520,
                'longitude' => 112.058340,
                'foto_selfie' => $file,
                'is_mock_location' => true,
            ]);

        $response->assertStatus(403)
            ->assertJsonPath('error_code', 'MOCK_LOCATION_DETECTED');
    }

    public function test_check_in_and_check_out_success(): void
    {
        $token = $this->siswa->createToken('test')->plainTextToken;
        $selfieIn = UploadedFile::fake()->image('selfie_in.jpg');

        // 1. Check-In
        $responseIn = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/absensi/check-in', [
                'latitude' => -6.894520,
                'longitude' => 112.058340,
                'accuracy' => 8.0,
                'foto_selfie' => $selfieIn,
                'liveness_score' => 0.95,
            ]);

        $responseIn->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'hadir');

        // 2. Check-Out
        $selfieOut = UploadedFile::fake()->image('selfie_out.jpg');
        $responseOut = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/absensi/check-out', [
                'latitude' => -6.894520,
                'longitude' => 112.058340,
                'foto_selfie' => $selfieOut,
            ]);

        $responseOut->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('absensis', [
            'siswa_id' => $this->siswa->id,
            'status' => 'hadir',
        ]);
    }

    public function test_jurnal_creation_with_multi_photos(): void
    {
        $token = $this->siswa->createToken('test')->plainTextToken;
        $photo1 = UploadedFile::fake()->image('doc1.jpg');
        $photo2 = UploadedFile::fake()->image('doc2.jpg');

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/jurnal', [
                'tanggal' => now()->toDateString(),
                'minggu_ke' => 4,
                'kegiatan' => 'Melakukan perakitan unit komputer dan instalasi OS Windows 11.',
                'durasi_jam' => 8,
                'foto_dokumentasi' => [$photo1, $photo2],
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'pending');

        $this->assertDatabaseHas('jurnal_fotos', [
            'jurnal_harian_id' => $response->json('data.id'),
        ]);
    }

    public function test_emergency_koreksi_submission_and_approval(): void
    {
        $tokenSiswa = $this->siswa->createToken('siswa')->plainTextToken;
        $tokenPembimbing = $this->pembimbing->createToken('guru')->plainTextToken;

        // 1. Siswa submit tiket koreksi
        $responseKoreksi = $this->withHeader('Authorization', "Bearer {$tokenSiswa}")
            ->postJson('/api/v1/absensi/koreksi', [
                'tanggal' => now()->toDateString(),
                'jenis_koreksi' => 'masuk',
                'jam_diajukan' => '07:45',
                'alasan' => 'Baterai HP habis saat tiba di kantor industri pagi ini.',
            ]);

        $responseKoreksi->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'pending');

        $koreksiId = $responseKoreksi->json('data.id');

        // 2. Guru Pembimbing approve tiket koreksi
        $responseApprove = $this->withHeader('Authorization', "Bearer {$tokenPembimbing}")
            ->postJson("/api/v1/pembimbing/koreksi/{$koreksiId}/action", [
                'action' => 'approve',
                'catatan' => 'Dikonfirmasi langsung ke pembimbing industri.',
            ]);

        $responseApprove->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('koreksi_absensis', [
            'id' => $koreksiId,
            'status' => 'disetujui',
        ]);

        $this->assertDatabaseHas('absensis', [
            'siswa_id' => $this->siswa->id,
            'status' => 'hadir',
            'jam_masuk' => '07:45:00',
        ]);
    }
}
