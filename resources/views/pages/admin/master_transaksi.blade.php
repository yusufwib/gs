@extends('layout.index', ['title' => 'Transaksi', 'heading' => 'Transaksi'])

@section('contents')
<div class="box">
    <div class="box-head">
        <h4>Daftar Transaksi</h4>
    </div>
    <div class="box-body">
        {{-- TABS --}}
        <ul class="nav nav-tabs mb-10">
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" data-page="all">Semua</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" data-page="unpaid">Belum Dibayar</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" data-page="waiting">Menunggu Konfirmasi</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" data-page="done">Lunas</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" data-page="cancelled">Batal</a></li>
        </ul>
        <table class="table table-bordered data-table" id="table-transaksi">
                <thead>
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Tanggal & Waktu</th>
                        <th>Nama</th>
                        <th>Nama Lomba</th>
                        <th>Kelas</th>
                        <th>Nominal</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
            </table>
    </div>
</div>
@endsection

@section('modal')
    <!-- Modal -->
    <div class="modal fade" id="modalKonfir" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Detail Pesanan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-15">
                        <div class="col-6">
                            <div class="text-body-light">ID Pesanan</div>
                            <div class="text-heading" id="id-pesanan">-</div>
                        </div>
                        <div class="col-6">
                            <div class="text-body-light">Tanggal</div>
                            <div class="text-heading" id="tanggal">-</div>
                        </div>
                    </div>
                    <div class="row mb-15">
                        <div class="col-12">
                            <div class="text-body-light">Nama</div>
                            <div class="text-heading" id="nama">-</div>
                        </div>
                    </div>
                    <div class="row mb-15">
                        <div class="col-12">
                            <div class="text-body-light">Nama Lomba</div>
                            <div class="text-heading" id="nama-lomba">-</div>
                        </div>
                    </div>
                    <div class="row mb-15">
                        <div class="col-6">
                            <div class="text-body-light">Kelas</div>
                            <div class="text-heading" id="kelas">-</div>
                        </div>
                        <div class="col-6">
                            <div class="text-body-light">Jenis Burung</div>
                            <div class="text-heading" id="jenis-burung">-</div>
                        </div>
                    </div>
                    <div class="row mb-15">
                        <div class="col-6">
                            <div class="text-body-light">Nominal Pembayaran</div>
                            <div class="text-heading" id="nominal">-</div>
                        </div>
                        <div class="col-6">
                            <div class="text-body-light">Status</div>
                            <div class="text-heading" id="status">-</div>
                        </div>
                    </div>
                    <div class="row mb-15">
                        <div class="col-12">
                            <div class="text-body-light">Bank Tujuan</div>
                            <div class="text-heading" id="bank-detail">-</div>
                        </div>
                    </div>
                    <div class="bukti">
                        <div class="text-body-light">Bukti Pembayaran</div>
                        <div class="img-wrap" id="bukti-pembayaran">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="id-transaksi">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="cancel-payment" class="btn button-secondary">Tolak</button>
                    <button type="button" id="done-payment" class="btn btn-primary">Konfirmasi</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script src="{{ asset('assets/js/plugins/datatables/datatables.min.js')}}"></script>
<script src="{{ asset('assets/js/plugins/datatables/datatables.active.js')}}"></script>
<script>
    dataListTrasaksiAll = () => {
        let data;

        let base_url = window.location.origin;
        let enpoint = base_url+'/api/v1/transaction/list-payment';

        $.ajax({
            type: "POST",
            url: enpoint,
            data : {
                'status' : 'all'
            },
            async: false,
            dataType : 'json',
            success: function (response) {
                data = response.data;
            }
        });
        return data;
    }

    dataListTrasaksiUnpaid = () => {
        let data;

        let base_url = window.location.origin;
        let enpoint = base_url+'/api/v1/transaction/list-payment';

        $.ajax({
            type: "POST",
            url: enpoint,
            data : {
                'status' : 'unpaid'
            },
            async: false,
            dataType : 'json',
            success: function (response) {
                data = response.data;
            }
        });
        return data;
    }

    dataListTrasaksiWaiting= () => {
        let data;

        let base_url = window.location.origin;
        let enpoint = base_url+'/api/v1/transaction/list-payment';

        $.ajax({
            type: "POST",
            url: enpoint,
            data : {
                'status' : 'waiting'
            },
            async: false,
            dataType : 'json',
            success: function (response) {
                data = response.data;
            }
        });
        return data;
    }

    dataListTrasaksiDone = () => {
        let data;

        let base_url = window.location.origin;
        let enpoint = base_url+'/api/v1/transaction/list-payment';

        $.ajax({
            type: "POST",
            url: enpoint,
            data : {
                'status' : 'done'
            },
            async: false,
            dataType : 'json',
            success: function (response) {
                data = response.data;
            }
        });
        return data;
    }

    dataListTrasaksiCancelled = () => {
        let data;

        let base_url = window.location.origin;
        let enpoint = base_url+'/api/v1/transaction/list-payment';

        $.ajax({
            type: "POST",
            url: enpoint,
            data : {
                'status' : 'cancelled'
            },
            async: false,
            dataType : 'json',
            success: function (response) {
                data = response.data;
            }
        });
        return data;
    }
</script>
<script>
    // MAIN JS
    $(document).ready(function () {
        var dataTransaksi = dataListTrasaksiAll();

        var table = $('#table-transaksi').DataTable({
                data : dataTransaksi,
                columns : [
                    {
                        data : "id_ticket",
                        render : function (data) {
                            return '#'+data;
                        }
                    },
                    {
                        data : "created_at",
                        render : function (data) {
                                moment.locale('id');

                                let dateParse = moment(data).utc().format('DD MMM YYYY - hh:mm');

                                return dateParse;
                            }
                    },
                    {
                        data : "buyers_name"
                    },
                    {
                        data : "contest_name"
                    },
                    {
                        data : "criteria_name"
                    },
                    {
                        data : "price",
                        render : function (data){
                            let rupiah = new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR" }).format(data);
                            return rupiah;
                        }
                    },
                    {
                        className: "text-center",
                        data : "status",
                        render : function (data){
                            let domStatus;

                            if (data === 'done') {
                                domStatus = '<div class="badge badge-outline badge-success">Lunas</div>'
                            }
                            else if(data === 'waiting'){
                                domStatus = '<div class="badge badge-outline badge-secondary">Menunggu Konfirmasi</div>'
                            }
                            else if(data === 'unpaid'){
                                domStatus = '<div class="badge badge-outline badge-warning">Belum Dibayar</div>'
                            }
                            else {
                                domStatus = '<div class="badge badge-outline badge-dark">Batal</div>'
                            }
                            return domStatus;
                        }
                    },
                    {
                        className: "text-center",
                        data : "id",
                        render : function (data, type, row, meta){
                            return `
                                    <a href="#modalKonfir"
                                        data-toggle="modal"
                                        data-id="${data}"
                                        data-ticket="${row.id_ticket}"
                                        data-tanggal="${row.created_at}"
                                        data-nama="${row.buyers_name}"
                                        data-nama_lomba="${row.contest_name}"
                                        data-kelas="${row.criteria_name}"
                                        data-jenis_burung="${row.bird_name}"
                                        data-nominal="${row.price}"
                                        data-status="${row.status}"
                                        data-bukti="${row.payment_image}"
                                        data-bank='${JSON.stringify(row.bank)}'
                                        class="open-modalKonfir button bg-blue text-white button-sm button-box">
                                        <i class="zmdi zmdi-eye"></i> </a>

                                    <a href="https://api.whatsapp.com/send?phone=${row.contestant_number != null ? row.contestant_number : "+62"}"
                                        target="_blank"><button data-tippy-content="Whatsapp"
                                        class="button button-box button-sm button-whatsapp"><i
                                        class="zmdi zmdi-whatsapp"></i><span></span></button></a>
                                `
                        }
                    }
                ],
                responsive: true,
                paging: true,
                language: {
                    paginate: {
                        previous: '<i class="zmdi zmdi-chevron-left"></i>',
                        next: '<i class="zmdi zmdi-chevron-right"></i>'
                    }
                },
        });

        // Function UPDATE TABLE
        updateDataTable = (data) => {
            table.clear().draw();
            table.rows.add(data);
            table.columns.adjust().draw();
        }

        //STATUS JS
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            let comp = e.target.getAttribute('data-page')

            if (comp == "all") {
                loadingTrue();
                let data = dataListTrasaksiAll();
                updateDataTable(data);
                loadingFalse();
            }

            if (comp == "waiting") {
                loadingTrue();
                let data = dataListTrasaksiWaiting();
                updateDataTable(data);
                loadingFalse();
            }

            if (comp == "unpaid") {
                loadingTrue();
                let data = dataListTrasaksiUnpaid();
                updateDataTable(data);
                loadingFalse();
            }

            if (comp == "cancelled") {
                loadingTrue();
                let data = dataListTrasaksiCancelled();
                updateDataTable(data);
                loadingFalse();
            }

            if (comp == "done") {
                loadingTrue();
                let data = dataListTrasaksiDone();
                updateDataTable(data);
                loadingFalse();
            }
        });

        loadingFalse();
    });

    // JS MODAL
    $(document).on("click", ".open-modalKonfir", function () {
        let base_url = window.location.origin;

        var id = $(this).data('id');
        var ticket = $(this).data('ticket');
        var tanggal = $(this).data('tanggal');
        var nama = $(this).data('nama');
        var nama_lomba = $(this).data('nama_lomba');
        var kelas = $(this).data('kelas');
        var jenis_burung = $(this).data('jenis_burung');
        var nominal = $(this).data('nominal');
        var status = $(this).data('status');
        var bukti = $(this).data('bukti');
        var bank = $(this).data('bank');


        let dateParse = moment(tanggal).format('DD MMM YYYY - hh:mm');
        let rupiah = new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR" }).format(nominal);
        let domStatus;
        let domImg;
        let domBank;

        if (status === 'done') {
            domStatus = '<div class="badge badge-outline badge-success">Lunas</div>'
        }
        else if(status === 'waiting'){
            domStatus = '<div class="badge badge-outline badge-secondary">Menunggu Konfirmasi</div>'
        }
        else if(status === 'unpaid'){
            domStatus = '<div class="badge badge-outline badge-warning">Belum Dibayar</div>'
        }
        else {
            domStatus = '<div class="badge badge-outline badge-dark">Batal</div>'
        }

        if (status != 'waiting') {
            $(".modal-footer").css("display","none");
        }

        if (bukti != null) {
            domImg = `<img src="${base_url+bukti}" alt="">`
        }

        console.log(bank)
        if (!$.isEmptyObject(bank)) {
            domBank = `${bank.name} - ${bank.owner_name}`
        }else{
            domBank = `Pembelian melalui admin`
        }

        $(".modal-footer #id-transaksi").val(id);

        $(".modal-body #id-pesanan").html('#'+ticket);
        $(".modal-body #tanggal").html(dateParse);
        $(".modal-body #nama").html(nama);
        $(".modal-body #nama-lomba").html(nama_lomba);
        $(".modal-body #kelas").html(kelas);
        $(".modal-body #jenis-burung").html(jenis_burung);
        $(".modal-body #nominal").html(rupiah);
        $(".modal-body #status").html(domStatus);
        $(".modal-body #bukti-pembayaran").html(domImg);
        $(".modal-body #bank-detail").html(domBank);
    });

    // ACTION MODAL JS
    updateStatus = (id,status,type) => {

        let title,text;

        if (type === 0) {
            title = 'Berhasil Kornfirmasi!';
            text = 'Pembayaran telah dikonfirmasi.'
        }else{
            title = 'Berhasil Menolak Pembayaran!';
            text = 'Pembayaran telah ditolak.'
        }

        $.ajax({
            type: "POST",
            url: base_url+'/api/v1/transaction/confirm-payment',
            data: {
                status : status,
                id_transaction : id
            },
            success: function (response) {
                swal({
                    title: title,
                    text: text,
                    icon: "success",
                }).then(() => {
                    window.location.reload();
                })
            },
            error: function (err) {
                swal({
                    title: 'Gagal mengibah status pembayaran',
                    icon: "danger",
                })
            }
        });
    }

    $(document).on("click", "#cancel-payment", function () {
        swal({
            title: "Yakin Menolak Pembayaran ?",
            text: "Status ditolak tidak dapat dikembalikan lagi!",
            icon: "warning",
            buttons: true,
        })
        .then((e) => {
            if (e) {
                let id = $("#id-transaksi").val();
                updateStatus(id,'cancelled',1)
            }
        });
    });

    $(document).on("click", "#done-payment", function () {
        swal({
            title: "Yakin Konfirmasi Pembayaran ?",
            text: "Status konfirmasi tidak dapat dikembalikan lagi!",
            icon: "warning",
            buttons: true,
        })
        .then((e) => {
            if (e) {
                let id = $("#id-transaksi").val();
                updateStatus(id,'done',0)
            }
        });
    });

    $(document).on('hidden.bs.modal', '#bukti-pembayaran', function () {
        $(".modal-body #bukti-pembayaran").html('');
    });
</script>
@endsection
