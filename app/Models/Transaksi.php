<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model {
    use HasFactory;
    protected $table 	= 'app_transaksi';
	protected $fillabel = ['no_transaksi','pembeli','tgl','total_bayar','user_id'];
	protected $guarded	= ['created_at','updated_at'];

    public function transaksi_bantu() {
        return $this->hasMany("App\Models\TransaksiBantu", "transaksi_id","id");
    }
}
