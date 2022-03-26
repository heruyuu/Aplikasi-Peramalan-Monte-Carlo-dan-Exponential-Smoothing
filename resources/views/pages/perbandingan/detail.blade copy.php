@extends("layout")
@section("title", "Perbandingan")

@section('header')
<div class="row">
    <div id="paper-top">
        <div class="col-sm-12"><h2 class="tittle-content-header"><i class="icon-media-record"></i> <span>Perbandingan Metode</span></h2></div>
    </div>
</div>
@endsection

@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="nest">
            <div class="title-alt"><h6>Perbandingan Metode</h6></div>

            <div class="body-nest">
                
                <form class="form-horizontal" id="data_form">
                    @csrf

                    <div class="form-group">
                        <label class="col-lg-2 col-sm-2 control-label">Kategori</label>
                        <div class="col-lg-4">
                            <select class="form-control" name="kategori_id" id="kategori_id">
                                <option value="">: : Pilih Kategori : :</option>
                                @foreach ($kategori as $item_kategori)
                                    <option value="{{$item_kategori->id}}">{{$item_kategori->kategori}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-2 col-sm-2 control-label">Awal</label>
                        <div class="col-lg-2">
                            <select class="form-control" name="bulan_awal" id="bulan_awal">
                                <option value="">: : Pilih Bulan : :</option>
                                @for($i=0;$i<12;$i++)
                                    @php 
                                        $bln    = $i+1;
                                        $bulan  = $bln<9?"0".$bln:$bln;
                                    @endphp
                                    <option value="{{$bulan}}">{{Custom::bulan_indo($bln)}}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <select class="form-control" name="tahun_awal" id="tahun_awal" onchange="change_thn('tahun_awal')">
                                <option value="">: : Pilih Tahun : :</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-2 col-sm-2 control-label">Akhir</label>
                        <div class="col-lg-2">
                            <select class="form-control" name="bulan_akhir" id="bulan_akhir">
                                <option value="">: : Pilih Bulan : :</option>
                                @for($i=0;$i<12;$i++)
                                    @php 
                                        $bln    = $i+1;
                                        $bulan  = $bln<9?"0".$bln:$bln;
                                    @endphp
                                    <option value="{{$bulan}}">{{Custom::bulan_indo($bln)}}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <select class="form-control" name="tahun_akhir" id="tahun_akhir" onchange="change_thn('tahun_akhir')">
                                <option value="">: : Pilih Tahun : :</option>
                            </select>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <button type="submit" class="btn btn-info btn-sm"><span class="entypo-check"></span> Submit</button>
                        </div>
                    </div>
                    <div id="container"></div>

                </form>
                
            </div>

        </div>
    </div>
</div>

@endsection


@section('custom_js')
<script type="text/javascript" src="{{asset('plugins/highcharts/highcharts.js')}}"></script>


<script>
$(function(){
    change_thn('tahun_awal');
    change_thn('tahun_akhir');
});
$('#data_form').on('submit', function(event) {
    event.preventDefault();
    idata = new FormData($('#data_form')[0]);

    $.ajax({
        type	: "POST",
        dataType: "json",
        url		: "{{asset('perbandingan/show')}}",
        data	: idata,
        processData: false,
        contentType: false,
        cache 	: false,
        beforeSend: function () { 
            in_load();
        },
        success	:function(data) {
            console.log(data.data)

            var container = "";
            $.each(data.data, function (key, item)  {
                container = container+"<div id='grafis_"+key+"' style='min-width: 310px;border-top:1px solid #ccc;padding-top:10px'></div>"+
                            "<div id='table_hasil_"+key+"'></div>";
            });

            $("#container").html(container);            

            $.each(data.data, function (key, item)  {
                var hasil_monte_carlo = item.monte_carlo.hasil_simulasi;
                var hasil_single_exponential_smoothing = item.single_exponential_smoothing.hasil_simulasi

                var ranges_tgl = [];
                var n_SES = [];
                var n_MC = [];
                for(urut=0;urut<hasil_monte_carlo.length;urut++) {
                    ranges_tgl.push(hasil_monte_carlo[urut]['tgl']);
                    n_SES.push(hasil_single_exponential_smoothing[urut]['hasil']);
                    n_MC.push(hasil_monte_carlo[urut]['hasil']);
                }

                Highcharts.chart("grafis_"+key, {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'METODE SINGLE EXPONENTIAL SMOOTHING DAN METODE MONTE CARLO'
                    },
                    subtitle: {
                        text: 'Hasil Produk ( '+item.produk+' )'
                    },
                    xAxis: {
                        categories: ranges_tgl,
                        crosshair: true
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Hasil'
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                            '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0
                        }
                    },
                    
                    series: [{
                        name: 'Monte Carlo',
                        data: n_MC

                    }, {
                        name: 'Single Exponential Smoothing',
                        data: n_SES

                    }]
                });



                var tabel_montecarlo = "<h4>METODE MONTE CARLO</h4><table class='table table-striped table-bordered'>"+ 
                        "<tr>"+
                            "<td>Tanggal</td>"+
                            "<td>Jumlah Penjualan</td>"+
                            "<td>Probalitas</td>"+
                            "<td>Probalitas Komulatif</td>"+
                            "<td>%</td>"+
                            "<td>Acak</td>"+
                        "</tr>";
                $.each(item.monte_carlo.montecarlo, function (key, item)  {
                    tabel_montecarlo = tabel_montecarlo+"<tr>"+
                        "<td>"+item.tgl+"</td>"+
                        "<td>"+item.penjualan+"</td>"+
                        "<td>"+item.probalitas+"</td>"+
                        "<td>"+item.probalitas_komulatif+"</td>"+
                        "<td>"+item.nilai_komulatif+"</td>"+
                        "<td>"+item.bil_acak.awal+" s/d "+item.bil_acak.akhir+"</td>"+
                    "</tr>";
                });
                tabel_montecarlo = tabel_montecarlo+"<tr><td>SUM</td><td>"+item.monte_carlo.sum_penjualan+"</td><td colspan='4'></td></tr></table>";

                tabel_montecarlo = tabel_montecarlo+"<table class='table table-striped table-bordered'>"+ 
                        "<tr>"+
                            "<td>Tanggal</td>"+
                            "<td>Bilangan Acak</td>"+
                            "<td>Hasil Simulasi</td>"+
                        "</tr>";
                $.each(hasil_monte_carlo, function (key, item)  {
                    tabel_montecarlo = tabel_montecarlo+"<tr>"+
                        "<td>"+item.tgl+"</td>"+
                        "<td>"+item.bil_acak+"</td>"+
                        "<td>"+item.hasil+"</td>"+
                    "</tr>";
                });
                tabel_montecarlo = tabel_montecarlo+"</table>";

                //=====================================================================
                //=====================================================================
                //=====================================================================

                var tabel_ses = "<h4>METODE SINGLE EXPONENTIAL SMOOTHING</h4><table class='table table-striped table-bordered'>"+ 
                    "<tr>"+
                        "<td>Tanggal</td>"+
                        "<td>Jumlah Penjualan</td>"+
                        "<td>Forecast</td>"+
                        "<td>Error</td>"+
                        "<td>Error^2</td>"+
                    "</tr>";
                $.each(item.single_exponential_smoothing.forecast, function (key, item)  {
                tabel_ses = tabel_ses+"<tr>"+
                    "<td>"+item.tgl+"</td>"+
                    "<td>"+item.penjualan+"</td>"+
                    "<td>"+item.forecast+"</td>"+
                    "<td>"+item.error+"</td>"+
                    "<td>"+item.error2+"</td>"+
                "</tr>";
                });
                tabel_ses = tabel_ses+"<tr><td colspan='4'>Jumlah</td><td>"+item.single_exponential_smoothing.sum_erorr2+"</td></tr>"+
                        "<tr><td colspan='4'>Tingkat Prediksi Kesalahan ( RMSE )</td><td>"+item.single_exponential_smoothing.rmse+"</td></tr></table>";

                tabel_ses = tabel_ses+"<table class='table table-striped table-bordered'>"+ 
                    "<tr>"+
                        "<td>Tanggal</td>"+
                        "<td>Hasil Simulasi</td>"+
                    "</tr>";
                $.each(hasil_single_exponential_smoothing, function (key, item)  {
                tabel_ses = tabel_ses+"<tr>"+
                    "<td>"+item.tgl+"</td>"+
                    "<td>"+item.hasil+"</td>"+
                "</tr>";
                });
                tabel_ses = tabel_ses+"</table>";

                

                $("#table_hasil_"+key).html("<div class='row'>"+
                        "<div class='col-lg-6'>"+tabel_montecarlo+"</div>"+
                        "<div class='col-lg-6'>"+tabel_ses+"</div>"+
                    "</div>");
            });

            // swal(''+data.status+'',''+data.messages+'',"success").then((value) => { window.location.href = "{{ asset('produk') }}"; });
            out_load();
        },
        error: function (error) {
			error_detail(error);
			out_load();
		}
    });
});
</script>
@endsection