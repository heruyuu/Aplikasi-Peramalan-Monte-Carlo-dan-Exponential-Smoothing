<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;
    protected $table 	= 'app_kategori';
	protected $fillabel = ['kategori','user_id'];
	protected $guarded	= ['created_at','updated_at'];
}
