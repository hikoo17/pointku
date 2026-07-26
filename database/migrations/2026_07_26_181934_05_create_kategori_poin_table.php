<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_poin', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis', ['pelanggaran', 'apresiasi']);
            $table->string('nama_kategori');
            $table->integer('bobot_poin');
            $table->enum('tingkat', ['ringan', 'sedang', 'berat']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_poin');
    }
};
