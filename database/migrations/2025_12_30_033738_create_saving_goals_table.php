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
        Schema::create('saving_goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Contoh: Beli iPhone 15
            $table->decimal('target_amount', 15, 2); // Target: Rp 20.000.000
            $table->decimal('current_amount', 15, 2)->default(0); // Terkumpul: Rp 5.000.000
            $table->date('deadline')->nullable(); // Target tanggal tercapai
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saving_goals');
    }
};
