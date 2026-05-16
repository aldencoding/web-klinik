@extends('layouts.mantis')
@section('content')
<div class="">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4>No Antrian - <b>P001</b></h4>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Usia</th>
                        <th>Tanggal Lahir</th>
                        <th>Jenis Kelamin</th>
                        <th>keluhan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Fulan</td>
                        <td>22 Tahun</td>
                        <td>01 Desember 2000</td>
                        <td>Pria</td>
                        <td>Demam</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection