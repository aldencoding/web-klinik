@extends('layouts.app')

@section('content')

<div class="container">

    <div class="card">

        <div class="card-header">
            Detail Antrian
        </div>

        <div class="card-body">

            <h2>
                {{ $kunjungan->no_antrian }}
            </h2>

            <hr>

            <p>
                <strong>Pasien:</strong>
                {{ $kunjungan->pasien->user->name }}
            </p>

            <p>
                <strong>Poli:</strong>
                {{ $kunjungan->poli }}
            </p>

            <p>
                <strong>Status:</strong>
                {{ $kunjungan->status_antrian }}
            </p>

            <p>
                <strong>Dokter:</strong>
                {{ $kunjungan->dokter->nama }}
            </p>

            <p>
                <strong>Jadwal:</strong>
                {{ $kunjungan->created_at }}
            </p>

        </div>

    </div>

</div>

@endsection