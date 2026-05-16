@extends('layouts.mantis')
@section('content')
<div class="">
    <div class="card">
        <div class="card-header">
            <h4>Data Dokter</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('dokter.store') }}" method="post">
                @csrf

                <div class="form-group my-2">
                    <label class="form-label" for="namaDokter">Nama Dokter</label>
                    <input
                        class="form-control"
                        type="text"
                        name="nama_dokter"
                        id="namaDokter"
                        value="{{ old('nama_dokter') }}">
                    @error('nama_dokter')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group my-2">
                    <label for="layanan">Layanan</label>
                    <select name="jenis_layanan" class="form-select" id="layanan">
                        <option selected value="" selected>Pilih Layanan</option>
                        @foreach ($layanan as $index => $item )
                        <option value="{{ $item->id }}">
                            {{ $item->nama }}
                        </option>
                        @endforeach
                    </select>
                    @error('jenis_layanan')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group my-2">
                    <label for="jenisKelamin">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select" id="jenisKelamin">
                        <option selected value="">Pilih Jenis Kelamin</option>
                        <option value="pria" {{old('jenis_kelamin') == 'pria' ? 'selected':''}}>Pria</option>
                        <option value="wanita" {{old('jenis_kelamin') == 'pria' ? 'selected':''}}>Wanita</option>
                    </select>
                    @error('jenis_kelamin')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group my-2">
                    <label class="form-label" for="noTelepon">Nomor Telepon</label>
                    <input
                        class="form-control"
                        type="text"
                        name="no_telepon"
                        id="noTelepon"
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