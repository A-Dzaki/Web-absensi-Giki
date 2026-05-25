<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('no_telp')->nullable()->after('email');
            $table->string('mapel')->nullable()->after('no_telp');
            $table->string('kelas_diampu')->nullable()->after('mapel');
            $table->string('foto')->nullable()->after('kelas_diampu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'no_telp',
                'mapel',
                'kelas_diampu',
                'foto'
            ]);
        });
    }
};