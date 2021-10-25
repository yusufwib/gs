@extends('layout.index', ['title' => 'Tambah Lomba', 'heading' => 'Lomba', 'sub' => 'Tambah Lomba'])

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/djibe/clockpicker@1d03466e3b5eebc9e7e1dc4afa47ff0d265e2f16/dist/bootstrap4-clockpicker.min.css"/>
<style>
    .select2-results__options {
        max-height: 500px;
    }

</style>
@endsection

@section('contents')
<form id="uploadform">
{{-- INFORMASI LOMBA --}}
    <div class="box">
        <div class="box-head">
            <h4>Informasi Lomba</h4>
        </div>
        <div class="box-body">
            <h4>Lampiran</h4>
            <input class="file-lampiran" type="file" multiple>
            <h4 class="mt-3">Tentang Lomba</h4>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="namaLomba">Nama Lomba <span class="text-danger">*</span> </label>
                    <input class="form-control" type="text" name="name" id="nama-kontes" required>
                </div>
                <div class="col-md-6">
                    <label for="">Kota <span class="text-danger">*</span> </label>
                    <select name="city" id="" class="form-control select-kota" >
                        <option value=""></option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="">Tanggal <span class="text-danger">*</span> </label>
                    <input class="form-control input-date-single" type="text" name="contest_date" id="tanggal-kontes" required>
                </div>
                <div class="col-md-4">
                    <div class="form-group clockpicker">
                        <label for="">Jam Mulai <span class="text-danger">*</span> </label>
                        <input class="form-control" type="text" name="contest_time" id="jam-kontes" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Kuota Per Jenis Burung <span class="text-danger">*</span> </label>
                        <select name="" id="kuota" class="form-control" placeholder="Kuota" disabled>
                            <option value="24">24</option>
                            <option value="36">36</option>
                            <option value="56">56</option>
                            <option value="60">60</option>
                            <option value="70">70</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="">Lokasi <span class="text-danger">*</span> </label>
                    <input class="form-control" type="text" name="location_address" id="lokasi-kontes" required>
                </div>
                <div class="col-md-6">
                    <label for="">Detail Lokasi <span class="text-danger">*</span> </label>
                    <input class="form-control" type="text" name="location_name" id="detail-lokasi-kontes" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="">Peraturan Lomba <span class="text-body-light">(opsional)</span> </label>
                    <textarea class="form-control" name="contest_terms" id="peraturan-kontes" cols="30" rows="10"></textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- KELAS --}}
    <div class="box">
        <div class="box-head">
            <h4>Kelas</h4>
        </div>
        <div class="box-body list-kelas">
        </div>
     </div>

     {{-- FORM FOOTER --}}
     <div class="form-foot mt-10">
        <div class="row">
            <div class="col-md-8">
                <div class="form-group d-flex align-items-start">
                </div>
            </div>

            <div class="col-md-4 d-flex justify-content-end">
                <input type="hidden" name="drafted" id="drafted" value="">
                <button type="submit" class="button button-outline button-success" id="submit_contest">Simpan</button>
            </div>
        </div>
     </div>
</form>
@endsection

@section('js')
<!-- Plugins & Activation JS For Only This Page -->
<script src="{{ asset('assets/js/plugins/filepond/filepond.min.js')}}"></script>
<script src="{{ asset('assets/js/plugins/filepond/filepond-plugin-image-exif-orientation.min.js')}}"></script>
<script src="{{ asset('assets/js/plugins/filepond/filepond-plugin-image-preview.min.js')}}"></script>
<script src="{{ asset('assets/js/plugins/filepond/filepond.active.js')}}"></script>
<script src="{{ asset('assets/js/plugins/moment/moment.min.js')}}"></script>
<script src="{{ asset('assets/js/plugins/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{ asset('assets/js/plugins/daterangepicker/daterangepicker.active.js')}}"></script>
<script src="{{ asset('assets/js/plugins/inputmask/bootstrap-inputmask.js')}}"></script>
<script src="https://cdn.jsdelivr.net/gh/djibe/clockpicker@6d385d49ed6cc7f58a0b23db3477f236e4c1cd3e/dist/bootstrap4-clockpicker.min.js"></script>
<script>
    var segment_str = window.location.pathname;
    var segment_array = segment_str.split( '/' );
    var last_segment = segment_array.pop();

    const id_contest = atob(last_segment);

    dataKota = () => {
        let data,kota;
        let ArrayKota = [];

        let base_url = window.location.origin;
        let enpoint = base_url+'/api/v1/contest/cities';

        $.ajax({
            type: "POST",
            url: enpoint,
            async: false,
            success: function (response) {
                data = response.data;
                $.each(data, function (indexInArray, val) {
                    kota = {
                        id : val.city_name_full,
                        text : val.city_name_full
                    }
                    ArrayKota.push(kota);
                });
            }
        });
        return ArrayKota;
    }
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
</script>
<script>
    var dataDraft = data();
    var dataKota = dataKota();

    var contest_criteria = [];

    $(document).ready(() => {
        const isFile = input => 'File' in window && input instanceof File;

        $('.select-kota').select2({
            placeholder: "Nama Kota",
            data: dataKota
        });

        // SELECT KUOTA
        $('#kuota').on('change', function() {
            if (contest_criteria.length != 0) {
                swal({
                    title: "Yakin Mengganti Kuota?",
                    text: 'Data kuota semua kelas akan dirubah!',
                    icon: "warning",
                    buttons: true,
                    dangerMode: true
                }).then((e) => {
                    if (e) {
                        $.map(contest_criteria, function (val, idx) {
                            $.map(val.birds, function (bird, idx) {
                                bird.participants = parseInt($("#kuota").find(":selected").val());
                            });
                        });

                        $('.kuota-burung').each(function () {
                            $(this).html($("#kuota").find(":selected").val());
                        });
                    }else{
                        let prevVal;
                        $.map(contest_criteria, function (val, idx) {
                            $.map(val.birds, function (bird, idx) {
                                 prevVal = bird.participants;
                            });
                        });

                        $("#kuota").val(prevVal);
                    }
                })
            }

        });

        $('.clockpicker').clockpicker({
            'default': 'now',
            vibrate: true,
            placement: "bottom",
            align: "left",
            autoclose: false,
            twelvehour: false
        });

        var banner = [];

        if (dataDraft.banner.length > 0) {
            $.map(dataDraft.banner, function (val, indexOrKey) {
                let img = {
                    source: base_url+val.url,
                    options: {type: "local"}
                }
                banner.push(img);
            });
        }
        //FILE DROP
        FilePond.registerPlugin(FilePondPluginImageExifOrientation, FilePondPluginImagePreview);
        const inputElement = document.querySelector('.file-lampiran');
        const pond = FilePond.create( inputElement, {
            server: {
                load: (source, load, error, progress, abort, headers) => {
                    var myRequest = new Request(source);
                    fetch(myRequest).then(function(response) {
                        response.blob().then(function(myBlob) {
                            let img = myBlob.slice(0, myBlob.size, "image/jpeg");
                            load(img);
                        });
                    });
                }
            },
            files:banner,
            imagePreviewHeight: 140,
            allowMultiple: true,
            instantUpload: false,
            allowProcess: false,
            labelIdle: 'Drag & Drop your files or <span class="filepond--label-action"> Browse </span>'
        });

        var submitted = 1;

        // UPLOAD CONTEST FORM
        $("#uploadform").submit(function (e) {
            e.preventDefault();

            $(document.body).css({'cursor' : 'wait'});

            var $inputs = $('#uploadform :input');
            var valuesSubmit = {};
            var listPanitia = [];
            var listJuri = [];

            var formdata = new FormData();
            pondFiles = pond.getFiles();

            if (pondFiles.length >= 1) {
                for (var i = 0; i < pondFiles.length; i++) {
                    if (isFile(pondFiles[i].file)) {
                        formdata.append('banner[]', pondFiles[i].file);
                    }
                    else{
                        let blobToFile = new File([pondFiles[i].file], `banner-${btoa(pondFiles[i].file.size)}.png`, {lastModified: Date.now(), lastModifiedDate: new Date(), type:'img/png'});
                        formdata.append('banner[]', blobToFile);
                    }
                }
            }else{
                $(document.body).css({'cursor' : 'default'});
                swal({
                    title: "Lampiran Lomba Tidak boleh kosong!",
                    icon: "error"
                })
                return false;
            }

            if (contest_criteria.length < 1) {
                $(document.body).css({'cursor' : 'default'});
                swal({
                    title: "Kelas Lomba Tidak boleh kosong!",
                    icon: "error"
                })
                return false;
            }

            $inputs.each(function() {
                valuesSubmit[this.name] = $(this).val();
            });

            let formatedDate = moment(valuesSubmit.contest_date).format('YYYY-MM-DD');
            let startRegister = moment().format('YYYY-MM-DD');

            let draft = $('#drafted').val();

            formdata.append('name', valuesSubmit.name);
            formdata.append('city', valuesSubmit.city);
            formdata.append('location_name', valuesSubmit.location_name);
            formdata.append('location_address', valuesSubmit.location_address);
            formdata.append('contest_terms', valuesSubmit.contest_terms);
            formdata.append('contest_time', valuesSubmit.contest_time);
            formdata.append('contest_date', formatedDate);
            formdata.append('start_register', startRegister);
            formdata.append('drafted', draft);
            formdata.append('id_contest', id_contest);


            let base_url = window.location.origin;
            let enpoint = base_url+'/api/v1/contest/update';

            if (submitted === 1) {
                $.ajax({
                    type: "POST",
                    url: enpoint,
                    data: formdata,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        $(document.body).css({'cursor' : 'default'});
                        if (response.success) {
                            swal({
                                title: "Berhasil Update Lomba!",
                                text: 'Data lomba berhasil diedit.',
                                icon: "success"
                            }).then(() => {
                                window.history.back()
                            })
                        } else {
                            submitted--;
                            swal({
                                title: "Gagal Update lomba!",
                                icon: "error"
                            })
                        }
                    }
                });
            }
            submitted++;
        });

        // INIT VALUE
        $('#nama-kontes').val(dataDraft.name);
        $('.select-kota').val(dataDraft.city).trigger('change');
        $('#tanggal-kontes').val(moment(dataDraft.contest_date).format('MM/DD/YYYY'));
        $('#jam-kontes').val(dataDraft.contest_time);
        $('#kuota').val(dataDraft.criteria[0].content[0].participants).trigger('change');
        $('#lokasi-kontes').val(dataDraft.location_address);
        $('#detail-lokasi-kontes').val(dataDraft.location_name);
        $('#peraturan-kontes').val(dataDraft.contest_terms);
        $('#drafted').val(dataDraft.drafted);

        // INIT VALUE KELAS
        $.map(dataDraft.criteria, function (val, idx) {

            var Arrprizes = [];
            var Arrbirds = [];
            var payload, domBirds = '', domPrize = '', domKelas;

            $.map(val.content, function (birds, idx) {
                let Objbird = {
                    bird_name : birds.bird_name,
                    participants : parseInt(birds.participants)
                }
                domBirds += `<div class="badge badge-primary mr-2">${birds.bird_name}</div>`
                Arrbirds.push(Objbird);
            });

            $.map(val.prize, function (prizes, idx) {
                let Objprizes = {
                    champion_title : prizes.champion_title,
                    champion_prize : prizes.champion_prize
                }
                domPrize += `<div>${prizes.champion_title} = ${prizes.champion_prize}</div>`
                Arrprizes.push(Objprizes);
            });

            let fixedPrize=true;

            payload = {
                criteria_name : val.criteria_name,
                registration_fee : parseInt(val.registration_fee),
                fixed_price : fixedPrize,
                birds : Arrbirds,
                prizes : Arrprizes
            }

            contest_criteria.push(payload);
            var kuota = $('#kuota').val();

            domKelas = `
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="">Nama Kelas</label>
                                        <div class="nama-kelas font-weight-bold">${val.criteria_name}</div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="">Harga Tiket</label>
                                        <div class="harga-tiket">${new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR" }).format(parseInt(val.registration_fee))}</div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="">Kuota Per Jenis Burung</label>
                                        <div class="kuota-burung">${kuota}</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="">Jenis Burung</label>
                                        ${domBirds}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="">Hadiah</label>
                                        ${domPrize}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `
            $('.list-kelas').append(domKelas);
        });

        loadingFalse();

    });
</script>
@endsection
