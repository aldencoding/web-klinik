@extends('layouts.mantis')
@section('content')
<div class="">
    <div class="card">
        <div class="card-header">
            <h4>Buat Kunjungan Pasien</h4>
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
            <form action="{{ route('kunjungan.store') }}" method="post">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="nik">NIK</label>
                    <input
                        class="form-control"
                        type="number"
                        name="nik"
                        value="{{ old('nik') }}"
                        maxlength="16"
                        id="nikPasien">
                    <span class="text-danger" id="searchNikMessage"></span>
                </div>

                <div class="form-group text-end">
                    <input class="d-none" id="userID" name="pasien_id" type="text">
                    <button type="button" class="btn btn-primary" id="cariNikBtn">Cari NIK</button>
                </div>

                <div class="form-group">
                    <label class="form-label" for="nama">Nama Pasien</label>
                    <input
                        class="form-control"
                        type="text"
                        placeholder="Silahkan Cari NIK dari Pasien"
                        readonly
                        name="pasien"
                        value="{{ old('nama_pasien') }}"
                        maxlength="255"
                        id="namaPasien">

                </div>

                <div class="form-group">
                    <label class="form-label" for="tanggal_lahir_pasien">Tanggal Lahir</label>
                    <input
                        class="form-control"
                        type="date"
                        placeholder="Silahkan Cari NIK dari Pasien"
                        readonly
                        name="tanggal_lahir"
                        value="{{ old('tanggal_lahir') }}"
                        maxlength="255"
                        id="tanggalLahirPasien">

                </div>

                <div class="form-group">
                    <label class="form-label" for="jenis_kelamin">Jenis Kelamin</label>
                    <input
                        class="form-control"
                        type="text"
                        placeholder="Silahkan Cari NIK dari Pasien"
                        readonly
                        name="jenis_kelamin"
                        value="{{ old('jenis_kelamin') }}"
                        maxlength="255"
                        id="jenisKelaminPasien">

                </div>

                <div class="form-group my-2">
                    <label for="jaminan">Jaminan</label>
                    <select name="jaminan" class="form-select" id="jaminan">
                        <option selected value="">Pilih Jaminan</option>
                        <option value="Asuransi" {{old('jenis_jaminan') == 'Asuransi' ? 'selected':''}}>Asuransi</option>
                        <option value="Pribadi" {{old('jenis_jaminan') == 'Pribadi' ? 'selected':''}}>Pribadi</option>
                    </select>
                </div>

                <div class="form-group my-2">
                    <label for="poli">Poli</label>
                    <select name="poli" class="form-select" id="jenisPoli">
                        <option selected value="">Pilih Jenis Poli</option>
                        <option value="Umum" {{old('jenis_poli') == 'Umum' ? 'selected':''}}>Umum</option>
                        <option value="Gigi" {{old('jenis_poli') == 'Gigi' ? 'selected':''}}>Gigi</option>
                    </select>
                </div>

                <div class="form-group my-2">
                    <label class="form-label" for="keluhan">Keluhan</label>
                    <textarea name="keluhan" id="keluhan" class="form-control" cols="30" rows="10" id="keluhan"></textarea>
                    @error('keluhan')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="d-flex justify-content-end my-4">
                    <button class="btn btn-primary" type="submit">Buat Antrian</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>
    $(document).ready(function() {
        $('#nikMessage').hide();
        let table = new DataTable('#table');
        //hidden tag

        $('#cariNikBtn').on('click', function() {
            console.log('gw diklik');
            console.log($('#nikPasien').val())
            let nik = $('#nikPasien').val();
            if (!nik) {
                alert('NIK wajib di isi');
                return;
            }

            $.ajax({
                url: '{{route("kunjungan.nik")}}',
                type: 'GET',
                data: {
                    nik: $('#nikPasien').val()
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    if (response.status == "fail") {
                        alert("NIK tidak terdaftar");
                        return;
                    }
                    const data = response.data;
                    console.log('Pasien: ' + data.id);
                    $('#nikMessage').text('NIK terdaftar').attr('class', 'text-success');
                    $('#nikMessage').show();
                    $('#userID').val(data.id);
                    $('#namaPasien').val(data.user.name).attr('class', function(index, currentValue) {
                        return currentValue + ' text-success';
                    });
                    $('#tanggalLahirPasien').val(data.tanggal_lahir).attr('class', function(index, currentValue) {
                        return currentValue + ' text-success';
                    });
                    $('#jenisKelaminPasien').val(data.jenis_kelamin).attr('class', function(index, currentValue) {
                        return currentValue + ' text-success';
                    });
                    alert("NIK Berhasil Ditemukan");
                },
                error: function(xhr) {
                    console.log('error');
                    alert(xhr.responseJSON?.message);
                }
            });
        });

    });
</script>
@endpush
@endsection