@extends("layout")
@section("title", "Produk")

@section('header')
<div class="row">
    <div id="paper-top">
        <div class="col-sm-10"><h2 class="tittle-content-header"><i class="icon-media-record"></i> <span>Produk</span></h2></div>
        @if(auth()->user()->level!="pimpinan")
        <div class="col-sm-2">
            <div class="btn-group btn-wigdet pull-right"><a href="{{asset('produk/tambah')}}" class="btn btn-info"><span class="entypo-plus-squared"></span> Tambah </a></div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="nest">
            <div class="title-alt"><h6>Detail Produk</h6></div>

            <div class="body-nest">
                <table class="table table-striped table-bordered" id="data_table">
                    <thead class="table_head">
                        <tr>
                            <th>No</th>
                            <th>Kode Produk</th>
                            <th>Nama Produk</th>
                            <th>Satuan</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            @if(auth()->user()->level!="pimpinan") <th>#</th> @endif
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
            "url"       : "{{ asset('produk') }}",
            "type"      : "get",
        },
        "columns": [
            { "data": "DT_RowIndex","width": "5%","sClass": "text-center","orderable": false, 'searchable':false },
            { "data": "kd_produk" },
            { "data": "nm_produk" },
            { "data": "satuan.satuan", "name":"satuan.satuan" },
            { "data": "kategori.kategori", "name":"kategori.kategori" },
            { "data": "harga" },
            @if(auth()->user()->level!="pimpinan") { "data": "act","width": "10%","sClass":"text-center","orderable": false, 'searchable':false } @endif
        ],
        "order": []
    });
});

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
                url		: "{{ asset('produk/delete') }}/"+id,
                data	: "_method=DELETE&_token="+tokenCSRF,
                beforeSend: function () { 
                    in_load();
                },
                success	:function(data) {
                    swal(''+data.status+'',''+data.messages+'',"success").then((value) => { window.location.href = "{{ asset('produk') }}"; });
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