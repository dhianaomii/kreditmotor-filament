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
        Schema::create('kredits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengajuan_kredit_id');
            $table->foreign('pengajuan_kredit_id')->references('id')->on('pengajuan_kredits');
            $table->unsignedBigInteger('metode_pembayaran_id');
            $table->foreign('metode_pembayaran_id')->references('id')->on('metode_pembayarans');
            $table->date('tgl_mulai_kredit');
            $table->date('tgl-_selesai_kredit');
            $table->string('url_bukti_bayar');
            $table->double('sisa_kredit');
            $table->enum('status_kredit', ['Dicicil','Macet', 'Lunas']);
            $table->string('keterangan_status_kredit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kredits');
    }
};
