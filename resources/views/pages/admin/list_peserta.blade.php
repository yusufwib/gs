@extends('layout.index', ['title' => 'Daftar Peserta', 'heading' => 'Peserta','prev' => 'Detail Lomba', 'link' => '/lomba/lomba-mendatang/detail'])

@section('contents')
<div class="box">
    <div class="box-head">
        <h4 id="title">-</h4>
    </div>
    <div class="box-body">
        <table class="table table-bordered data-table" id="data-table-peserta">
                <thead>
                    <tr>
                        <th>ID Tiket</th>
                        <th>Nama Peserta</th>
                        <th>Kelas</th>
                        <th>Jenis Burung</th>
                        <th>Terdaftar Pada</th>
                        <th>No. Peserta</th>
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
    var segment_str = window.location.pathname;
    var segment_array = segment_str.split( '/' );
    var last_segment = segment_array.pop();

    const id_contest = atob(last_segment);

    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('idCriteria');
    const id_criteria_content = atob(id);
    const encodedtitle = urlParams.get('title');
    const title = atob(encodedtitle);

    dataPeserta = () => {
        let data;

        let base_url = window.location.origin;
        let enpoint = base_url+'/api/v1/contest/get-web-detail-participants-content';

        $.ajax({
            type: "POST",
            url: enpoint,
            data : {
                'id_criteria_contents' : id_criteria_content
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
        var data = dataPeserta();

        console.log(data);

        $("#prev_page").attr("href", `/lomba/detail/${last_segment}`);

        $('#title').html(title);

        $('#data-table-peserta').dataTable({
            data: data,
            columns: [
                {
                    data: 'id_ticket',
                    render: function (data) {
                        return `#${data}`
                    }
                },
                {
                    data: "user_name"
                },
                {
                    data: "criteria_name"
                },
                {
                    data: "bird_name"
                },
                {
                    data: "created_at",
                    render: function (data, type, row, meta) {
                        moment.locale('id')

                        let dateParse = moment(data).format('dddd, Do MMMM YYYY');

                        return dateParse;
                    }
                },
                {
                    data: "contestant_number",
                    render : function (data, type, row, meta){
                            if (data != null) {
                                return data;
                            }else{
                                return '-';
                            }
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
        loadingFalse();
    });

</script>
@endsection
