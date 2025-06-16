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
        Schema::create('stock_histories', function (Blueprint $table) {
            $table->id('history_id');
            $table->foreignId('stock_id')->constrained('stocks')->onDelete('cascade');
            $table->foreignId('menu_id')->nullable()->constrained('menus')->onDelete('cascade');
            $table->integer('quantity');
            $table->enum('type', ['in', 'out']);
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_histories');
    }
};
