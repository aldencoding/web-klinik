@extends('layouts.mantis')

@section('content')
<style>
    .queue-board {
        width: 100%;
        padding: 50px;
    }

    .queue-hero {
        background: linear-gradient(135deg, #0f766e 0%, #155e75 52%, #1f2937 100%);
        border-radius: 8px;
        color: #fff;
        min-height: 300px;
        padding: 28px;
        position: relative;
        overflow: hidden;
    }

    .queue-hero::after {
        background: rgba(255, 255, 255, 0.08);
        content: "";
        height: 220px;
        position: absolute;
        right: -80px;
        top: -80px;
        transform: rotate(18deg);
        width: 280px;
    }

    .queue-number {
        font-size: clamp(56px, 9vw, 112px);
        font-weight: 800;
        letter-spacing: 0;
        line-height: 1;
    }

    .queue-called {
        animation: queueBlink 900ms ease-in-out infinite;
        box-shadow: 0 0 0 0 rgba(20, 184, 166, 0.7);
    }

    @keyframes queueBlink {

        0%,
        100% {
            box-shadow: 0 0 0 0 rgba(20, 184, 166, 0.55);
            filter: brightness(1);
        }

        50% {
            box-shadow: 0 0 0 12px rgba(20, 184, 166, 0);
            filter: brightness(1.2);
        }
    }

    .summary-card {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        min-height: 112px;
    }

    .summary-value {
        font-size: 34px;
        font-weight: 800;
        line-height: 1;
    }

    .status-badge {
        border-radius: 999px;
        display: inline-flex;
        font-size: 12px;
        font-weight: 700;
        padding: 6px 10px;
        text-transform: uppercase;
    }

    .status-menunggu {
        background: #fef3c7;
        color: #92400e;
    }

    .status-dipanggil {
        background: #ccfbf1;
        color: #115e59;
    }

    .status-selesai {
        background: #dcfce7;
        color: #166534;
    }

    .status-default {
        background: #e5e7eb;
        color: #374151;
    }

    .table-fixed {
        table-layout: fixed;
    }

    .table-fixed td,
    .table-fixed th {
        vertical-align: middle;
        word-wrap: break-word;
    }

    .queue-table-wrap {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .queue-table {
        min-width: 760px;
    }

    @media (max-width: 767.98px) {
        .queue-board {
            padding: 16px 12px;
        }

        .queue-hero {
            min-height: auto;
            padding: 20px;
        }

        .queue-hero::after {
            height: 150px;
            right: -100px;
            top: -90px;
            width: 220px;
        }

        .queue-top {
            align-items: flex-start !important;
            flex-direction: column;
            gap: 12px !important;
            margin-bottom: 18px !important;
        }

        .queue-top .text-end {
            text-align: left !important;
        }

        .queue-number {
            font-size: 64px;
            max-width: 100%;
            overflow-wrap: anywhere;
        }

        .called-title {
            font-size: 13px;
        }

        #calledPatient {
            font-size: 22px;
            line-height: 1.2;
            overflow-wrap: anywhere;
        }

        #calledPoli,
        #calledDoctor {
            font-size: 16px;
            line-height: 1.25;
            overflow-wrap: anywhere;
        }

        .summary-card {
            min-height: 92px;
        }

        .summary-card .card-body {
            padding: 14px;
        }

        .summary-card .text-muted {
            font-size: 12px;
            line-height: 1.2;
        }

        .summary-value {
            font-size: 28px;
        }

        .queue-table-card .card-header > div {
            align-items: flex-start !important;
            flex-direction: column;
            gap: 6px !important;
        }

        .queue-table-card .card-body {
            padding: 0;
        }

        .queue-table {
            font-size: 13px;
            margin-bottom: 0;
            min-width: 680px;
        }

        .queue-table th,
        .queue-table td {
            padding: 10px;
        }

        .status-badge {
            font-size: 11px;
            padding: 5px 8px;
        }
    }

    @media (max-width: 380px) {
        .queue-board {
            padding-left: 8px;
            padding-right: 8px;
        }

        .queue-number {
            font-size: 54px;
        }

        .summary-card .card-body {
            padding: 12px 10px;
        }
    }
</style>

@php
$current = $dipanggil ?? null;
@endphp

<div class="queue-board">
    <div class="row g-3 mb-3">
        <div class="col-12 col-xl-7">
            <div class="queue-hero {{ $current ? 'queue-called' : '' }}" id="calledCard">
                <div class="position-relative">
                    <div class="d-flex justify-content-between align-items-start gap-3 mb-4 queue-top">
                        <div>
                            <div class="text-white-50 fw-semibold mb-1 called-title">Nomor Antrian Dipanggil</div>
                            <div class="queue-number" id="calledNumber">{{ $current?->no_antrian ?? '-' }}</div>
                        </div>
                        <div class="text-end">
                            <div class="text-white-50 small">Update</div>
                            <div class="fw-bold" id="lastUpdated">{{ now()->format('H:i:s') }}</div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="text-white-50 small">Nama Pasien</div>
                            <h4 class="text-white mb-0" id="calledPatient">{{ $current?->pasien?->user?->name ?? '-' }}</h4>
                        </div>
                        <div class="col-sm-3">
                            <div class="text-white-50 small">Poli</div>
                            <h5 class="text-white mb-0" id="calledPoli">{{ $current?->poli ?? '-' }}</h5>
                        </div>
                        <div class="col-sm-3">
                            <div class="text-white-50 small">Dokter</div>
                            <h5 class="text-white mb-0" id="calledDoctor">{{ $current?->dokter?->user?->name ?? '-' }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-5">
            <div class="row g-3">
                <div class="col-6">
                    <div class="card summary-card">
                        <div class="card-body">
                            <div class="text-muted fw-semibold">Total Hari Ini</div>
                            <div class="summary-value" id="totalQueue">{{ $summary['total'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card summary-card">
                        <div class="card-body">
                            <div class="text-muted fw-semibold">Menunggu</div>
                            <div class="summary-value text-warning" id="waitingQueue">{{ $summary['menunggu'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card summary-card">
                        <div class="card-body">
                            <div class="text-muted fw-semibold">Dipanggil</div>
                            <div class="summary-value text-info" id="calledQueue">{{ $summary['dipanggil'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card summary-card">
                        <div class="card-body">
                            <div class="text-muted fw-semibold">Selesai</div>
                            <div class="summary-value text-success" id="doneQueue">{{ $summary['selesai'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card queue-table-card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center gap-3">
                <h4 class="mb-0">Kunjungan Hari Ini</h4>
                <span class="text-muted small" id="realtimeInfo">Realtime aktif</span>
            </div>
        </div>
        <div class="card-body">
            <div class="queue-table-wrap">
                <table class="table table-bordered table-hover table-fixed queue-table mb-0">
                    <thead>
                        <tr>
                            <th style="width: 120px;">No Antrian</th>
                            <th>Nama Pasien</th>
                            <th style="width: 140px;">Poli</th>
                            <th>Keluhan</th>
                            <th style="width: 150px;">Status</th>
                            <th style="width: 120px;">Jam Daftar</th>
                        </tr>
                    </thead>
                    <tbody id="queueTableBody">
                        @forelse ($kunjungans as $kunjungan)
                        <tr class="{{ $kunjungan->status_antrian === 'dipanggil' ? 'table-info' : '' }}">
                            <td class="fw-bold">{{ $kunjungan->no_antrian ?? '-' }}</td>
                            <td>{{ $kunjungan->pasien?->user?->name ?? '-' }}</td>
                            <td>{{ $kunjungan->poli ?? '-' }}</td>
                            <td>{{ $kunjungan->keluhan ?? '-' }}</td>
                            <td>
                                <span class="status-badge status-{{ $kunjungan->status_antrian ?? 'default' }}">
                                    {{ ucfirst(str_replace('_', ' ', $kunjungan->status_antrian ?? '-')) }}
                                </span>
                            </td>
                            <td>{{ $kunjungan->created_at?->format('H:i') ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada kunjungan hari ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(function() {
        const endpoint = "{{ route('kunjungan.getKunjunganToday') }}";
        const refreshInterval = 5000;

        function statusClass(status) {
            const knownStatus = ['menunggu', 'dipanggil', 'selesai'];
            return knownStatus.includes(status) ? status : 'default';
        }

        function statusText(status) {
            return (status || '-').replaceAll('_', ' ').replace(/\b\w/g, function(char) {
                return char.toUpperCase();
            });
        }

        function escapeHtml(value) {
            return $('<div>').text(value || '-').html();
        }

        function renderCalledCard(kunjungan) {
            $('#calledCard').toggleClass('queue-called', !!kunjungan);
            $('#calledNumber').text(kunjungan?.no_antrian || '-');
            $('#calledPatient').text(kunjungan?.nama_pasien || '-');
            $('#calledPoli').text(kunjungan?.poli || '-');
            $('#calledDoctor').text(kunjungan?.nama_dokter || '-');
        }

        function renderSummary(summary) {
            $('#totalQueue').text(summary?.total || 0);
            $('#waitingQueue').text(summary?.menunggu || 0);
            $('#calledQueue').text(summary?.dipanggil || 0);
            $('#doneQueue').text(summary?.selesai || 0);
        }

        function renderTable(kunjungans) {
            if (!kunjungans.length) {
                $('#queueTableBody').html('<tr><td colspan="6" class="text-center text-muted py-4">Belum ada kunjungan hari ini.</td></tr>');
                return;
            }

            const rows = kunjungans.map(function(kunjungan) {
                const rowClass = kunjungan.status_antrian === 'dipanggil' ? 'table-info' : '';
                const badgeClass = statusClass(kunjungan.status_antrian);

                return `
                    <tr class="${rowClass}">
                        <td class="fw-bold">${escapeHtml(kunjungan.no_antrian)}</td>
                        <td>${escapeHtml(kunjungan.nama_pasien)}</td>
                        <td>${escapeHtml(kunjungan.poli)}</td>
                        <td>${escapeHtml(kunjungan.keluhan)}</td>
                        <td>
                            <span class="status-badge status-${badgeClass}">
                                ${escapeHtml(statusText(kunjungan.status_antrian))}
                            </span>
                        </td>
                        <td>${escapeHtml(kunjungan.jam_daftar)}</td>
                    </tr>
                `;
            }).join('');

            $('#queueTableBody').html(rows);
        }

        function refreshQueue() {
            $.ajax({
                url: endpoint,
                method: 'GET',
                dataType: 'json',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    renderCalledCard(response.dipanggil);
                    renderSummary(response.summary);
                    renderTable(response.kunjungans || []);
                    $('#lastUpdated').text(response.last_updated || '-');
                    $('#realtimeInfo').removeClass('text-danger').addClass('text-muted').text('Realtime aktif');
                },
                error: function() {
                    $('#realtimeInfo').removeClass('text-muted').addClass('text-danger').text('Realtime terputus');
                }
            });
        }

        setInterval(refreshQueue, refreshInterval);
    });
</script>
@endpush
@endsection
