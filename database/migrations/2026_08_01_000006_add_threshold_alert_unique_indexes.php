<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifikasi', function (Blueprint $table) {
            $table->unique(['siswa_id', 'aturan_threshold_id'], 'notifikasi_siswa_threshold_unique');
        });

        Schema::table('surat_panggilan', function (Blueprint $table) {
            $table->unique(['siswa_id', 'aturan_threshold_id'], 'surat_siswa_threshold_unique');
        });
    }

    public function down(): void
    {
        Schema::table('notifikasi', function (Blueprint $table) {
            $table->dropUnique('notifikasi_siswa_threshold_unique');
        });

        Schema::table('surat_panggilan', function (Blueprint $table) {
            $table->dropUnique('surat_siswa_threshold_unique');
        });
    }
};
