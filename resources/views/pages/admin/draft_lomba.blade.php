@extends('layout.index', ['title' => 'Daftar Draft Lomba', 'heading' => 'Draft Lomba'])

@section('contents')
<div class="box">
    <div class="box-head">
        <h4>Daftar Draft Lomba</h4>
    </div>
    <div class="box-body">
        <table class="table table-bordered data-table" id="table-lomba-berlalu">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Ditambahkan pada</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
            </table>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('assets/js/plugins/datatables/datatables.min.js')}}"></script>
<script src="{{ asset('assets/js/plugins/datatables/datatables.active.js')}}"></script>
<script>
        dataDraft = () => {
        let data;

        let base_url = window.location.origin;
        let enpoint = base_url+'/api/v1/contest/get-web-drafted';

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
    $(document).ready(function () {
        var dataLomba = dataDraft();

        var tableLomba = $('#table-lomba-berlalu').DataTable({
                data : dataLomba,
                columns : [
                    {
                        data : "name"
                    },
                    {
                        data : "created_at",
                        render : function (data) {
                            moment.locale('id')

                            let dateParse = moment(data).format('dddd, Do MMMM YYYY');

                            return dateParse;
                        }
                    },
                    {
                        className: "text-center",
                        data : "id",
                        render : function (data, type, row, meta){
                            let encodeId = btoa(data)
                            return `
                                <a href="/lomba/tambah-lomba/${encodeId}" class="button bg-blue text-white button-sm button-box"> <i class="zmdi zmdi-eye"></i> </a>
                                <a class="button button-danger text-white button-sm button-box delete-draft" data-id=${data}> <i class="fa fa-trash"></i> </a>
                            `;
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
    });

    // DELETE DRAFT
    $(document).on('click', '.delete-draft', function (e) {
        e.preventDefault();
        let idDraft  = $(this).data('id')

        swal({
            title: "Yakin Menghapus draft?",
            text: 'Data yang dihapus tidak dapat dikembalikan',
            icon: "error",
            buttons: true,
            dangerMode: true
        }).then((e) => {
            if (e) {
                let base_url = window.location.origin;
                let enpoint = base_url+'/api/v1/contest/update-draft-status';

                $.ajax({
                    type: "POST",
                    url: enpoint,
                    data: {
                        id_contest : idDraft,
                        drafted : 0
                    },
                    success: function (response) {
                        if (response.success) {
                            swal({
                                title: "Berhasil Hapus Draft!",
                                text: 'Data rekening berhasil dihapus.',
                                icon: "success"
                            }).then(() => {
                                window.location.reload()
                            })
                        }else{
                            swal({
                                title: "Gagal Hapus Draft!",
                                icon: "error"
                            })
                        }
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
</script>
@endsection
