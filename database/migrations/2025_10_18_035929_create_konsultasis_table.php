<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // public function up(): void
    // {
    //     Schema::create('konsultasis', function (Blueprint $table) {
    //         $table->id();
    //         $table->foreignId('jadwal_id')
    //             ->constrained('jadwal_dokters')
    //             ->cascadeOnDelete();

    //         $table->foreignId('pasien_id')
    //             ->constrained('pasiens')
    //             ->cascadeOnDelete();

    //         $table->foreignId('dokter_id')
    //             ->constrained('dokters')
    //             ->cascadeOnDelete();

    //         $table->text('keluhan');
    //         $table->text('diagnosa');
    //         $table->text('resep');
    //         $table->enum('status_konsultasi', ['menunggu', 'selesai', 'dibatalkan'])->default('menunggu');
    //         $table->timestamps();
    //     });
    // }

    /**
 * Reverse the migrations.
 */
    // public function down(): void
    // {
    //     Schema::dropIfExists('konsultasis');
    // }
};
