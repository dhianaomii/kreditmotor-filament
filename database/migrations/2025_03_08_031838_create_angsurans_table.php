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
        Schema::create('angsurans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kredit_id');
            $table->foreign('kredit_id')->references('id')->on('kredits');
            $table->date('tgl_bayar');
            $table->integer('angsuran_ke');
            $table->double('total_bayar');
            $table->text('keterangan');
            $table->string('bukti_angsuran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('angsurans');
    }
};
