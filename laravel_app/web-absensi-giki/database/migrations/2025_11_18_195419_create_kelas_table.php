<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id(); // ini WAJIB bigIncrements
            $table->string('nama_kelas'); // contoh: X-RPL-1, XI-IPA-2, dll
            $table->string('tingkat')->nullable(); // X, XI, XII
            $table->string('jurusan')->nullable(); // RPL, TKJ, IPA, dll
            $table->integer('jumlah_siswa')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};