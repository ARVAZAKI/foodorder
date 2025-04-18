@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Scan QR Code</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <button id="startButton" class="btn btn-primary mb-3">Buka Kamera</button>
                            <div id="qr-reader" style="width: 100%; max-width: 500px;"></div>
                            <div id="qr-reader-results" class="mt-3"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="searchCode">Kode Transaksi:</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="searchCode" placeholder="Masukkan kode transaksi">
                                    <button class="btn btn-outline-secondary" type="button" id="searchButton">Cari</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Daftar Pesanan</span>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="transactionTable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Kode Transaksi</th>
                                    <th scope="col">Item</th>
                                    <th scope="col">Total Harga</th>
                                    <th scope="col">Status Pembayaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $index => $transaction)
                                <tr data-transaction-code="{{ $transaction->transaction_code }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $transaction->transaction_code }}</td>
                                    <td>
                                        <ul class="list-unstyled mb-0">
                                        @foreach($transaction->cart as $cart)
                                            <li>{{ $cart->item->name }} ({{ $cart->quantity }}x) - Rp {{ number_format($cart->item->price * $cart->quantity, 0, ',', '.') }}</li>
                                        @endforeach
                                        </ul>
                                    </td>
                                    <td>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                                    <td>
                                        @if($transaction->payment_status == 'paid')
                                            <span class="badge bg-success">Dibayar</span>
                                        @elseif($transaction->payment_status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @else
                                            <span class="badge bg-danger">Belum Dibayar</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data pesanan</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="https://unpkg.com/html5-qrcode@2.3.4/html5-qrcode.min.js"></script>
<script>
      document.addEventListener('DOMContentLoaded', function () {
    let html5QrCode = null;
    let isScanning = false;
    const qrReader = document.getElementById('qr-reader');
    const qrReaderResults = document.getElementById('qr-reader-results');
    const startButton = document.getElementById('startButton');
    const searchCode = document.getElementById('searchCode');
    const searchButton = document.getElementById('searchButton');

    // Inisialisasi scanner dengan pendekatan yang berbeda
    async function initializeScanner() {
        html5QrCode = new Html5Qrcode("qr-reader", { 
            verbose: false,
            experimentalFeatures: {
                useBarCodeDetectorIfSupported: true // Menggunakan Barcode Detection API jika tersedia
            }
        });
        
        try {
            const cameras = await Html5Qrcode.getCameras();
            if (cameras && cameras.length) {
                // Menggunakan kamera belakang jika tersedia
                const rearCamera = cameras.find(camera => camera.label.toLowerCase().includes('back') || 
                                                        camera.label.toLowerCase().includes('rear') ||
                                                        camera.label.toLowerCase().includes('belakang'));
                
                const cameraId = rearCamera ? rearCamera.id : cameras[0].id;
                
                // Konfigurasi yang lebih agresif
                const config = {
                    fps: 30, // FPS sangat tinggi
                    qrbox: { width: 400, height: 400 }, // Area pemindaian lebih besar
                    aspectRatio: 1.0,
                    disableFlip: false,
                    formatsToSupport: [
                        Html5QrcodeSupportedFormats.QR_CODE,
                        Html5QrcodeSupportedFormats.EAN_13,
                        Html5QrcodeSupportedFormats.CODE_39,
                        Html5QrcodeSupportedFormats.CODE_128,
                        Html5QrcodeSupportedFormats.EAN_8,
                        Html5QrcodeSupportedFormats.UPC_A,
                        Html5QrcodeSupportedFormats.UPC_E,
                        Html5QrcodeSupportedFormats.DATA_MATRIX
                    ]
                };
                
                qrReaderResults.innerHTML = '<div class="alert alert-info">Memulai pemindaian, mengarahkan kamera...</div>';
                
                // Mulai pemindaian dengan kamera yang dipilih
                await html5QrCode.start(
                    cameraId, 
                    config,
                    (decodedText) => {
                        console.log('Barcode detected:', decodedText);
                        searchCode.value = decodedText;
                        qrReaderResults.innerHTML = `<div class="alert alert-success">Barcode berhasil discan: ${decodedText}</div>`;
                        searchTransaction(decodedText);
                        
                        // Hentikan pemindaian setelah berhasil
                        html5QrCode.stop().then(() => {
                            isScanning = false;
                            startButton.textContent = 'Buka Kamera';
                        });
                    },
                    (errorMessage) => {
                        // Silent error handling
                        console.log('Scanning...', errorMessage);
                    }
                );
                
                isScanning = true;
                startButton.textContent = 'Tutup Kamera';
                
            } else {
                qrReaderResults.innerHTML = '<div class="alert alert-warning">Tidak ada kamera terdeteksi.</div>';
            }
        } catch (err) {
            console.error('Error starting scanner:', err);
            qrReaderResults.innerHTML = `<div class="alert alert-danger">Error: ${err.message}</div>`;
        }
    }
    
    // Fungsi untuk menghentikan scanner
    async function stopScanner() {
        if (html5QrCode && isScanning) {
            try {
                await html5QrCode.stop();
                isScanning = false;
                startButton.textContent = 'Buka Kamera';
                qrReaderResults.innerHTML = '';
            } catch (err) {
                console.error('Error stopping scanner:', err);
            }
        }
    }

    // Event listener untuk tombol start
    startButton.addEventListener('click', async function() {
        if (isScanning) {
            await stopScanner();
        } else {
            // Tambahkan styling untuk meningkatkan visibilitas pemindai
            qrReader.style.border = '2px solid #007bff';
            qrReader.style.borderRadius = '8px';
            qrReader.style.overflow = 'hidden';
            
            await initializeScanner();
        }
    });

    searchButton.addEventListener('click', function () {
        const code = searchCode.value.trim();
        if (code) {
            searchTransaction(code);
        } else {
            alert('Masukkan kode transaksi terlebih dahulu');
        }
    });

    function searchTransaction(code) {
        const rows = document.querySelectorAll('#transactionTable tbody tr');
        let found = false;

        rows.forEach(row => {
            const transactionCode = row.getAttribute('data-transaction-code');
            if (!transactionCode) {
                row.style.display = '';
                return;
            }

            if (transactionCode === code) {
                row.classList.add('table-primary');
                row.style.display = '';
                row.scrollIntoView({ behavior: 'smooth', block: 'center' });
                found = true;
            } else {
                row.classList.remove('table-primary');
                row.style.display = 'none';
            }
        });

        qrReaderResults.innerHTML = found
            ? `<div class="alert alert-success">Transaksi dengan kode "${code}" ditemukan</div>`
            : `<div class="alert alert-danger">Kode transaksi "${code}" tidak ditemukan</div>`;
    }
});
</script>
@endsection