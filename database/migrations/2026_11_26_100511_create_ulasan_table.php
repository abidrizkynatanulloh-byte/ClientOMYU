<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ulasan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk')->cascadeOnDelete();
            $table->foreignId('pesanan_id')->constrained('pesanan')->cascadeOnDelete();
            $table->foreignId('pelanggan_id')->nullable()->constrained('pelanggan')->cascadeOnDelete();
            $table->string('device_token', 80)->nullable();
            $table->unsignedTinyInteger('bintang');
            $table->text('komentar')->nullable();
            $table->timestamps();

            // Satu pesanan hanya boleh memberi satu penilaian per menu.
            $table->unique(['pesanan_id', 'produk_id'], 'ulasan_pesanan_produk_unik');
            $table->index(['produk_id', 'bintang'], 'ulasan_produk_bintang_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ulasan');
    }
};
