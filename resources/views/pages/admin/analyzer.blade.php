@extends('layout.index', ['title' => 'Analizer', 'heading' => 'Analizer'])

@section('css')
<style>
.action-button{
    width: 75% !important;
    display: flex;
    justify-content: flex-end;
    align-items: center;
}
#filter-date{
    display: none;
}

.select2-container--default.select2 {
    width: 25% !important;
}
.header-print{
    visibility: hidden;
    display: none;
}

@media print {
  * {
    visibility: hidden;
  }
  .header-logo a img {
    visibility: hidden !important;
  }
  #section-to-print, #section-to-print *, .header-print {
    visibility: visible;
  }

  .header-print{
        display: block;
    }
  #section-to-print {
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    right: 0;
  }
}
</style>
@endsection

@section('action-button')
    <button class="button button-info button-lg button-outline" onclick="window.print()">Export PDF</button>
    <div class="adomx-checkbox-radio-group inline mr-2">
        <label class="adomx-radio-2" for="">Filter : </label>
        <label class="adomx-radio-2"><input type="radio" name="filter_type" value="contest" checked> <i class="icon"></i> Lomba</label>
        <label class="adomx-radio-2"><input type="radio" name="filter_type" value="date"> <i class="icon"></i> Tanggal</label>
    </div>
    <input type="date-local" class="form-control input-date w-25" id="filter-date">
    <select class="select-contest form-control w-25" name="id_contest" data-live-search="true" id="dd-filter-contest">
        <option value=""></option>
    </select>
@endsection

@section('contents')
    {{-- ANALIZER --}}
    <div id="section-to-print">
        <div class="header-print">
            <h3>Analizer</h3>
            <div class="text-body" id="detail-filter"></div>
            <hr>
        </div>
        <div class="col-12 mb-30">
            <div class="row">
                <div class="col-md-4">
                    <div class="box">
                        <div class="box-head font-weight-bold color-blue">
                            Total Peserta
                        </div>
                        <div class="box-body">
                            <h2 id="total_peserta">
                                -
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="box">
                        <div class="box-head font-weight-bold color-blue">
                            Total Juri
                        </div>
                        <div class="box-body">
                            <h2 id="total_juri">
                                -
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="box">
                        <div class="box-head font-weight-bold color-blue">
                            Total Lomba
                        </div>
                        <div class="box-body">
                            <h2 id="total_lomba">
                                -
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mb-30">
            <div class="row">
                <div class="col-md-4">
                    <div class="box">
                        <div class="box-head font-weight-bold color-blue">
                            Total Tiket
                        </div>
                        <div class="box-body">
                            <h2 id="total_tiket">
                                -
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="box">
                        <div class="box-head font-weight-bold color-blue">
                            Total Tiket Terjual
                        </div>
                        <div class="box-body">
                            <h2 id="total_tiket_terjual">
                                -
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="box">
                        <div class="box-head font-weight-bold color-blue">
                            Total Pemasuakan Tiket
                        </div>
                        <div class="box-body">
                            <h2 id="income_tiket">
                                -
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mb-30">
            <div class="row">
                <div class="col-md-4">
                    <div class="box">
                        <div class="box-head font-weight-bold color-blue">
                            Total Pemasukan
                        </div>
                        <div class="box-body">
                            <h2 id="total_income">
                                -
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="box">
                        <div class="box-head font-weight-bold color-blue">
                            Total Pengeluaran
                        </div>
                        <div class="box-body">
                            <h2 id="total_outcome">
                                -
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="box">
                        <div class="box-head font-weight-bold color-blue">
                            Total Keuntungan
                        </div>
                        <div class="box-body">
                            <h2 id="total_profit">
                                -
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/js/plugins/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/datatables/datatables.active.js') }}"></script>

    <!-- Plugins & Activation JS For Only This Page -->
    <script src="assets/js/plugins/moment/moment.min.js"></script>
    <script src="assets/js/plugins/daterangepicker/daterangepicker.js"></script>
    <script src="assets/js/plugins/daterangepicker/daterangepicker.active.js"></script>
    <script>
        listLomba = () => {
            let ArrLomba = [];

            let endpoint = base_url+'/api/v1/contest/get-web';

            $.ajax({
                type: "POST",
                url: endpoint,
                data : {
                    'status' : 'all'
                },
                async: false,
                dataType : 'json',
                success: function (response) {
                    let data = response.data;
                    $.map(data, function (val, indexOrKey) {
                        let contest ={
                            id : val.id,
                            text : val.name
                        }
                        ArrLomba.push(contest);
                    });
                }
            });

            return ArrLomba;
        };

        rupiahFormatter = (data) => {
            let rupiah = new Intl.NumberFormat("id-ID", {style: "currency", currency: "IDR"}).format(data);
            return rupiah;
        }

        updateDataPage = (data) => {
            $('#total_peserta').text(data.contestant_total);
            $('#total_juri').text(data.jury_total);
            $('#total_lomba').text(data.contest_total);
            $('#total_tiket').text(data.ticket_total);
            $('#total_tiket_terjual').text(data.ticket_sold);
            $('#income_tiket').text(rupiahFormatter(data.ticket_income));
            $('#total_income').text(rupiahFormatter(data.income_total));
            $('#total_outcome').text(rupiahFormatter(data.outcome_total));
            $('#total_profit').text(rupiahFormatter(data.profit_total));
        }
    </script>
    <script>
        $(document).ready(function () {

            dataContest = listLomba();

            $('.select-contest').select2({
                data: dataContest,
                width: 'resolve',
                placeholder: 'Pilih Kontes'
            })

            $('.select-contest').on('select2:select',function (e) {
                var id = parseInt(e.params.data.id);
                var filter = 'contest'

                $('#detail-filter').html(`Nama Lomba: `+e.params.data.text);
                $.ajax({
                    type: "POST",
                    url: base_url+'/api/v1/analyzer/get',
                    data: {
                        filter : filter,
                        id_contest : id
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            updateDataPage(response.data)
                        }else{
                            swal({
                                title: "Gagal Mendapatkan data!",
                                icon: "error"
                            })
                        }
                    }
                });
            });

            $('input[type=radio][name=filter_type]').change(function() {
                if (this.value == 'contest') {
                    $('#filter-date').css('display', 'none')
                    $('.select2-container--default.select2').css('display', 'block')
                }
                else if (this.value == 'date') {
                    $('#filter-date').css('display', 'block')
                    $('.select2-container--default.select2').css('display', 'none')
                }
            });

            $('.input-date').daterangepicker({
                locale: {
                    format: 'DD/MMMM/YYYY',
                    monthNames: [
                        "Januari",
                        "Februari",
                        "Maret",
                        "April",
                        "Mei",
                        "Juni",
                        "Juli",
                        "Augustus",
                        "September",
                        "Oktober",
                        "November",
                        "Desember"
                    ],
                }
            });

            $('.input-date').on('apply.daterangepicker', function(ev, picker) {
                var startDate = picker.startDate.format('YYYY-MM-DD');
                var endDate = picker.endDate.format('YYYY-MM-DD');
                var filter = 'date'

                $('#detail-filter').html(`Tanggal: ${picker.startDate.format('DD/MM/YYYY')+' - '+picker.endDate.format('DD/MM/YYYY')}`);


                $.ajax({
                    type: "POST",
                    url: base_url+'/api/v1/analyzer/get',
                    data: {
                        filter : filter,
                        date_start : startDate,
                        date_end : endDate,
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            updateDataPage(response.data)
                        }else{
                            swal({
                                title: "Gagal Mendapatkan data!",
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
