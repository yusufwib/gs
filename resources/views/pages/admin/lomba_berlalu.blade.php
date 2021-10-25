@extends('layout.index', ['title' => 'Daftar Lomba', 'heading' => 'Lomba','sub' => 'Berlalu'])

@section('contents')
<div class="box">
    <div class="box-head">
        <h4>Lomba Berlalu</h4>
    </div>
    <div class="box-body">
        <table class="table table-bordered data-table" id="table-lomba-berlalu">
                <thead>
                    <tr>
                        <th>Jadwal Lomba</th>
                        <th>Pukul</th>
                        <th>Nama Lomba</th>
                        <th>Kota</th>
                        <th>Kriteria Lomba</th>
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
    dataListLombaBerlalu = () => {
        let data;

        let base_url = window.location.origin;
        let enpoint = base_url+'/api/v1/contest/get-web';

        $.ajax({
            type: "POST",
            url: enpoint,
            data : {
                'status' : 'past'
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
        var dataLomba = dataListLombaBerlalu();

        var tableLomba = $('#table-lomba-berlalu').DataTable({
                data : dataLomba,
                columns : [
                    {
                        data : "contest_date",
                        render : function (data) {
                            moment.locale('id')

                            let dateParse = moment(data).format('dddd, Do MMMM YYYY');

                            return dateParse;
                        }
                    },
                    {
                        data : "contest_time"
                    },
                    {
                        data : "name"
                    },
                    {
                        data : "city"
                    },
                    {
                        data : "total_criteria"
                    },
                    {
                        className: "text-center",
                        data : "id",
                        render : function (data){
                            let encodeId = btoa(data)

                            return `<a href="/lomba/detail/${encodeId}" class="button bg-blue text-white button-sm button-box"> <i class="zmdi zmdi-eye"></i> </a>`
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

        $(".spinner").css("display","none");
        $(".spinner-bg").css("display","none");
    });
</script>
@endsection
