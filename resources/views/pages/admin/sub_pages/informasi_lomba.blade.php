{{-- Informasi & Lapiran --}}
<div class="lampiran box mb-4">
    <div class="box-head">
        <h4>Informasi Lomba</h4>
    </div>
    <div class="box-body">
        <h4>Lampiran</h4>
        <div id="lampiran">
            {{-- <img src="{{ asset('assets/images/gallery/profile-gallery-1.jpg') }}" class="d-inline" width="250"> --}}
            -
        </div>
    </div>
    <div class="box-body">
        <h4>Tentang Lomba</h4>
        <div class="row mb-10">
            <div class="col-md-4">
                <div class="text-body-light">Nama Lomba</div>
                <div class="font-weight-bold" id="contest_name">-</div>
            </div>
            <div class="col-md-4">
                <div class="text-body-light">Kota</div>
                <div class="text-heading" id="city">-</div>
            </div>
        </div>
        <div class="row mb-10">
            <div class="col-md-4">
                <div class="text-body-light">Tanggal</div>
                <div class="text-heading" id="date">-</div>
            </div>
            <div class="col-md-4">
                <div class="text-body-light">Jam Mulai</div>
                <div class="text-heading" id="start_time">-</div>
            </div>
            <div class="col-md-4">
                <div class="text-body-light">Jam Selesai</div>
                <div class="text-heading">Sampai Selesai</div>
            </div>
        </div>
        <div class="row mb-10">
            <div class="col-md-4">
                <div class="text-body-light">Lokasi</div>
                <div class="text-heading" id="address">-</div>
            </div>
            <div class="col-md-4">
                <div class="text-body-light">Detail Lokasi</div>
                <div class="text-heading" id="location">-</div>
            </div>
        </div>
        <div class="row mb-10">
            <div class="col-md-4">
                <div class="text-body-light">Peraturan Lomba</div>
                <div class="text-heading" id="terms">-</div>
            </div>
        </div>
    </div>
</div>

{{-- Kelas --}}
<div class="kelas box mb-4">
    <div class="box-head">
        <h4>Kelas</h4>
    </div>
    <div class="box-body" id="contest_criteria">

    </div>
</div>

{{-- Jury --}}
<div class="penyelenggara box mb-4">
    <div class="box-head d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Juri</h4>
        <a href="" class="button button-primary button-sm" id="atur-juri">Atur Juri</a>
    </div>
    <div class="box-body" id="list-juri">
    </div>
</div>

{{-- PENYELENGGARA --}}
<div class="penyelenggara box">
    <div class="box-head d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Penyelenggara Lomba</h4>
        <a href="#" class="button button-primary button-sm" id="atur-penyelenggara">Atur Penyelenggara</a>
    </div>
    <div class="box-body" id="organizer">
    </div>
</div>
