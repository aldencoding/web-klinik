@extends('layouts.mantis')
@section('content')
<div class="">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Daftar Antrian Kunjungan</h4>
                <div>
                    <a href="{{ route('kunjungan.create') }}" class="btn btn-primary">Tambah Data</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('kunjungan.index') }}" method="GET" id="filterKunjungan" class="row g-3 mb-4">
                <div class="col-md-3">
                    <label for="periode" class="form-label">Tanggal</label>
                    <select name="periode" id="periode" class="form-select">
                        <option value="semua_tanggal" {{ $periode === 'semua_tanggal' ? 'selected' : '' }}>semua tanggal</option>
                        <option value="hari_ini" {{ $periode === 'hari_ini' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="mingguan" {{ $periode === 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                        <option value="bulanan" {{ $periode === 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="poli" class="form-label">Poli</label>
                    <select name="poli" id="poli" class="form-select">
                        <option value="">Semua Poli</option>
                        @foreach ($poliOptions as $poli)
                        <option value="{{ $poli }}" {{ request('poli') === $poli ? 'selected' : '' }}>
                            {{ $poli }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="jaminan" class="form-label">Jaminan</label>
                    <select name="jaminan" id="jaminan" class="form-select">
                        <option value="">Semua Jaminan</option>
                        @foreach ($jaminanOptions as $jaminan)
                        <option value="{{ $jaminan }}" {{ request('jaminan') === $jaminan ? 'selected' : '' }}>
                            {{ $jaminan }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="dipanggil" {{ request('status') === 'dipanggil' ? 'selected' : '' }}>Dipanggil</option>
                        <option value="menunggu_obat" {{ request('status') === 'menunggu_obat' ? 'selected' : '' }}>Menunggu Obat</option>
                        <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary" id="applyFilter">Filter</button>
                    <a href="{{ route('kunjungan.index') }}" class="btn btn-light" id="resetFilter">Reset</a>
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
                error: function() {
                    errorContainer
                        .removeClass('d-none')
                        .text('Data kunjungan gagal dimuat. Silakan coba kembali.');
                },
                complete: function() {
                    applyButton.prop('disabled', false).text('Filter');
                }
            });
        }
        // helper - end


        //event - start
        form.on('submit', function(event) {
            event.preventDefault();

            const queryString = form.serialize();
            const url = form.attr('action') + (queryString ? '?' + queryString : '');
            loadKunjungan(url);
        });

        $('#resetFilter').on('click', function(event) {
            event.preventDefault();

            $('#periode').val('hari_ini');
            $('#poli, #jaminan, #status').val('');
            loadKunjungan($(this).attr('href'));
        });

        initializeDataTable();
        //event - end
    });
</script>
@endpush
@endsection