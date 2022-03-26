@extends("layout")
@section("title", "Customer")

@section('header')
<div class="row">
    <div id="paper-top">
        <div class="col-sm-10"><h2 class="tittle-content-header"><i class="icon-media-record"></i> <span>Customer</span></h2></div>
    </div>
</div>
@endsection

@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="nest">
            <div class="title-alt"><h6>Ubah Customer</h6></div>

            <div class="body-nest">

                <form class="form-horizontal" id="data_form">
                    @csrf
                    @method("PUT")

                    <div class="form-group">
                        <label class="col-lg-2 col-sm-2 control-label">Nama Customer</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" name="nm_customer" id="nm_customer" value="{{$data->nm_customer}}" placeholder="Nama Customer">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-2 col-sm-2 control-label">Pemilik</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" name="pemilik" id="pemilik" value="{{$data->pemilik}}" placeholder="Pemilik">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-2 col-sm-2 control-label">Contact Person</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" name="cp" id="cp"  value="{{$data->cp}}" placeholder="Contact Person">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-2 col-sm-2 control-label">Alamat</label>
                        <div class="col-lg-4">
                            <textarea class="form-control" name="alamat" id="alamat" placeholder="Alamat" rows="3">{{$data->alamat}}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <button type="submit" class="btn btn-info btn-sm"><span class="entypo-check"></span> Simpan</button>
                            <a href="{{asset('customer')}}" class="btn btn-danger btn-sm"><span class="entypo-cancel-circled"></span> Batal</a>
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
        url		: "{{asset('customer/update/'.$data->id)}}",
        data	: idata,
        processData: false,
        contentType: false,
        cache 	: false,
        beforeSend: function () { 
            in_load();
        },
        success	:function(data) {
            swal(''+data.status+'',''+data.messages+'',"success").then((value) => { window.location.href = "{{ asset('customer') }}"; });
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