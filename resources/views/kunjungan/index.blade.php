@extends('layouts.mantis')
@section('content')
<div class="">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Daftar Antrian Kunjungan</h4>
                <!-- <div>
                    <a href="{{ route('kunjungan.create') }}" class="btn btn-primary">Tambah Data</a>
                </div> -->
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('kunjungan.index') }}" method="GET" id="filterKunjungan" class="p-4 bg-light rounded shadow-sm mb-4">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="tanggal_mulai" class="form-label fw-semibold">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-select"
                            value="{{ request('tanggal_mulai') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="tanggal_selesai" class="form-label fw-semibold">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-select"
                            value="{{ request('tanggal_selesai') }}"
                            min="{{ request('tanggal_mulai') }}"
                            {{ request('tanggal_mulai') ? '' : 'disabled' }}>
                    </div>
                </div>

                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="poli" class="form-label fw-semibold">Poli</label>
                        <select name="poli" id="poli" class="form-select">
                            <option value="">Semua Poli</option>
                            @foreach ($poliOptions as $poli)
                            <option value="{{ $poli }}" {{ request('poli') === $poli ? 'selected' : '' }}>
                                {{ $poli }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="jaminan" class="form-label fw-semibold">Jaminan</label>
                        <select name="jaminan" id="jaminan" class="form-select">
                            <option value="">Semua Jaminan</option>
                            @foreach ($jaminanOptions as $jaminan)
                            <option value="{{ $jaminan }}" {{ request('jaminan') === $jaminan ? 'selected' : '' }}>
                                {{ $jaminan }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="status" class="form-label fw-semibold">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="dipanggil" {{ request('status') === 'dipanggil' ? 'selected' : '' }}>Dipanggil</option>
                            <option value="menunggu_obat" {{ request('status') === 'menunggu_obat' ? 'selected' : '' }}>Menunggu Obat</option>
                            <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                </div>

                <div class="row mt-3 pt-3 border-top">
                    <div class="col-12 d-flex justify-content-end gap-2">
                        <a href="{{ route('kunjungan.index') }}" class="btn btn-outline-secondary px-4" id="resetFilter">Reset</a>
                        <button type="submit" class="btn btn-primary px-4" id="applyFilter">
                            <i class="bi bi-filter"></i> Terapkan Filter
                        </button>
                    </div>
                </div>
            </form>

            <div id="filterError" class="alert alert-danger d-none"></div>

            <div id="kunjunganTable">
                @include('kunjungan._table', ['data' => $data])
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(function() {
        // initiation - start
        const form = $('#filterKunjungan');
        const tableContainer = $('#kunjunganTable');
        const errorContainer = $('#filterError');
        const applyButton = $('#applyFilter');
        const tanggalMulai = $('#tanggal_mulai');
        const tanggalSelesai = $('#tanggal_selesai');
        let dataTable = null;
        // initiation - end


        // helper - start
        function initializeDataTable() {
            if ($('#table').length && !DataTable.isDataTable('#table')) {
                dataTable = new DataTable('#table');
            }
        }

        function loadKunjungan(url) {
            applyButton.prop('disabled', true).text('Memuat...');
            errorContainer.addClass('d-none').text('');

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'html',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    if (dataTable) {
                        dataTable.destroy();
                        dataTable = null;
                    }

                    tableContainer.html(response);
                    initializeDataTable();
                    window.history.replaceState({}, '', url);
                },
                error: function(xhr) {
                    const errors = xhr.responseJSON?.errors || {};
                    const message = Object.values(errors).flat()[0] || 'Data kunjungan gagal dimuat. Silakan coba kembali.';

                    errorContainer
                        .removeClass('d-none')
                        .text(message);
                },
                complete: function() {
                    applyButton.prop('disabled', false).html('<i class="bi bi-filter"></i> Terapkan Filter');
                }
            });
        }

        function syncTanggalSelesai() {
            const mulai = tanggalMulai.val();
            const selesai = tanggalSelesai.val();

            tanggalSelesai.prop('disabled', !mulai);
            tanggalSelesai.attr('min', mulai || null);

            if (!mulai) {
                tanggalSelesai.val('');
                return;
            }

            if (selesai && selesai < mulai) {
                tanggalSelesai.val('');
                errorContainer
                    .removeClass('d-none')
                    .text('Tanggal selesai tidak boleh lebih awal dari tanggal mulai.');
            } else {
                errorContainer.addClass('d-none').text('');
            }
        }

        function isRentangTanggalValid() {
            const mulai = tanggalMulai.val();
            const selesai = tanggalSelesai.val();

            if (selesai && !mulai) {
                errorContainer
                    .removeClass('d-none')
                    .text('Pilih tanggal mulai terlebih dahulu.');
                return false;
            }

            if (mulai && !selesai) {
                errorContainer
                    .removeClass('d-none')
                    .text('Pilih tanggal selesai terlebih dahulu.');
                return false;
            }

            if (mulai && selesai && selesai < mulai) {
                errorContainer
                    .removeClass('d-none')
                    .text('Tanggal selesai tidak boleh lebih awal dari tanggal mulai.');
                return false;
            }

            errorContainer.addClass('d-none').text('');
            return true;
        }
        // helper - end


        //event - start
        tanggalMulai.on('change', syncTanggalSelesai);
        tanggalSelesai.on('change', syncTanggalSelesai);

        form.on('submit', function(event) {
            event.preventDefault();

            if (!isRentangTanggalValid()) {
                return;
            }

            const queryString = form.serialize();
            const url = form.attr('action') + (queryString ? '?' + queryString : '');
            loadKunjungan(url);
        });

        $('#resetFilter').on('click', function(event) {
            event.preventDefault();

            tanggalMulai.val('');
            tanggalSelesai.val('');
            syncTanggalSelesai();
            $('#poli, #jaminan, #status').val('');
            loadKunjungan($(this).attr('href'));
        });

        syncTanggalSelesai();
        initializeDataTable();
        //event - end
    });
</script>
@endpush
@endsection