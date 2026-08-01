<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('aturan_threshold_id')->constrained('aturan_threshold')->cascadeOnDelete();
            $table->enum('level', ['ringan', 'sedang', 'berat']);
            $table->string('judul');
            $table->text('pesan');
            $table->morphs('notifikasiable');
            $table->timestamp('dibaca_pada')->nullable();
            $table->boolean('is_resolved')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
