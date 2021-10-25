@extends('layout.index', ['title' => 'Detail Lomba', 'heading' => 'Detail Lomba', 'prev' => 'Mendatang', 'link' => '/lomba/lomba-mendatang'])

@section('css')
<style>
    .peserta{
        display: none;
    }
    .jadwal{
        display: none;
    }
    .penjurian{
        display: none;
    }
    .jadwal-create{
        background: rgba(66, 139, 250, 0.05);
        text-align: center;
        width: 100%;
        padding: 5%;
    },
</style>
@endsection

@section('contents')
<div class="wrapper mt-25">
    <div class="row">
        <div class="col-md-6">
            {{-- TABS --}}
            <ul class="nav nav-tabs">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" data-page="informasi" href="#informasi">Informasi</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" data-page="peserta" href="#peserta">Peserta</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" data-page="jadwal" href="#jadwal">Jadwal</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" data-page="penjurian" href="#penjurian">Penjurian</a></li>
            </ul>
        </div>
        <div class="col-md-6">
            <div class="action-button-wrapper float-right">
                <div class="informasi">
                    <button class="button button-outline button-warning" id="button-selesai">Lomba Selesai</button>
                    <a class="button button-outline button-success" id="button-edit-lomba">Edit Informasi</a>
                </div>
                <div class="peserta">
                    <div class="card">
                        <div class="card-body" style="padding: 0.5rem">
                            <div class="adomx-checkbox-radio-group">
                                <label class="adomx-switch"><span class="text mr-3">Pendaftaran Dibuka</span><input id="status_pendaftaran" type="checkbox" checked="true"> <i class="lever"></i></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="jadwal">
                </div>
                <div class="penjurian">
                    <a href="#" class="button button-outline button-success" id="atur-block">Atur Block</a>
                </div>
            </div>
        </div>
    </div>

    <hr>

    {{-- SUB PAGES --}}
    <div class="tab-content">
        <div class="tab-pane fade show active" id="informasi">
            @include('pages.admin.sub_pages.informasi_lomba')
        </div>
        <div class="tab-pane fade" id="peserta">
            @include('pages.admin.sub_pages.peserta_lomba')
        </div>
        <div class="tab-pane fade" id="jadwal">
            @include('pages.admin.sub_pages.jadwal_lomba')
        </div>
        <div class="tab-pane fade" id="penjurian">
            {{-- APPEND IN JS --}}
        </div>
    </div>
</div>
@endsection

@section('modal')
    <!-- Modal Add Peserta -->
    <div class="modal fade" id="modalAddPeserta" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Tambah Peserta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <form id="buyAdmin">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="">Nama Peserta <span class="text-danger">*</span></label>
                        <input type="text" name="name_user" id="" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Kelas <span class="text-danger">*</span></label>
                        <select class="select-modal-kelas form-control" name="id_criteria" style="height: 100%">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Jenis Burung <span class="text-danger">*</span></label>
                        <select class="select-modal-jenis form-control" name="id_criteria_contents" style="height: 100%">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id_contest" id="idLomba">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <!-- Modal Acak Nomor Peserta -->
    <div class="modal fade" id="modalAcakNomor" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Tambah Peserta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <form id="arrangePeserta">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="">Jumlah Peserta Lomba</label>
                        <div id="jumlah-peserta">-</div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Template no. peserta <span class="text-danger">*</span></label>
                        <select class="select-modal-template form-control" name="id_random_template" style="height: 100%">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id_contest" id="idLombaAcak">
                    <button type="submiy" class="btn btn-primary">Simpan</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Agenda -->
    <div class="modal fade" id="modalEditJadwal" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Urutan Agenda</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body list-edit-agenda">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="editAgenda">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
{{-- DATA TABLE --}}
<script src="{{ asset('assets/js/plugins/datatables/datatables.min.js')}}"></script>
<script src="{{ asset('assets/js/plugins/datatables/datatables.active.js')}}"></script>
<script src="{{ asset('assets/js/plugins/select2/select2.full.min.js')}}"></script>
<script src="{{ asset('assets/js/plugins/select2/select2.active.js')}}"></script>
<script>
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        let comp = e.target.getAttribute('data-page')

        if (comp == "peserta") {
            $('.informasi').css('display', 'none')
            $('.peserta').css('display', 'block')
            $('.jadwal').css('display', 'none')
            $('.penjurian').css('display', 'none')
        }

        if (comp == "informasi") {
            $('.informasi').css('display', 'block')
            $('.peserta').css('display', 'none')
            $('.jadwal').css('display', 'none')
            $('.penjurian').css('display', 'none')
        }

        if (comp == "jadwal") {
            $('.informasi').css('display', 'none')
            $('.peserta').css('display', 'none')
            $('.jadwal').css('display', 'block')
            $('.penjurian').css('display', 'none')
        }

        if (comp == "penjurian") {
            $('.informasi').css('display', 'none')
            $('.peserta').css('display', 'none')
            $('.jadwal').css('display', 'none')
            $('.penjurian').css('display', 'block')
        }
    });
</script>
<script src="{{ asset('assets/js/pages/detailLomba.js')}}"></script>
@endsection
