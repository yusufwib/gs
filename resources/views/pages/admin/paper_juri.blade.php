@extends('layout.index', ['title' => 'Paper Juri', 'heading' => 'Detail Penilaian Juri','prev' => 'Sesi Penjurian'])

@section('contents')
<div class="box">
    <div class="box-head d-flex justify-content-between align-items-center">
        <h4 id="jury-name"></h4>
    </div>
    <div class="box-body">
        <div class="table-responsive" style="overflow-y: hidden;">
            <table class="table table-bordered data-table text-center" id="data-table-paper">
                <thead>
                    <tr>
                        <th>Blok</th>
                        <th>No. Peserta</th>
                        <th>Irama Lagu Roll </th>
                        <th>Irama Lagu Tembak</th>
                        <th>Durasi</th>
                        <th>Volume</th>
                        <th>Gaya</th>
                        <th>Fisik</th>
                        <th>Nilai</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
            </table>
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

    const id_jury = atob(last_segment);
    const urlParams = new URLSearchParams(window.location.search);
    const id_criteria_content = atob(urlParams.get('idContent'));

    dataNilai = () => {
        let data;

        let base_url = window.location.origin;
        let enpoint = base_url+'/api/v1/contest/get-web-detail-participants-jury-score';

        $.ajax({
            type: "POST",
            url: enpoint,
            data : {
                'id_criteria_contents' : id_criteria_content,
                'id_jury' : id_jury,

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
        $("#prev_page").attr("href", `javascript:history.back()`);

        var data = dataNilai();
        $('#jury-name').text('Juri - '+data.jury_name);
        $('#data-table-paper').DataTable({
            paging: true,
            data: data.score,
            columns: [
                {
                    data: "contestant_block",
                },
                {
                    data: "contestant_number",
                },
                {
                    data: "score_irama_lagu_roll",
                    render: function (data) {
                        let nilai = `<td>${data} / 5 <span style="color: #4E92F0"><i class="fa fa-star"></i></span></td>`;
                        return nilai;
                    },
                    orderable : false
                },
                {
                    data: "score_irama_lagu_tembak",
                    render: function (data) {
                        let nilai = `<td>${data} / 5 <span style="color: #4E92F0"><i class="fa fa-star"></i></span></td>`;
                        return nilai;
                    },
                    orderable : false
                },
                {
                    data: "score_durasi",
                    render: function (data) {
                        let nilai = `<td>${data} / 5 <span style="color: #4E92F0"><i class="fa fa-star"></i></span></td>`;
                        return nilai;
                    },
                    orderable : false
                },
                {
                    data: "score_volume",
                    render: function (data) {
                        let nilai = `<td>${data} / 5 <span style="color: #4E92F0"><i class="fa fa-star"></i></span></td>`;
                        return nilai;
                    },
                    orderable : false
                },
                {
                    data: "score_gaya",
                    render: function (data) {
                        let nilai = `<td>${data} / 5 <span style="color: #4E92F0"><i class="fa fa-star"></i></span></td>`;
                        return nilai;
                    },
                    orderable : false
                },
                {
                    data: "score_fisik",
                    render: function (data) {
                        let nilai = `<td>${data} / 5 <span style="color: #4E92F0"><i class="fa fa-star"></i></span></td>`;
                        return nilai;
                    },
                    orderable : false
                },
                {
                    data: "score",
                    orderable : false
                },
                {
                    data: "score_description",
                    orderable : false
                },
            ],
            language: {
                paginate: {
                    previous: '<i class="zmdi zmdi-chevron-left"></i>',
                    next: '<i class="zmdi zmdi-chevron-right"></i>'
                }
            },
        });
        loadingFalse();
    });
</script>
@endsection
