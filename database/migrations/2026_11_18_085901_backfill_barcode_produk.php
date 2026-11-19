<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('produk') || ! Schema::hasColumn('produk', 'barcode')) {
            return;
        }

        DB::table('produk')->whereNull('barcode')->orderBy('id')->chunkById(100, function ($products) {
            foreach ($products as $produk) {
                $barcode = '2'.str_pad((string) $produk->id, 12, '0', STR_PAD_LEFT);

                while (DB::table('produk')->where('barcode', $barcode)->exists()) {
                    $barcode = str_pad((string) ((int) $barcode + 1), 13, '0', STR_PAD_LEFT);
                }

                DB::table('produk')->where('id', $produk->id)->whereNull('barcode')->update(['barcode' => $barcode]);
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('produk') || ! Schema::hasColumn('produk', 'barcode')) {
            return;
        }

        DB::table('produk')
            ->where('barcode', 'like', '200000000%')
            ->update(['barcode' => null]);
    }
};
