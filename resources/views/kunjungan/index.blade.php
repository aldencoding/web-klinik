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
            <table class="table table-bordered" id="table">
                <thead>
                    <tr>
                        <th>No Antrian</th>
                        <th>Nama Pasien</th>
                        <th>Poli</th>
                        <th>Jaminan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $index => $item )
                    <tr>
                        <td>{{ $item->no_antrian?? '-'}}</td>
                        <td>{{ $item->nama_pasien?? '-' }}</td>
                        <td>{{ $item->poli?? '-' }}</td>
                        <td>{{ $item->jaminan?? '-' }}</td>
                        <td>{{ $item->staus_antrian?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection