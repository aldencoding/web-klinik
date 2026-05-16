@extends('layouts.mantis')
@section('content')
<div class="">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Data Pasien</h4>
                <div>
                    <a href="{{ route('pasien.create') }}" class="btn btn-primary">Tambah Data</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NIK</th>
                        <th>BPJS</th>
                        <th>Tanggal Lahir</th>
                        <th>Jenis Kelamin</th>
                        <th>Alamat</th>
                        <th>No Telp</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pasien as $index => $item )
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->user->name?? "-" }}</td>
                        <td>{{ $item->nik}}</td>
                        <td>{{ $item->no_bpjs}}</td>
                        <td>{{ $item->tanggal_lahir}}</td>
                        <td>{{ $item->jenis_kelamin}}</td>
                        <td>{{ $item->alamat}}</td>
                        <td>{{ $item->no_telepon}}</td>
                        <td class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Aksi
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('pasien.edit',$item->id) }}">Update</a></li>
                                <form action="{{ route('pasien.destroy',$item->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <li>
                                        <a href="{{ route('pasien.destroy', $item->id) }}"
                                            class="dropdown-item text-danger"
                                            data-confirm-delete="true"
                                            role="button"
                                            type="submit">Delete</a>
                                    </li>
                                </form>
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