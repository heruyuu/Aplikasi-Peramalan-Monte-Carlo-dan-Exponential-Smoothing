<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use HasFactory, Notifiable;
    protected $table 	= "app_user";
    protected $fillable = ['username','password','nm_lengkap','level'];
    protected $guarded	= ["created_at","updated_at"];
    protected $hidden   = ['password'];
}
