<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration {
    public function up() {
        Schema::create('app_user', function (Blueprint $table) {
            $table->id();
            $table->string('nm_lengkap', 50);
            $table->string('username', 20)->unique();
            $table->string('password');
            $table->enum('level', ['staff','administrator','pimpinan']);
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('app_user');
    }
}
