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
        Schema::create('jenis_cicilans', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_cicilan');
            $table->integer('lama_cicilan');
            $table->double('margin_kredit', 8, 2);
            $table->decimal('dp', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_cicilans');
    }
};
