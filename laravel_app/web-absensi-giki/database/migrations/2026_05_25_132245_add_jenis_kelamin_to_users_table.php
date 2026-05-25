<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJenisKelaminToUsersTable extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'jenis_kelamin')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->after('email');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'jenis_kelamin')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('jenis_kelamin');
            });
        }
    }
}