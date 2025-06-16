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
        Schema::table('transactions', function (Blueprint $table) {

            $table->dropForeign(['user_id']); // hapus foreign key
            $table->dropColumn('user_id'); // hapus kolom user_id
            $table->string('customer_name'); // tambahin kolom nama manual
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->dropColumn('customer_name');
        });
    }
};
