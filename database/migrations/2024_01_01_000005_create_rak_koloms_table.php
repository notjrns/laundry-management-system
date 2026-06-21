<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rak_koloms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rak_id')->constrained('raks')->cascadeOnDelete();
            $table->unsignedInteger('nomor_kolom');
            $table->foreignId('transaksi_id')->nullable()->constrained('transaksis')->nullOnDelete();
            $table->string('nama_pelanggan')->nullable();
            $table->string('jenis_layanan')->nullable();
            $table->dateTime('estimasi_pengambilan')->nullable();
            $table->enum('status', ['diproses', 'selesai', 'diambil'])->nullable();
            $table->boolean('terisi')->default(false);
            $table->timestamps();

            $table->unique(['rak_id', 'nomor_kolom']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rak_koloms');
    }
};
