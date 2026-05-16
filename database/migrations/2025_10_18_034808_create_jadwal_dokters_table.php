<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jadwal_dokters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dokter_id');
            $table->string('hari');           // Senin, Selasa, dll.
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->timestamps();
        });
        Schema::table('dokters', function (Blueprint $table) {
            // Sekarang kita hubungkan kolom 'jadwal_id' di tabel dokter ke tabel 'jadwal_dokters'
            $table->foreign('jadwal_id')
                ->references('id')
                ->on('jadwal_dokters')
                ->onDelete('cascade'); // Opsional
        });
    }

    public function down()
    {
        Schema::dropIfExists('jadwal_dokters');
    }
};
