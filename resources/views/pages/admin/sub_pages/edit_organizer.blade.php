@extends('layout.index', ['title' => 'Atur Penyelenggara', 'heading' => 'Atur Penyelenggara', 'prev' => 'Detail Lomba'])

@section('action-button')
<a href="#modalAdd" data-toggle="modal" class="button button-outline button-info">Tambah Penyelenggara</a>
@endsection

@section('contents')
<div class="box">
    <div class="box-head">
        <h4>Daftar Penyelenggara</h4>
    </div>
    <div class="box-body">
        <table class="table table-bordered data-table" id="data-table-akun">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Telepon</th>
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
                    <h5 class="modal-title">Tambah Penyelenggara</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createPenyelenggara">
                    <div class="form-group mb-3">
                        <label for="">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="" class="form-control" placeholder="Nama Lengkap" required>
                    </div>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">+62</div>
                        </div>
                        <input type="text" class="form-control" name="nomor" required>
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
                    <h5 class="modal-title">Edit Penyelenggara</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editPenyelenggara">
                    <div class="form-group mb-3">
                        <label for="">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="" class="form-control" placeholder="Nama Lengkap" required>
                    </div>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">+62</div>
                        </div>
                        <input type="text" class="form-control" name="nomor" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" id="id-edit">
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

    const id_contest = atob(last_segment);

    dataOrganizer = () => {
        let data;

        let base_url = window.location.origin;
        let enpoint = base_url+'/api/v1/contest/organizer/get-by-contest';

        $.ajax({
            type: "POST",
            url: enpoint,
            data : {
                'id_contest' : id_contest
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
    var dataOrganizer = dataOrganizer();

    $(document).ready(function () {

        $("#prev_page").attr("href", `/lomba/detail/${last_segment}`);

        var tableAkun = $('#data-table-akun').dataTable({
                data : dataOrganizer,
                columns : [
                    {
                        data : "name"
                    },
                    {
                        data : "phone"
                    },
                    {
                        className: "text-center",
                        data : "id",
                        render : function (data, type, row, meta){
                            return `
                                <a class="button button-info text-white button-sm button-box mr-2 edit-penyelenggara" data-id="${data}" data-name="${row.name}" data-phone="${row.phone}"> <i class="fa fa-pencil"></i> </a>
                                <a class="button button-danger text-white button-sm button-box delete-penyelenggara" data-id="${data}"> <i class="fa fa-trash"></i> </a>
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

        // CREATE PENYELENGGARA
        $('#createPenyelenggara').submit(function (e) {
            e.preventDefault();
            var formdata = new FormData(this);

            var phone = $('#createPenyelenggara input[name=nomor]').val();
            formdata.append('phone','+62'+phone);
            formdata.append('id_contest',id_contest);

            let enpoint = base_url+'/api/v1/contest/organizer/create-by-contest';

            $.ajax({
                type: "POST",
                url: enpoint,
                data: formdata,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.success) {
                        swal({
                        title: "Berhasil Tambah Penyelenggara!",
                        text: 'Data penyelenggara baru berhasil ditambahakan.',
                        icon: "success"
                        }).then(() => {
                            window.location.reload()
                        })
                    }else{
                        swal({
                            title: "Gagal Tambah Penyelenggara!",
                            icon: "error"
                        })
                    }
                },
                error: function (err) {
                    swal({
                        title: "Gagal Tambah Penyelenggara!",
                        icon: "error"
                    })
                }
            });
        });

        // EDIT PENYELENGGARA
        $('#editPenyelenggara').submit(function (e) {
            e.preventDefault();
            var formdata = new FormData(this);
            var phone = $('#editPenyelenggara input[name=nomor]').val();

            formdata.append('id_contest',id_contest);
            formdata.append('phone','+62'+phone);

            let enpoint = base_url+'/api/v1/contest/organizer/update-by-contest';

            $.ajax({
                type: "POST",
                url: enpoint,
                data: formdata,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.success) {
                        swal({
                        title: "Berhasil Edit Penyelenggara!",
                        text: 'Data penyelenggara berhasil diedit.',
                        icon: "success"
                        }).then(() => {
                            window.location.reload()
                        })
                    }else{
                        swal({
                            title: "Gagal Edit Penyelenggara!",
                            icon: "error"
                        })
                    }
                },
                error: function (err) {
                    swal({
                        title: "Gagal Edit Penyelenggara!",
                        icon: "error"
                    })
                }
            });
        });

        // EDIT PENYELENGGARA
        $('.edit-penyelenggara').click(function (e) {
            e.preventDefault();
            let id = $(this).data('id');
            let name = $(this).data('name');
            let phone = $(this).data('phone');

            $('#editPenyelenggara input[name=name]').val(name);
            $('#editPenyelenggara input[name=nomor]').val(phone.substr(3));
            $('#id-edit').val(id);

            $('#modalEdit').modal('show');
        });

        // DELETE PENYELENGGARA
        $('.delete-penyelenggara').click(function (e) {
            e.preventDefault();
            let idOrganizer  = $(this).data('id')

            swal({
                title: "Yakin Menghapus data?",
                text: 'Data yang dihapus tidak dapat dikembalikan dan dapat berefek pada data lainnya',
                icon: "error",
                buttons: true,
                dangerMode: true
            }).then((e) => {
                if (e) {
                    let base_url = window.location.origin;
                    let enpoint = base_url+'/api/v1/contest/organizer/delete-by-contest';

                    $.ajax({
                        type: "POST",
                        url: enpoint,
                        data: {
                            id : idOrganizer
                        },
                        success: function (response) {
                            if (response.success) {
                                swal({
                                title: "Berhasil Menghapus Penyelenggara!",
                                text: 'Data penyelenggara baru berhasil dihapus.',
                                icon: "success"
                                }).then(() => {
                                    window.location.reload()
                                })
                            }else{
                                swal({
                                    title: "Gagal Hapus Penyelenggara!",
                                    icon: "error"
                                })
                            }
                        },
                        error: function (err) {
                            swal({
                                title: "Gagal Hapus Penyelenggara!",
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
