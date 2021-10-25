@extends('layout.index', ['title' => 'Tambah Jadwal', 'heading' => 'Jadwal', 'prev' => 'Detail Lomba'])

@section('contents')
<form id="createAgenda">
<div class="box mb-3">
    <div class="box-head d-flex justify-content-between align-items-center">
        <h4>Agenda</h4>
    </div>
        <div class="box-body list-agenda">
            <div class="form-group mb-3">
                <div class="row">
                    <div class="col-11">
                        <label for="">Agenda 1</label>
                        <select name="" id="" class="form-control select-agenda">
                        </select>
                    </div>
                    <div class="col-1">
                        <a class="button button-danger button-box text-white mt-25 delete-agenda">
                            <i class="fa fa-trash"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="box-footer d-flex justify-content-center p-3">
            <div class="btn btn-primary" id="add-agenda">+ Tambah Agenda Lomba</div>
        </div>
    </div>
    <button type="submit" class="button button-outline button-success float-right">Simpan</button>
</form>
@endsection

@section('js')
<script src="{{ asset('assets/js/plugins/datatables/datatables.min.js')}}"></script>
<script src="{{ asset('assets/js/plugins/datatables/datatables.active.js')}}"></script>
<script>
    var segment_str = window.location.pathname;
    var segment_array = segment_str.split( '/' );
    var last_segment = segment_array.pop();

    const id_contest = atob(last_segment);
    var jumlahAgenda = 1;

    data = () => {
        let ArrJadwal = [];

        let endpoint = base_url+'/api/v1/contest/get-web-detail';

        $.ajax({
            type: "POST",
            url: endpoint,
            data : {
                'id_contest' : id_contest
            },
            async: false,
            dataType : 'json',
            success: function (response) {
                let data = response.data;
                $.map(data.criteria, function (val, idx) {
                    $.map(val.content, function (bird, idx) {
                        let option = bird.bird_name+' - '+val.criteria_name;

                        ArrJadwal.push(option);
                    });
                });
            }
        });

        return ArrJadwal;
    };
</script>
<script>
    $(document).ready(function () {
        var dataOption = data();

        $("#prev_page").attr("href", `/lomba/detail/${last_segment}`);

        $.map(dataOption, function (element, key) {
            $('.select-agenda').append(`<option value="${element}">${element}</option>`);
        });

        // CREATE AGENDA
        $('#add-agenda').click(function () {
            jumlahAgenda++;


            let newOption = dataOption;
            let selectedOption = [];
            let domOption = '';

            $.each($('.select-agenda'), function (idx, val) {
                let selected = $('.select-agenda').eq(idx).find(":selected").val();
                selectedOption.push(selected);
            });

            $.map(newOption, function (val, idx) {
                if (!selectedOption.includes(val)) {
                    domOption += `<option value="${val}">${val}</option>`;
                }
            });

            let newAgenda= `
            <div class="form-group mb-3">
                    <div class="row">
                        <div class="col-11">
                            <label for="">Agenda ${jumlahAgenda}</label>
                            <select name="" id="" class="form-control select-agenda">
                                ${domOption}
                            </select>
                        </div>
                        <div class="col-1">
                            <a class="button button-danger button-box text-white mt-25 delete-agenda">
                                <i class="fa fa-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>`;

            $('.list-agenda').append(newAgenda);
        });

        // POST AGENDA
        $('#createAgenda').submit(function (e) {
            e.preventDefault();
            let endpoint = base_url+'/api/v1/contest/add-schedule';
            let count = $('.select-agenda').length;

            $('.select-agenda').each(function (idx, val) {
                let selected = $('.select-agenda').eq(idx).find(":selected").val();
                let number = idx+1;

                $.ajax({
                    type: "POST",
                    url: endpoint,
                    data: {
                       'id_contest' : id_contest,
                       'number' : number,
                       'criteria_content' : selected
                    },
                    async: false,
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            console.log('good')
                        }else{
                            swal({
                                title: "Gagal Tambah agenda!",
                                icon: "error"
                            })
                        }
                    }
                });

                if (!--count) {
                    swal({
                        title: "Berhasil Tambah Agenda!",
                        text: 'Data agenda berhasil dibuat.',
                        icon: "success"
                    }).then(() => {
                        window.location.href = `/lomba/detail/${last_segment}`
                    });
                };
            });


        });
        loadingFalse();
    });
    // DELETE AGENDA
    $(document).on('click', '.delete-agenda', function (e) {
        // Mendapatkan posisi index Component
        let index = $('.delete-agenda').index(this)
        if (jumlahAgenda > 1) {
            jumlahAgenda--;
        }

        if (index != 0) {
            $('.list-agenda .row').eq(index).remove();
        }else{
            swal({
                title: "Agenda Tidak Boleh Kosong!",
                text: 'Agenda harus ada minimal 1 agenda.',
                icon: "error"
            })
        }
    });
</script>
@endsection
