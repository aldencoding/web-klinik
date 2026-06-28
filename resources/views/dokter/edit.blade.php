@extends('layouts.mantis')
@section('content')
<div class="">
    <div class="card">
        <div class="card-header">
            <h4>Edit Data Dokter</h4>
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

            <form action="{{ route('dokter.update', $dokter->id) }}" method="post">
                @csrf
                @method('PUT')

                <div class="form-group my-2">
                    <label class="form-label" for="nama_dokter">Nama Dokter</label>
                    <input
                        class="form-control"
                        type="text"
                        name="nama_dokter"
                        id="nama_dokter"
                        value="{{ $dokter->user->name }}">
                    @error('nama_dokter')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group my-2">
                    <label class="form-label" for="nama_dokter">Email</label>
                    <input
                        class="form-control"
                        type="text"
                        name="email"
                        id="email"
                        value="{{ $dokter->user->email }}">
                    @error('nama_dokter')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group my-2">
                    <label for="jenisKelamin">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select" id="jenisKelamin">
                        <option selected value="">Pilih Jenis Kelamin</option>
                        <option value="pria" {{$dokter->jenis_kelamin == 'pria' ? 'selected':''}}>Pria</option>
                        <option value="wanita" {{$dokter->jenis_kelamin == 'wanita' ? 'selected':''}}>Wanita</option>
                    </select>
                    @error('jenis_kelamin')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group my-2">
                    <label for="layanan">Layanan</label>
                    <select name="jenis_layanan" class="form-select" id="layanan">
                        <option selected value="">Pilih Layanan</option>
                        @foreach ($layanan as $index => $item )
                        <option value="{{ $item->id }}" {{ $dokter->layanan_id == $item->id ? 'selected' : '' }}>
                            {{ $item->nama }}
                        </option>
                        @endforeach
                    </select>
                    @error('jenis_layanan')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group my-2">
                    <label class="form-label" for="nomor_telepon">Nomor Telepon</label>
                    <input
                        class="form-control"
                        type="text"
                        name="no_telepon"
                        id="noTelepon"
                        value="{{ $dokter->no_telepon}}">
                    @error('no_telepon')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group my-2">
                    <label class="form-label" for="jadwal_dokter">Jadwal Praktek</label>
                    <!-- Select Hari -->
                    <select name="hari" class="form-select" id="">
                        <option value="">Pilih Hari</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jum'at">Jum'at</option>
                        <option value="Sabtu">Sabtu</option>
                    </select>
                    @error('hari')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="form-group my-2">
                    <!-- Select Jam -->
                    <select name="jam" class="form-select" id="">
                        <option value="">Pilih Jam</option>
                        <option value="08:00 - 11:00">08:00 - 11:00</option>
                        <option value="13:00 - 15:00">13:00 - 15:00</option>
                    </select>
                    @error('jam')
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