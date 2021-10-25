@extends('layout.index', ['title' => 'Atur Juri', 'heading' => 'Atur Juri', 'prev' => 'Detail Lomba'])

@section('action-button')
<a href="#modalAdd" data-toggle="modal" class="button button-outline button-info">Tambah Juri</a>
@endsection

@section('contents')
<div class="box">
    <div class="box-head">
        <h4>Daftar Juri</h4>
    </div>
    <div class="box-body">
        <table class="table table-bordered data-table" id="data-table-akun">
                <thead>
                    <tr>
                        <th>Naman Juri</th>
                        <th>Email</th>
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
                        <div class="form-group">
                            <label for="">Nama Juri <span class="text-danger">*</span> </label>
                            <select class="form-control select-jury-create" name="id_jury" required>
                                <option value=""></option>
                            </select>
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
                    <h5 class="modal-title">Edit Jury</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editJuri">
                        <div class="form-group">
                            <label for="">Nama Juri <span class="text-danger">*</span> </label>
                            <select class="form-control select-jury-edit" name="id_jury" id="dd-edit-juri" required>
                                <option value=""></option>
                            </select>
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

    dataJury = () => {
        let data;

        let base_url = window.location.origin;
        let enpoint = base_url+'/api/v1/contest/jury/get-by-contest';

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

    dataAkun = () => {
        let data;
        let listData = [];

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

                data.map((val,index) => {
                    let jury ={
                        id : val.id,
                        text : val.name
                    }
                    listData.push(jury);
                });
            }
        });
        return listData;
    }
</script>
<script>
    var dataJury = dataJury();
    var dataAkun = dataAkun();

    $(document).ready(function () {

        $("#prev_page").attr("href", `/lomba/detail/${last_segment}`);

        $('.select-jury-create').select2({
            placeholder: "Nama Juri",
            data: dataAkun,
            dropdownParent: $('#modalAdd')
        });

        $('.select-jury-edit').select2({
            placeholder: "Nama Juri",
            data: dataAkun,
            dropdownParent: $('#modalEdit')
        });

        var tableAkun = $('#data-table-akun').dataTable({
                data : dataJury,
                columns : [
                    {
                        data : "jury_name"
                    },
                    {
                        data : "jury_email"
                    },
                    {
                        className: "text-center",
                        data : "id",
                        render : function (data, type, row, meta){
                            return `
                                <a class="button button-info text-white button-sm button-box mr-2 edit-juri" data-id="${data}" data-id_jury="${row.id_jury}"> <i class="fa fa-pencil"></i> </a>
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

        // CREATE Juri
        $('#createJuri').submit(function (e) {
            e.preventDefault();
            var formdata = new FormData(this);

            formdata.append('id_contest',id_contest);

            let enpoint = base_url+'/api/v1/contest/jury/create-by-contest';

            $.ajax({
                type: "POST",
                url: enpoint,
                data: formdata,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.success) {
                        swal({
                        title: "Berhasil Tambah Juri!",
                        text: 'Data juri baru berhasil ditambahakan.',
                        icon: "success"
                        }).then(() => {
                            window.location.reload()
                        })
                    }else{
                        swal({
                            title: "Gagal Tambah Juri!",
                            icon: "error"
                        })
                    }
                },
                error: function (err) {
                    swal({
                        title: "Gagal Tambah Juri!",
                        icon: "error"
                    })
                }
            });
        });

        // EDIT Juri
        $('#editJuri').submit(function (e) {
            e.preventDefault();
            var formdata = new FormData(this);

            formdata.append('id_contest',id_contest);

            let enpoint = base_url+'/api/v1/contest/jury/update-by-contest';

            $.ajax({
                type: "POST",
                url: enpoint,
                data: formdata,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.success) {
                        swal({
                        title: "Berhasil Edit Juri!",
                        text: 'Data Juri berhasil diedit.',
                        icon: "success"
                        }).then(() => {
                            window.location.reload()
                        })
                    }else{
                        swal({
                            title: "Gagal Edit Juri!",
                            icon: "error"
                        })
                    }
                },
                error: function (err) {
                    swal({
                        title: "Gagal Edit Juri!",
                        icon: "error"
                    })
                }
            });
        });

        // EDIT Juri
        $('.edit-juri').click(function (e) {
            e.preventDefault();
            let id = $(this).data('id');
            let idJury = $(this).data('id_jury');

            $('#id-edit').val(id);
            $('#dd-edit-juri').val(idJury).trigger('change');

            $('#modalEdit').modal('show');
        });

        // DELETE Juri
        $('.delete-juri').click(function (e) {
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
                    let enpoint = base_url+'/api/v1/contest/jury/delete-by-contest';

                    $.ajax({
                        type: "POST",
                        url: enpoint,
                        data: {
                            id : idOrganizer
                        },
                        success: function (response) {
                            if (response.success) {
                                swal({
                                title: "Berhasil Menghapus Juri!",
                                text: 'Data Juri baru berhasil dihapus.',
                                icon: "success"
                                }).then(() => {
                                    window.location.reload()
                                })
                            }else{
                                swal({
                                    title: "Gagal Hapus Juri!",
                                    icon: "error"
                                })
                            }
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

        loadingFalse();
    });
</script>
@endsection
