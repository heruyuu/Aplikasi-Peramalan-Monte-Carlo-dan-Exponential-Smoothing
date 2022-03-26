<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    use HasFactory;
    protected $table 	= 'app_satuan';
	protected $fillabel = ['satuan','user_id'];
	protected $guarded	= ['created_at','updated_at'];
}
