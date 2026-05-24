@extends('layouts.mantis')
@section('content')
<div class="">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Daftar Catatan Medis</h4>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="table">
                <thead>
                    <tr>
                        <th>ID Kunjungan</th>
                        <th>Nama Pasien</th>
                        <th>Keluhan</th>
                        <th>Dokter</th>
                        <th>Status</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rekamMedis as $index => $item )
                    <tr>
                        <td>{{ $item->kunjungan->id?? '-'}}</td>
                        <td>{{ $item->pasien->user->name?? '-' }}</td>
                        <td>{{ $item->keluhan?? '-' }}</td>
                        <td>{{ $item->dokter->user->name?? '-' }}</td>
                        <td>{{ $item->status?? '-' }}</td>
                        <td class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Aksi
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('rekamMedis.create',$item->pasien_id) }}">Parani </a></li>
                                @if(auth()->user() && auth()->user()->role === 'admin')
                                <form action="{{ route('pasien.destroy',$item->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <li>
                                        <a href="{{ route('rekamMedis.destroy', $item->id) }}"
                                            class="dropdown-item text-danger"
                                            data-confirm-delete="true"
                                            role="button"
                                            type="submit">Delete</a>
                                    </li>
                                </form>
                                @endif
                            </ul>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection