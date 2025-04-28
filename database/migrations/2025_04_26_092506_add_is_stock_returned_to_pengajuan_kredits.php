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
        Schema::table('pengajuan_kredits', function (Blueprint $table) {
            $table->boolean('is_stock_returned')->default(false)->after('keterangan_status_pengajuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_kredits', function (Blueprint $table) {
            $table->dropColumn('is_stock_returned');
        });
    }
};
