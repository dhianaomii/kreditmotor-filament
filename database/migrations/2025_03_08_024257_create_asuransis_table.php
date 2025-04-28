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
        Schema::create('asuransis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_perusahaan_asuransi');
            $table->string('nama_asuransi', 50);
            $table->decimal('margin_asuransi', 8, 2);
            $table->string('no_rekening', 25);
            $table->string('logo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asuransis');
    }
};
