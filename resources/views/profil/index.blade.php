@extends('layouts.mantis')
@section('content')
<div class="">
    <div class="card">
        <div class="card-header">
            <h4>Profil Pengguna</h4>
        </div>
        <div class="card-body">
            <div class="d-flex align-items-start gap-4">
                <div class="flex-shrink-0 text-center">
                    <img src="https://i.pinimg.com/736x/34/50/54/3450547a2107d458f5810f8f3cd94362.jpg"
                        alt="Foto {{ $userProfile->nama }}"
                        class="img-thumbnail rounded"
                        style="width: 150px; height: 150px; object-fit: cover;">
                </div>

                <div class="table-responsive flex-grow-1">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">

                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row" class="fw-bold">Nama</th>
                                <td>{{ $userProfile->nama?? "Alicia Cheng" }}</td>
                            </tr>
                            <tr>
                                <th scope="row" class="fw-bold">Email</th>
                                <td>{{ $userProfile->email }}</td>
                            </tr>
                            <tr>
                                <th scope="row" class="fw-bold">Spesialis</th>
                                <td>{{ $userProfile->spesialis ?? 'Belum diisi' }}</td>
                            </tr>
                            <tr>
                                <th scope="row" class="fw-bold">No. Telepon</th>
                                <td>{{ $userProfile->telepon ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection