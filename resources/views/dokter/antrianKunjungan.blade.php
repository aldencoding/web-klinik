@extends('layouts.mantis')
@section('content')
<div class="">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Antrian Kunjungan Pasien</h4>

            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="table">
                <thead>
                    <tr>
                        <th>No Antrian</th>
                        <th>Nama Pasien</th>
                        <th>Jaminan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($antrian as $index => $item )
                    <tr>

                        <td>{{ $item->no_antrian}}</td>
                        <td>{{ $item->user->nama?? '-' }}</td>
                        <td>{{ $item->jenis_jaminan }}</td>
                        <td class="dropdown">
                            <form action="{{ route('dokter.getkonsultasiPasien',$item->user->id) }}" method="get">
                                @csrf
                                @method('GET')
                                <a href="{{ route('dokter.getkonsultasiPasien', $item->user->id) }}"
                                    class="text-primary"
                                    role="button"
                                    type="submit">Panggil</a>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection