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
        Schema::create('splits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->foreignId('requester_id')->constrained('users')->onDelete('cascade'); // Bölme isteği yapan
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Bölme isteği gönderilen kişi
            $table->decimal('weight', 5, 2)->default(1.00); // Ağırlık (eşit bölüşüm için 1.00)
            $table->decimal('share_amount', 10, 2); // Bu kişinin ödemesi gereken tutar
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->json('meta')->nullable(); // Ek bilgiler
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
            $table->index(['requester_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('splits');
    }
};
