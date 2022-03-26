@extends("layout")
@section("title", "Home")

@section('header')
<div class="row">
    <div id="paper-top">
        <div class="col-sm-12">
            <h2 class="tittle-content-header"><i class="icon-media-record"></i> <span>Dashboard</span></h2>
        </div>
    </div>
</div>
@endsection

@section('content')

@php
    $list   = ['Satuan','Kategori','Produk','Transaksi','User'];
    $color  = ['teal','danger','info','purple','pink','orange','success'];
    $color  = ['teal','danger','info','purple','pink','orange','success'];
@endphp

    <br>
    <div style="font-size:25px;font-weight:bold;margin-bottom:0px;
    line-height: 0.9;" id="load_tgl"></div>
    <div style="font-size:30px;font-weight:bold;margin-top:0px;
    line-height: 0.9;" id="load_jam"></div>

<br>

    <div class="row">
        @foreach($list as $key_list => $item_list)

        <div class="col-lg-4">
            <div style="margin-bottom: 0.5rem;" class="card card-body bg-{{$color[$key_list]}} has-bg-image">
                <div class="media media-text-white">
                    <div class="media-body">
                        <h3 class="mb-0 mt-0">{{$data[$item_list] }}</h3>
                        <span style="font-size:18px;color:#f2f2f2">{{$item_list }}</span>
                    </div>
                </div>
            </div>
        </div>

        @endforeach
    </div>

@endsection

@section('custom_js')
    <script>
        $(function(){
            load_date_time();
        });
        function load_date_time() {
            var date_now = new Date();
            var tgl = date_now.getDate();
            var bln = date_now.getMonth()+1;
            var thn = date_now.getFullYear();
            var jam = date_now.getHours();
            var mnt = date_now.getMinutes();
            var sec = date_now.getSeconds();

            tgl = tgl<=9?"0"+tgl:tgl;
            bln = bln<=9?"0"+bln:bln;
            jam = jam<=9?"0"+jam:jam;
            mnt = mnt<=9?"0"+mnt:mnt;
            sec = sec<=9?"0"+sec:sec;
            

            $("#load_tgl").text(tgl+"-"+bln+"-"+thn);
            $("#load_jam").text(jam+":"+mnt+":"+sec);
        }
        setInterval(function() { 
            load_date_time();
        }, 500);
    </script>
@endsection