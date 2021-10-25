@extends('layout.index', ['title' => 'Daftar Peserta', 'heading' => 'Atur Block','prev' => 'Detail Lomba'])

@section('contents')
<div class="box">
    <div class="box-head">
        <h4 id="title">Atur Block</h4>
    </div>
    <div class="box-body">
        <table class="table table-bordered data-table" id="data-table-block">
                <thead>
                    <tr>
                        <th>Block</th>
                        <th>Nomor Peserta</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
    </div>
</div>
@endsection

@section('modal')
<!-- Modal Set Block -->
<div class="modal fade" id="modalSet" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Set Block Peserta</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="setBlock">
                <div class="form-group">
                    <label for="">Nomor Peserta</label>
                    <div class="nomor-peserta">-</div>
                </div>
                <div class="form-group">
                    <label for="">Block <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" name="contestant_block" id="block-peserta" required>
                </div>

        </div>
        <div class="modal-footer">
            <input type="hidden" name="id_contest" id="id-contest">
            <input type="hidden" name="contestant_number" id="number">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Set Block</button>
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

    dataNumber = () => {
        let data;

        let base_url = window.location.origin;
        let enpoint = base_url+'/api/v1/contest/jugding/current-block';

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
    $(document).ready(function () {
        var data = dataNumber();

        $("#prev_page").attr("href", `/lomba/detail/${last_segment}`);

        $('#data-table-block').dataTable({
            data: data,
            columns: [
                {
                    data: 'block',
                    render: function (data) {
                        if (data === null) {
                            return 'Block belum ditentukan'
                        }else{
                            return data;
                        }

                    }
                },
                {
                    data: "number"
                },
                {
                    data: null,
                    render : function (data, type, row, meta){
                            return `<a class="button button-info text-white button-sm button-box mr-2 edit-block" data-number=${row.number} data-block=${row.block !== null ? row.block : ''}> <i class="fa fa-pencil"></i> </a>`
                        }
                }
            ],
            responsive: true,
            language: {
                paginate: {
                    previous: '<i class="zmdi zmdi-chevron-left"></i>',
                    next: '<i class="zmdi zmdi-chevron-right"></i>'
                }
            }
        });

        // SET BLOCK
        $('#setBlock').submit(function (e) {
            e.preventDefault();

            var formdata = new FormData(this);

            let base_url = window.location.origin;
            let enpoint = base_url+'/api/v1/contest/jugding/set-block-contestant';

            $.ajax({
                type: "POST",
                url: enpoint,
                data: formdata,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.success) {
                        swal({
                            title: "Berhasil Set Block!",
                            text: 'Data block peserta berhasil ditetapkan.',
                            icon: "success"
                        }).then(() => {
                            window.location.reload();
                        })
                    } else {
                        swal({
                            title: "Gagal Set Block!",
                            icon: "error"
                        })
                    }
                },
            });
        });

        loadingFalse();
    });

    // SET BLOCK MODAL TRIGGER
    $(document).on('click','.edit-block', function (e) {
        let number = $(this).data('number');
        let block = $(this).data('block');

        $('#id-contest').val(id_contest);
        $('#number').val(number);
        $('.nomor-peserta').html(number);
        if (block !== '') {
            $('#block-peserta').val(block);
        }

        $('#modalSet').modal('show');
    });

</script>
@endsection
