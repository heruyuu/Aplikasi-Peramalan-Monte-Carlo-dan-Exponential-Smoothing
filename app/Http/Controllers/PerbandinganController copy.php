<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

use Carbon\Carbon;
use App\Models\Transaksi;
use App\Models\Kategori;
use App\Models\Produk;
use Validator;
use DB;
use Custom;

class PerbandinganController extends Controller {
    public function index() {
        $kategori = Kategori::all();
        return view('pages.perbandingan.detail', compact('kategori'));
    }

    public function detail(Request $request) {
        // $validates 	= [
        //     "produk_id"  => "required",
        //     "tgl_awal"  => "required",
        //     "tgl_akhir"  => "required",
        // ];

        // $validation = Validator::make($request->all(), $validates, Custom::messages(), []);
        // if($validation->fails()) {
        //     return response()->json([
        //         "status"    => "warning",
        //         "messages"   => $validation->errors()->first()
        //     ], 422);
        // }
        try {
            $kategori_id  = $request->kategori_id;
            $awal   = $request->tahun_awal."-".$request->bulan_awal;
            $akhir  = $request->tahun_akhir."-".$request->bulan_akhir;


            $produk = Produk::where('kategori_id', $kategori_id)->get();
            $output=[];
            foreach($produk AS $key => $item_produk) {
                $data_penjualan = Transaksi::select(DB::raw('DATE_FORMAT(app_transaksi.tgl, "%Y-%m") as thn_bln, SUM(app_transaksi_bantu.qty) as penjualan'))
                ->leftJoin('app_transaksi_bantu', 'app_transaksi_bantu.transaksi_id', '=', 'app_transaksi.id')
                ->leftJoin('app_produk', 'app_produk.id', '=', 'app_transaksi_bantu.produk_id')
                ->where('app_produk.id', $item_produk->id)
                ->where(DB::raw('DATE_FORMAT(app_transaksi.tgl, "%Y-%m")'),'<', $awal)
                ->groupBy('thn_bln')
                ->get()
                ->toArray();
                if(count($data_penjualan)>2) {
                    $output[$item_produk->kd_produk]['produk'] = $item_produk->nm_produk;
                    $output[$item_produk->kd_produk]['monte_carlo']  = Custom::monte_carlo($data_penjualan, $awal, $akhir);
                    $output[$item_produk->kd_produk]['single_exponential_smoothing'] = Custom::single_exponential_smoothing($data_penjualan, $awal, $akhir);
                }
            }

            return response()->json(['status'=>'success','messages'=>'proses success', 'data' => $output], 201);
        } catch(QueryException $e) { 
            return response()->json(['status'=>'error','messages'=> $e->errorInfo ], 500);
        }
    }
}
