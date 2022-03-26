<?php
namespace App\Helpers;

use Request;
use Image;
use File;

use Carbon\Carbon;

class Custom {

	public static function bulan_indo($bulan) {
		$bulans = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
		return $bulans[$bulan-1];
	}
	
	public static function conver_rupiah($nilai) {
		return number_format($nilai,0,',','.');
	}

	public static function conver_angka($nilai) {
		return preg_replace("/[^0-9]/", "", $nilai);
	}

	//Exponentional Smoothing
	public static function single_exponential_smoothing($data_penjualan, $priode_awal, $priode_akhir) {
		$alfa = 0.9;

		$forecast = [];
		foreach($data_penjualan as $key => $item_data) {
			$dt         = $item_data['penjualan'];
			if($key==0) {
				$n_forecast = '';
				$error      = '';
				$error2     = '';
			} else if($key==1) {
				$n_forecast = $data_penjualan[$key-1]['penjualan'];
				$error      = round($dt - $n_forecast, 2);
				$error2     = round(pow($error, 2), 2);
			} else {
				$n_forecast = round($alfa * $dt + (1-$alfa) * $forecast[$key-1]['forecast'], 2);
				$error      = round($dt - $n_forecast, 2);
				$error2     = round(pow($error, 2), 2);
			}
			$forecast[] = [
				'tgl'       => $item_data['thn_bln'],
				'penjualan'	=> $item_data['penjualan'],
				'forecast'  => $n_forecast,
				'error'     => $error, 
				'error2'    => $error2, 
			];
		}

		$nilai_forecast = round($alfa * $dt + (1-$alfa) * $forecast[count($data_penjualan)-1]['forecast'], 2);
		$sum_erorr2     = array_sum(array_column($forecast, 'error2'));
		$rmse           = round(pow($sum_erorr2 / (count($data_penjualan)-1), 0.5), 2);

		$hasil_simulasi=[];

		$awal 	= strtotime($priode_awal);
		$akhir 	= strtotime($priode_akhir);
		
		for($tgl=$awal;$tgl<=$akhir;$tgl++) {
            $tgl = $tgl==$awal?$tgl:strtotime("+1 month", $tgl);
			$hasil_simulasi[] = [
				"tgl"       => date( 'Y-m', $tgl),
				"hasil"     => $tgl==$awal?$nilai_forecast:0,
			];
		}

		// $tgl_awal 	= strtotime($tgl_awal);
		// $tgl_akhir 	= strtotime($tgl_akhir);
		
		// for($tgl=$tgl_awal;$tgl<=$tgl_akhir;$tgl = $tgl + 86400) {
		// 	$hasil_simulasi[] = [
		// 		"tgl"       => date( 'Y-m-d', $tgl ),
		// 		"hasil"     => $tgl==$tgl_awal?$nilai_forecast:0,
		// 	];
		// }
		
		$hasil['forecast']       = $forecast;
		$hasil['nilai_forecast'] = $nilai_forecast;
		$hasil['hasil_simulasi'] = $hasil_simulasi;
		$hasil['sum_erorr2']     = $sum_erorr2;
		$hasil['rmse']           = $rmse;

		return $hasil;
	}

	//Monte Carlo
	public static function monte_carlo($data_penjualan, $priode_awal, $priode_akhir) {
		$sum_penjualan = array_sum(array_column($data_penjualan, 'penjualan'));

		$montecarlo=[];
		foreach($data_penjualan as $key_item => $item_penjualan) {
			$probalitas = round($item_penjualan['penjualan'] / $sum_penjualan, 2);
			$probalitas_komulatif = $key_item==0?$probalitas: $probalitas + $montecarlo[$key_item-1]['probalitas_komulatif'];
			$nilai_komulatif  = round($probalitas_komulatif * 100, 2);
			$awal = $key_item==0?"0":$montecarlo[$key_item-1]['nilai_komulatif']+1;
			$montecarlo[] = [
				'tgl'        => $item_penjualan['thn_bln'],
				'penjualan'	 => $item_penjualan['penjualan'],
				'probalitas' => $probalitas,
				'probalitas_komulatif'  => round($probalitas_komulatif, 2),
				'nilai_komulatif'       => $nilai_komulatif,
				'bil_acak'  => [
					"awal"  => $awal,
					"akhir" => $nilai_komulatif
				],
			];
		}

		$n_komulatif_akhir = round($nilai_komulatif, 2);

		$hasil_simulasi=[];
		$awal 	= strtotime($priode_awal);
		$akhir 	= strtotime($priode_akhir);
		
		for($tgl=$awal;$tgl<=$akhir;$tgl++) {
            $tgl = $tgl==$awal?$tgl:strtotime("+1 month", $tgl);
			$acak_bilangan = rand(1, $n_komulatif_akhir);
			$nilai = Custom::search_data_acak($montecarlo, $acak_bilangan);
			$hasil_simulasi[] = [
				"tgl"       => date( 'Y-m', $tgl),
				"bil_acak"  => $acak_bilangan,
				"hasil"     => $data_penjualan[$nilai]['penjualan']
			];
		}

		// $tgl_awal 	= strtotime($tgl_awal);
		// $tgl_akhir 	= strtotime($tgl_akhir);
		// for($tgl=$tgl_awal;$tgl<=$tgl_akhir;$tgl = $tgl + 86400) {
		// 	$acak_bilangan = rand(1, $n_komulatif_akhir);
		// 	$nilai = Custom::search_data_acak($montecarlo, $acak_bilangan);
		// 	$hasil_simulasi[] = [
		// 		"tgl"       => date( 'Y-m-d', $tgl ),
		// 		"bil_acak"  => $acak_bilangan,
		// 		"hasil"     => $data_penjualan[$nilai]['penjualan']
		// 	];
		// }
		
		$hasil['sum_penjualan']  = $sum_penjualan;
		$hasil['montecarlo']     = $montecarlo;
		$hasil['hasil_simulasi'] = $hasil_simulasi;

		return $hasil;
	}

	public static function search_data_acak($data, $nilai) {
		foreach($data as $key => $item) {
			if($nilai==$item['bil_acak']['awal']) {
				return $key;
			} else if($nilai==$item['bil_acak']['akhir']) {
				return $key;
			} else if($nilai>$item['bil_acak']['awal'] && $nilai<$item['bil_acak']['akhir']) {
				return $key;
			}
		}
	}
	
	public static function uploadImg($imgFile, $nmImg, $uploadDir, $dimensiImg, $dimensi_thumbnail) {
		$dir_path	= public_path($uploadDir);
		$file 		= $imgFile;
		$nmImg 		= $nmImg;
		if (!File::isDirectory($dir_path)) {
			File::makeDirectory($dir_path);
		}
		$width 	= Image::make($file)->width();
		$height = Image::make($file)->height();
		if ($dimensiImg==0) {
			$thumb_width 	= $width;
			$thumb_height 	= $height;
		} else {
			$thumb_width 	= $dimensiImg;
			$thumb_height 	= ($thumb_width/$width) * $height;
		}
		$canvas 		= Image::canvas($thumb_width, $thumb_height);
		$resizeImage  	= Image::make($file)->resize($thumb_width, $thumb_height, function($constraint) {
			$constraint->aspectRatio();
		});
		$canvas->insert($resizeImage, 'center');
		$canvas->save($dir_path.$nmImg);

		if(!empty($dimensi_thumbnail)) {
			foreach ($dimensi_thumbnail as $res_dimensi) {
				$nm_thumbnail 		= $res_dimensi[0]."x".$res_dimensi[1]."-".$nmImg;
				$canvas_thumbnail 	= Image::canvas($res_dimensi[0], $res_dimensi[1]);
				$size_thumbnail  	= Image::make($file)->fit($res_dimensi[0], $res_dimensi[1]);
				$canvas_thumbnail->insert($size_thumbnail, 'center');
				$canvas_thumbnail->save($dir_path."/".$nm_thumbnail);
			}
		}
		return true;
	}

	public static function deleteImg($imgFile, $uploadDir, $dimensi_thumbnail) {
		$dir_path	= public_path($uploadDir);
		File::delete($dir_path . '/' . $imgFile);
		if(!empty($dimensi_thumbnail)) {
			foreach ($dimensi_thumbnail as $res_dimensi) {
				$nm_thumbnail 		= $res_dimensi[0]."x".$res_dimensi[1]."-".$imgFile;
				File::delete($dir_path."/".$nm_thumbnail);
			}
		}
		return true;
	}

	public static function nameImg($file) {
		$ext 		= $file->getClientOriginalExtension();
		$file_name 	= $file->getClientOriginalName();
		$nmImg 		= substr(md5($file_name.date('Y-m-d H:i:s')), 0, 25).".".$ext;
		return $nmImg;
	}

    public static function messages() {
		return [
			'accepted' => 'Isian :attribute harus diterima.',
			'active_url' => 'Isian :attribute bukan URL yang valid.',
			'after' => 'Isian :attribute harus tanggal setelah :date.',
			'after_or_equal' => 'Isian :attribute harus berupa tanggal setelah atau sama dengan tanggal :date.',
			'alpha' => 'Isian :attribute hanya boleh berisi huruf.',
			'alpha_dash' => 'Isian :attribute hanya boleh berisi huruf, angka, dan strip.',
			'alpha_num' => 'Isian :attribute hanya boleh berisi huruf dan angka.',
			'array' => 'Isian :attribute harus berupa sebuah array.',
			'before' => 'Isian :attribute harus tanggal sebelum :date.',
			'before_or_equal' => 'Isian :attribute harus berupa tanggal sebelum atau sama dengan tanggal :date.',
			'between' => [
				'numeric' => 'Isian :attribute harus antara :min dan :max.',
				'file' => 'Isian :attribute harus antara :min dan :max kilobytes.',
				'string' => 'Isian :attribute harus antara :min dan :max karakter.',
				'array' => 'Isian :attribute harus antara :min dan :max item.',
			],
			'boolean' => 'Isian :attribute harus berupa true atau false',
			'confirmed' => 'Konfirmasi :attribute tidak cocok.',
			'date' => 'Isian :attribute bukan tanggal yang valid.',
			'date_format' => 'Isian :attribute tidak cocok dengan format :format.',
			'different' => 'Isian :attribute dan :other harus berbeda.',
			'digits' => 'Isian :attribute harus berupa angka :digits.',
			'digits_between' => 'Isian :attribute harus antara angka :min dan :max.',
			'dimensions' => 'Form :attribute tidak memiliki dimensi gambar yang valid.',
			'distinct' => 'Form isian :attribute memiliki nilai yang duplikat.',
			'email' => 'Isian :attribute harus berupa alamat surel yang valid.',
			'exists' => 'Isian :attribute yang dipilih tidak valid.',
			'file' => 'Form :attribute harus berupa sebuah berkas.',
			'filled' => 'Isian :attribute harus memiliki nilai.',
			'image' => 'Isian :attribute harus berupa gambar.',
			'in' => 'Isian :attribute yang dipilih tidak valid.',
			'in_array' => 'Form isian :attribute tidak terdapat dalam :other.',
			'integer' => 'Isian :attribute harus merupakan bilangan bulat.',
			'ip' => 'Isian :attribute harus berupa alamat IP yang valid.',
			'ipv4' => 'Isian :attribute harus berupa alamat IPv4 yang valid.',
			'ipv6' => 'Isian :attribute harus berupa alamat IPv6 yang valid.',
			'json' => 'Isian :attribute harus berupa JSON string yang valid.',
			'max' => [
				'numeric' => 'Isian :attribute seharusnya tidak lebih dari :max.',
				'file' => 'Isian :attribute seharusnya tidak lebih dari :max kilobytes.',
				'string' => 'Isian :attribute seharusnya tidak lebih dari :max karakter.',
				'array' => 'Isian :attribute seharusnya tidak lebih dari :max item.',
			],
			'mimes' => 'Isian :attribute harus dokumen berjenis : :values.',
			'mimetypes' => 'Isian :attribute harus dokumen berjenis : :values.',
			'min' => [
				'numeric' => 'Isian :attribute harus minimal :min.',
				'file' => 'Isian :attribute harus minimal :min kilobytes.',
				'string' => 'Isian :attribute harus minimal :min karakter.',
				'array' => 'Isian :attribute harus minimal :min item.',
			],
			'not_in' => 'Isian :attribute yang dipilih tidak valid.',
			'numeric' => 'Isian :attribute harus berupa angka.',
			'present' => 'Form isian :attribute wajib ada.',
			'regex' => 'Format isian :attribute tidak valid.',
			'required' => 'Form isian :attribute wajib diisi.',
			'required_if' => 'Form isian :attribute wajib diisi bila :other adalah :value.',
			'required_unless' => 'Form isian :attribute wajib diisi kecuali :other memiliki nilai :values.',
			'required_with' => 'Form isian :attribute wajib diisi bila terdapat :values.',
			'required_with_all' => 'Form isian :attribute wajib diisi bila terdapat :values.',
			'required_without' => 'Form isian :attribute wajib diisi bila tidak terdapat :values.',
			'required_without_all' => 'Form isian :attribute wajib diisi bila tidak terdapat ada :values.',
			'same' => 'Isian :attribute dan :other harus sama.',
			'size' => [
				'numeric' => 'Isian :attribute harus berukuran :size.',
				'file' => 'Isian :attribute harus berukuran :size kilobyte.',
				'string' => 'Isian :attribute harus berukuran :size karakter.',
				'array' => 'Isian :attribute harus mengandung :size item.',
			],
			'string' => 'Isian :attribute harus berupa string.',
			'timezone' => 'Isian :attribute harus berupa zona waktu yang valid.',
			'unique' => 'Isian :attribute sudah ada sebelumnya.',
			'uploaded' => 'Isian :attribute gagal diunggah.',
			'url' => 'Format isian :attribute tidak valid.',
			'custom' => [
				'attribute-name' => [
					'rule-name' => 'custom-message',
				],
			],
			'attributes' => [],
		];
	}
	

}