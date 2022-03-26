<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder {
    public function run() {
        \App\Models\User::create([
            "username"      => "administrator",
            "password"      => bcrypt("administrator"),
            "nm_lengkap"    => "administrator",
            "level"         => "administrator",
        ]);
    }
}
