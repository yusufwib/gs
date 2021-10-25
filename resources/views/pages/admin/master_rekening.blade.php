@extends('layout.index', ['title' => 'Master Rekening', 'heading' => 'Pengaturan', 'sub' => 'Rekening'])

@section('css')
    <style>
        .select2-container{
            z-index:100000;
        }
    </style>
@endsection

@section('action-button')
<a href="#modalCreate" data-toggle="modal" class="button button-outline button-primary">Tambah Rekening</a>
@endsection

@section('contents')
<div class="box">
    <div class="box-head">
        <h4>Rekening</h4>
    </div>
    <div class="box-body">
        <table class="table table-bordered data-table" id="data-table-rekening">
                <thead>
                    <tr>
                        <th>Nama Bank</th>
                        <th>Nomor Rekening</th>
                        <th>Nama Pemilik</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
            </table>
    </div>
</div>
@endsection

@section('modal')
<!-- Modal Create Bank -->
<div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Tambah Rekening Bank</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="createBank">
                <div class="form-group">
                    <label for="">Nama Bank <span class="text-danger">*</span> </label>
                    <select class="form-control select-bank" name="bank" id="dd-create-bank" required>
                        <option value=""></option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Nomor Rekening <span class="text-danger">*</span> </label>
                    <input type="number" class="form-control" name="rekening" id="" required>
                </div>
                <div class="form-group">
                    <label for="">Nama Pemilik <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" name="owner_name" id="" required>
                </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Tambah Rekening</button>
            </form>
        </div>
        </div>
    </div>
</div>

<!-- Modal Edit Bank -->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Edit Rekening Bank</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="editBank">
                <div class="form-group">
                    <label for="">Nama Bank <span class="text-danger">*</span> </label>
                    <select class="form-control select-bank" name="bank-edit" id="dd-edit-bank" required>
                        <option value=""></option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Nomor Rekening <span class="text-danger">*</span> </label>
                    <input type="number" class="form-control" name="rekening" id="" required>
                </div>
                <div class="form-group">
                    <label for="">Nama Pemilik <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" name="owner_name" id="" required>
                </div>

        </div>
        <div class="modal-footer">
            <input type="hidden" name="id" id="id-bank">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Edit Rekening</button>
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
    dataRekening = () => {
        let data;

        let base_url = window.location.origin;
        let enpoint = base_url+'/api/v1/bank/get';

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
    masterBank = () => {
        let data;
        let masterBank = [];

        let base_url = window.location.origin;
        let enpoint = base_url+'/api/v1/bank/get-masterbank';

        $.ajax({
            type: "POST",
            url: enpoint,
            async: false,
            dataType : 'json',
            success: function (response) {
                data = response.data;

                data.map((val,index) => {
                    let bank ={
                        id : val.url,
                        text : val.name
                    }
                    masterBank.push(bank);
                })
            }
        });
        return masterBank;
    }
</script>
<script>
    var dataRekening = dataRekening();
    var masterBank = masterBank();

    $(document).ready(function () {
        var tableLomba = $('#data-table-rekening').dataTable({
                data : dataRekening,
                columns : [
                    {
                        data : "name"
                    },
                    {
                        data : "rekening"
                    },
                    {
                        data : "owner_name"
                    },
                    {
                        className: "text-center",
                        data : "id",
                        render : function (data, type, row, meta){
                            return `
                                <a class="button button-info text-white button-sm button-box mr-2 edit-bank" data-id=${data} data-rekening=${row.rekening} data-bank=${row.img} data-owner="${row.owner_name}"> <i class="fa fa-pencil"></i> </a>
                                <a class="button button-danger text-white button-sm button-box delete-bank" data-id=${data}> <i class="fa fa-trash"></i> </a>
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

        $('.select-bank').select2({
            placeholder: "Nama Bank",
            data: masterBank
        });

        // CREATE BANK
        $('#createBank').submit(function (e) {
            e.preventDefault();

            var formdata = new FormData(this);
            var dataBank = $('#dd-create-bank').select2('data');

            formdata.append('name', dataBank[0].text);
            formdata.append('img', dataBank[0].id);

            for (var pair of formdata.entries()) {
                console.log(pair[0]+ ', ' + pair[1]);
            }

            let base_url = window.location.origin;
            let enpoint = base_url+'/api/v1/bank/create';

            $.ajax({
                type: "POST",
                url: enpoint,
                data: formdata,
                processData: false,
                contentType: false,
                success: function (response) {
                    swal({
                        title: "Berhasil Tambah Rekening!",
                        text: 'Data rekening baru berhasil ditambahakan.',
                        icon: "success"
                    }).then(() => {
                        window.location.reload()
                    })
                },
                error: function (err) {
                    swal({
                        title: "Gagal Tambah Rekening!",
                        icon: "error"
                    })
                }
            });
        });

        // DELETE BANK
        $('.delete-bank').click(function (e) {
            e.preventDefault();
            let idRekening  = $(this).data('id')

            swal({
                title: "Yakin Menghapus data?",
                text: 'Data yang dihapus tidak dapat dikembalikan',
                icon: "error",
                buttons: true,
                dangerMode: true
            }).then((e) => {
                if (e) {
                    let base_url = window.location.origin;
                    let enpoint = base_url+'/api/v1/bank/delete';

                    $.ajax({
                        type: "POST",
                        url: enpoint,
                        data: {
                            id : idRekening
                        },
                        success: function (response) {
                            console.log(response)
                            swal({
                                title: "Berhasil Hapus Rekening!",
                                text: 'Data rekening berhasil dihapus.',
                                icon: "success"
                            }).then(() => {
                                window.location.reload()
                            })
                        },
                        error: function (err) {
                            swal({
                                title: "Gagal Hapus Rekening!",
                                icon: "error"
                            })
                        }
                    });
                }
            })
        });

        // EDIT BANK MODAL TRIGGER
        $('.edit-bank').click(function (e) {
            e.preventDefault();
            let id = $(this).data('id');
            let owner = $(this).data('owner');
            let dropDownVal = $(this).data('bank');
            let rekening = $(this).data('rekening');

            $('#dd-edit-bank').val(dropDownVal).trigger('change');
            $('#editBank input[name=rekening]').val(rekening);
            $('#editBank input[name=owner_name]').val(owner);
            $('#id-bank').val(id);

            $('#modalEdit').modal('show');
        });

        // EDIT BANK
        $('#editBank').submit(function (e) {
            e.preventDefault();

            var formdata = new FormData(this);
            var dataBank = $('#dd-edit-bank').select2('data');

            formdata.append('name', dataBank[0].text);
            formdata.append('img', dataBank[0].id);

            let base_url = window.location.origin;
            let enpoint = base_url+'/api/v1/bank/update';

            $.ajax({
                type: "POST",
                url: enpoint,
                data: formdata,
                processData: false,
                contentType: false,
                success: function (response) {
                    swal({
                        title: "Berhasil Edit Rekening!",
                        text: 'Data rekening berhasil diedit.',
                        icon: "success"
                    }).then(() => {
                        window.location.reload()
                    })
                },
                error: function (err) {
                    swal({
                        title: "Gagal Edit Rekening!",
                        icon: "error"
                    })
                }
            });
        });

        loadingFalse();
    });
</script>
@endsection
