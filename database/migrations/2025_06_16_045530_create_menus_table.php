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
        Schema::create('menus', function (Blueprint $table) {
            $table->id('menu_id'); // PK auto-increment
            $table->string('menu_name', 100);
            $table->foreignId('category_id')->constrained('category_menus', 'category_id')->onDelete('cascade');
            $table->decimal('price', 10, 2); // Format: 12345678.99
            $table->text('description')->nullable();
            $table->integer('stock')->default(0);
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
