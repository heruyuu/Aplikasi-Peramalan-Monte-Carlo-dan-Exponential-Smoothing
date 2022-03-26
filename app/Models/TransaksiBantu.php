<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiBantu extends Model {
    use HasFactory;
    public $timestamps  = false;
    protected $table    = "app_transaksi_bantu";
    protected $fillable = ['transaksi_id','produk_id','qty','harga'];
    protected $guarded	= [];

    public function produk() {
        return $this->belongsTo("App\Models\Produk", "produk_id","id");
    }
}
