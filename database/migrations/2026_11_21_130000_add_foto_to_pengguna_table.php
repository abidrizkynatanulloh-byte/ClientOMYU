<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('pengguna')) {
            return;
        }

        if (! Schema::hasColumn('pengguna', 'foto')) {
            Schema::table('pengguna', function (Blueprint $table) {
                $table->string('foto')->nullable()->after('telepon');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('pengguna')) {
            return;
        }

        if (Schema::hasColumn('pengguna', 'foto')) {
            Schema::table('pengguna', function (Blueprint $table) {
                $table->dropColumn('foto');
            });
        }
    }
};
