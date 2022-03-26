<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKategorisTable extends Migration {
    public function up() {
        Schema::create('app_kategori', function (Blueprint $table) {
            $table->id();
            $table->string('kategori')->unique();
            $table->bigInteger('user_id');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('app_kategori');
    }
}
