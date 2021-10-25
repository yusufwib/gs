        <!-- Side Header Start -->
        <div class="side-header show">
            <button class="side-header-close"><i class="zmdi zmdi-close"></i></button>
            <!-- Side Header Inner Start -->
            <div class="side-header-inner custom-scroll">

                <nav class="side-header-menu" id="side-header-menu">
                    <ul>
                        <li class="{{ (request()->is('dashboard')) ? 'active' : '' }}"><a href="/dashboard"><i class="ti-layout-grid2-alt"></i><span>Dashboard</span></a>
                        </li>
                        <li class="{{ (request()->is('transaksi')) ? 'active' : '' }}"><a href="/transaksi"><i class="ti-ticket"></i><span>Transaksi</span></a>
                        </li>
                        <li class="has-sub-menu {{ (request()->is('lomba*')) ? 'active' : '' }}"><a href="#"><i class="ti-calendar"></i> <span>Lomba</span></a>
                            <ul class="side-header-sub-menu">
                                <li class="{{ (request()->is('lomba/lomba-mendatang')) ? 'active' : '' }}"><a href="/lomba/lomba-mendatang"><span>Mendatang</span></a></li>
                                <li class="{{ (request()->is('lomba/lomba-berlalu')) ? 'active' : '' }}"><a href="/lomba/lomba-berlalu"><span>Berlalu</span></a></li>
                                <li class="{{ (request()->is('lomba/draft-lomba')) ? 'active' : '' }}"><a href="/lomba/draft-lomba"><span>Draft Lomba</span></a></li>
                                <li class="{{ (request()->is('lomba/tambah-lomba')) ? 'active' : '' }}"><a href="/lomba/tambah-lomba"><span>Tambah Lomba</span></a></li>
                            </ul>
                        </li>
                        <li class="has-sub-menu {{ (request()->is('akun*')) ? 'active' : '' }}"><a href="#"><i class="fa fa-users"></i> <span>Akun</span></a>
                            <ul class="side-header-sub-menu">
                                <li class="{{ (request()->is('akun/akun-peserta')) ? 'active' : '' }}"><a href="/akun/akun-peserta"><span>Peserta</span></a></li>
                                <li class="{{ (request()->is('akun/akun-juri')) ? 'active' : '' }}"><a href="/akun/akun-juri"><span>Juri</span></a></li>
                            </ul>
                        </li>
                        <li class="has-sub-menu {{ (request()->is('laporan*')) ? 'active' : '' }}"><a href="#"><i class="ti-exchange-vertical"></i> <span>Laporan</span></a>
                            <ul class="side-header-sub-menu">
                                <li class="{{ (request()->is('laporan/laporan-pemasukan')) ? 'active' : '' }}"><a href="/laporan/laporan-pemasukan"><span>Pemasukan</span></a></li>
                                <li class="{{ (request()->is('laporan/laporan-pengeluaran')) ? 'active' : '' }}"><a href="/laporan/laporan-pengeluaran"><span>Pengeluaran</span></a></li>
                            </ul>
                        </li>
                        <li class="{{ (request()->is('analyzer')) ? 'active' : '' }}"><a href="/analyzer"><i class="ti-bar-chart"></i><span>Analyzer</span></a>
                        </li>
                        <li class="has-sub-menu {{ (request()->is('pengaturan*')) ? 'active' : '' }}"><a href="#"><i class="ti-settings"></i> <span>Pengaturan</span></a>
                            <ul class="side-header-sub-menu">
                                <li class="{{ (request()->is('pengaturan/rekening')) ? 'active' : '' }}" ><a href="/pengaturan/rekening"><span>Rekening</span></a></li>
                                <li class="{{ (request()->is('pengaturan/admin')) ? 'active' : '' }}"><a href="/pengaturan/admin"><span>Admin</span></a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>

            </div><!-- Side Header Inner End -->
        </div><!-- Side Header End -->
