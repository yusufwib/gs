@extends('layout.index', ['title' => 'Master Juri', 'heading' => 'Juri'])

@section('action-button')
<a href="#modalAdd" data-toggle="modal" class="button button-outline button-info">Tambah Juri</a>
@endsection

@section('contents')
<div class="box">
    <div class="box-head">
        <h4>Daftar Akun Juri</h4>
    </div>
    <div class="box-body">
        <table class="table table-bordered data-table text-center" id="data-table-akun">
                <thead>
                    <tr>
                        <th>Id Juri</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Terdaftar pada</th>
                        <th>Aksi</th>
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
                    <h5 class="modal-title">Tambah Juri</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createJuri">
                    <div class="form-group mb-3">
                        <label for="">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="" class="form-control" placeholder="Nama Lengkap" required>
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
                    <input type="hidden" name="role_id" value="3">
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
                    <h5 class="modal-title">Edit Juri</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editJuri">
                    <div class="form-group mb-3">
                        <label for="">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="" class="form-control" placeholder="Nama Lengkap" required>
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
                    <input type="hidden" name="role_id" value="3">
                    <input type="hidden" name="id" id="id-juri" value="">
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
                'role_type' : 'jury'
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
    var dataAkun = dataAkun();

    $(document).ready(function () {
        var tableAkun = $('#data-table-akun').dataTable({
                data : dataAkun,
                columns : [
                    {
                        data : "id"
                    },
                    {
                        data : "name"
                    },
                    {
                        data : "email"
                    },
                    {
                        data : "created_at",
                        render : function (data) {
                                moment.locale('id')

                                if (data != null) {
                                    let dateParse = moment(data).format('Do MMM YYYY');
                                    return dateParse;
                                }

                                return '<div class="">Tanggal tidak valid</div>'
                            }
                    },
                    {
                        className: "text-center",
                        data : "id",
                        render : function (data, type, row, meta){
                            return `
                                <a class="button button-info text-white button-sm button-box mr-2 edit-juri" data-id="${data}" data-name="${row.name}" data-email="${row.email}" data-password="${row.password_show}"> <i class="fa fa-pencil"></i> </a>
                                <a class="button button-danger text-white button-sm button-box delete-juri" data-id="${data}"> <i class="fa fa-trash"></i> </a>
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
        loadingFalse();

        // CREATE JURI
        $('#createJuri').submit(function (e) {
            e.preventDefault();
            var formdata = new FormData(this);

            var confirmPass = $('#createJuri input[name=password]').val();
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
                        title: "Berhasil Tambah Juri!",
                        text: 'Data juri baru berhasil ditambahakan.',
                        icon: "success"
                    }).then(() => {
                        window.location.reload()
                    })
                },
                error: function (err) {
                    swal({
                        title: "Gagal Tambah Juri!",
                        icon: "error"
                    })
                }
            });
        });

        // EDIT JURI
        $('#editJuri').submit(function (e) {
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
                        title: "Berhasil Edit Juri!",
                        text: 'Data juri baru berhasil ditambahakan.',
                        icon: "success"
                    }).then(() => {
                        window.location.reload()
                    })
                },
                error: function (err) {
                    swal({
                        title: "Gagal Edit Juri!",
                        icon: "error"
                    })
                }
            });
        });

        // EDIT MEMBER MODAL
        $('.edit-juri').click(function (e) {
            e.preventDefault();
            let id = $(this).data('id');
            let name = $(this).data('name');
            let email = $(this).data('email');
            let password = $(this).data('password');

            $('#editJuri input[name=name]').val(name);
            $('#editJuri input[name=email]').val(email);
            $('#editJuri input[name=password]').val(password);
            $('#id-juri').val(id);
            $('#modalEdit').modal('show');
        });

        // DELETE JURI
        $('.delete-juri').click(function (e) {
            e.preventDefault();
            let idJuri  = $(this).data('id')

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
                            id : idJuri
                        },
                        success: function (response) {
                            swal({
                                title: "Berhasil Hapus Juri!",
                                text: 'Data juri berhasil dihapus.',
                                icon: "success"
                            }).then(() => {
                                window.location.reload()
                            })
                        },
                        error: function (err) {
                            swal({
                                title: "Gagal Hapus Juri!",
                                icon: "error"
                            })
                        }
                    });
                }
            })
        });
    });
</script>
@endsection
