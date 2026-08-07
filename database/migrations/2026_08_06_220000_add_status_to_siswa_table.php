<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->string('status', 20)->default('aktif')->after('jenis_kelamin')->index();
            $table->timestamp('dinonaktifkan_pada')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropColumn(['status', 'dinonaktifkan_pada']);
        });
    }
};
