@extends("layout")
@section("title", "Produk")

@section('header')
<div class="row">
    <div id="paper-top">
        <div class="col-sm-10"><h2 class="tittle-content-header"><i class="icon-media-record"></i> <span>Produk</span></h2></div>
    </div>
</div>
@endsection

@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="nest">
            <div class="title-alt"><h6>Tambah Produk</h6></div>

            <div class="body-nest">

                <form class="form-horizontal" id="data_form" >
                    @csrf

                    <div class="form-group">
                        <label class="col-lg-2 col-sm-2 control-label">Kode Produk</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" name="kd_produk" id="kd_produk" placeholder="Kode Produk">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-2 col-sm-2 control-label">Nama Produk</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" name="nm_produk" id="nm_produk" placeholder="Nama Produk">
                        </div>
                    </div>

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
                        <label class="col-lg-2 col-sm-2 control-label">Satuan</label>
                        <div class="col-lg-4">
                            <select class="form-control" name="satuan_id" id="satuan_id">
                                <option value="">: : Pilih Satuan : :</option>
                                @foreach ($satuan as $item_satuan)
                                    <option value="{{$item_satuan->id}}">{{$item_satuan->satuan}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-2 col-sm-2 control-label">Harga</label>
                        <div class="col-lg-3">
                            <input type="text" class="form-control" name="harga" onkeyup="rupiah(this)" id="harga" placeholder="Harga">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <button type="submit" class="btn btn-info btn-sm"><span class="entypo-check"></span> Simpan</button>
                            <a href="{{asset('produk')}}" class="btn btn-danger btn-sm"><span class="entypo-cancel-circled"></span> Batal</a>
                        </div>
                    </div>

                </form>
                
            </div>

        </div>
    </div>
</div>

@endsection


@section('custom_js')
<script>
    
$('#data_form').on('submit', function(event) {
    event.preventDefault();
    idata = new FormData($('#data_form')[0]);

    $.ajax({
        type	: "POST",
        dataType: "json",
        url		: "{{asset('produk/create')}}",
        data	: idata,
        processData: false,
        contentType: false,
        cache 	: false,
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
});
</script>
@endsection