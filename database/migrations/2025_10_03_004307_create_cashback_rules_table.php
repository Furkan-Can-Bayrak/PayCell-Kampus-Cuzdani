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
        Schema::create('cashback_rules', function (Blueprint $table) {
            $table->id();
            $table->string('rule_type'); // 'category_percentage', 'first_qr_bonus', etc.
            $table->string('name'); // Kural adı (örn: "Kafe %5 İade")
            $table->string('description')->nullable(); // Açıklama
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('cascade'); // Kategori bazlı kurallar için
            $table->decimal('rate', 5, 4)->nullable(); // Yüzde oranı (0.05 = %5)
            $table->decimal('flat_amount', 10, 2)->nullable(); // Sabit tutar (20 TL gibi)
            $table->decimal('cap', 10, 2)->nullable(); // Maksimum iade tutarı
            $table->boolean('first_time_only')->default(false); // Tek seferlik mi?
            $table->boolean('is_active')->default(true); // Aktif mi?
            $table->timestamp('starts_at')->nullable(); // Başlangıç tarihi
            $table->timestamp('ends_at')->nullable(); // Bitiş tarihi
            $table->timestamps();
            
            $table->index(['rule_type', 'is_active']);
            $table->index(['category_id', 'is_active']);
            $table->index(['starts_at', 'ends_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashback_rules');
    }
};
