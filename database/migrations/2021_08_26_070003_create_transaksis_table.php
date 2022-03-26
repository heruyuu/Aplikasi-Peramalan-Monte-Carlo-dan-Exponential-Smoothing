<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksisTable extends Migration {
    public function up() {
        Schema::create('app_transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaksi', 15)->unique();
            $table->string('pembeli', 50)->nullable();
            $table->date('tgl');
            $table->string('total_bayar', 20);
            $table->bigInteger('user_id');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('app_transaksi');
    }
}
