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
        Schema::create('product_kamar_kosans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_kosan_id')->constrained('product_kosans','id')->onDelete('cascade');
            $table->string('room');
            $table->text('description')->nullable();
            $table->text('fasilitas')->nullable();
            $table->integer('cumulative_discount')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_kamar_kosans');
    }
};
