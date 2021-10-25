@extends('layout.index', ['title' => 'Master Member', 'heading' => 'Detail Peserta', 'prev' => 'Peserta', 'link' => '/akun/akun-peserta'])

@section('css')
    <style>
        .select2-container{
            z-index:100000;
        }
    </style>
@endsection

@section('action-button')
<a href="#modalAdd" data-toggle="modal" class="button button-outline button-primary" id="ubah_member">Ubah Member</a>
@endsection

@section('contents')
<div class="box mb-25">
    <div class="box-head">
        <h4>Informasi Peserta</h4>
    </div>
    <div class="box-body">
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="text-body-light">Nama Lengkap</div>
                <div class="text-heading" id="nama">-</div>
            </div>
            <div class="col-md-4">
                <div class="text-body-light">Terdaftar Pada</div>
                <div class="text-heading" id="created_at">-</div>
            </div>
            <div class="col-md-4">
                <div class="text-body-light">Role</div>
                <div class="text-heading" id="role">-</div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="text-body-light">Email</div>
                <div class="text-heading" id="email">-</div>
            </div>
            <div class="col-md-4">
                <div class="text-body-light">Telepon</div>
                <div class="text-heading" id="telp">-</div>
            </div>
        </div>
    </div>
</div>
<div class="box">
    <div class="box-head">
        <h4>Riwayat Lomba</h4>
    </div>
    <div class="box-body">
        <table class="table table-bordered data-table text-center" id="data-table-riwayat">
                <thead>
                    <tr>
                        <th>Nama Lomba</th>
                        <th>Jadwal Lomba</th>
                        <th>Pukul</th>
                        <th>Kota</th>
                        <th>Kelas</th>
                        <th>Jenis Burung</th>
                    </tr>
                </thead>
            </table>
    </div>
</div>
@endsection

@section('modal')
    <!-- Modal Edit Role -->
    <div class="modal fade" id="modalEditMember" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Role</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editMember">
                    <div class="form-group mb-3">
                        <label for="">Nama Peserta</label>
                        <div id="nama-peserta">-</div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Member <span class="text-danger">*</span></label>
                        <select class="form-control select-role" name="role_id" id="dd-edit-role" required>
                            <option value=""></option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" id="id_user">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script src="{{ asset('assets/js/plugins/datatables/datatables.min.js')}}"></script>
<script src="{{ asset('assets/js/plugins/datatables/datatables.active.js')}}"></script>

<script>
    var segment_str = window.location.pathname;
    var segment_array = segment_str.split( '/' );
    var last_segment = segment_array.pop();

    const id_user = atob(last_segment);

    dataPeserta = () => {
        let data;

        let base_url = window.location.origin;
        let enpoint = base_url+'/api/v1/account/history-contest-by-id-user';

        $.ajax({
            type: "POST",
            url: enpoint,
            data : {
                'id_user' : id_user
            },
            async: false,
            dataType : 'json',
            success: function (response) {
                data = response.data;
            }
        });
        return data;
    }

    dataRole = () => {
        let data;
        let masterRole = [];

        let base_url = window.location.origin;
        let enpoint = base_url+'/api/v1/role/get';

        $.ajax({
            type: "POST",
            url: enpoint,
            async: false,
            dataType : 'json',
            success: function (response) {
                data = response.data;
                data.map((val,index) => {
                    let role ={
                        id : val.id,
                        text : val.name
                    }
                    masterRole.push(role);
                })
            }
        });
        return masterRole;
    }
</script>
<script>
var dataPeserta = dataPeserta();
var dataRole = dataRole();

$(document).ready(function () {
    $('.select-role').select2({
        placeholder : 'Pilih Member',
        data : dataRole
    })

    // SET DETAIL PESERTA
    const detailPeserta = dataPeserta.detail_user;
    detailPeserta.name != null ? $('#nama').html(detailPeserta.name) : $('#nama').html('Data tidak tersedia');
    detailPeserta.created_at != null ? $('#created_at').html(moment(detailPeserta.created_at).format('dddd, Do MMMM YYYY')) : $('#created_at').html('Data tidak tersedia');
    detailPeserta.role_name != null ? $('#role').html(detailPeserta.role_name) : $('#role').html('Data tidak tersedia');
    detailPeserta.email != null ? $('#email').html(detailPeserta.email) : $('#email').html('Data tidak tersedia');
    detailPeserta.phone != null ? $('#telp').html(detailPeserta.phone) : $('#telp').html('Data tidak tersedia');

    $('#ubah_member').attr('data-id', detailPeserta.id);
    $('#ubah_member').attr('data-role', detailPeserta.role_id);
    $('#ubah_member').attr('data-nama', detailPeserta.name);

    var tableLomba = $('#data-table-riwayat').dataTable({
            data : dataPeserta.detail_contest,
            columns : [
                {
                    data : "contest_name"
                },
                {
                    data : "contest_date",
                    render : function (data) {
                            moment.locale('id')

                            let dateParse = moment(data).format('Do MMM YYYY');

                            return dateParse;
                        }
                },
                {
                    data : "contest_time"
                },
                {
                    data : "city"
                },
                {
                    data : "criteria_name"
                },
                {
                    data : "bird_name"
                },
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

    $('#ubah_member').click(function (e) {
        e.preventDefault();

        let nama = $(this).data('nama')
        let role = $(this).data('role')
        let id = $(this).data('id')



        $('#nama-peserta').html(nama);
        $('#id_user').val(id);
        $('#dd-edit-role').val(role).trigger('change');

        $('#modalEditMember').modal('show');
    });

    // SUBMIT EDIT ROLE
    $('#editMember').submit(function (e) {
        e.preventDefault();

        var formdata = new FormData(this)

        $.ajax({
            type: "POST",
            url: base_url+'/api/v1/account/update-member-previlege',
            data: formdata,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success == true) {
                    swal({
                        title: "Berhasil Ubah Member!",
                        text: 'Data member perserta berhasil diubah.',
                        icon: "success"
                    }).then(() => {
                        window.location.reload()
                    })
                }else{
                    swal({
                        title: "Gagal Ubah Member!",
                        icon: "error"
                    })
                }
            }
        });
    });

    loadingFalse();
});
</script>
@endsection
