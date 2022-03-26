@if(auth()->user()->level=="administrator")
    <ul class="topnav menu-left-nest">
        <li><a class="tooltip-tip" href="{{asset('/')}}" title="Dashboard"><i class="entypo-home"></i><span>Dashboard</span></a></li>
        <li>
            <a class="tooltip-tip" href="#" title="Data Pendukung"><i class="entypo-folder"></i><span>Data Pendukung</span></a>
            <ul>
                <li><a class="tooltip-tip2" href="{{asset('satuan')}}" title="Data Satuan"><i class="entypo-target"></i><span>Data Satuan</span></a></li>
                <li><a class="tooltip-tip2" href="{{asset('kategori')}}" title="Data Kategori"><i class="entypo-target"></i><span>Data Kategori</span></a></li>
            </ul>
        </li>
        
        
        <li><a class="tooltip-tip" href="{{asset('produk')}}" title="Produk"><i class="entypo-database"></i><span>Produk</span></a></li>
    </ul>

    <ul class="topnav menu-left-nest">
        <li><a class="tooltip-tip" href="{{asset('transaksi')}}" title="Transaksi"><i class="entypo-box"></i><span>Transaksi</span></a></li>
        <li><a class="tooltip-tip" href="{{asset('perbandingan')}}" title="Transaksi"><i class="entypo-chart-bar"></i><span>Perbandingan</span></a></li>
    </ul>

    <ul class="topnav menu-left-nest">
        <li><a class="tooltip-tip" href="{{asset('user')}}" title="Manajement User"><i class="entypo-user"></i><span>Manajement User</span></a></li>
    </ul>
@elseif(auth()->user()->level=="staff")

    <ul class="topnav menu-left-nest">
        <li><a class="tooltip-tip" href="{{asset('/')}}" title="Dashboard"><i class="entypo-home"></i><span>Dashboard</span></a></li>
        <li>
            <a class="tooltip-tip" href="#" title="Data Pendukung"><i class="entypo-folder"></i><span>Data Pendukung</span></a>
            <ul>
                <li><a class="tooltip-tip2" href="{{asset('satuan')}}" title="Data Satuan"><i class="entypo-target"></i><span>Data Satuan</span></a></li>
                <li><a class="tooltip-tip2" href="{{asset('kategori')}}" title="Data Kategori"><i class="entypo-target"></i><span>Data Kategori</span></a></li>
            </ul>
            <li><a class="tooltip-tip" href="{{asset('produk')}}" title="Produk"><i class="entypo-database"></i><span>Produk</span></a></li>

        </li>
    </ul>

    <ul class="topnav menu-left-nest">
        <li><a class="tooltip-tip" href="{{asset('transaksi')}}" title="Transaksi"><i class="entypo-box"></i><span>Transaksi</span></a></li>
    </ul>

@elseif(auth()->user()->level=="pimpinan")
    <ul class="topnav menu-left-nest">
        <li><a class="tooltip-tip" href="{{asset('/')}}" title="Dashboard"><i class="entypo-home"></i><span>Dashboard</span></a></li>
        
        <li><a class="tooltip-tip" href="{{asset('produk')}}" title="Produk"><i class="entypo-database"></i><span>Produk</span></a></li>
    </ul>

    <ul class="topnav menu-left-nest">
        <li><a class="tooltip-tip" href="{{asset('transaksi')}}" title="Transaksi"><i class="entypo-box"></i><span>Transaksi</span></a></li>
        <li><a class="tooltip-tip" href="{{asset('perbandingan')}}" title="Transaksi"><i class="entypo-chart-bar"></i><span>Perbandingan</span></a></li>
    </ul>

@endif