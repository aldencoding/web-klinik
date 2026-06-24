<div class="modal fade" id="queueTicketModal" tabindex="-1" aria-labelledby="queueTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="queueTicketModalLabel">Unduh Tiket Antrian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body d-flex justify-content-center bg-light">
                <section id="queueTicketPrintable" class="queue-ticket" style="background: #fff; padding: 20px; border-radius: 8px;">
                    <header class="queue-ticket__header">
                        <strong>KLINIK SEHAT</strong>
                        <span>Tiket Antrian Kunjungan</span>
                    </header>

                    <div class="queue-ticket__divider"></div>

                    <p class="queue-ticket__label">NOMOR ANTRIAN</p>
                    <div class="queue-ticket__number">{{ $ticket['number'] }}</div>

                    <div class="queue-ticket__divider"></div>

                    <dl class="queue-ticket__details">
                        <div>
                            <dt>Nama Pasien</dt>
                            <dd>{{ $ticket['patient_name'] }}</dd>
                        </div>
                        <div>
                            <dt>Poli</dt>
                            <dd>{{ $ticket['clinic'] }}</dd>
                        </div>
                        <div>
                            <dt>Jadwal Kunjungan</dt>
                            <dd>{{ $ticket['schedule'] }} WIB</dd>
                        </div>
                    </dl>

                    <div class="queue-ticket__barcode" aria-label="Barcode {{ $ticket['barcode'] }}">
                        <svg viewBox="0 0 {{ $barcodeWidth }} 64" role="img" preserveAspectRatio="none">
                            @foreach ($bars as $bar)
                            <rect x="{{ $bar['x'] }}" y="2" width="{{ $bar['width'] }}" height="52" fill="#000" />
                            @endforeach
                        </svg>
                        <span>{{ $ticket['barcode'] }}</span>
                    </div>

                    <div class="queue-ticket__divider"></div>

                    <footer class="queue-ticket__footer">
                        <strong>Harap menunggu hingga nomor Anda dipanggil.</strong>
                        <span>Terima kasih. Semoga lekas sehat.</span>
                    </footer>
                </section>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success" id="downloadQueueTicket">
                    <i class="ti ti-download me-1"></i>Unduh PNG
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .queue-ticket {
        width: 80mm;
        max-width: 100%;
        padding: 7mm 6mm;
        background: #fff;
        color: #111;
        font-family: "Courier New", monospace;
        font-size: 12px;
        line-height: 1.35;
        box-shadow: 0 2px 12px rgba(0, 0, 0, .08);
    }

    .queue-ticket__header,
    .queue-ticket__footer {
        display: flex;
        flex-direction: column;
        gap: 2px;
        text-align: center;
    }

    .queue-ticket__header strong {
        font-size: 18px;
        letter-spacing: .8px;
    }

    .queue-ticket__divider {
        margin: 10px 0;
        border-top: 1px dashed #111;
    }

    .queue-ticket__label {
        margin: 0;
        text-align: center;
        font-size: 11px;
    }

    .queue-ticket__number {
        margin-top: 2px;
        text-align: center;
        font-size: 36px;
        font-weight: 700;
        line-height: 1;
    }

    .queue-ticket__details {
        margin: 0;
    }

    .queue-ticket__details div {
        display: grid;
        grid-template-columns: 38% 1fr;
        gap: 8px;
        margin-bottom: 5px;
    }

    .queue-ticket__details dt {
        font-weight: 400;
    }

    .queue-ticket__details dd {
        margin: 0;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .queue-ticket__barcode {
        margin: 12px 0 4px;
        text-align: center;
    }

    .queue-ticket__barcode svg {
        display: block;
        width: 100%;
        height: 56px;
    }

    .queue-ticket__barcode span {
        display: block;
        margin-top: 2px;
        font-size: 11px;
        letter-spacing: 3px;
    }

    .queue-ticket__footer {
        font-size: 10px;
    }

    @page {
        size: 80mm auto;
        margin: 0;
    }

    @media print {

        html,
        body {
            width: 80mm;
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
        }

        body * {
            visibility: hidden !important;
        }

        #queueTicketPrintable,
        #queueTicketPrintable * {
            visibility: visible !important;
        }

        #queueTicketPrintable {
            position: absolute;
            inset: 0 auto auto 0;
            width: 80mm;
            max-width: none;
            margin: 0;
            box-shadow: none;
        }
    }
</style>

@push('scripts')
<script>
    $(document).ready(function() {
        const $modalElement = $('#queueTicketModal');

        // 1. Tampilkan modal otomatis (Sintaks jQuery)
        if ($modalElement.length) {
            $modalElement.modal('show');
        }

        // 2. Event handler klik tombol (Sintaks jQuery)
        $('#downloadQueueTicket').on('click', function() {
            // Mengambil elemen HTML menggunakan selektor jQuery [0] untuk mendapatkan objek DOM mentah yang dibutuhkan html2canvas
            const ticketElement = $('#queueTicketPrintable')[0];
            const nomorAntrian = "{{ $ticket['number'] }}";

            // Proses konversi html2canvas
            html2canvas(ticketElement, {
                scale: 2,
                backgroundColor: '#ffffff'
            }).then(function(canvas) {
                const imageString = canvas.toDataURL("image/png");

                // 3. Membuat & memicu download link sepenuhnya dengan jQuery
                $('<a>', {
                    href: imageString,
                    download: 'Tiket_Antrian_' + nomorAntrian + '.png'
                }).appendTo('body')[0].click();

                // Hapus kembali link virtual dari body setelah didownload
                $('body').children('a:last').remove();
            });
        });
    });
</script>
@endpush