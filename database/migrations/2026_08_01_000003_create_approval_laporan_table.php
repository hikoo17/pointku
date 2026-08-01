<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approval_laporan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_kesiswaan_id')->constrained('laporan_kesiswaan')->cascadeOnDelete();
            $table->foreignId('approver_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['pending', 'disetujui', 'ditolak']);
            $table->text('catatan_approval')->nullable();
            $table->timestamp('disetujui_pada')->nullable();
            $table->timestamps();
        });

        Schema::table('laporan_kesiswaan', function (Blueprint $table) {
            $table->timestamp('diajukan_pada')->nullable()->after('status');
            $table->timestamp('selesai_pada')->nullable()->after('diajukan_pada');
        });
    }

    public function down(): void
    {
        Schema::table('laporan_kesiswaan', function (Blueprint $table) {
            $table->dropColumn(['diajukan_pada', 'selesai_pada']);
        });

        Schema::dropIfExists('approval_laporan');
    }
};
