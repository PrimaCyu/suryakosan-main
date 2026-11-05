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
        // 1. Indexing pada tabel product_kosans
        Schema::table('product_kosans', function (Blueprint $table) {
            $table->index('slug');
            $table->index('wilayah');
            $table->index('created_at');
        });

        // 2. Indexing pada tabel product_kamar_kosans
        Schema::table('product_kamar_kosans', function (Blueprint $table) {
            $table->index('product_kosan_id');
            $table->index('views');
        });

        // 3. Indexing pada tabel artikels
        Schema::table('artikels', function (Blueprint $table) {
            $table->index('slug');
            $table->index('created_at');
        });

        // 4. Indexing pada tabel tamus
        Schema::table('tamus', function (Blueprint $table) {
            $table->index('status');
            $table->index('product_kamar_kosan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_kosans', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropIndex(['wilayah']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('product_kamar_kosans', function (Blueprint $table) {
            $table->dropIndex(['product_kosan_id']);
            $table->dropIndex(['views']);
        });

        Schema::table('artikels', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('tamus', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['product_kamar_kosan_id']);
        });
    }
};
