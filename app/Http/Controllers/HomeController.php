<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;


class HomeController extends Controller {
    public function index() {
        $data = [
            'Satuan'    => \App\Models\Satuan::count(),
            'Kategori'  => \App\Models\Kategori::count(),
            'Produk'    => \App\Models\Produk::count(),
            'Transaksi' => \App\Models\Transaksi::count(),
            'User'      => \App\Models\User::count(),
        ];
        return view('pages.home.detail', compact('data'));
    }

    public function test() {

        $tgl_awal 	= strtotime('2016-01-01');
		$tgl_akhir 	= strtotime('2020-12-31');
		
		for($tgl=$tgl_awal;$tgl<=$tgl_akhir;$tgl = $tgl + 86400) {
            if(date( 'l', $tgl )!='Sunday') {
			    $tanggal= date( 'Y-m-d', $tgl );
                $xxx[]  = $tanggal;
                $id     = rand(1,1000);
                $no     = rand(6068, 9999);
                $inv    = array_rand(['NM0','NS0']);
                $cek = \App\Models\Transaksi::where('no_transaksi', $inv.$no)->count();
                if($cek<=0) {
                    $transaksi = \App\Models\Transaksi::with('transaksi_bantu')->find($id);
                    $data = [
                        "no_transaksi"  => $inv.$no, 
                        "tgl"           => $tanggal, 
                        "total_bayar"   => $transaksi->total_bayar,
                        "user_id"       => auth()->user()->id
                    ];

                    $result = \App\Models\Transaksi::create($data);

                    foreach($transaksi->transaksi_bantu AS $item) {
                        $data_bantu = [
                            'transaksi_id'  => $result->id,
                            'produk_id'     => $item->produk_id,
                            'qty'           => $item->qty,
                            'harga'         => $item->harga,
                        ];
        
                        \App\Models\TransaksiBantu::create($data_bantu);
                    }
                }
                
            }
		}

        return $xxx;
    }
}
