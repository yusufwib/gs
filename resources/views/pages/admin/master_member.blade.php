@extends('layout.index', ['title' => 'Master Member', 'heading' => 'Atur Member', 'prev' => 'Peserta', 'link' => '/akun/akun-peserta'])

@section('action-button')
<a href="#modalAdd" data-toggle="modal" class="button button-outline button-primary">Tambah Member</a>
@endsection

@section('contents')
<div class="box">
    <div class="box-head">
        <h4>Daftar Member</h4>
    </div>
    <div class="box-body">
        <table class="table table-bordered data-table" id="data-table-member">
                <thead>
                    <tr>
                        <th>Nama Member</th>
                        <th>Batas Pembelian per Lomba</th>
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
                    <h5 class="modal-title">Tambah Member</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createMember">
                    <div class="form-group mb-3">
                        <label for="">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Batas Pembelian per Lomba <span class="text-danger">*</span></label>
                        <input type="number" name="max_buy" id="" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="role_type" value="user">
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
                    <h5 class="modal-title">Edit Member</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editMember">
                    <div class="form-group mb-3">
                        <label for="">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Batas Pembelian per Lomba <span class="text-danger">*</span></label>
                        <input type="number" name="max_buy" id="" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="role_type" value="user">
                    <input type="hidden" name="id" id="id-member">
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
    dataMember = () => {
        let data;

        let base_url = window.location.origin;
        let enpoint = base_url+'/api/v1/account/master-member';

        $.ajax({
            type: "POST",
            url: enpoint,
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
var dataMember = dataMember();

$(document).ready(function () {
    var tableMember = $('#data-table-member').dataTable({
            data : dataMember,
            columns : [
                {
                    data : "name"
                },
                {
                    data : "max_buy"
                },
                {
                    className: "text-center",
                    data : "id",
                    render : function (data, type, row, meta){
                        return `
                                <a href="#editModal" class="button button-info text-white button-sm button-box edit-member" data-id=${data} data-name="${row.name}" data-max=${row.max_buy}> <i class="fa fa-pencil"></i> </a>
                                <a class="button button-danger text-white button-sm button-box delete-member" data-id=${data}> <i class="fa fa-trash"></i> </a>
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

    // CREATE MEMBER
    $('#createMember').submit(function (e) {
        e.preventDefault();
        var formdata = new FormData(this);
        let enpoint = base_url+'/api/v1/account/master-member-create';

        $.ajax({
            type: "POST",
            url: enpoint,
            data: formdata,
            processData: false,
            contentType: false,
            success: function (response) {
                swal({
                    title: "Berhasil Tambah Member!",
                    text: 'Data member baru berhasil ditambahakan.',
                    icon: "success"
                }).then(() => {
                    window.location.reload()
                })
            },
            error: function (err) {
                swal({
                    title: "Gagal Tambah Member!",
                    icon: "error"
                })
            }
        });
    });

    // EDIT MEMBER
    $('#editMember').submit(function (e) {
        e.preventDefault();
        var formdata = new FormData(this);
        let enpoint = base_url+'/api/v1/account/master-member-update';

        $.ajax({
            type: "POST",
            url: enpoint,
            data: formdata,
            processData: false,
            contentType: false,
            success: function (response) {
                swal({
                    title: "Berhasil Edit Member!",
                    text: 'Data member baru berhasil diedit.',
                    icon: "success"
                }).then(() => {
                    window.location.reload()
                })
            },
            error: function (err) {
                swal({
                    title: "Gagal Tambah Member!",
                    icon: "error"
                })
            }
        });
    });

    // EDIT MEMBER MODAL
    $('.edit-member').click(function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        let name = $(this).data('name');
        let maxBuy = $(this).data('max');

        console.log(maxBuy)

        $('#editMember input[name=name]').val(name);
        $('#editMember input[name=max_buy]').val(parseInt(maxBuy));
        $('#id-member').val(id);
        $('#modalEdit').modal('show');
    });

    // DELETE TEMPLATE
    $('.delete-member').click(function (e) {
        e.preventDefault();
        let idMember  = $(this).data('id')

        swal({
            title: "Yakin Menghapus data?",
            text: 'Data yang dihapus tidak dapat dikembalikan dan dapat berefek pada data lainnya',
            icon: "error",
            buttons: true,
            dangerMode: true
        }).then((e) => {
            if (e) {
                let base_url = window.location.origin;
                let enpoint = base_url+'/api/v1/account/master-member-delete';

                $.ajax({
                    type: "POST",
                    url: enpoint,
                    data: {
                        id : idMember
                    },
                    success: function (response) {
                        swal({
                            title: "Berhasil Hapus Member!",
                            text: 'Data member berhasil dihapus.',
                            icon: "success"
                        }).then(() => {
                            window.location.reload()
                        })
                    },
                    error: function (err) {
                        swal({
                            title: "Gagal Hapus Member!",
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
