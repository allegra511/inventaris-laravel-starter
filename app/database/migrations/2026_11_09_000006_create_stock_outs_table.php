<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stock_outs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->restrictOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('date');
            $table->unsignedInteger('quantity');
            $table->text('note');
            $table->timestamps();
            $table->index(['date', 'item_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('stock_outs'); }
};
