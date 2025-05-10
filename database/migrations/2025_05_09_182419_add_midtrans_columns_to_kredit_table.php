<?php

   use Illuminate\Database\Migrations\Migration;
   use Illuminate\Database\Schema\Blueprint;
   use Illuminate\Support\Facades\Schema;

   class AddMidtransColumnsToKreditTable extends Migration
   {
       public function up()
       {
           Schema::table('kredits', function (Blueprint $table) {
               $table->string('order_id', 50)->nullable()->after('pengajuan_kredit_id');
               $table->string('snap_token', 36)->nullable()->after('order_id');
               $table->enum('payment_status', ['pending', 'paid', 'failed', 'expired'])->default('pending')->after('status_kredit');
           });
       }

       public function down()
       {
           Schema::table('kredits', function (Blueprint $table) {
               $table->dropColumn(['order_id', 'snap_token', 'payment_status']);
           });
       }
   }