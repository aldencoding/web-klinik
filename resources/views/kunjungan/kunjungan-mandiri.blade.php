@extends('layouts.mantis')
@section('content')
<div class="container-fluid px-2 py-3">
    <!-- Card dibuat borderless di mobile untuk menghemat space layar -->
    <div class="card border-0 border-sm shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="card-title mb-0 fw-bold text-primary">Buat Kunjungan Pasien</h5>
        </div>

        <div class="card-body p-3">
            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('kunjungan.store') }}" method="post" class="needs-validation">
                @csrf
                <input type="hidden" name="user_id" id="userID">

                <!-- Cari Pasien -->
                <div class="mb-3">
                    <label for="selectPasien" class="form-label fw-semibold">Cari Nama Pasien</label>
                    <select class="form-select form-select-lg" id="selectPasien" name="pasien_id" required>
                        <option value="" selected disabled>Pilih Pasien...</option>
                        @foreach($pasiens as $pasien)
                        <option value="{{ $pasien->id }}">{{ $pasien->user->name ?? '-' }}</option>
                        @endforeach
                    </select>
                    <div id="pasienMessage" class="form-text text-danger mt-1" style="display: none;"></div>
                </div>

                <!-- Detail Pasien (Read-Only) -->
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label small text-muted mb-1" for="tanggalLahirPasien">Tanggal Lahir</label>
                        <input type="date" class="form-control bg-light" readonly name="tanggal_lahir" id="tanggalLahirPasien">
                    </div>
                    <div class="col-6">
                        <label class="form-label small text-muted mb-1" for="jenisKelaminPasien">Jenis Kelamin</label>
                        <input type="text" class="form-control bg-light" readonly name="jenis_kelamin" id="jenisKelaminPasien">
                    </div>
                </div>

                <hr class="text-muted my-3 opacity-25">

                <!-- Jaminan -->
                <div class="mb-3">
                    <label for="jaminan" class="form-label fw-semibold">Jaminan / Metode Bayar</label>
                    <select name="jaminan" class="form-select form-select-lg" id="jaminan" required>
                        <option value="" selected disabled>Pilih Jaminan</option>
                        <option value="Asuransi" {{ old('jenis_jaminan') == 'Asuransi' ? 'selected' : '' }}>Asuransi</option>
                        <option value="Pribadi" {{ old('jenis_jaminan') == 'Pribadi' ? 'selected' : '' }}>Pribadi</option>
                    </select>
                </div>

                <!-- Poli -->
                <div class="mb-3">
                    <label for="jenisPoli" class="form-label fw-semibold">Poli Tujuan</label>
                    <select name="poli" class="form-select form-select-lg" id="jenisPoli" required>
                        <option value="" selected disabled>Pilih Jenis Poli</option>
                        <option value="Umum" {{ old('jenis_poli') == 'Umum' ? 'selected' : '' }}>Umum</option>
                        <option value="Gigi" {{ old('jenis_poli') == 'Gigi' ? 'selected' : '' }}>Gigi</option>
                    </select>
                </div>

                <!-- Keluhan -->
                <div class="mb-4">
                    <label class="form-label fw-semibold" for="keluhan">Keluhan Pasien</label>
                    <textarea name="keluhan" id="keluhan" class="form-control" rows="4" placeholder="Tuliskan keluhan singkat pasien saat ini..." required>{{ old('keluhan') }}</textarea>
                    @error('keluhan')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tombol Submit (Full Width di Mobile agar mudah ditekan jempol) -->
                <div class="d-grid gap-2 d-sm-flex justify-content-sm-end">
                    <button class="btn btn-primary btn-lg px-4 fs-6" type="submit">
                        <i class="bi bi-calendar-plus me-1"></i> Buat Antrian
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(session()->has('queue_ticket'))
<x-queue-ticket-modal :ticket="session('queue_ticket')" />
@endif

@push('scripts')
<script>
    $('#selectPasien').on('change', function() {
        let pasienId = $(this).val();
        $('#pasienMessage').hide().text('');

        if (!pasienId) {
            resetPasienForm();
            return;
        }

        $.ajax({
            url: '/get-pasien/' + pasienId,
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.status === 'fail') {
                    $('#pasienMessage').text('Pasien tidak ditemukan/terdaftar').show();
                    resetPasienForm();
                    if ($.fn.select2) {
                        $('#selectPasien').val(null).trigger('change.select2');
                    }
                    return;
                }

                const data = response.data;
                $('#userID').val(data.id);
                $('#tanggalLahirPasien').val(data.tanggal_lahir).removeClass('text-danger').addClass('text-success fw-semibold');
                $('#jenisKelaminPasien').val(data.jenis_kelamin).removeClass('text-danger').addClass('text-success fw-semibold');
            },
            error: function(xhr) {
                console.error(xhr);
                $('#pasienMessage').text('Gagal mengambil data pasien').show();
            }
        });
    });

    function resetPasienForm() {
        $('#userID').val('');
        $('#tanggalLahirPasien').val('').removeClass('text-success fw-semibold');
        $('#jenisKelaminPasien').val('').removeClass('text-success fw-semibold');
    }
</script>
@endpush
@endsection