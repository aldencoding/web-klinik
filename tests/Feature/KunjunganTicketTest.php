<?php

namespace Tests\Feature;

use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Poli;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class KunjunganTicketTest extends TestCase
{
    use DatabaseTransactions;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_created_visit_flashes_printable_queue_ticket(): void
    {
        Carbon::setTestNow('2026-06-21 09:30:00');

        $admin = $this->createUser('admin', 'Admin Klinik');
        $pasienUser = $this->createUser('pasien', 'Budi Santoso');
        $dokterUser = $this->createUser('dokter', 'Dokter Umum');

        $poli = Poli::create(['nama' => 'Umum']);

        Dokter::create([
            'user_id' => $dokterUser->id,
            'poli_id' => $poli->id,
            'no_telepon' => '081234567890',
            'alamat' => 'Alamat dokter',
        ]);

        $pasien = Pasien::create([
            'user_id' => $pasienUser->id,
            'nik' => '3273010101900001',
            'no_bpjs' => '0001234567890',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'pria',
            'alamat' => 'Alamat pasien',
            'no_telepon' => '081234567891',
        ]);

        $response = $this->actingAs($admin)->post(route('kunjungan.store'), [
            'pasien_id' => $pasien->id,
            'poli' => 'Umum',
            'jaminan' => 'Pribadi',
            'keluhan' => 'Demam',
        ]);

        $response->assertRedirect(route('kunjungan.create'));
        $response->assertSessionHas('queue_ticket', function (array $ticket) {
            return $ticket['number'] === 'U001'
                && $ticket['patient_name'] === 'Budi Santoso'
                && $ticket['clinic'] === 'Umum'
                && $ticket['barcode'] === 'U001';
        });

        $page = $this->actingAs($admin)
            ->withSession(['queue_ticket' => session('queue_ticket')])
            ->get(route('kunjungan.create'));

        $page->assertOk();
        $page->assertSee('Cetak Antrian');
        $page->assertSee('Budi Santoso');
        $page->assertSee('queueTicketPrintable', false);
        $page->assertSee('<svg', false);
    }

    private function createUser(string $role, string $name): User
    {
        return User::create([
            'name' => $name,
            'email' => Str::uuid().'@example.com',
            'password' => bcrypt('password'),
            'role' => $role,
        ]);
    }
}
