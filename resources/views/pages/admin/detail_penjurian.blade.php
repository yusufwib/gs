@extends('layout.index', ['title' => 'Daftar Lomba', 'heading' => 'Sesi Penjurian','prev' => 'Detail Lomba'])

@section('contents')
<div class="box desc-kelas mb-3">
    <div class="box-head">
        <h4 id="title">-</h4>
    </div>
    <div class="box-body">
        <div class="row mb-3">
            <div class="col-md-4">
                <p>Kelas</p>
                <p class="font-weight-bold" id="nama_kelas">-</p>
            </div>
            <div class="col-md-4">
                <p>Jenis Burung</p>
                <p class="font-weight-bold" id="jenis_burung">-</p>
            </div>
            <div class="col-md-4">
                <p>Kode Penjuarian</p>
                <p class="font-weight-bold" id="kode_penjurian">-</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <p>Jumlah Peserta</p>
                <p class="font-weight-bold" id="jumlah_peserta">-</p>
            </div>
        </div>
    </div>
</div>

{{-- KETERANGAN KONCER --}}
<div class="row mb-3">
    <div class="col-md-8">
        <div class="box">
            <div class="box-head">
                <h4>Akumulasi Jumlah Koncer</h4>
            </div>
            <div class="box-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Juri</th>
                            <th>A</th>
                            <th>B</th>
                            <th>C</th>
                            <th>D</th>
                            <th>E</th>
                            <th>F</th>
                        </tr>
                    </thead>
                    <tbody id="list-koncer-position">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4    ">
        <div class="box">
            <div class="box-head d-flex justify-content-between align-items-center">
                <h4>Keterangan Juara</h4>
                <div class="group">
                    <a href="#modalEditWinner" data-toggle="modal" class="button text-white button-info button-xs button-box"> <i class="zmdi zmdi-edit"></i></a>
                    <a class="button text-white button-dark button-xs button-box"> <i class="zmdi zmdi-cast"></i></a>
                </div>
            </div>
            <div class="box-body">
                <table class="table table-bordered" id="table-list-juara">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Peserta</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody id="list-winner">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- DAFTAR JURI --}}
<div class="box">
    <div class="box-head">
        <h4>Daftar Juri</h4>
    </div>
    <div class="box-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Slot Juri</th>
                    <th>Nama Juri</th>
                    <th>Email</th>
                    <th>Penilaian</th>
                </tr>
            </thead>
            <tbody id="list-juri">

            </tbody>
        </table>
    </div>
</div>
@endsection


@section('modal')
        <!-- Modal -->
        <div class="modal fade" id="modalEditWinner" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Juara</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <table class="table table-bordered" id="">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Peserta</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody id="winner-reference">
                                </tbody>
                            </table>
                        </div>
                        <div class="col-6">
                            @for ($i = 0; $i < 6; $i++)
                                <div class="form-group mb-3">
                                    <label for="">Juara {{$i+1}} <span class="text-danger">*</span></label>
                                    <input type="number" name="juara" class="form-control new-winner" required placeholder="No. Peserta">
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="update-winner" class="btn btn-primary">Simpan</button>
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
    const urlParams = new URLSearchParams(window.location.search);
    const id_criteria_content = atob(urlParams.get('idContent'));

    dataPenjurian = () => {
        let data;

        let base_url = window.location.origin;
        let enpoint = base_url+'/api/v1/contest/get-web-detail-participants-jury';

        $.ajax({
            type: "POST",
            url: enpoint,
            data : {
                'id_criteria_contents' : id_criteria_content,
                'id_contest' : id_contest,

            },
            async: false,
            dataType : 'json',
            success: function (response) {
                data = response.data;
            }
        });
        return data;
    }
    dataWinner = () => {
        let data;

        let base_url = window.location.origin;
        let enpoint = base_url+'/api/v1/contest/get-web-detail-participants-jury-winner';

        $.ajax({
            type: "POST",
            url: enpoint,
            data : {
                'id_criteria_contents' : id_criteria_content,
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
        var data = dataPenjurian();
        var winnerList = dataWinner();

        $("#prev_page").attr("href", `/lomba/detail/${last_segment}`);

        // INFO SESI PENJURIAN
        var infoSesi = data.detail_content;
        $('#title').html('Sesi '+infoSesi.criteria_name +' - '+ infoSesi.bird_name);
        $('#nama_kelas').html(infoSesi.criteria_name);
        $('#jenis_burung').html(infoSesi.bird_name);
        $('#kode_penjurian').html(infoSesi.jury_code);
        $('#jumlah_peserta').html(infoSesi.registered_participants);

        // DATA JURY
        var dataJuri = data.data_jury;
        $.map(dataJuri, function (val, idx) {
            let encodedId = btoa(val.id_jury);
            let juri = `
                <tr>
                    <td>${idx+1}</td>
                    <td>${val.jury_name}</td>
                    <td>${val.jury_email}</td>
                    <td class="text-center"><a href="/lomba/detail-penilaian-juri/${encodedId}?idContent=${btoa(id_criteria_content)}" class="button button-primary button-sm text-white font-weight-bold">Lihat Penilaian</a></td>
                </tr>
            `;
            $('#list-juri').append(juri);
        });

        // DATA KONCER
        var dataKoncer = data.detail_koncer;
        $.map(dataKoncer, function (val, idx) {
            let namaJuri = `<td>${val.jury_name}</td>`;
            let juriKoncer = '';

            $.map(val.data_koncer, function (koncer, idx) {
                let domDataKoncer = `<td>${koncer.contestant_number}</td>`;
                juriKoncer += domDataKoncer
            });

            let data = val.data_koncer;

            if (data.length < 6) {
                let loop = 6 - data.length;

                for (let index = 0; index < loop; index++) {
                    juriKoncer += '<td>-</td>'
                }
            }

            let domKoncer = `
                <tr>
                    ${namaJuri+juriKoncer}
                </tr>
            `;

            $('#list-koncer-position').append(domKoncer);
        });

        // DATA WINNER
        var allWinner  = '';
        var presentWinner = [];

        $.map(winnerList, function (val, idx) {
            let domWinner =  `
                <tr>
                    <td>${idx+1}</td>
                    <td>${val.contestant_number}</td>
                    <td>${val.count}</td>
                </tr>
            `

            let objWinner = {
                    contestant_number : val.contestant_number,
                    winner_position : idx+1,
                    id_criteria_contents : id_criteria_content,
                    is_winner : 0
            };

            allWinner += domWinner;

            if (idx <= 6) {
                presentWinner.push(objWinner);
                $('#list-winner').append(domWinner);
            }
        });

        // SET WINNER
        $(document).on('show.bs.modal', '#modalEditWinner', function () {
            if (allWinner === '') {
                $('#modalEditWinner .modal-body').html('<h3 class="text-center">Belum Ada List Juara</h3>');
            } else {
                $( "#winner-reference" ).html(allWinner);
            }
        });

        $(document).on('hidden.bs.modal', '#modalEditWinner', function () {
            $('.modal-body #winner-reference').html('');
        });

        // UPDATE WINNER
        $('#update-winner').click(function (e) {
            e.preventDefault();

            let newWinnerList = [];

            $('.new-winner').each(function (idx, val) {
                let newWinner = {
                    contestant_number : val.value,
                    winner_position : idx+1,
                    id_criteria_contents : id_criteria_content,
                    is_winner : 1
                }

                if (val.value !== "") {
                    newWinnerList.push(newWinner);
                }
            });

            let payload = presentWinner.concat(newWinnerList);

            console.log(payload);

            $.ajax({
                type: "POST",
                url: base_url+"/api/v1/contest/set-winner",
                data: {
                    winner : JSON.stringify(payload)
                },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        swal({
                        title: "Berhasil Edit Winner!",
                        text: 'Data Winner berhasil diedit.',
                        icon: "success"
                        }).then(() => {
                            window.location.reload()
                        })
                    }else{
                        swal({
                            title: "Gagal Edit Winner!",
                            icon: "error"
                        })
                    }
                }
            });

        });

        loadingFalse();
    });


</script>
@endsection
