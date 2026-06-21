<?php

namespace Tests\Feature;

use App\Models\Dokter;
use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\Poli;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class KunjunganFilterTest extends TestCase
{
    use DatabaseTransactions;

    private User $admin;

    private Dokter $dokter;

    private Pasien $pasien;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow('2026-06-17 10:00:00');

        $this->admin = User::create([
            'name' => 'Admin Klinik',
            'email' => Str::uuid().'@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $dokterUser = User::create([
            'name' => 'Dokter Klinik',
            'email' => Str::uuid().'@example.com',
            'password' => bcrypt('password'),
            'role' => 'dokter',
        ]);

        $pasienUser = User::create([
            'name' => 'Pasien Klinik',
            'email' => Str::uuid().'@example.com',
            'password' => bcrypt('password'),
            'role' => 'pasien',
        ]);

        $poli = Poli::create([
            'nama' => 'Umum',
        ]);

        $this->dokter = Dokter::create([
            'user_id' => $dokterUser->id,
            'poli_id' => $poli->id,
            'no_telepon' => '081234567890',
            'alamat' => 'Alamat dokter',
        ]);

        $this->pasien = Pasien::create([
            'user_id' => $pasienUser->id,
            'nik' => (string) random_int(1000000000000000, 9999999999999999),
            'no_bpjs' => (string) random_int(1000000000000, 9999999999999),
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'pria',
            'alamat' => 'Alamat pasien',
            'no_telepon' => '081234567891',
        ]);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_default_filter_only_displays_todays_visits(): void
    {
        $this->createKunjungan('U001', '2026-06-17 08:00:00');
        $this->createKunjungan('U002', '2026-06-16 08:00:00');

        $response = $this->actingAs($this->admin)
            ->get(route('kunjungan.index'));

        $response->assertOk();
        $response->assertSee('U001');
        $response->assertDontSee('U002');
    }

    public function test_weekly_filter_can_be_combined_with_poli_jaminan_and_status(): void
    {
        $this->createKunjungan('G001', '2026-06-16 08:00:00', 'Gigi', 'Asuransi', 'menunggu');
        $this->createKunjungan('G002', '2026-06-16 09:00:00', 'Gigi', 'Pribadi', 'menunggu');
        $this->createKunjungan('U001', '2026-06-16 10:00:00', 'Umum', 'Asuransi', 'menunggu');
        $this->createKunjungan('G003', '2026-06-08 08:00:00', 'Gigi', 'Asuransi', 'menunggu');

        $response = $this->actingAs($this->admin)
            ->get(route('kunjungan.index', [
                'periode' => 'mingguan',
                'poli' => 'Gigi',
                'jaminan' => 'Asuransi',
                'status' => 'menunggu',
            ]), [
                'X-Requested-With' => 'XMLHttpRequest',
            ]);

        $response->assertOk();
        $response->assertViewIs('kunjungan._table');
        $response->assertSee('G001');
        $response->assertDontSee('G002');
        $response->assertDontSee('U001');
        $response->assertDontSee('G003');
    }

    public function test_monthly_filter_displays_visits_from_the_current_month(): void
    {
        $this->createKunjungan('U-JUNE', '2026-06-01 08:00:00');
        $this->createKunjungan('U-MAY', '2026-05-31 08:00:00');

        $response = $this->actingAs($this->admin)
            ->get(route('kunjungan.index', [
                'periode' => 'bulanan',
            ]));

        $response->assertOk();
        $response->assertSee('U-JUNE');
        $response->assertDontSee('U-MAY');
    }

    private function createKunjungan(
        string $nomor,
        string $createdAt,
        string $poli = 'Umum',
        string $jaminan = 'Pribadi',
        string $status = 'menunggu'
    ): Kunjungan {
        $kunjungan = Kunjungan::create([
            'no_antrian' => $nomor,
            'dokter_id' => $this->dokter->id,
            'pasien_id' => $this->pasien->id,
            'poli' => $poli,
            'jaminan' => $jaminan,
            'keluhan' => 'Keluhan pasien',
            'status_antrian' => $status,
        ]);

        $kunjungan->timestamps = false;
        $kunjungan->created_at = Carbon::parse($createdAt);
        $kunjungan->updated_at = Carbon::parse($createdAt);
        $kunjungan->save();

        return $kunjungan;
    }
}
