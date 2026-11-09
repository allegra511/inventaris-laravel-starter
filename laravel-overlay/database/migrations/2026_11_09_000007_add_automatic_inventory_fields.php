<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->unsignedInteger('target_stock')->default(0);
        });

        // Set a sensible replenishment target for existing items (2x minimum stock).
        DB::table('items')->update([
            'target_stock' => DB::raw('minimum_stock * 2'),
        ]);

        Schema::table('stock_ins', function (Blueprint $table) {
            $table->string('transaction_code', 40)->nullable()->unique();
        });

        Schema::table('stock_outs', function (Blueprint $table) {
            $table->string('transaction_code', 40)->nullable()->unique();
        });

        // Backfill readable references for movements that existed before this migration.
        DB::table('stock_ins')->orderBy('id')->chunkById(500, function ($rows) {
            foreach ($rows as $row) {
                DB::table('stock_ins')->where('id', $row->id)->update([
                    'transaction_code' => 'IN-' . str_replace('-', '', substr((string) $row->date, 0, 10)) . '-' . str_pad((string) $row->id, 6, '0', STR_PAD_LEFT),
                ]);
            }
        });

        DB::table('stock_outs')->orderBy('id')->chunkById(500, function ($rows) {
            foreach ($rows as $row) {
                DB::table('stock_outs')->where('id', $row->id)->update([
                    'transaction_code' => 'OUT-' . str_replace('-', '', substr((string) $row->date, 0, 10)) . '-' . str_pad((string) $row->id, 6, '0', STR_PAD_LEFT),
                ]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('stock_outs', function (Blueprint $table) {
            $table->dropUnique(['transaction_code']);
            $table->dropColumn('transaction_code');
        });

        Schema::table('stock_ins', function (Blueprint $table) {
            $table->dropUnique(['transaction_code']);
            $table->dropColumn('transaction_code');
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('target_stock');
        });
    }
};
