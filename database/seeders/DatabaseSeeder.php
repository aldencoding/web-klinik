<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Dokter;
use App\Models\Pasien;
// use App\Models\Layanan;
use Illuminate\Database\Seeder;
// use Database\Seeders\HariSeeder;
// use Database\Seeders\JadwalDokterSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call([HariSeeder::class, JadwalDokterSeeder::class]);

        // Layanan::create([
        //     'nama' => 'Poli Umum',
        // ]);

        // Layanan::create([
        //     'nama' => 'Gigi',
        // ]);


        User::factory(3)
            ->has(Dokter::factory())
            ->create(['role' => 'dokter']);

        User::factory(5)
            ->has(Pasien::factory())
            ->create(['role' => 'pasien']);
    }
}
