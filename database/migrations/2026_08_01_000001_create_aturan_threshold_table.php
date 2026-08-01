<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aturan_threshold', function (Blueprint $table) {
            $table->id();
            $table->integer('poin_batas');
            $table->enum('level', ['ringan', 'sedang', 'berat']);
            $table->string('judul_notifikasi');
            $table->text('deskripsi');
            $table->string('template_surat')->nullable();
            $table->boolean('has_surat_panggilan')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aturan_threshold');
    }
};
