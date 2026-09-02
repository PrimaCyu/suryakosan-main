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
        Schema::table('product_kosans', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
            $table->text('fasilitas')->nullable()->change();
            $table->string('wilayah')->nullable()->change();
            $table->string('view')->default('0')->nullable()->change();
            $table->string('tersedia')->default('0')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_kosans', function (Blueprint $table) {
            $table->text('description')->nullable(false)->change();
            $table->text('fasilitas')->nullable(false)->change();
            $table->string('wilayah')->nullable(false)->change();
        });
    }
};
