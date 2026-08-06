<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_panggilan', function (Blueprint $table) {
            $table->index('siswa_id', 'surat_panggilan_siswa_index');
            $table->dropUnique('surat_siswa_threshold_unique');
            $table->integer('poin_pemicu')->nullable()->after('aturan_threshold_id');
            $table->unique(
                ['siswa_id', 'aturan_threshold_id', 'poin_pemicu'],
                'surat_siswa_threshold_pemicu_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('surat_panggilan', function (Blueprint $table) {
            $table->dropUnique('surat_siswa_threshold_pemicu_unique');
            $table->dropColumn('poin_pemicu');
            $table->unique(['siswa_id', 'aturan_threshold_id'], 'surat_siswa_threshold_unique');
            $table->dropIndex('surat_panggilan_siswa_index');
        });
    }
};
