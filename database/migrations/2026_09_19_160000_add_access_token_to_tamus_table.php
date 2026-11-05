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
        Schema::table('tamus', function (Blueprint $table) {
            $table->string('access_token', 64)->nullable()->unique()->after('status');
        });

        // Backfill existing records with tokens
        $tamus = \App\Models\Tamu::whereNull('access_token')->get();
        foreach ($tamus as $tamu) {
            $tamu->update(['access_token' => bin2hex(random_bytes(32))]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tamus', function (Blueprint $table) {
            $table->dropColumn('access_token');
        });
    }
};
