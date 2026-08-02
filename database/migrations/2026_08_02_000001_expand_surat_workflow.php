<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_panggilan', function (Blueprint $table) {
            $table->string('status')->default('draft')->change();
            $table->string('nomor_surat')->nullable()->change();
            $table->foreignId('dibuat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('diajukan_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('diajukan_pada')->nullable();
            $table->timestamp('disetujui_pada')->nullable();
            $table->timestamp('dicetak_pada')->nullable();
            $table->timestamp('dikirim_pada')->nullable();
            $table->timestamp('selesai_pada')->nullable();
            $table->text('catatan_revisi')->nullable();
        });

        Schema::create('surat_panggilan_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_panggilan_id')->constrained('surat_panggilan')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('status_sebelumnya')->nullable();
            $table->string('status_baru');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_panggilan_histories');

        Schema::table('surat_panggilan', function (Blueprint $table) {
            $table->dropForeign(['dibuat_oleh']);
            $table->dropForeign(['diajukan_oleh']);
            $table->dropForeign(['disetujui_oleh']);
            $table->dropColumn([
                'dibuat_oleh', 'diajukan_oleh', 'disetujui_oleh', 'diajukan_pada',
                'disetujui_pada', 'dicetak_pada', 'dikirim_pada', 'selesai_pada', 'catatan_revisi',
            ]);
        });
    }
};
