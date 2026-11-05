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
        // Bersihkan format data jika ada sebelum konversi kolom ke tipe native
        try {
            DB::statement("UPDATE tamus SET end_date = SUBSTRING(end_date, 1, 10) WHERE LENGTH(end_date) > 10 AND end_date IS NOT NULL");
            DB::statement("UPDATE tamus SET total_price = '0' WHERE total_price IS NULL OR total_price = ''");
        } catch (\Exception $e) {
            // Lanjutkan jika tabel kosong
        }

        Schema::table('tamus', function (Blueprint $table) {
            $table->date('end_date')->change();
            $table->decimal('total_price', 15, 2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tamus', function (Blueprint $table) {
            $table->string('end_date')->change();
            $table->string('total_price')->change();
        });
    }
};
