<div class="table-responsive">
    <table class="table table-bordered" id="table">
        <thead>
            <tr>
                <th>No Antrian</th>
                <th>Nama Pasien</th>
                <th>Nama Dokter</th>
                <th>Poli</th>
                <th>Jaminan</th>
                <th>Keluhan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
            <tr>
                <td>{{ $item->no_antrian ?? '-' }}</td>
                <td>{{ $item->pasien->user->name ?? '-' }}</td>
                <td>{{ $item->dokter->user->name ?? '-' }}</td>
                <td>{{ $item->poli ?? '-' }}</td>
                <td>{{ $item->jaminan ?? '-' }}</td>
                <td>{{ $item->keluhan ?? '-' }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $item->status_antrian ?? '-')) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
