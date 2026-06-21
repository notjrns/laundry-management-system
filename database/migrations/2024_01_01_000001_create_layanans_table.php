<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('layanans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('satuan', ['kg', 'pcs'])->default('kg');
            $table->unsignedInteger('estimasi_nilai')->default(1);
            $table->enum('estimasi_satuan', ['jam', 'hari'])->default('hari');
            $table->unsignedInteger('harga');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('layanans');
    }
};
