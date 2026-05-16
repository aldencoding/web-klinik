@extends('layouts.mantis')
@section('content')
<div class="">
    <div class="card">
        <div class="card-header">
            <h4>Kunjungan Pasien</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('dokter.rekam.medis.post', $antrian->id) }}" method="post">
                @csrf
                @method('POST')

                <div class="form-group my-2">
                    <label class="form-label" for="no_antrian">No Antrian</label>
                    <input
                        class="form-control"
                        type="text"
                        name="no_antrian"
                        id="noAntrian"
                        value="{{ $antrian->no_antrian }}">
                    @error('no_antrian')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group my-2">
                    <label for="layanan">Nama Pasien</label>
                    <input
                        class="form-control"
                        type="text"
                        name="nama_pasien"
                        id="namaPasien"
                        value="{{ $antrian->user->nama }}">
                    @error('nama_pasien')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group my-2">
                    <label class="form-label" for="jenis_jaminan">Jaminan</label>
                    <input
                        class="form-control"
                        type="text"
                        name="jenis_jaminan"
                        id="jenisJaminan"
                        value="{{ $antrian->jenis_jaminan }}">
                    @error('jenis_jaminan')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group my-2">
                    <label class="form-label" for="keluhan">Keluhan</label>
                    <input
                        class="form-control"
                        type="text"
                        name="keluhan"
                        id="keluhan"
                        value="{{ $antrian->jenis_jaminan }}">
                    @error('nomor_telepon')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="d-flex justify-content-end my-4">
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection