var segment_str = window.location.pathname;
var segment_array = segment_str.split( '/' );
var last_segment = segment_array.pop();

const id_contest = atob(last_segment);

data = () => {
    let data;

    let enpoint = base_url+'/api/v1/contest/get-web-detail';

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
};

peserta = () => {
    let data;

    let enpoint = base_url+'/api/v1/contest/get-web-detail-participants';

    $.ajax({
        type: "POST",
        url: enpoint,
        data : {
            'id_contest' : id_contest,
            'id_criteria' : 'all'
        },
        async: false,
        dataType : 'json',
        success: function (response) {
            data = response.data;
        }
    });
    return data;
};

masterTemplate = () => {
    let data;
    let masterTemplate = [];

    let enpoint = base_url+'/api/v1/random-number-template/get';

    $.ajax({
        type: "POST",
        url: enpoint,
        async: false,
        dataType : 'json',
        success: function (response) {
            data = response.data;

            data.map((val,index) => {
                let template ={
                    id : val.id,
                    text : val.name
                }
                masterTemplate.push(template);
            })
        }
    });
    return masterTemplate;
}
$(document).ready(function () {

    var dataLomba = data();
    var dataPeserta = peserta();
    var dataTemplate = masterTemplate();

    // HREF LINK
    $('#atur-block').attr('href', `/lomba/atur-block/${last_segment}`);
    $('#button-edit-lomba').attr('href', `/lomba/edit-lomba/${last_segment}`);
    $('#atur-juri').attr('href', `/lomba/edit-juri/${last_segment}`);
    $('#atur-penyelenggara').attr('href', `/lomba/edit-penyelenggara/${last_segment}`);


    //INIT SELECT
    $(".select-modal-kelas").select2()
    $(".select-modal-jenis").select2()

    let lampiran = dataLomba.banner;
    let domLampiran = '';

    $.map(lampiran, function (val, index) {
        let dom = `<img src="${base_url+val.url}" class="d-inline" width="250">`
        domLampiran += dom;
    });

    // INFORMASI LOMBA
    $('#lampiran').html(domLampiran);
    $('#contest_name').html(dataLomba.name);
    $('#city').html(dataLomba.city);
    $('#date').html(moment(dataLomba.contest_date).format('DD MMMM YYYY'));
    $('#start_time').html(dataLomba.contest_time);
    $('#location').html(dataLomba.location_name);
    $('#address').html(dataLomba.location_address);
    $('#terms').html(dataLomba.contest_terms);

    // KELAS LOMBA
    let domClasses = '';

    $.map(dataLomba.criteria, function (val, index) {

        // JENIS BURUNG
        let domBirds = ''
        $.map(val.content, function (element, idx) {
            let bird = `<div class="d-inline-block mb-1 mr-1 badge bg-blue text-white p-2">${element.bird_name}</div>`
            domBirds += bird;
        });

        // PRIZE
        let domPrizes = '';
        $.map(val.prize, function (element, idx) {
            let prize = `<div class="text-heading">${element.champion_title+' = '+element.champion_prize}</div>`
            domPrizes += prize;
        });

        let domClass = `
        <div class="card mb-5">
            <div class="card-body">
                <div class="row">

                    <div class="col-md-4 mb-10">
                        <div class="text-body-light">Nama Kelas</div>
                        <div class="font-weight-bold">${val.criteria_name}</div>
                        <br>
                        <div class="text-body-light">Harga Tiket</div>
                        <div class="text-heading">${new Intl.NumberFormat("id-ID", {style: "currency",currency: "IDR"}).format(val.registration_fee)}</div>
                        <br>
                        <div class="text-body-light">Kuota per Jenis Burung</div>
                        <div class="text-heading">${val.content[0].participants}</div>
                    </div>


                    <div class="col-md-4 mb-10">
                        <div class="text-body-light mb-10">Kuota per Jenis Burung</div>
                        ${domBirds}
                    </div>

                    <div class="col-md-4 mb-10">
                        <div class="text-body-light mb-10">Hadiah</div>
                        ${domPrizes}
                    </div>
                </div>
            </div>
        </div>`;

        domClasses += domClass;
    });
    $('#contest_criteria').html(domClasses);

    // JURY
    let domJurys = '';

    $.map(dataLomba.jury, function (val, index) {
        let domJury = `
            <div class="row">
                <div class="col-12">
                    <div class="text-body-light">Juri ${index+1}</div>
                    <div class="text-heading">${val.name}</div>
                </div>
            </div>
            <hr>`;

            domJurys += domJury;
    });
    $('#list-juri').html(domJurys);

    // ORGENIZER
    let domOrganizers = '';

    $.map(dataLomba.organizer, function (val, index) {
        let domOrganizer = `
            <div class="row">
                <div class="col-md-4 col-sm-6 col-6">
                    <div class="text-body-light">Nama</div>
                    <div class="text-heading">${val.name}</div>
                </div>
                <div class="col-md-4 col-sm-6 col-6">
                    <div class="text-body-light">Nomor Telepon</div>
                    <div class="text-heading">${val.phone}</div>
                </div>
            </div>
            <hr>`;

            domOrganizers += domOrganizer;
    });
    $('#organizer').html(domOrganizers);

    // LIST PESERTA
    var tablePeserta = $('#table-kelas-peserta').DataTable({
        data: dataPeserta,
        columns: [{
                data: null,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: "criteria_name"
            },
            {
                data: "bird_name"
            },
            {
                data: null,
                render: function (data, type, row, meta) {
                    return `<b>${row.registered_participants}</b>/${row.participants}`;
                }
            },
            {
                className: "text-center",
                data: "id_contest",
                render : function (data, type, row, meta){
                        let encodeId = btoa(data)
                        let encodeIdCriteria = btoa(row.id)
                        let title = row.criteria_name+' - '+row.bird_name;


                        return `<a href="/lomba/detail/list-peserta/${encodeId+'?idCriteria='+encodeIdCriteria+'&title='+btoa(title)}" class="button bg-blue text-white button-sm button-box"> <i class="zmdi zmdi-eye"></i></a>`
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

    // FILTER LIST KELAS
    var filterKelas = [];
    var filterBurung = [];

    dataLomba.criteria.map((val,idx) => {
        let criteria = {
            'id' : val.id,
            'text' : val.criteria_name
        }

        let birds = [];

        $.map(val.content, function (val, idx) {
            let bird = {
                'id' : val.id,
                'text' : val.bird_name
            }

            birds.push(bird);
        });

        let burungByKelas ={
            'id_kelas' : val.id,
            'jenis_burung' : birds
        }

        filterBurung.push(burungByKelas);
        filterKelas.push(criteria);
    });

    $('.filter-kelas').select2({
        placeholder: "Nama Kelas",
        data: filterKelas
    });
    var newOption = new Option('Semua Kelas', 'all', false, false);
    $('.filter-kelas').append(newOption).trigger('change');

    //FUNC UPDATE DATA TABLE
    updateDataTable = (data) => {
        tablePeserta.clear().draw();
        tablePeserta.rows.add(data); // Add new data
        tablePeserta.columns.adjust().draw(); // Redraw the DataTable
    }

    // FILTER BY KELAS
    $('.filter-kelas').on('select2:select',function (e) {
        var id = e.params.data.id;

        $.ajax({
            type: "POST",
            url: base_url+"/api/v1/contest/get-web-detail-participants",
            data: {
                'id_contest' : id_contest,
                'id_criteria': id
            },
            success: function (response) {
                if (response.success) {
                    updateDataTable(response.data)
                } else {
                    swal({
                        title: "Gagal Mendapatkan Data!",
                        icon: "error"
                    })
                }
            }
        });
    });

    // DROPDOWN KELAS MODAL
    $('.select-modal-kelas').select2({
        placeholder: "Nama Kelas",
        data: filterKelas,
        dropdownParent: $('#modalAddPeserta')
    });

    $('.select-modal-jenis').select2({
        placeholder: "Jenis Burung",
        data: filterBurung[0].jenis_burung,
        dropdownParent: $('#modalAddPeserta')
    });

    $('.select-modal-kelas').on('select2:select',function (e) {
        var id = parseInt(e.params.data.id);

        let dataBurung = filterBurung.find(element => element.id_kelas === id)

        $('.select-modal-jenis').html('');
        $('.select-modal-jenis').select2({placeholder: "Jenis Burung", data:dataBurung.jenis_burung, dropdownParent: $('#modalAddPeserta')});
        $('.select-modal-jenis').val('').trigger('change');

    });

    var jadwal = dataLomba.schedule;

    // LIST JADWAL
    if (jadwal.length === 0) {
        $('.jadwal').html(`<a href="/lomba/tambah-jadwal/${last_segment}" class="button button-outline button-primary">Buat Jadwal Lomba</a>`);
        $('.jadwal-create').append(`<a href="/lomba/tambah-jadwal/${last_segment}" class="btn btn-primary">+ Buat Jadwal Lomba</a>`);
    }
    else {
        let agendas= '';

        $('.jadwal').html(`<a href="#modalEditJadwal" data-toggle="modal" class="button button-outline button-primary">Edit Jadwal Lomba</a>`);
        $.map(jadwal, function (val, idx) {
            let agenda = `
            <div class="d-flex align-items-center mb-3">
                <span class="avatar bg-blue mr-3"><h4 class="text-white" style="margin-top: 0.5rem">${val.number}</h4></span>
                <h4 style="margin-top: 0.5rem">${val.criteria_content}</h4>
            </div>
            `;

            agendas += agenda;
        });

        let domAgenda = `
        <div class="box">
            <div class="box-head">
                <h4>Jadwal / Agenda</h4>
            </div>
            <div class="box-body">
                ${agendas}
            </div>
        </div>
        `;
        $('.jadwal-container').html(domAgenda);
    }

    // EDIT AGENDA
    $('#modalEditJadwal').on('show.bs.modal', function (e) {
        $.map(jadwal, function (val, idx) {
            var options = ''

            $.map(jadwal, function (val, index) {
                let domOption = '';

                if (idx === index) {
                    domOption = `<option value="${index+1}" selected="selected">${index+1}</option>`;
                }else{
                    domOption = `<option value="${index+1}">${index+1}</option>`;
                }

                options += domOption;
            });

            let domEditJadwal =`
            <div class="form-group edited-agenda mb-3">
                <label for="">Angenda ${idx+1}</label>
                <div class="row">
                    <div class="col-9 d-flex align-items-center">
                        <h5 class="agenda-name" >${val.criteria_content}</h5>
                    </div>
                    <div class="col-3">
                        <select class="form-control urutan-agenda">
                            ${options}
                        </select>
                        <input type="hidden" class="id-agenda" value=${val.id}>
                    </div>
                </div>
            </div>
            `
            $('.list-edit-agenda').append(domEditJadwal);
        });
    });

    $('#modalEditJadwal').on('hidden.bs.modal', function (e) {
        $('.list-edit-agenda').html('');
    });

    $('#editAgenda').click(function (e) {
        e.preventDefault();
        $('#editAgenda').prop('disable', true);

        let pass = true;

        $.each($('.edited-agenda'), function (idx, val) {
            let number = $('.urutan-agenda').eq(idx).val();
            let idContent = $('.id-agenda').eq(idx).val();
            let agendaName = $('.agenda-name').eq(idx).text();

            if (pass) {
                $.ajax({
                    type: "POST",
                    url: base_url+'/api/v1/contest/update-schedule',
                    data: {
                        id_contest : id_contest,
                        number : parseInt(number),
                        criteria_content : agendaName,
                        id : parseInt(idContent)
                    },
                    dataType: "json",
                    async: false,
                    success: function (response) {
                        if (response.success) {
                            console.log('good')
                        } else {
                            pass = false;
                        }
                    }
                });
            }else{
                swal({
                    title: "Gagal Edit Agenda!",
                    icon: "error"
                }).then(e => {
                    $('#editAgenda').prop('disable', false);
                    return false;
                })
            }

        });

        swal({
            title: "Berhasil Edit Agenda!",
            text: 'Edit agenda berhasil.',
            icon: "success"
        }).then(() => {
            window.location.reload()
        })
    });

    // BUY FROM ADMIN
    $('#idLomba').val(id_contest);

    $('#buyAdmin').submit(function (e) {
        e.preventDefault();

        var formdata = new FormData(this);

        $.ajax({
            type: "POST",
            url: base_url+'/api/v1/transaction/buy-from-admin',
            data: formdata,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success === true) {
                    swal({
                        title: "Berhasil Tambah Peserta!",
                        text: 'Data peserta berhasil ditambahkan.',
                        icon: "success"
                    }).then(() => {
                        window.location.reload()
                    })
                } else {
                    swal({
                        title: "Gagal Tambah Peserta!",
                        icon: "error"
                    })
                }
            }
        });
    });

    if (dataLomba.id_template_number !== null) {
        $('#button-acak').prop('disabled', true);
    }

    if (dataLomba.is_open !== 1) {
        $("#status_pendaftaran").prop("checked", false);
        $('#button-buy').prop('disabled', true);
        $("#button-edit-lomba").css("display", 'none');
    }
    else
    {
        $('#button-acak').prop('disabled', true);
    }

    if (dataLomba.contest_status !== 'upcoming') {
        $("#button-selesai").css("display", 'none');
        $("#button-edit-lomba").css("display", 'none');
        $("#status_pendaftaran").css("display", 'none');
    }

    // SESI PENJURIAN
    var sesiPenjurian = dataLomba.criteria;
    var domSesi = '';

    $.map(sesiPenjurian, function (val, idx) {
        $.map(val.content, function (valBirds, idx2) {
            let payloads = last_segment+'?idContent='+this.btoa(valBirds.id);
            let sesi =`
            <div class="box mb-3">
                <div class="box-head">
                    <h4>Sesi ${val.criteria_name + ' - ' + valBirds.bird_name}</h4>
                </div>
                <div class="box-body" id="sesi-penjurian">
                    <div class="row">
                        <div class="col-md-4">
                            <p>Kelas</p>
                            <p class="font-weight-bold">${val.criteria_name}</p>
                        </div>
                        <div class="col-md-4">
                            <p>Kode Penjuarian</p>
                            <p class="font-weight-bold">${valBirds.jury_code}</p>
                        </div>
                        <div class="col-md-4">
                            <a href='/lomba/detail-sesi-penjurian/${payloads}' class="button button-primary">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            </div>
            `;
            domSesi += sesi;
        });
    });

    $('#penjurian').html(domSesi);

    // ARRANGE PESERTA ? CONFIRM UCUP
    $('.select-modal-template').select2({
        placeholder: "Nama Template",
        data: dataTemplate,
        dropdownParent: $('#modalAcakNomor')
    });

    $('#modalAcakNomor').on('show.bs.modal', function (e) {
        let jumlah = dataPeserta[0].participants;
        $('#jumlah-peserta').html(jumlah);
        $('#idLombaAcak').val(id_contest);
    });

    $('#arrangePeserta').submit(function (e) {
        e.preventDefault();

        var formdata = new FormData(this);

        $.ajax({
            type: "POST",
            url: base_url+'/api/v1/contest/jugding/arrange-contestant',
            data: formdata,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success === true) {
                    swal({
                        title: "Berhasil Acak Nomor Peserta!",
                        text: 'Acak nomor perserta berhasil.',
                        icon: "success"
                    }).then(() => {
                        window.location.reload()
                    })
                } else {
                    swal({
                        title: "Gagal Acak Nomor Peserta!",
                        icon: "error"
                    })
                }
            }
        });

    });

    // KONTES STATUS
    $('#status_pendaftaran').change(function() {
        if(this.checked) {
            $.ajax({
                type: "POST",
                url: base_url+'/api/v1/contest/update-register-open',
                data: {
                    'id_contest' : id_contest,
                    'is_open' : 1
                },
                success: function (response) {
                    if (response.success === true) {
                        swal({
                            title: "Berhasil Membuka Pendaftaran!",
                            text: 'Pendaftaran berhasil dibuka.',
                            icon: "success"
                        }).then(() => {
                            window.location.reload()
                        });
                    } else {
                        swal({
                            title: "Gagal Membuka Pendaftaran!",
                            icon: "error"
                        })
                    }
                }
            });
        }else{
            $.ajax({
                type: "POST",
                url: base_url+'/api/v1/contest/update-register-open',
                data: {
                    'id_contest' : id_contest,
                    'is_open' : 0
                },
                success: function (response) {
                    if (response.success === true) {
                        swal({
                            title: "Berhasil Menutup Pendaftaran!",
                            text: 'Pendaftaran berhasil ditutup.',
                            icon: "success"
                        }).then(() => {
                            window.location.reload()
                        });
                    } else {
                        swal({
                            title: "Gagal Menutup Pendaftaran!",
                            icon: "error"
                        })
                    }
                }
            });
        }
    });

    // LOMBA SELESAI
    $('#button-selesai').click(function (e) {
        e.preventDefault();

        swal({
            title: "Yakin Mengubah Status Lomba?",
            text: 'Apakah anda yakin untuk mengubah status lomba menjadi selesai?',
            icon: "info",
            buttons: true,
            dangerMode: true
        }).then((e) => {
            if (e) {
                $.ajax({
                    type: "POST",
                    url: base_url+'/api/v1/contest/update-contest-status',
                    data: {
                        'id_contest' : id_contest,
                        'contest_status' : 'past'
                    },
                    success: function (response) {
                        if (response.success === true) {
                            swal({
                                title: "Berhasil Mengubah Status Lomba!",
                                text: 'Status lomba berhasil diganti.',
                                icon: "success"
                            }).then(() => {
                                window.location.reload()
                            });
                        } else {
                            swal({
                                title: "Gagal Mengubah Status Lomba!",
                                icon: "error"
                            })
                        }
                    }
                });
            }
        })

    });

    loadingFalse();
});
