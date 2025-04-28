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
        Schema::create('pengajuan_kredits', function (Blueprint $table) {
            $table->id();
            $table->date('tgl_pengajuan_kredit');
            $table->unsignedBigInteger('pelanggan_id');
            $table->foreign('pelanggan_id')->references('id')->on('pelanggans');
            $table->unsignedBigInteger('motor_id');
            $table->foreign('motor_id')->references('id')->on('motors');
            $table->integer('harga_cash');
            $table->integer('dp');
            $table->unsignedBigInteger('jenis_cicilan_id');
            $table->foreign('jenis_cicilan_id')->references('id')->on('jenis_cicilans');
            $table->double('harga_kredit');
            $table->unsignedBigInteger('asuransi_id');
            $table->foreign('asuransi_id')->references('id')->on('asuransis');
            $table->double('biaya_asuransi_perbulan');
            $table->double('cicilan_perbulan');
            $table->string('url_kk');
            $table->string('url_ktp');
            $table->string('url_npwp');
            $table->string('url_slip_gaji');
            $table->string('url_foto');
            $table->enum('status_pengajuan', ['Menunggu Konfirmasi', 'Diproses', 'Dibatalkan Pembeli', 'DIbatalkan Penjual', 'Bermasalah', 'Diterima']);
            $table->string('keterangan_status_pengajuan')->nullable();
            $table->boolean('is_stock_returned')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_kredits');
    }
};
