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
        Schema::create('tamus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_kamar_kosan_id')->constrained('product_kamar_kosans','id')->onDelete('cascade');
            $table->string('name');
            $table->string('telp');
            $table->string('email');
            $table->string('start_time');
            $table->date('start_date');
            $table->string('end_date');
            $table->string('payment_method')->default('cod');
            $table->string('proof_of_transfer')->nullable();
            $table->string('total_price');
            $table->enum('status',['pending','reject','approved'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tamus');
    }
};
