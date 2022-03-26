@extends("layout")
@section("title", "Transaksi")

@section('header')
<div class="row">
    <div id="paper-top">
        <div class="col-sm-10"><h2 class="tittle-content-header"><i class="icon-media-record"></i> <span>Transaksi</span></h2></div>
        @if(auth()->user()->level!="pimpinan")
        <div class="col-sm-2">
            <div class="btn-group btn-wigdet pull-right"><a href="{{asset('transaksi/tambah')}}" class="btn btn-info"><span class="entypo-plus-squared"></span> Tambah </a></div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="nest">
            <div class="title-alt"><h6>Detail Transaksi</h6></div>

            <div class="body-nest">
                <table class="table table-striped table-bordered" id="data_table">
                    <thead class="table_head">
                        <tr>
                            <th>No</th>
                            <th>No Transaksi</th>
                            <th>Tanggal</th>
                            <th>Total Bayar</th>
                            <th>#</th>
                        </tr>
                    </thead>
                </table>
                
            </div>

        </div>
    </div>
</div>

@endsection


@section('custom_js')
<script>
$(document).ready(function() {	
    $('#data_table').DataTable({
        "serverSide": true,
        "ajax": {
            "url"       : "{{ asset('transaksi') }}",
            "type"      : "get",
        },
        "columns": [
            { "data": "DT_RowIndex","width": "5%","sClass": "text-center","orderable": false, 'searchable':false },
            { "data": "no_transaksi" },
            { "data": "tgl" },
            { "data": "total_bayar" },
            { "data": "act","width": "12%","sClass":"text-center","orderable": false, 'searchable':false }
        ],
        "order": []
    });
});

function show_data(id) {
    $.ajax({
        type	: "GET",
        dataType: "json",
        url		: "{{ asset('transaksi/show') }}/"+id,
        beforeSend: function () { 
            in_load();
        },
        success	:function(data) {

            console.log(data.data);
            var data = data.data;
            var transaksi_bantu = "";
            console.log(data);
            $.each(data.transaksi_bantu, function (key, item)  {
            var total_harga = item.qty*item.harga;
            transaksi_bantu = transaksi_bantu+"<tr>"+
                    "<td>"+item.produk.kd_produk+"</td>"+
                    "<td>"+item.produk.nm_produk+"</td>"+
                    "<td>"+item.produk.kategori.kategori+"</td>"+
                    "<td>"+item.produk.satuan.satuan+"</td>"+
                    "<td>"+item.qty+"</td>"+
                    "<td>Rp. "+conver_rupiah(item.harga)+"</td>"+
                    "<td>Rp. "+conver_rupiah(total_harga)+"</td>"+
                "</tr>";
            });


            $("#modal_title").text('Detail Transaksi');
            $("#modal_size").addClass("modal-lg");
            $("#modal_content").html("<form class='form-horizontal'>"+
                "<div class='row'>"+
                    "<div class='col-sm-6'>"+
                        "<div class='form-group'>"+
                            "<label class='col-sm-4 control-label'>No Transaksi</label>"+
                            "<div class='col-lg-8'><input type='text' class='form-control' value='"+data.no_transaksi+"' readonly></div>"+
                        "</div>"+
                        "<div class='form-group'>"+
                            "<label class='col-sm-4 control-label'>Pembeli</label>"+
                            "<div class='col-lg-8'><input type='text' class='form-control' value='"+data.pembeli+"' readonly></div>"+
                        "</div>"+
                    "</div>"+
                    "<div class='col-sm-6'>"+
                        "<div class='form-group'>"+
                            "<label class='col-sm-4 control-label'>Tanggal Transaksi</label>"+
                            "<div class='col-lg-8'><input type='text' class='form-control' value='"+data.tgl+"' readonly></div>"+
                        "</div>"+
                        "<div class='form-group'>"+
                            "<label class='col-sm-4 control-label'>Total Bayar</label>"+
                            "<div class='col-lg-8'><input type='text' class='form-control' value='Rp. "+conver_rupiah(data.total_bayar)+"' readonly></div>"+
                        "</div>"+
                    "</div>"+
                "</div>"+
            "</form>"+

            "<table class='table table-striped table-bordered'>"
                +"<thead class='table_head'>"
                    +"<tr class='bg-slate-800'>"
                        +"<th>Kode produk</th>"
                        +"<th>Nama produk</th>"
                        +"<th>Kategori</th>"
                        +"<th>Satuan</th>"
                        +"<th>QTY</th>"
                        +"<th>Harga</th>"
                        +"<th>Total Harga</th>"
                    +"</tr>"
            +"</thead>"
            +"<tbody>"+transaksi_bantu+"</tbody>"
            +"</table>");

            $("#modal_footer").hide();
            $("#modal_detail").modal('show');
            out_load();
        },
        error: function (error) {
            error_detail(error);
            out_load();
        }
    });
}

@if(auth()->user()->level!="pimpinan")
function delete_data(id) {
    swal({
        title 	: 'Konfirmasi Hapus!',
        text  	: "apakah anda yakin ingin menghapus data",
        icon 	: 'warning',
        buttons : {
            cancel: "Batal",
            confirm: "Ya, Hapus!",
        },
        dangerMode: true
    }).then((deleteFile) => {
        if (deleteFile) {
            $.ajax({
                type	: "POST",
                dataType: "json",
                url		: "{{ asset('transaksi/delete') }}/"+id,
                data	: "_method=DELETE&_token="+tokenCSRF,
                beforeSend: function () { 
                    in_load();
                },
                success	:function(data) {
                    swal(''+data.status+'',''+data.messages+'',"success").then((value) => { window.location.href = "{{ asset('transaksi') }}"; });
                    out_load();
                },
                error: function (error) {
                    error_detail(error);
                    out_load();
                }
            });
        }
    });
}
@endif
</script>
@endsection