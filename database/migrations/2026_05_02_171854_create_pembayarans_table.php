<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kunjungan_id')->constrained('kunjungans')->onDelete('cascade');
            $table->decimal('total_biaya', 15, 2);
            $table->enum('metode_pembayaran', ['tunai', 'transfer', 'qris']);
            $table->enum('status_pembayaran', ['menunggu_pembayaran', 'lunas'])->default('menunggu_pembayaran');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pembayarans');
    }
};