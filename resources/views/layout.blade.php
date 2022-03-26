<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>@yield('title', ' Ramalan Produk ')</title>

    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/entypo-icon.css')}}">
    <link rel="stylesheet" href="{{asset('assets/js/skin-select/skin-select.css')}}">
    <link rel="stylesheet" href="{{asset('assets/js/slidebars/slidebars.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/bootstrap/css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatable/css/dataTables.bootstrap.css')}}">

	<link rel="stylesheet" href="{{asset('assets/css/loading.css')}}">
	<link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">
    
    <link rel="stylesheet" href="{{asset('plugins/datepicker/bootstrap-datepicker3.css')}}">
    
	<link rel="stylesheet" href="{{asset('assets/css/colors.css')}}">

    <script type="text/javascript" src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('plugins/bootstrap/js/bootstrap.js')}}"></script>
    <script type="text/javascript">
		var tokenCSRF   = jQuery('meta[name="csrf-token"]').attr('content');
        var url_link    = "{{ asset('/') }}";
		$(document).ready(function() {
			$('.preload-wrapper').show();
		});
    </script>

    <style>
        .paper-wrap {
            min-height: 700px !important;
        }
        body {
            color: #656565 !important;
        }
        #logo h1, #logo-mobile h1 {
            font-size: 20px;
            padding-top: 10px;
        }
    </style>

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


    @include('layout.navbar')
    @include('layout.sidebar')

    <div class="wrap-fluid">
        <div class="container-fluid paper-wrap bevel tlbr">


            @yield('header')

            <div class="content-wrap">@yield('content')</div>

            <div class="footer-space"></div>
                <div id="footer">
                    <div class="devider-footer-left"></div>
                    <div class="time">
                        <p id="spanDate"></p>
                        <p id="clock"></p>
                    </div>
                    <div class="copyright">STMIK AKBA
                        &nbsp; 2021 <a href="#">Skripsi</a> Peramalan Produk</div>
                    <div class="devider-footer"></div>

                </div>
            </div>

        </div>
    </div>


    <div class='modal fade in' id='modal_detail' data-keyboard="false" data-backdrop="static" tabindex='0' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
		<div class='modal-dialog' id="modal_size">
			<div class='modal-content'>
				<div class="modal-header">
					<h5 class="modal-title" id="modal_title"></h5>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class='modal-body' id="modal_content"></div>
				<div class='modal-footer' id="modal_footer"></div>
			</div>
		</div>
    </div>
    
    
    <script type="text/javascript" src="{{asset('plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('plugins/datatable/js/dataTables.bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('plugins/datepicker/bootstrap-datepicker.min.js')}}"></script>
    
    <script type="text/javascript" src="{{asset('assets/js/skin-select/skin-select.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/slidebars/slidebars.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/custom/scriptbreaker-multiple-accordion-1.js')}}"></script>
    
    <script type="text/javascript" src="{{asset('assets/js/main.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/sweet_alert.min.js')}}"></script>

    @yield('custom_js',' ')
    <script type="text/javascript">
        setInterval(function() { 
            $('.preload-wrapper').hide();
        }, 500);

        $(".topnav").accordionze({
            accordionze: true,
            speed: 500,
            closedSign: '<img src="{{asset('assets/img/plus.png')}}">',
            openedSign: '<img src="{{asset('assets/img/minus.png')}}">'
        });
    </script>
</body>
</html>