@extends('layout.index', ['title' => 'Daftar Lomba', 'heading' => 'Template Nomor Peserta', 'prev' => 'Mendatang', 'link' => '/lomba/lomba-mendatang'])

@section('action-button')
<button class="button button-outline button-primary" data-toggle="modal"
data-target="#modalAdd">Tambah Template Urutan</button>
@endsection

@section('css')
<style>
</style>
@endsection

@section('contents')
<div class="box">
    <div class="box-head">
        <h4>Daftar Template</h4>
    </div>
    <div class="box-body">
        <table class="table table-bordered data-table" id="data-table-template">
                <thead>
                    <tr>
                        <th>Nama Template</th>
                        <th>Urutan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
    </div>
</div>
@endsection

@section('modal')
    <!-- Modal Create -->
    <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Acak Nomor Peserta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createTemplate">
                    <div class="form-group mb-3">
                        <label for="">Nama Template <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Nomor <span class="text-danger">*</span></label>
                        <select class="form-control select-tag" name="nomor" id="" multiple required>
                        </select>
                    </div>
                    <div class="form-group">
                        <p>Jumlah yang sudah diinput : <span id="count-number">0</span></p>
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
    <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Acak Nomor Peserta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editTemplate">
                    <div class="form-group mb-3">
                        <label for="">Nama Template <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Nomor <span class="text-danger">*</span></label>
                        <select class="form-control select-tag" name="nomor" id="edit_nomor" multiple required>
                        </select>
                    </div>
                    <div class="form-group">
                        <p>Jumlah yang sudah diinput : <span id="count-number-edit">0</span></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" id="id-template">
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
<script src="{{ asset('assets/js/plugins/select2/select2.full.min.js')}}"></script>
<script src="{{ asset('assets/js/plugins/select2/select2.active.js')}}"></script>
<script>
    dataTemplate = () => {
    let data;

    let base_url = window.location.origin;
    let enpoint = base_url+'/api/v1/random-number-template/get';

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
var dataTemplate = dataTemplate();

$(document).ready(function () {
        $('.select-tag').select2({
            tags:true,
            tokenSeparators: [',', ' ']
        }).on('select2:open', function (e) {
             $('.select2-container--open .select2-dropdown--below').css('display','none');
        });

        $('.select-tag').on('change', function (evt) {
            var count = $(this).select2('data').length
            $('#count-number').html(count)
            $('#count-number-edit').html(count)
        });

        var tableTemplate = $('#data-table-template').dataTable({
                data : dataTemplate,
                columns : [
                    {
                        data : "name"
                    },
                    {
                        data : "number"
                    },
                    {
                        className: "text-center",
                        data : "id",
                        render : function (data, type, row, meta){
                            return `
                                <a class="button button-info text-white button-sm button-box mr-2 edit-template" data-id=${data} data-name="${row.name}" data-number=${row.number}> <i class="fa fa-pencil"></i></a>
                                <a class="button button-danger text-white button-sm button-box delete-template" data-id=${data}><i class="fa fa-trash"></i> </a>
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

        // CLEAR INPUT TAGS
        $('#modalEdit').on('hidden.bs.modal', function (e) {
            $('#edit_nomor').val(null).trigger('change');
            $('#edit_nomor').html('');
            $('#count-number-edit').html(0)
            $('#count-number').html(0)
        })

        // CREATE TEMPLATE
        $('#createTemplate').submit(function (e) {
            e.preventDefault();
            var values = {};
            var formdata = new FormData(this);
            var listNumber = $('#createTemplate :input[name=nomor]').val();
            var numberPayload = '';

            $.each(listNumber, function (idx, val) {
                numberPayload += (val+',');
            });

            formdata.append('number',numberPayload.substr(0, (numberPayload.length - 1)))

            let enpoint = base_url+'/api/v1/random-number-template/create';

            $.ajax({
                type: "POST",
                url: enpoint,
                data: formdata,
                processData: false,
                contentType: false,
                success: function (response) {
                    swal({
                        title: "Berhasil Tambah Template!",
                        text: 'Data template baru berhasil ditambahakan.',
                        icon: "success"
                    }).then(() => {
                        window.location.reload()
                    })
                },
                error: function (err) {
                    swal({
                        title: "Gagal Tambah Template!",
                        icon: "error"
                    })
                }
            });
        });

        // EDIT TEMPLATE
        $('#editTemplate').submit(function (e) {
            e.preventDefault();
            var formdata = new FormData(this);
            var listNumber = $('#editTemplate :input[name=nomor]').val();
            var numberPayloadEdit = '';

            $.each(listNumber, function (idx, val) {
                numberPayloadEdit += (val+',');
            });

            formdata.append('number',numberPayloadEdit.substr(0, (numberPayloadEdit.length - 1)))

            let enpoint = base_url+'/api/v1/random-number-template/update';

            $.ajax({
                type: "POST",
                url: enpoint,
                data: formdata,
                processData: false,
                contentType: false,
                success: function (response) {
                    swal({
                        title: "Berhasil Edit Template!",
                        text: 'Data template baru berhasil ditambahakan.',
                        icon: "success"
                    }).then(() => {
                        window.location.reload()
                    })
                },
                error: function (err) {
                    swal({
                        title: "Gagal Edit Template!",
                        icon: "error"
                    })
                }
            });
        });

        // DELETE TEMPLATE
        $('.delete-template').click(function (e) {
            e.preventDefault();
            let idTemplate  = $(this).data('id')

            swal({
                title: "Yakin Menghapus data?",
                text: 'Data yang dihapus tidak dapat dikembalikan dan dapat berefek pada data lainnya',
                icon: "error",
                buttons: true,
                dangerMode: true
            }).then((e) => {
                if (e) {
                    let base_url = window.location.origin;
                    let enpoint = base_url+'/api/v1/random-number-template/delete';

                    $.ajax({
                        type: "POST",
                        url: enpoint,
                        data: {
                            id : idTemplate
                        },
                        success: function (response) {
                            console.log(response)
                            swal({
                                title: "Berhasil Hapus Template!",
                                text: 'Data template berhasil dihapus.',
                                icon: "success"
                            }).then(() => {
                                window.location.reload()
                            })
                        },
                        error: function (err) {
                            swal({
                                title: "Gagal Hapus Template!",
                                icon: "error"
                            })
                        }
                    });
                }
            })
        });

        // EDIT TEMPLATE
        $('.edit-template').click(function (e) {
            e.preventDefault();
            let id = $(this).data('id');
            let name = $(this).data('name');
            let number = $(this).data('number');
            var arrayNumber = number.split(',');

            $('#editTemplate input[name=name]').val(name);
            $('#id-template').val(id);

            arrayNumber.map((val,index) => {
                var newOption = new Option(val, val, true, true);
                $('#edit_nomor').append(newOption).trigger('change');
            });

            $('#count-number-edit').html(arrayNumber.length)

            $('#modalEdit').modal('show');
        });

        loadingFalse();
});
</script>
@endsection
