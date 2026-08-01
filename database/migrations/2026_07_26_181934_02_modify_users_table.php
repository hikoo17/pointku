<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('id');
            $table->string('nama_lengkap')->after('username');
            $table->foreignId('role_id')->after('nama_lengkap')->constrained()->cascadeOnDelete();

            $table->dropUnique('users_email_unique');
            $table->dropColumn(['name', 'email', 'email_verified_at', 'remember_token']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->string('email')->unique()->after('name');
            $table->timestamp('email_verified_at')->nullable()->after('email');
            $table->rememberToken()->after('password');
            $table->dropUnique('users_username_unique');
            $table->dropForeign(['role_id']);
            $table->dropColumn(['role_id', 'username', 'nama_lengkap']);
        });
    }
};
