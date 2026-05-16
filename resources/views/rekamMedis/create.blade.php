@extends('layouts.mantis')
@section('content')
<div class="">
    <div class="card">
        <div class="card-header">
            <h4>Rekam Medis</h4>
        </div>
        <div class="card-body">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('rekamMedis.store') }}" method="post">
                @csrf
                <div class="form-group my-2">
                    <label class="form-label" for="pasienName">Nama Pasien</label>
                    <input class="" hidden id="pasien_id" name="pasien_id" value="{{$pasienId}}"></input>
                    <input class="" hidden id="kunjungan_id" name="kunjungan_id" value="{{$kunjunganId}}"></input>
                    <input class="" hidden id="dokter_id" name="dokter_id" value="{{$dokterId}}"></input>
                    <input
                        class="form-control"
                        type="text"
                        id="pasien_name"
                        value="{{$pasienName}}"
                        readonly>
                    @error('')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group my-2">
                    <label class="form-label" for="riwayat_penyakit">Riwayat Penyakit</label>
                    <textarea name="riwayat_penyakit" id="riwayat_penyakit" class="form-control" cols="30" rows="5" id="alamat">{{ old('riwayat_penyakit') }}</textarea>
                    @error('alamat')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="form-group my-2">
                    <label class="form-label" for="keluhan">Keluhan</label>
                    <textarea name="keluhan" id="keluhan" class="form-control" cols="30" rows="5" id="keluhan">{{ old('keluhan') }}</textarea>
                    @error('keluhan')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="form-group my-2">
                    <label class="form-label" for="resep">Resep</label>
                    <textarea name="resep" id="resep" class="form-control" cols="30" rows="5" id="resep">{{ old('resep') }}</textarea>
                    @error('resep')
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