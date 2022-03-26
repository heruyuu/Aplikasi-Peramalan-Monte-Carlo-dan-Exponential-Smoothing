<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiBantusTable extends Migration {
    public function up() {
        Schema::create('app_transaksi_bantu', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('transaksi_id')->unsigned()->index();
            $table->bigInteger('produk_id')->unsigned()->index();
            $table->string('qty', 10);
            $table->string('harga', 15);
        });

        Schema::table('app_transaksi_bantu', function (Blueprint $table) {
            $table->foreign('transaksi_id')->references('id')->on('app_transaksi')->onDelete('cascade');
        });

        Schema::table('app_transaksi_bantu', function (Blueprint $table) {
            $table->foreign('produk_id')->references('id')->on('app_produk')->onDelete('cascade');
        });
    }
    public function down() {
        Schema::dropIfExists('app_transaksi_bantu');
    }
}
