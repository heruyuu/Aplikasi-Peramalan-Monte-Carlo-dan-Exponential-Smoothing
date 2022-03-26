<nav role="navigation" class="navbar navbar-static-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button data-target="#bs-example-navbar-collapse-1" data-toggle="collapse" class="navbar-toggle" type="button"><span class="entypo-menu"></span></button>
            <div id="logo-mobile" class="visible-xs"><h1>Peramalan<span>Produk</span></h1></div>

        </div>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
			@csrf
        </form>
        <div id="bs-example-navbar-collapse-1" class="collapse navbar-collapse">

            <ul style="margin-right:0;" class="nav navbar-nav navbar-right">
                <li>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                       {{auth()->user()->nm_lengkap}} <b class="caret"></b>
                    </a>
                    <ul style="margin-top:14px;" role="menu" class="dropdown-setting dropdown-menu">
                        <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();"><span class="entypo-logout"></span>&#160;&#160;Logout</a></li>
                    </ul>
                </li>
            </ul>

        </div>
    </div>
</nav>