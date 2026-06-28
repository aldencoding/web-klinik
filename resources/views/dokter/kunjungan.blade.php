@extends('layouts.mantis')
@section('content')
<div class="">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-kunjungans-center">
                <h4>Kunjungan Hari ini</h4>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="table">
                <thead>
                    <tr>
                        <th>No Antrian</th>
                        <th>Nama Pasien</th>
                        <th>Keluhan</th>
                        <th>Status</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kunjungans as $index => $kunjungan )
                    <tr>
                        <td>{{ $kunjungan->no_antrian?? "-" }}</td>
                        <td>{{ $kunjungan->pasien->user->name}}</td>
                        <td>{{ $kunjungan->keluhan}}</td>
                        <td>{{ $kunjungan->status_antrian}}</td>
                        <td>
                            <a class="text text-primary" href="{{ route('rekamMedis.create', $kunjungan->pasien->id) }}">Layani</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection