@extends('layout.index', ['title' => 'Tambah Lomba', 'heading' => 'Lomba', 'sub' => 'Tambah Lomba'])

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/djibe/clockpicker@1d03466e3b5eebc9e7e1dc4afa47ff0d265e2f16/dist/bootstrap4-clockpicker.min.css">
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
                    <input class="form-control" type="text" name="name" id="" required>
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
                    <input class="form-control input-date-single" type="text" name="contest_date" id="" required>
                </div>
                <div class="col-md-4">
                    <div class="form-group clockpicker">
                        <label for="">Jam Mulai <span class="text-danger">*</span> </label>
                        <input class="form-control" type="text" name="contest_time" id="" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Kuota Per Jenis Burung <span class="text-danger">*</span> </label>
                        <select name="" id="kuota" class="form-control" placeholder="Kuota">
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
                    <input class="form-control" type="text" name="location_address" id="" required>
                </div>
                <div class="col-md-6">
                    <label for="">Detail Lokasi <span class="text-danger">*</span> </label>
                    <input class="form-control" type="text" name="location_name" id="" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="">Peraturan Lomba <span class="text-body-light">(opsional)</span> </label>
                    <textarea class="form-control" name="contest_terms" id="" cols="30" rows="10"></textarea>
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
        <hr>
        <div class="box-foot text-center">
            <button type="button" class="button button-primary" data-toggle="modal" data-target="#modalAddKelas">+ Tambah Kelas</button>
        </div>
     </div>
     {{-- JURI --}}
     <div class="box">
         <div class="box-head d-flex align-items-center">
            <h4 class="mr-4">Juri</h4>
            <div class="check-box d-flex mb-2">
                <input type="checkbox" name="skipJuri" id="skipJuri" value="1">
                <label for="skipJuri">Isi juri nanti</label>
            </div>
         </div>
         <div class="juri-nanti">
             <div class="box-body">
                 <div class="form-group list-juri">
                     <div class="row mb-3">
                         <div class="col-11">
                             <label for="juri">Juri 1</label>
                             <select class="form-control select-juri" name="juri[]">
                                 <option></option>
                             </select>
                         </div>
                         <div class="col-1 d-flex align-items-center">
                            <a class="button button-danger button-box text-white mt-4 delete-juri">
                                <i class="fa fa-trash"></i>
                            </a>
                         </div>
                     </div>
                </div>
             </div>
             <div class="box-foot text-center">
                <div class="button button-primary" id="add-juri">+ Tambah Juri</div>
            </div>
         </div>
     </div>
     {{-- PANITIA --}}
     <div class="box">
         <div class="box-head">
            <h4>Penyelenggara Lomba</h4>
         </div>
         <div class="box-body list-panitia">
             <div class="row row-group mb-3">
                <div class="col-11">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Nama</label>
                                <input type="text" name="nama-penyelenggara[]" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                               <label for="">Nomor Telepon</label>
                               <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">+62</div>
                                    </div>
                                    <input type="text" class="form-control" name="nomor[]">
                                </div>
                           </div>
                        </div>
                    </div>
                </div>
                <div class="col-1 d-flex align-items-center">
                    <a class="button button-danger button-box text-white mb-0 mt-3 delete-panitia">
                        <i class="fa fa-trash"></i>
                    </a>
                 </div>
            </div>
         </div>
         <div class="box-foot text-center">
            <div class="button button-primary" id="add-panitia">+ Tambah Panitia</div>
        </div>
     </div>

     {{-- FORM FOOTER --}}
     <div class="form-foot mt-10">
        <div class="row">
            <div class="col-md-8">
                <div class="form-group d-flex align-items-start">
                    <input type="checkbox" name="draft" id="draft">
                    <div class="ml-2 text">
                        <label class="mb-0" for="draft"><b>Simpan ke Draft</b></label>
                        <small>Anda dapat menggunakan menggunakan template yang sama untuk lomba selanjutnya</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 d-flex justify-content-end">
                <input type="hidden" name="fill_jury" id="fill_jury" value="1">
                <input type="hidden" name="drafted" id="drafted" value="0">
                <button type="submit" class="button button-outline button-success" id="submit_contest">Simpan</button>
            </div>
        </div>
     </div>
</form>
@endsection

@section('modal')
{{-- Modal Tambah Kelas --}}
<div class="modal fade" id="modalAddKelas" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Tambah Kelas</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="addKelas">
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Nama Kelas <span class="text-danger">*</span> </label>
                    <input class="form-control" type="text" name="nama_kelas" required>
                </div>
                <div class="form-group">
                    <label for="">Harga Tiket <span class="text-danger">*</span> </label>
                    <input class="form-control" type="number" name="harga_tiket" required>
                </div>
                <div class="form-group">
                    <label for="">Jenis Burung <span class="text-danger">*</span> </label>
                    <select class="form-control select-tag" name="jenis_burung" id="jenis_burung" multiple required>
                    </select>
                </div>
                <div class="mb-2 mt-3">Model Hadiah</div>
                <div class="adomx-checkbox-radio-group inline">
                    <label class="adomx-radio-2"><input type="radio" name="model_hadiah" value="fixed" checked> <i class="icon"></i> Tetap</label>
                    <label class="adomx-radio-2"><input type="radio" name="model_hadiah" value="notFix"> <i class="icon"></i> Menyesuaikan</label>
                </div>
                <hr>
                <div class="list-juara mb-3">
                    <div class="row">
                        <div class="col-5">
                            <input type="text" class="form-control" name="nama-juara[]" value="Juara 1">
                        </div>
                        <div class="col-5">
                            <input type="text" class="form-control" name="value-juara[]" value="Sembako">
                        </div>
                        <div class="col-2">
                            <div class="button button-danger text-white button-sm button-box delete-juara mt-2"> <i class="fa fa-trash"></i> </div>
                        </div>
                    </div>
                </div>
                <div class="button button-primary button-sm" id="add-juara">+ Tambah Juara</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Modal Edit Kelas --}}
<div class="modal fade" id="modalEditKelas" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Edit Kelas</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="editKelas">
            <div class="modal-body">
                <input type="hidden" name="id_kelas" id="id_kelas">
                <div class="form-group">
                    <label for="">Nama Kelas <span class="text-danger">*</span> </label>
                    <input class="form-control" type="text" name="nama_kelas" id="nama_kelas" required>
                </div>
                <div class="form-group">
                    <label for="">Harga Tiket <span class="text-danger">*</span> </label>
                    <input class="form-control" type="number" name="harga_tiket" id="harga_tiket" required>
                </div>
                <div class="form-group">
                    <label for="">Jenis Burung <span class="text-danger">*</span> </label>
                    <select class="form-control" name="jenis_burung_edit" id="edit-burung" multiple required>
                    </select>
                </div>
                <div class="mb-2 mt-3">Model Hadiah</div>
                <div class="adomx-checkbox-radio-group inline">
                    <label class="adomx-radio-2"><input type="radio" name="model_hadiah_edit" value="fixed" required> <i class="icon"></i> Tetap</label>
                    <label class="adomx-radio-2"><input type="radio" name="model_hadiah_edit" value="notFixed"> <i class="icon"></i> Menyesuaikan</label>
                </div>
                <hr>
                <div class="list-juara-edit mb-3">
                </div>
                <div class="button button-primary button-sm" id="add-juara-edit">+ Tambah Juara</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
      </div>
    </div>
  </div>
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
    dataJuri = () => {
        let data;

        let base_url = window.location.origin;
        let enpoint = base_url+'/api/v1/account/get-by-role-type';
        let dataJuri = [];

        $.ajax({
            type: "POST",
            url: enpoint,
            async: false,
            data : {
                'role_type' : 'jury'
            },
            dataType : 'json',
            success: function (response) {
                data = response.data;

                data.map((val,index) => {
                    let juri ={
                        id : val.id,
                        text : val.name
                    }
                    dataJuri.push(juri);
                })
            }
        });
        return dataJuri;
    }
</script>
<script>
    var dataKota = dataKota();
    var dataJuri = dataJuri();
    var jumlahJuri = 1;

    var contest_criteria = [];

    $(document).ready(() => {

        //INIT SELECT TAG
        $(".select-tag").select2({
            tags: true,
            tokenSeparators: [','],
        });

        $('.select-kota').select2({
            placeholder: "Nama Kota",
            data: dataKota
        });

        $('.select-juri').select2({
            placeholder: "Nama Juri",
            data: dataJuri
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

        // JURI
        $("#skipJuri").change(function() {
            if(this.checked) {
                $('.juri-nanti').css('display', 'none');
                $('#fill_jury').val("0");
            }else{
                $('.juri-nanti').css('display', 'block');
                $('#fill_jury').val("1");
            }
        });

        $("#draft").change(function() {
            if(this.checked) {
                $('#drafted').val("1");
            }else{
                $('#drafted').val("0");
            }
        });

        // CREATE JURI
        $('#add-juri').click(function () {
            jumlahJuri++;

            let newJuri = `<div class="row mb-3">
                        <div class="col-11">
                            <label for="juri">Juri ${jumlahJuri}</label>
                            <select class="form-control select-juri" name="juri[]">
                                <option></option>
                            </select>
                        </div>
                        <div class="col-1 d-flex align-items-center">
                        <a class="button button-danger button-box text-white mt-4 delete-juri" style="">
                            <i class="fa fa-trash"></i>
                        </a>
                        </div>
                    </div>`;

            $('.list-juri').append(newJuri);

            $('.select-juri').select2({
                placeholder: "Nama Juri",
                data: dataJuri
            });
        });

        // CREATE PANITIA
        $('#add-panitia').click(function () {

            let newPanitia = `
            <div class="row row-group mb-3">
                <div class="col-11">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Nama</label>
                                <input type="text" name="nama-penyelenggara[]" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Nomor Telepon</label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">+62</div>
                                </div>
                                <input type="text" class="form-control" name="nomor[]">
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="col-1 d-flex align-items-center">
                    <div class="button button-danger button-box text-white mb-0 mt-3 delete-panitia">
                        <i class="fa fa-trash"></i>
                    </div>
                </div>
            </div>`;

            $('.list-panitia').append(newPanitia);
        });

        $(".select-juri").on("select2:select", function (evt) {
            var element = evt.params.data.element;
            var $element = $(element);
            $element.detach();
            $(this).append($element);
            $(this).trigger("change");
        });

        //FILE DROP
        FilePond.registerPlugin(FilePondPluginImageExifOrientation, FilePondPluginImagePreview);
        const inputElement = document.querySelector('.file-lampiran');
        const pond = FilePond.create( inputElement, {
            imagePreviewHeight: 140,
            allowMultiple: true,
            instantUpload: false,
            allowProcess: false
        });

        // CREATE MODAL
        $('#add-juara').click(function () {
            let newJuara = `<div class="row">
                    <div class="col-5">
                        <input type="text" class="form-control" name="nama-juara[]" value="Juara">
                    </div>
                    <div class="col-5">
                        <input type="text" class="form-control" name="value-juara[]">
                    </div>
                    <div class="col-2">
                        <div class="button button-danger text-white button-sm button-box delete-juara mt-2"> <i class="fa fa-trash"></i> </div>
                    </div>
                </div>`;

            $('.list-juara').append(newJuara);
        });

        // EDIT MODAL
        $('#add-juara-edit').click(function () {
            let newJuara = `<div class="row">
                    <div class="col-5">
                        <input type="text" class="form-control" name="nama-juara-edit[]" value="Juara">
                    </div>
                    <div class="col-5">
                        <input type="text" class="form-control" name="value-juara-edit[]">
                    </div>
                    <div class="col-2">
                        <div class="button button-danger text-white button-sm button-box delete-juara-edit mt-2"> <i class="fa fa-trash"></i> </div>
                    </div>
                </div>`;

            $('.list-juara-edit').append(newJuara);
        });

        // SUBMIT LIST KELAS
        $('#addKelas').submit(function (e) {
                e.preventDefault();
                // get all the inputs into an array.
                var $inputs = $('#addKelas :input');
                var kuota = $('#kuota').val();


                var values = {};
                var Arrprizes = [];
                var Arrbirds = [];
                var payload, domBirds = '', domPrize = '', domKelas;

                $inputs.each(function() {
                    values[this.name] = $(this).val();
                });

                $('input[name="nama-juara[]"]').each(function (index, member) {
                    let title = $(member).val();
                    let prize = $(`input[name="value-juara[]"]`).eq(index).val();
                    let Objprizes = {
                        champion_title : title,
                        champion_prize : prize
                    }

                    domPrize += `<div>${title} = ${prize}</div>`
                    Arrprizes.push(Objprizes);
                });

                $(values.jenis_burung).each(function (index, member) {
                    let Objbird = {
                        bird_name : member,
                        participants : parseInt(kuota)
                    }

                    domBirds += `<div class="badge badge-primary mr-2">${member}</div>`

                    Arrbirds.push(Objbird);
                });

                let prizeModel = $('input[name="model_hadiah"]:checked').val();
                let fixedPrize;

                if (prizeModel === "fixed") {
                    fixedPrize = true
                }else{
                    fixedPrize = false
                }

                payload = {
                    criteria_name : values.nama_kelas,
                    registration_fee : parseInt(values.harga_tiket),
                    fixed_price : fixedPrize,
                    birds : Arrbirds,
                    prizes : Arrprizes
                }

                contest_criteria.push(payload);

                domKelas = `
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="">Nama Kelas</label>
                                        <div class="nama-kelas font-weight-bold">${values.nama_kelas}</div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="">Harga Tiket</label>
                                        <div class="harga-tiket">${new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR" }).format(parseInt(values.harga_tiket))}</div>
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
                        <div class="card-footer d-flex justify-content-end">
                            <div class="button button-outline button-success mr-3 edit-kelas">Edit Kelas</div>
                            <div class="button button-outline button-danger delete-kelas">Hapus Kelas</div>
                        </div>
                    </div>
                `
                $('.list-kelas').append(domKelas);

                $('#modalAddKelas').modal('hide');
        });

        // SUBMIT CONTEST FORM
        // $('#submit_contest').click(function (e) {
        //     e.preventDefault();
        // });
        var submitted = 1;

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
                    formdata.append('banner[]', pondFiles[i].file);
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

            let fillJuri = $('#fill_jury').val();
            let draft =$('#drafted').val();

            $('input[name="nama-penyelenggara[]"]').each(function (index, member) {
                let panitiaName = $(member).val();
                let nomor = $(`input[name="nomor[]"]`).eq(index).val();
                let Objpanitia = {
                    name : panitiaName,
                    phone : '+62'+nomor
                }
                listPanitia.push(Objpanitia);
            });

            $('.select-juri').each(function (index, member) {
                let idJuri = $(member).val();
                let Objjuri = {
                    id_jury : idJuri,
                }
                listJuri.push(Objjuri);
            });

            formdata.append('name', valuesSubmit.name);
            formdata.append('city', valuesSubmit.city);
            formdata.append('location_name', valuesSubmit.location_name);
            formdata.append('location_address', valuesSubmit.location_address);
            formdata.append('contest_terms', valuesSubmit.contest_terms);
            formdata.append('contest_time', valuesSubmit.contest_time);
            formdata.append('contest_date', formatedDate);
            formdata.append('start_register', startRegister);
            formdata.append('contest_criteria', JSON.stringify(contest_criteria));
            formdata.append('fill_jury', fillJuri);
            formdata.append('drafted', draft);
            if (parseInt(fillJuri) === 1) {
                formdata.append('list_jury', JSON.stringify(listJuri));
            }
            formdata.append('contest_organizer', JSON.stringify(listPanitia));

            let base_url = window.location.origin;
            let enpoint = base_url+'/api/v1/contest/create';

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
                                title: "Berhasil Tambah Lomba!",
                                text: 'Data lomba berhasil dibuat.',
                                icon: "success"
                            }).then(() => {
                                window.location.href = '/lomba/lomba-mendatang'
                            })
                        } else {
                            submitted--;
                            swal({
                                title: "Gagal Tambah lomba!",
                                icon: "error"
                            })
                        }
                    }
                });
            }
            submitted++;
        });

        loadingFalse();

        $('#modalEditKelas').on('hidden.bs.modal', function (e) {
            $('#edit-burung').val(null).trigger('change');
            $('#edit-burung').html('');
            $('.list-juara-edit').html('');
        })
    });

    // DELETE JURI
    $(document).on('click', '.delete-juri', function (e) {
        // Mendapatkan posisi index Component
        let index = $('.delete-juri').index(this)
        if (jumlahJuri > 1) {
            jumlahJuri--;
        }

        if (index != 0) {
            $('.list-juri .row').eq(index).remove();
        }else{
            swal({
                title: "Juri Tidak Boleh Kosong!",
                text: 'Juri harus ada minimal 1 juri.',
                icon: "error"
            })
        }
    });

    // DELETE PANITIA
    $(document).on('click', '.delete-panitia', function (e) {

        // Mendapatkan posisi index Component
        let index = $('.delete-panitia').index(this)
        console.log(index)

        if (index != 0) {
            $('.list-panitia .row-group').eq(index).remove();
        }else{
            swal({
                title: "Penyelenggara Tidak Boleh Kosong!",
                text: 'Penyelenggara harus ada minimal 1 penyelenggara.',
                icon: "error"
            })
        }
    });

    // DELETE JUARA MODAL CREATE
    $(document).on('click', '.delete-juara', function (e) {
        // Mendapatkan posisi index Component
        let index = $('.delete-juara').index(this)

        if (index != 0) {
            $('.list-juara .row').eq(index).remove();
        }else{
            swal({
                title: "Juara Tidak Boleh Kosong!",
                text: 'Juara harus diisi',
                icon: "error"
            })
        }
    });

    // DELETE JUARA MODAL EDIT
    $(document).on('click', '.delete-juara-edit', function (e) {
        // Mendapatkan posisi index Component
        let index = $('.delete-juara-edit').index(this)

        if (index != 0) {
            $('.list-juara-edit .row').eq(index).remove();
        }else{
            swal({
                title: "Juara Tidak Boleh Kosong!",
                text: 'Juara harus diisi',
                icon: "error"
            })
        }
    });

    // DELETE KELAS
    $(document).on('click', '.delete-kelas', function (e) {
        // Mendapatkan posisi index Component
        let index = $('.delete-kelas').index(this);

        if (index != 0) {
            swal({
                title: "Yakin Menghapus Kelas ?",
                text: 'Anda Harus memasukkan data kembali.',
                icon: "warning",
                buttons: true
            }).then((e) => {
                if (e) {
                    contest_criteria.splice(index, 1);
                    $('.list-kelas .card').eq(index).remove();
                }
            })
        }else{
            swal({
                title: "Kelas Tidak Boleh Kosong!",
                text: 'Harus ada minimal 1 kelas didalam 1 kontes.',
                icon: "error"
            })
        }
    });

    //EDIT KELAS MODAL TRIGGER
    $(document).on('click', '.edit-kelas', function (e) {
        // Mendapatkan posisi index Component
        let index = $('.edit-kelas').index(this);

        let data = contest_criteria[index];

        $('#id_kelas').val(index);
        $('#nama_kelas').val(data.criteria_name);
        $('#harga_tiket').val(data.registration_fee);

        let tagValues = [];

        $('#edit-burung').select2({
            tags: true,
            tokenSeparators: [','],
        });

        let dataBirds = [];

        data.birds.map((val,index) => {
            var newOption = new Option(val.bird_name, val.bird_name, true, true);
            $('#edit-burung').append(newOption).trigger('change');
        });

        // $('#edit-burung').val(dataBirds).trigger('change');

        if (data.fixed_price) {
            $("input[name=model_hadiah_edit][value='fixed']").prop("checked",true);
        }else{
            $("input[name=model_hadiah_edit][value='notFixed']").prop("checked",true);
        }

        data.prizes.map((val,index) => {
            let newJuara = `<div class="row">
                    <div class="col-5">
                        <input type="text" class="form-control" name="nama-juara-edit[]" value="${val.champion_title}">
                    </div>
                    <div class="col-5">
                        <input type="text" class="form-control" name="value-juara-edit[]" value="${val.champion_prize}">
                    </div>
                    <div class="col-2">
                        <div class="button button-danger text-white button-sm button-box delete-juara-edit mt-2"> <i class="fa fa-trash"></i> </div>
                    </div>
                </div>`;

            $('.list-juara-edit').append(newJuara);
        });

        $('#modalEditKelas').modal('show');
    });

    // SUBMIT EDIT KELAS
    $(document).on('submit', '#editKelas', function (e) {
        e.preventDefault();

        // get all the inputs into an array.
        var $inputs = $('#editKelas :input');
        var kuotaEdit = $('#kuota').val();
        var valuesEdit = {};

        $inputs.each(function() {
            valuesEdit[this.name] = $(this).val();
        });

        var editedArrPrizes = [];
        var editedArrBirds = [];
        var payloadEdit, domPrizeEdit='', domBirdsEdit='';

        $('input[name="nama-juara-edit[]"]').each(function (index, member) {
            let title = $(member).val();
            let prize = $(`input[name="value-juara-edit[]"]`).eq(index).val();
            let Objprizes = {
                champion_title : title,
                champion_prize : prize
            }

            domPrizeEdit += `<div>${title} = ${prize}</div>`
            editedArrPrizes.push(Objprizes);
        });

        $(valuesEdit.jenis_burung_edit).each(function (index, member) {
            let Objbird = {
                bird_name : member,
                participants : kuotaEdit
            }

            domBirdsEdit += `<div class="badge badge-primary mr-2">${member}</div>`

            editedArrBirds.push(Objbird);
        });

        let prizeModel = $('input[name="model_hadiah_edit"]:checked').val();
        let fixedPrize;

        if (prizeModel === "fixed") {
            fixedPrize = true
        }else{
            fixedPrize = false
        }

        payloadEdit = {
            criteria_name : valuesEdit.nama_kelas,
            registration_fee : parseInt(valuesEdit.harga_tiket),
            fixed_price : fixedPrize,
            birds : editedArrBirds,
            prizes : editedArrPrizes
        }

        contest_criteria[valuesEdit.id_kelas] = payloadEdit;

        console.log(contest_criteria[valuesEdit.id_kelas])

        domKelasEdit = `
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="">Nama Kelas</label>
                                <div class="nama-kelas font-weight-bold">${valuesEdit.nama_kelas}</div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Harga Tiket</label>
                                <div class="harga-tiket">${new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR" }).format(parseInt(valuesEdit.harga_tiket))}</div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Kuota Per Jenis Burung</label>
                                <div class="harga-tiket">${kuotaEdit}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="">Jenis Burung</label>
                                ${domBirdsEdit}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="">Hadiah</label>
                                ${domPrizeEdit}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <div class="button button-outline button-success mr-3 edit-kelas">Edit Kelas</div>
                    <div class="button button-outline button-danger delete-kelas">Hapus Kelas</div>
                </div>
        `
        $('.list-kelas .card').eq(valuesEdit.id_kelas).html(domKelasEdit);

        $('#modalEditKelas').modal('hide');
    });
</script>
@endsection
