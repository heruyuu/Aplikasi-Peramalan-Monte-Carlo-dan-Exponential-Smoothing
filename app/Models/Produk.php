<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model {
    use HasFactory;
    protected $table 	= 'app_produk';
	protected $fillabel = ['kd_produk','nm_produk','kategori_id','satuan_id','harga','deskripsi','image','user_id'];
	protected $guarded	= ['created_at','updated_at'];

    public function kategori() {
        return $this->belongsTo("App\Models\Kategori", "kategori_id","id");
    }
    public function satuan() {
        return $this->belongsTo("App\Models\Satuan", "satuan_id","id");
    }
}
