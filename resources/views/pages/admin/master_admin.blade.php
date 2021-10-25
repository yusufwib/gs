@extends('layout.index', ['title' => 'Master Admin', 'heading' => 'Pengaturan', 'sub' => 'Admin'])

@section('css')
<style>
    .select2-container{
        z-index:100000;
    }
</style>
@endsection

@section('action-button')
<a href="#modalAdd" data-toggle="modal" class="button button-outline button-primary">Tambah Admin</a>
@endsection

@section('contents')
<div class="box">
    <div class="box-head">
        <h4>Admin</h4>
    </div>
    <div class="box-body">
        <table class="table table-bordered data-table" id="data-table-admin">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
            </table>
    </div>
</div>
@endsection

@section('modal')
    <!-- Modal Create -->
    <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby=""
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Admin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createAdmin">
                    <div class="form-group mb-3">
                        <label for="">Nama Admin <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="" class="form-control" placeholder="Nama Admin" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Role <span class="text-danger">*</span></label>
                        <select class="select-role form-control" name="role_id" id="">
                            <option value=""></option>
                            <option value="1">Owner</option>
                            <option value="2">Admin</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" placeholder="Email" id="" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Password <span class="text-danger">*</span></label>
                        <input type="text" name="password" placeholder="Password" id="" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Admin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editAdmin">
                    <div class="form-group mb-3">
                        <label for="">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="" class="form-control" placeholder="Nama Lengkap" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Role <span class="text-danger">*</span></label>
                        <select class="select-role form-control" name="role_id" id="">
                            <option value=""></option>
                            <option value="1">Owner</option>
                            <option value="2">Admin</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" placeholder="Email" id="" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Password <span class="text-danger">*</span></label>
                        <input type="text" name="password" placeholder="Password" id="" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" id="id-admin" value="">
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
    dataAkunAdmin = () => {
        let data;

        let base_url = window.location.origin;
        let enpoint = base_url+'/api/v1/account/get-by-role-type';

        $.ajax({
            type: "POST",
            url: enpoint,
            data : {
                'role_type' : 'admin'
            },
            async: false,
            dataType : 'json',
            success: function (response) {
                data = response.data;
            }
        });
        return data;
    }
    dataAkunOwner = () => {
        let data;

        let base_url = window.location.origin;
        let enpoint = base_url+'/api/v1/account/get-by-role-type';

        $.ajax({
            type: "POST",
            url: enpoint,
            data : {
                'role_type' : 'owner'
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
    var dataAkunAdmin = dataAkunAdmin();
    var dataAkunOwner = dataAkunOwner();

    var data = dataAkunAdmin.concat(dataAkunOwner);

    $(document).ready(function () {
        $('.select-role').select2({
            placeholder: "Pilih Role"
        });

        var tableAdmin = $('#data-table-admin').dataTable({
            data : data,
            columns : [
                {
                    data : "name"
                },
                {
                    data : "role_id",
                    render : function (data) {
                            let role;

                            if (data == 1) {
                                role = 'Owner'
                            }else{
                                role = 'Admin'
                            }
                            return role;
                        }
                },
                {
                    data : "email"
                },
                {
                    className: "text-center",
                    data : "id",
                    render : function (data, type, row, meta){
                        return `
                            <a class="button button-info text-white button-sm button-box mr-2 edit-admin" data-id=${data} data-name="${row.name}" data-email="${row.email}" data-password="${row.password_show}" data-role=${row.role_id}> <i class="fa fa-pencil"></i> </a>
                            <a class="button button-danger text-white button-sm button-box delete-admin" data-id=${data}> <i class="fa fa-trash"></i> </a>
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

        // CREATE ADMIN
        $('#createAdmin').submit(function (e) {
            e.preventDefault();
            var formdata = new FormData(this);

            var confirmPass = $('#createAdmin input[name=password]').val();
            formdata.append('password_confirmation',confirmPass);

            let enpoint = base_url+'/api/v1/account/register-admin-or-jury';

            $.ajax({
                type: "POST",
                url: enpoint,
                data: formdata,
                processData: false,
                contentType: false,
                success: function (response) {
                    swal({
                        title: "Berhasil Tambah Admin!",
                        text: 'Data admin baru berhasil ditambahakan.',
                        icon: "success"
                    }).then(() => {
                        window.location.reload()
                    })
                },
                error: function (err) {
                    swal({
                        title: "Gagal Tambah Admin!",
                        icon: "error"
                    })
                }
            });
        });

        // EDIT JURI
        $('#editAdmin').submit(function (e) {
            e.preventDefault();
            var formdata = new FormData(this);

            let enpoint = base_url+'/api/v1/account/update';

            $.ajax({
                type: "POST",
                url: enpoint,
                data: formdata,
                processData: false,
                contentType: false,
                success: function (response) {
                    swal({
                        title: "Berhasil Edit Admin!",
                        text: 'Data admin baru berhasil ditambahakan.',
                        icon: "success"
                    }).then(() => {
                        window.location.reload()
                    })
                },
                error: function (err) {
                    swal({
                        title: "Gagal Edit Admin!",
                        icon: "error"
                    })
                }
            });
        });

        // EDIT ADMIN MODAL
        $('.edit-admin').click(function (e) {
            e.preventDefault();
            let id = $(this).data('id');
            let name = $(this).data('name');
            let email = $(this).data('email');
            let role = $(this).data('role');
            let password = $(this).data('password');

            $('#editAdmin input[name=name]').val(name);
            $('#editAdmin input[name=email]').val(email);
            $('#editAdmin input[name=password]').val(password);
            $('.select-role').val(role).trigger('change');
            $('#id-admin').val(id);
            $('#modalEdit').modal('show');
        });

        // DELETE ADMIN
        $('.delete-admin').click(function (e) {
            e.preventDefault();
            let idAdmin  = $(this).data('id')

            swal({
                title: "Yakin Menghapus data?",
                text: 'Data yang dihapus tidak dapat dikembalikan dan dapat berefek pada data lainnya',
                icon: "error",
                buttons: true,
                dangerMode: true
            }).then((e) => {
                if (e) {
                    let base_url = window.location.origin;
                    let enpoint = base_url+'/api/v1/account/delete';

                    $.ajax({
                        type: "POST",
                        url: enpoint,
                        data: {
                            id : idAdmin
                        },
                        success: function (response) {
                            swal({
                                title: "Berhasil Hapus Admin!",
                                text: 'Data admin berhasil dihapus.',
                                icon: "success"
                            }).then(() => {
                                window.location.reload()
                            })
                        },
                        error: function (err) {
                            swal({
                                title: "Gagal Hapus Admin!",
                                icon: "error"
                            })
                        }
                    });
                }
            })
        });

        loadingFalse();
    });
</script>
@endsection
