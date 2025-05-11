<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('angsurans', function (Blueprint $table) {
            $table->string('transaction_id')->nullable()->after('total_bayar');
            $table->string('order_id')->nullable()->after('transaction_id');
            // $table->dropColumn('bukti_angsuran');
        });
    }

    public function down(): void
    {
        Schema::table('angsurans', function (Blueprint $table) {
            $table->dropColumn('transaction_id');
            $table->dropColumn('order_id');
            // $table->string('bukti_angsuran')->nullable();
        });
    }
};