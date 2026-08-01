<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_panggilan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('laporan_kesiswaan_id')->nullable()->constrained('laporan_kesiswaan')->nullOnDelete();
            $table->foreignId('aturan_threshold_id')->constrained('aturan_threshold')->cascadeOnDelete();
            $table->string('nomor_surat')->unique();
            $table->date('tanggal_surat');
            $table->text('alasan_pemanggilan');
            $table->text('daftar_kejadian')->nullable();
            $table->integer('total_poin');
            $table->string('tindakan_direkomendasikan');
            $table->enum('status', ['draft', 'disetujui', 'dicetak', 'dikirim', 'selesai'])->default('draft');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_panggilan');
    }
};
