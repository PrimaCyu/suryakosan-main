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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 255);
            $table->string('type', 30)->default('bank'); // 'qris', 'bank', 'ewallet', 'cash'
            $table->string('bank_code', 20)->nullable();  // e.g. 'BCA', 'MDR', 'BNI', 'BRI', 'DANA'
            $table->string('account_number', 100)->nullable();
            $table->string('account_holder', 255)->nullable();
            $table->string('qr_image', 255)->nullable();
            $table->text('instructions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};

