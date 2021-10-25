@extends('layout.index', ['title' => 'Master Peserta', 'heading' => 'Peserta'])

@section('action-button')
<a href="/akun/atur-member" class="button button-outline button-info">Atur Member</a>
@endsection

@section('css')
    <style>
        .select2-container{
            z-index:100000;
        }
    </style>
@endsection

@section('contents')
<div class="box">
    <div class="box-head">
        <h4>Daftar Akun Peserta</h4>
    </div>
    <div class="box-body">
        <table class="table table-bordered data-table text-center" id="data-table-akun">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Nomor Telepon</th>
                        <th>Member</th>
                        <th>Terdaftar pada</th>
                        <th>Aksi</th>
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
    dataAkun = () => {
        let data;

        let base_url = window.location.origin;
        let enpoint = base_url+'/api/v1/account/get-by-role-type';

        $.ajax({
            type: "POST",
            url: enpoint,
            data : {
                'role_type' : 'user'
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

var dataAkun = dataAkun();
var dataRole = dataRole();

$(document).ready(function () {
    $('.select-role').select2({
        placeholder : 'Pilih Member',
        data : dataRole
    })

    var tableLomba = $('#data-table-akun').dataTable({
            data : dataAkun,
            columns : [
                {
                    data : "name"
                },
                {
                    data : "phone"
                },
                {
                    data : "role_name"
                },
                {
                    data : "created_at",
                    render : function (data) {
                            moment.locale('id')

                            let dateParse = moment(data).format('Do MMM YYYY');

                            return dateParse;
                        }
                },
                {
                    className: "text-center",
                    data : "id",
                    render : function (data, type, row, meta){
                        let encodeId = btoa(data)

                        return `
                            <a href="/akun/akun-peserta/detail/${encodeId}" class="button bg-blue text-white button-sm button-box" data-id=${data}> <i class="zmdi zmdi-eye"></i> </a>
                            <a class="button button-info text-white button-sm edit-role" data-nama="${row.name}" data-role=${row.role_id} data-id=${data}>Ubah member</a>
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

    $('.edit-role').click(function (e) {
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
