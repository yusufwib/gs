{{-- PESERTA --}}
<div class="box">
    <div class="box-head d-flex justify-content-between align-items-center">
        <h4>Peserta</h4>
        <div class="group">
            <button class="btn btn-success" data-toggle="modal" data-target="#modalAcakNomor" id="button-acak">Acak Nomor Peserta</button>
            <div class="btn btn-primary" data-toggle="modal" data-target="#modalAddPeserta" id="button-buy">Tambah Peserta</div>
        </div>
    </div>
    <div class="box-body">
        <div class="row mb-3">
            <div class="col-4">
                <div class="filter d-flex justify-content-between align-items-center">
                    <div class="w-25">Kelas: </div>
                    <select name="" id="" class="form-control mr-2 filter-kelas">
                        <option value=""></option>
                    </select>
                    {{-- <div class="btn btn-primary">Filter</div> --}}
                </div>
            </div>
        </div>
        <table class="table table-bordered data-table" id="table-kelas-peserta">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kelas</th>
                    <th>Jenis Burung</th>
                    <th>Peserta Terdaftar</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
