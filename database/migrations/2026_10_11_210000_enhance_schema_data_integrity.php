<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambah soft deletes dan perbaiki tipe kolom pada product_kosans
        Schema::table('product_kosans', function (Blueprint $table) {
            if (!Schema::hasColumn('product_kosans', 'deleted_at')) {
                $table->softDeletes();
            }
            $table->unsignedInteger('view')->default(0)->change();
            $table->unsignedInteger('tersedia')->default(0)->change();
        });

        // 2. Tambah soft deletes pada product_kamar_kosans
        Schema::table('product_kamar_kosans', function (Blueprint $table) {
            if (!Schema::hasColumn('product_kamar_kosans', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // 3. Tambah unique index pada price_kamars untuk mencegah duplicate kategori per kamar
        Schema::table('price_kamars', function (Blueprint $table) {
            $table->unique(['product_kamar_kosan_id', 'kategori'], 'price_kamars_kamar_kategori_unique');
        });

        // 4. Tambah composite index pada tamus untuk mempercepat query collision booking
        Schema::table('tamus', function (Blueprint $table) {
            $table->index(['product_kamar_kosan_id', 'status', 'start_date', 'end_date'], 'tamus_booking_lookup_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tamus', function (Blueprint $table) {
            $table->dropIndex('tamus_booking_lookup_idx');
        });

        Schema::table('price_kamars', function (Blueprint $table) {
            $table->dropUnique('price_kamars_kamar_kategori_unique');
        });

        Schema::table('product_kamar_kosans', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('product_kosans', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->string('view')->default('0')->nullable()->change();
            $table->string('tersedia')->default('0')->nullable()->change();
        });
    }
};
