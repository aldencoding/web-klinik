@extends('layouts.mantis')
@section('content')
<div class="">
    <div class="card">
        <div class="card-header">
            <h4>Edit Data Pasien</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('pasien.update', $pasien->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label class="form-label" for="nik">NIK</label>
                    <input
                        class="form-control"
                        type="text"
                        name="nik"
                        value="{{ $pasien->nik }}"
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
                        maxlength="13"
                        value="{{ $pasien->no_bpjs }}"
                        id="no_bpjs">
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
                        id="namaPasien"
                        value="{{ $nama_pasien }}">
                    @error('namaPasien')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group my-2">
                    <label for="jenisKelamin">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select" id="jenisKelamin">
                        <option selected value="">Pilih Jenis Kelamin</option>
                        <option value="pria" {{$pasien->jenis_kelamin == 'pria' ? 'selected':''}}>Pria</option>
                        <option value="wanita" {{$pasien->jenis_kelamin == 'wanita' ? 'selected':''}}>Wanita</option>
                    </select>
                </div>

                <div class="form-group my-2">
                    <label class="form-label" for="tanggalLahir">Tanggal Lahir</label>
                    <input
                        class="form-control"
                        type="date"
                        name="tanggal_lahir"
                        id="tanggalLahir"
                        value="{{ $pasien->tanggal_lahir }}">
                    @error('tanggalLahir')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group my-2">
                    <label class="form-label" for="nomorTelepon">Nomor Telepon</label>
                    <input
                        class="form-control"
                        type="text"
                        name="no_telepon"
                        id="nomorTelepon"
                        value="{{ $pasien->no_telepon}}">
                    @error('nomorTelepon')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group my-2">
                    <label class="form-label" for="alamat">Alamat</label>
                    <textarea name="alamat" id="alamat" class="form-control" cols="30" rows="10" id="alamat">{{ $pasien->alamat }}</textarea>
                    @error('alamat')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="d-flex justify-content-end my-4">
                    <button class="btn btn-primary" type="submit">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection