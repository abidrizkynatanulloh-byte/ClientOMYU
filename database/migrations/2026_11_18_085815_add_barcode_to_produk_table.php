<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('produk')) {
            return;
        }

        if (! Schema::hasColumn('produk', 'barcode')) {
            Schema::table('produk', function (Blueprint $table) {
                $table->string('barcode', 64)->nullable()->after('slug');
            });
        }

        if (! Schema::hasIndex('produk', 'produk_barcode_unique')) {
            Schema::table('produk', function (Blueprint $table) {
                $table->unique('barcode', 'produk_barcode_unique');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('produk')) {
            return;
        }

        if (Schema::hasIndex('produk', 'produk_barcode_unique')) {
            Schema::table('produk', function (Blueprint $table) {
                $table->dropUnique('produk_barcode_unique');
            });
        }

        if (Schema::hasColumn('produk', 'barcode')) {
            Schema::table('produk', function (Blueprint $table) {
                $table->dropColumn('barcode');
            });
        }
    }
};
