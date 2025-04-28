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
        Schema::create('pelanggans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pelanggan')->unique();
            $table->string('email')->unique();
            $table->string('password',15);
            $table->string('no_hp', 15)->unique();
            $table->string('alamat1')->nullable();;
            $table->string('kota1')->nullable();;
            $table->string('provinsi1')->nullable();;
            $table->string('kode_pos1')->nullable();;
            $table->string('alamat2')->nullable();
            $table->string('kota2')->nullable();
            $table->string('provinsi2')->nullable();;
            $table->string('kode_pos2')->nullable();;
            $table->string('alamat3')->nullable();;
            $table->string('kota3')->nullable();;
            $table->string('provinsi3')->nullable();;
            $table->string('kode_pos3')->nullable();;
            $table->string('foto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggans');
    }
};
