<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSatuansTable extends Migration {
    public function up() {
        Schema::create('app_satuan', function (Blueprint $table) {
            $table->id();
            $table->string('satuan')->unique();
            $table->bigInteger('user_id');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('app_satuan');
    }
}
