@extends('layouts.mantis')
@section('content')
<div class="">
    <div class="card">
        <div class="card-header">
            <h4>Daftar Pasien</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('pasien.store') }}" method="post">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="nik">NIK</label>
                    <input
                        class="form-control"
                        type="text"
                        name="nik"
                        value="{{ old('nik') }}"
                        maxlength="16"
                        id="nik">
                    @error('nik')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="nik">No BPJS</label>
                    <input
                        class="form-control"
                        type="text"
                        name="no_bpjs"
                        value="{{ old('no_bpjs') }}"
                        maxlength="13"
                        id="bpjs">
                    @error('no_bpjs')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group my-2">
                    <label class="form-label" for="namaPasien">Nama Pasien</label>
                    <input
                        class="form-control"
                        type="text"
                        name="nama_pasien"
                        id="nama_pasien"
                        value="{{ old('nama_pasien') }}">
                    @error('nama_pasien')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group my-2">
                    <label for="jenis_kelamin">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select" id="jenis_kelamin">
                        <option selected value="">Pilih Jenis Kelamin</option>
                        <option value="pria" {{old('jenis_kelamin') == 'pria' ? 'selected':''}}>Pria</option>
                        <option value="wanita" {{old('jenis_kelamin') == 'pria' ? 'selected':''}}>Wanita</option>
                    </select>
                    @error('jenis_kelamin')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group my-2">
                    <label class="form-label" for="tanggal_lahir">Tanggal Lahir</label>
                    <input
                        class="form-control"
                        type="date"
                        name="tanggal_lahir"
                        id="tanggal_lahir"
                        value="{{ old('tanggal_lahir') }}">
                    @error('tanggal_lahir')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group my-2">
                    <label class="form-label" for="no_telepon">Nomor Telepon</label>
                    <input
                        class="form-control"
                        type="text"
                        name="no_telepon"
                        minlength="13"
                        maxlength="15"
                        id="no_telepon"
                        value="{{ old('no_telepon') }}">
                    @error('no_telepon')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group my-2">
                    <label class="form-label" for="alamat">Alamat</label>
                    <textarea name="alamat" id="alamat" class="form-control" cols="30" rows="10" id="alamat">{{ old('alamat') }}</textarea>
                    @error('alamat')
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