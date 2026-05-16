<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pasien>
 */
class PasienFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nik' => fake('id_ID')->nik(),
            'tanggal_lahir' => fake()->date(),
            'jenis_kelamin' => collect(['pria', 'wanita'])->random(),
            'alamat' => fake()->address(),
            'no_telepon' => fake()->phoneNumber(),
        ];
    }
}
