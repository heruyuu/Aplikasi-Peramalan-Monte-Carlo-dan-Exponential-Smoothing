<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProduksTable extends Migration {
    public function up() {
        Schema::create('app_produk', function (Blueprint $table) {
            $table->id();
            $table->string('kd_produk')->unique();
            $table->string('nm_produk', 100);
            $table->bigInteger('kategori_id')->unsigned()->index();
            $table->bigInteger('satuan_id')->unsigned()->index();
            $table->string('harga', 20);
            $table->bigInteger('user_id');
            $table->timestamps();
        });

        Schema::table('app_produk', function (Blueprint $table) {
            $table->foreign('kategori_id')->references('id')->on('app_kategori')->onDelete('cascade');
        });

        Schema::table('app_produk', function (Blueprint $table) {
            $table->foreign('satuan_id')->references('id')->on('app_satuan')->onDelete('cascade');
        });


    }

    public function down() {
        Schema::dropIfExists('app_produk');
    }
}