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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Dompet Sumber (Wajib)
            $table->foreignId('wallet_id')->constrained('wallets')->onDelete('cascade');

            // Dompet Tujuan (Hanya diisi jika tipe 'transfer', boleh NULL)
            $table->foreignId('destination_wallet_id')->nullable()->constrained('wallets')->onDelete('cascade');

            // Kategori (Boleh NULL jika tipe 'transfer')
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');

            $table->decimal('amount', 15, 2); // Nominal transaksi

            // Tipe Transaksi
            $table->enum('type', ['income', 'expense', 'transfer']);

            $table->date('transaction_date');
            $table->text('description')->nullable(); // Catatan tambahan
            $table->string('proof_image')->nullable(); // Upload bukti struk/bon
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
