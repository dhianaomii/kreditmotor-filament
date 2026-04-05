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
        Schema::table('kredits', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('angsurans', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('pelanggans', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('pengajuan_kredits', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kredits', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('angsurans', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('pelanggans', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('pengajuan_kredits', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
