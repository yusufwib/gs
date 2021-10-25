<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// AUTH
Route::get('/', function () {
    return view('pages.auth.login');
});

Route::get('/login', function () {
    return view('pages.auth.login');
});

Route::get('/lupa-password', function () {
    return view('pages.auth.lupa_password');
});

Route::get('/password-baru', function () {
    return view('pages.auth.password_baru');
});

// DASHBOARD
Route::get('/dashboard', function () {
    return view('pages.admin.dashboard');
});

// TRANSAKSI
Route::get('/transaksi', function () {
    return view('pages.admin.master_transaksi');
});

// LOMBA
Route::group(['prefix' => 'lomba'],function () {
    Route::get('/lomba-mendatang', function () {
        return view('pages.admin.lomba_mendatang');
    });

    Route::get('/lomba-berlalu', function () {
        return view('pages.admin.lomba_berlalu');
    });

    Route::get('/draft-lomba', function () {
        return view('pages.admin.draft_lomba');
    });

    Route::get('/detail/list-peserta/{id}', function () {
        return view('pages.admin.list_peserta');
    });

    Route::get('/detail/{id}', function () {
        return view('pages.admin.detail_lomba_mendatang');
    });

    Route::get('/tambah-jadwal/{id}', function () {
        return view('pages.admin.tambah_jadwal');
    });

    Route::get('/detail-sesi-penjurian/{id}', function () {
        return view('pages.admin.detail_penjurian');
    });

    Route::get('/detail-penilaian-juri/{id}', function () {
        return view('pages.admin.paper_juri');
    });

    Route::get('/tambah-lomba', function () {
        return view('pages.admin.tambah_lomba');
    });

    Route::get('/tambah-lomba/{id}', function () {
        return view('pages.admin.detail_draft');
    });

    Route::get('/edit-lomba/{id}', function () {
        return view('pages.admin.edit_lomba');
    });

    Route::get('/template-nomor-peserta', function () {
        return view('pages.admin.master_template_nomor');
    });

    Route::get('/atur-block/{id}', function () {
        return view('pages.admin.atur_block');
    });

    Route::get('/edit-juri/{id}', function () {
        return view('pages.admin.sub_pages.edit_juri');
    });

    Route::get('/edit-penyelenggara/{id}', function () {
        return view('pages.admin.sub_pages.edit_organizer');
    });
});


// CASHFLOW
Route::group(['prefix' => 'laporan'], function (){
    Route::get('/laporan-pemasukan', function () {
        return view('pages.admin.laporan_pemasukan');
    });

    Route::get('/laporan-pengeluaran', function () {
        return view('pages.admin.laporan_pengeluaran');
    });
});

// AKUN
Route::group(['prefix' => 'akun'], function (){
    Route::get('/akun-peserta', function () {
        return view('pages.admin.master_akun_peserta');
    });

    Route::get('/akun-peserta/detail/{id}', function () {
        return view('pages.admin.detail_peserta');
    });

    Route::get('/akun-juri', function () {
        return view('pages.admin.master_akun_juri');
    });

    Route::get('/atur-member', function () {
        return view('pages.admin.master_member');
    });
});


// ANALIZER
Route::get('/analyzer', function () {
    return view('pages.admin.analyzer');
});

// PENGARURAN
Route::get('/pengaturan/rekening', function () {
    return view('pages.admin.master_rekening');
});

Route::get('/pengaturan/admin', function () {
    return view('pages.admin.master_admin');
});

Route::get('/privacy-policy', function () {
    return view('pages.admin.privacy_policy');
});