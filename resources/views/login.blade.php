<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>

    <link rel="stylesheet" href="{{asset('plugins/bootstrap/css/bootstrap.css')}}">
	<link rel="stylesheet" href="{{asset('assets/css/loading.css')}}">
	<link rel="stylesheet" href="{{asset('assets/css/login.css')}}">

    <script type="text/javascript" src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/sweet_alert.min.js')}}"></script>

    <script>
        $(document).ready(function() {
			$('.preload-wrapper').show();
		});

        function error_detail(error) {
            console.log(error);
            if(error.responseJSON.status=="warning") {
                swal('Warning',''+error.responseJSON.messages+'','warning');
                return false;

            } else if(error.responseJSON.status=="error") {
                swal('Error',''+error.responseJSON.messages+'','error');
                return false;
            } else {
                swal(''+error.status+'',''+error.responseJSON.message+'','error');
                return false;
            }
        }
    </script>
</head>
<body>
    <div class="preload-wrapper">
        <div id="preloader_1">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>

    <div class="container">
        <div class="" id="login-wrapper">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div id="logo-login"><h1>Peramalan<span>Produk</span></h1></div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="account-box">
                        <form id="data_form">
                            @csrf
                            <div class="form-group">
                                <label >Username</label>
                                <input type="text" name="username" id="username" class="form-control" placeholder="Username">
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary" type="submit">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <script type="text/javascript">
        setInterval(function() { 
            $('.preload-wrapper').hide();
        }, 500);

        $('#data_form').on('submit', function(event) {
            event.preventDefault();
            idata = new FormData($('#data_form')[0]);

            $.ajax({
                type	: "POST",
                dataType: "json",
                url		: "{{asset('login')}}",
                data	: idata,
                processData: false,
                contentType: false,
                cache 	: false,
                beforeSend: function () {
                    $('.preload-wrapper').show();
                },
                success	:function(data) {
                    window.location.href = "{{ asset('/') }}";
                    $('.preload-wrapper').hide();
                },
                error: function (error) {
                    error_detail(error);
                    $('.preload-wrapper').hide();
                }
            });
        });
    </script>

</body>
</html>