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
                    <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png"
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
                                <td>{{ Auth()->user()->name?? "Alicia Cheng" }}</td>
                            </tr>
                            <tr>
                                <th scope="row" class="fw-bold">Email</th>
                                <td>{{ Auth()->user()->email?? "" }}</td>
                            </tr>
                            <tr>
                                <th scope="row" class="fw-bold">Spesialis</th>
                                <td>{{ Auth()->user()->dokter->spesialis?? 'Belum diisi' }}</td>
                            </tr>
                            <tr>
                                <th scope="row" class="fw-bold">No. Telepon</th>
                                <td>{{ Auth()->user()->dokter->no_telepon?? 'Belum diisi' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection