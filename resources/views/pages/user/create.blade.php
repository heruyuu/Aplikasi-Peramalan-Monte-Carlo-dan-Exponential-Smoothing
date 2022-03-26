@extends("layout")
@section("title", "User")

@section('header')
<div class="row">
    <div id="paper-top">
        <div class="col-sm-10"><h2 class="tittle-content-header"><i class="icon-media-record"></i> <span>Manajement User</span></h2></div>
    </div>
</div>
@endsection

@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="nest">
            <div class="title-alt"><h6>Tambah User</h6></div>

            <div class="body-nest">

                <form class="form-horizontal" id="data_form">
                    @csrf

                    <div class="form-group">
                        <label class="col-lg-2 col-sm-2 control-label">Nama Lengkap</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" name="nm_lengkap" id="nm_lengkap" placeholder="Nama Lengkap">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-2 col-sm-2 control-label">Username</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" name="username" id="username" placeholder="Username">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-2 col-sm-2 control-label">Password</label>
                        <div class="col-lg-4">
                            <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-2 col-sm-2 control-label">Level</label>
                        <div class="col-lg-4">
                            <select class="form-control" name="level" id="level">
                                <option value="">: : Pilih Level : :</option>
                                <option value="staff">Staff</option>
                                <option value="administrator">Administrator</option>
                                <option value="pimpinan">Pimpinan</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <button type="submit" class="btn btn-info btn-sm"><span class="entypo-check"></span> Simpan</button>
                            <a href="{{asset('user')}}" class="btn btn-danger btn-sm"><span class="entypo-cancel-circled"></span> Batal</a>
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
        url		: "{{asset('user/create')}}",
        data	: idata,
        processData: false,
        contentType: false,
        cache 	: false,
        beforeSend: function () { 
            in_load();
        },
        success	:function(data) {
            swal(''+data.status+'',''+data.messages+'',"success").then((value) => { window.location.href = "{{ asset('user') }}"; });
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