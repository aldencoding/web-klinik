<style>
    .queue-ticket {
        width: 80mm;
        background: #fff;
        padding: 7mm;
        font-family: "Courier New", monospace;
    }

    .queue-ticket__header,
    .queue-ticket__footer {
        text-align: center;
    }

    .queue-ticket__divider {
        margin: 10px 0;
        border-top: 1px dashed #000;
    }

    .queue-ticket__number {
        text-align: center;
        font-size: 36px;
        font-weight: bold;
    }

    .queue-ticket__details div {
        display: grid;
        grid-template-columns: 40% 1fr;
        margin-bottom: 5px;
    }

    .queue-ticket__details dd {
        margin: 0;
        font-weight: bold;
    }

    @page {
        size: 80mm auto;
        margin: 0;
    }

    @media print {

        body * {
            visibility: hidden !important;
        }

        #queueTicketPrintable,
        #queueTicketPrintable * {
            visibility: visible !important;
        }

        #queueTicketPrintable {
            position: absolute;
            left: 0;
            top: 0;
            width: 80mm;
        }
    }
</style>
<div class="modal fade" id="queueTicketModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Tiket Antrian
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body d-flex justify-content-center bg-light">

                <section
                    id="queueTicketPrintable"
                    class="queue-ticket">

                    <header class="queue-ticket__header">
                        <strong>KLINIK SEHAT</strong>
                        <span>Tiket Antrian Kunjungan</span>
                    </header>

                    <div class="queue-ticket__divider"></div>

                    <p class="queue-ticket__label">
                        NOMOR ANTRIAN
                    </p>

                    <div class="queue-ticket__number">
                        {{ $ticket['number'] }}
                    </div>

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
                            <dt>Jadwal</dt>
                            <dd>{{ $ticket['schedule'] }} WIB</dd>
                        </div>

                    </dl>

                    <div class="queue-ticket__divider"></div>

                    <div class="text-center my-3">

                        {!! QrCode::size(150)->generate($ticket['qr_url']) !!}

                        <small class="d-block mt-2">
                            Scan QR untuk melihat detail antrian
                        </small>

                    </div>

                    <div class="queue-ticket__divider"></div>

                    <footer class="queue-ticket__footer">
                        <strong>
                            Harap menunggu hingga nomor dipanggil
                        </strong>

                        <span>
                            Terima kasih. Semoga lekas sehat.
                        </span>
                    </footer>

                </section>

            </div>

            <div class="modal-footer">

                <button
                    class="btn btn-light"
                    data-bs-dismiss="modal">
                    Tutup
                </button>

                <button
                    class="btn btn-primary"
                    id="printQueueTicket">
                    Cetak
                </button>

            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {

        const modalElement =
            document.getElementById('queueTicketModal');

        if (modalElement) {

            const modal =
                new bootstrap.Modal(modalElement);

            modal.show();
        }

        document
            .getElementById('printQueueTicket')
            ?.addEventListener('click', () => {

                window.print();

            });

    });
</script>
@endpush