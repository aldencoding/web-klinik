@extends('layouts.mantis')
@section('content')
<div class="">
    <h1>Selamat Datang, {{ Auth::user()->name }}</h1>
    <!-- <div class="card">
        <a class="card-body" role="button" href="/dokter" class="cursor-pointer">
            <h6 class="mb-2 f-w-400 text-muted">Dokter</h6>
            <h4 class="mb-3">5<span class="badge bg-light-primary border border-primary"><i
                        class="ti ti-man"></i></span></h4>
            <p class="mb-0 text-muted text-sm">You made an extra <span class="text-primary">35,000</span> this year
            </p>
        </a>
    </div>
</div>
<div class="col-md-6 col-xl-3">
    <div class="card">
        <a class="card-body" role="button" href="/pasien" class="cursor-pointer">
            <h6 class="mb-2 f-w-400 text-muted">Pasien Terdaftar</h6>
            <h4 class="mb-3">70<span class="badge bg-light-primary border border-primary"><i
                        class="ti ti-man"></i></span></h4>
            <p class="mb-0 text-muted text-sm">You made an extra <span class="text-primary">35,000</span> this year
            </p>
        </a>
    </div>
</div>

<div class="col-md-12 col-xl-8">
    <h5 class="mb-3">Antrian Berjalan</h5>
    <div class="card tbl-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-borderless mb-0">
                    <thead>
                        <tr>
                            <th>No Antrian</th>
                            <th>Nama Pasien</th>
                            <th>Ruang</th>
                            <th>STATUS</th>
                            <th class="text-end">TOTAL AMOUNT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><a href="#" class="text-muted">84564564</a></td>
                            <td>Camera Lens</td>
                            <td>40</td>
                            <td><span class="d-flex align-items-center gap-2"><i
                                        class="fas fa-circle text-danger f-10 m-r-5"></i>Rejected</span>
                            </td>
                            <td class="text-end">$40,570</td>
                        </tr>
                        <tr>
                            <td><a href="#" class="text-muted">84564564</a></td>
                            <td>Laptop</td>
                            <td>300</td>
                            <td><span class="d-flex align-items-center gap-2"><i
                                        class="fas fa-circle text-warning f-10 m-r-5"></i>Pending</span>
                            </td>
                            <td class="text-end">$180,139</td>
                        </tr>
                        <tr>
                            <td><a href="#" class="text-muted">84564564</a></td>
                            <td>Mobile</td>
                            <td>355</td>
                            <td><span class="d-flex align-items-center gap-2"><i
                                        class="fas fa-circle text-success f-10 m-r-5"></i>Approved</span></td>
                            <td class="text-end">$180,139</td>
                        </tr>
                        <tr>
                            <td><a href="#" class="text-muted">84564564</a></td>
                            <td>Camera Lens</td>
                            <td>40</td>
                            <td><span class="d-flex align-items-center gap-2"><i
                                        class="fas fa-circle text-danger f-10 m-r-5"></i>Rejected</span>
                            </td>
                            <td class="text-end">$40,570</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="col-md-12 col-xl-8">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="mb-0">Grafik Kunjungan</h5>
        <ul class="nav nav-pills justify-content-end mb-0" id="chart-tab-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="chart-tab-home-tab" data-bs-toggle="pill" data-bs-target="#chart-tab-home"
                    type="button" role="tab" aria-controls="chart-tab-home" aria-selected="true">Month</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="chart-tab-profile-tab" data-bs-toggle="pill"
                    data-bs-target="#chart-tab-profile" type="button" role="tab" aria-controls="chart-tab-profile"
                    aria-selected="false">Week</button>
            </li>
        </ul>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="tab-content" id="chart-tab-tabContent">
                <div class="tab-pane" id="chart-tab-home" role="tabpanel" aria-labelledby="chart-tab-home-tab"
                    tabindex="0">
                    <div id="visitor-chart-1"></div>
                </div>
                <div class="tab-pane show active" id="chart-tab-profile" role="tabpanel"
                    aria-labelledby="chart-tab-profile-tab" tabindex="0">
                    <div id="visitor-chart"></div>
                </div>
            </div>
        </div>
    </div> -->
</div>

@endsection