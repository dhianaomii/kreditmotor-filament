<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsBlockedToPelanggansTable extends Migration
{
    public function up()
    {
        Schema::table('pelanggans', function (Blueprint $table) {
            $table->boolean('is_blocked')->default(false);
        });
    }

    public function down()
    {
        Schema::table('pelanggans', function (Blueprint $table) {
            $table->dropColumn('is_blocked');
        });
    }
}