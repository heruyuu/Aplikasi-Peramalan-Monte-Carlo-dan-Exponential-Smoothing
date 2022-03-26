@extends("layout")
@section("title", "Transaksi")

@section('header')
<div class="row">
    <div id="paper-top">
        <div class="col-sm-10"><h2 class="tittle-content-header"><i class="icon-media-record"></i> <span>Transaksi</span></h2></div>
    </div>
</div>
@endsection

@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="nest">
            <div class="title-alt"><h6>Tambah Transaksi</h6></div>

            <div class="body-nest">

                <form class="form-horizontal" id="data_form">
                    @csrf

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-lg-4 col-sm-4 control-label">No Transaksi</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="no_transaksi" id="no_transaksi" placeholder="No Transaksi">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-4 col-sm-4 control-label">Pembeli</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="pembeli" id="pembeli" placeholder="Pembeli">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-lg-4 col-sm-4 control-label">Tanggal Transaksi</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control datepicker" name="tgl" id="tgl" placeholder="Tanggal Transaksi" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-4 col-sm-4 control-label">Total Bayar</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="total_bayar" id="total_bayar" value="0" placeholder="Total Bayar" readonly>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-lg-12">
                            <button class="btn btn-success" onclick="add_produk()" type="button"><span class='entypo-search'></span>&nbsp; Cari Produk</button>
                        </div>
                    </div>
                    <table class="table table_input table-striped table-bordered" id="table_produk">
                        <thead class="table_head">
                            <tr>
                                <th width='1px'>#</th>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Satuan</th>
                                <th width='100px'>Qty</th>
                                <th>Harga</th>
                                <th>Total</th>
                            </tr>
                       </thead>
                       <tbody id="data_produk">
                           <tr><td colspan="8"><div class='td_null'>Data Belum Ada</div></td></tr>
                       </tbody>
                    </table>


                    <div class="form-group">
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-info btn-sm"><span class="entypo-check"></span> Simpan</button>
                            <a href="{{asset('transaksi')}}" class="btn btn-danger btn-sm"><span class="entypo-cancel-circled"></span> Batal</a>
                        </div>
                    </div>

                </form>
                
            </div>

        </div>
    </div>
</div>

@endsection


@section('custom_js')
@include('pages.transaksi.script')

<script>
$(function() {
    datepicker();
});
$('#data_form').on('submit', function(event) {
    event.preventDefault();
    idata = new FormData($('#data_form')[0]);

    $.ajax({
        type	: "POST",
        dataType: "json",
        url		: "{{asset('transaksi/create')}}",
        data	: idata,
        processData: false,
        contentType: false,
        cache 	: false,
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
});
</script>
@endsection