@extends('layouts.mantis')
@section('content')
<div class="">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Data Dokter</h4>

            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Dokter</th>
                        <th>Hari</th>
                        <th>Jam Praktek</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jadwal as $index => $item )
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->dokter->user->nama }}</td>
                        <td>{{ $item->hari ? $item->hari :'-' }}</td>
                        <td>{{ $item->jam }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection