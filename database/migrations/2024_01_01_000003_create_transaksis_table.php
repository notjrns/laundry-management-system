<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();           // nomor nota, contoh: TRX-20260621-0001
            $table->string('nama_pelanggan');
            $table->string('no_hp')->nullable();
            $table->text('alamat')->nullable();
            $table->foreignId('layanan_id')->constrained('layanans')->cascadeOnUpdate()->restrictOnDelete();
            $table->decimal('berat', 8, 2)->default(0);  // dalam kg
            $table->unsignedInteger('harga_satuan')->default(0); // harga/kg saat transaksi
            $table->unsignedInteger('total_harga')->default(0);  // berat * harga_satuan
            $table->date('tanggal_masuk');
            $table->dateTime('estimasi_selesai')->nullable();
            $table->enum('status', ['diproses', 'selesai', 'diambil'])->default('diproses');
            $table->enum('status_bayar', ['belum', 'lunas'])->default('belum');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
