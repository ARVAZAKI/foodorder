@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- QR Scanner Card -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Scan QR Code</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 mb-4 mb-lg-0">
                            <button id="startButton" class="btn btn-primary mb-3">Buka Kamera</button>
                            <div id="qr-reader" class="w-100" style="max-width: 100%; height: auto;"></div>
                            <div id="qr-reader-results" class="mt-3"></div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="searchCode">Kode Transaksi:</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="searchCode" placeholder="Masukkan kode transaksi">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" id="searchButton">Cari</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transactions Card -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Daftar Pesanan</span>
                </div>

                <div class="card-body px-0 px-md-3">
                    @if (session('success'))
                        <div class="alert alert-success mx-2" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="transactionTable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Kode</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Item</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $index => $transaction)
                                <tr data-transaction-code="{{ $transaction->transaction_code }}">
                                    <td>{{ ($transactions->currentPage() - 1) * $transactions->perPage() + $index + 1 }}</td>
                                    <td><span class="d-inline-block text-truncate" style="max-width: 120px;">{{ $transaction->transaction_code }}</span></td>
                                    <td><span class="d-inline-block text-truncate" style="max-width: 120px;">{{ $transaction->name }}</span></td>
                                    <td>
                                        <ul class="list-unstyled mb-0">
                                        @foreach($transaction->cart as $cart)
                                            <li class="text-truncate" style="max-width: 200px;">{{ $cart->item->name }} ({{ $cart->quantity }}x) - Rp {{ number_format($cart->item->price * $cart->quantity, 0, ',', '.') }}</li>
                                        @endforeach
                                        </ul>
                                    </td>
                                    <td>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $paymentStatusBadges[$transaction->payment_status] }}">
                                            {{ $paymentStatusLabels[$transaction->payment_status] }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('order.show', $transaction->id) }}" class="btn btn-sm btn-info">Detail</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data pesanan</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4 flex-wrap" id="pagination-container">
                        <div class="text-center w-100">
                            @if ($transactions->lastPage() > 1)
                                <div class="d-flex justify-content-center align-items-center mb-2">
                                    <small>{{ $transactions->firstItem() }} to {{ $transactions->lastItem() }} of {{ $transactions->total() }} results</small>
                                </div>
                                
                                <div class="d-flex justify-content-center flex-wrap mt-2">
                                    @if ($transactions->onFirstPage())
                                        <span class="btn btn-sm btn-outline-secondary disabled m-1">« Previous</span>
                                    @else
                                        <a href="{{ $transactions->previousPageUrl() }}" class="btn btn-sm btn-outline-secondary m-1">« Previous</a>
                                    @endif
                                    
                                    <div class="d-flex flex-wrap mx-1">
                                        @php
                                            $maxPages = 5; // Maximum number of pages to display
                                            $startPage = max(1, min($transactions->currentPage() - floor($maxPages/2), $transactions->lastPage() - $maxPages + 1));
                                            $endPage = min($startPage + $maxPages - 1, $transactions->lastPage());
                                            $startPage = max(1, $endPage - $maxPages + 1);
                                        @endphp
                                        
                                        @if($startPage > 1)
                                            <a href="{{ $transactions->url(1) }}" class="btn btn-sm btn-outline-secondary m-1">1</a>
                                            @if($startPage > 2)
                                                <span class="d-flex align-items-center px-2">...</span>
                                            @endif
                                        @endif
                                        
                                        @for ($i = $startPage; $i <= $endPage; $i++)
                                            @if ($i == $transactions->currentPage())
                                                <span class="btn btn-sm btn-outline-primary active m-1">{{ $i }}</span>
                                            @else
                                                <a href="{{ $transactions->url($i) }}" class="btn btn-sm btn-outline-secondary m-1">{{ $i }}</a>
                                            @endif
                                        @endfor
                                        
                                        @if($endPage < $transactions->lastPage())
                                            @if($endPage < $transactions->lastPage() - 1)
                                                <span class="d-flex align-items-center px-2">...</span>
                                            @endif
                                            <a href="{{ $transactions->url($transactions->lastPage()) }}" class="btn btn-sm btn-outline-secondary m-1">{{ $transactions->lastPage() }}</a>
                                        @endif
                                    </div>
                                    
                                    @if ($transactions->hasMorePages())
                                        <a href="{{ $transactions->nextPageUrl() }}" class="btn btn-sm btn-outline-primary m-1">Next »</a>
                                    @else
                                        <span class="btn btn-sm btn-outline-secondary disabled m-1">Next »</span>
                                    @endif
                                </div>
                            @endif
                        </div>
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
    const paginationContainer = document.getElementById('pagination-container');

    // Handle responsive QR Reader size
    function updateQrReaderSize() {
        const containerWidth = qrReader.parentElement.clientWidth;
        const size = Math.min(containerWidth, 500); // Max size 500px
        qrReader.style.height = size + 'px';
    }

    // Call on page load and window resize
    updateQrReaderSize();
    window.addEventListener('resize', updateQrReaderSize);

    // Inisialisasi scanner dengan pendekatan yang berbeda
    async function initializeScanner() {
        if (html5QrCode === null) {
            html5QrCode = new Html5Qrcode("qr-reader", { 
                verbose: false,
                experimentalFeatures: {
                    useBarCodeDetectorIfSupported: true // Menggunakan Barcode Detection API jika tersedia
                }
            });
        }
        
        try {
            const cameras = await Html5Qrcode.getCameras();
            if (cameras && cameras.length) {
                // Menggunakan kamera belakang jika tersedia
                const rearCamera = cameras.find(camera => camera.label.toLowerCase().includes('back') || 
                                                      camera.label.toLowerCase().includes('rear') ||
                                                      camera.label.toLowerCase().includes('belakang'));
                
                const cameraId = rearCamera ? rearCamera.id : cameras[0].id;
                
                // Konfigurasi yang lebih responsif
                const containerWidth = qrReader.clientWidth;
                const qrboxSize = Math.min(containerWidth, 300); // Responsive QR box size
                
                const config = {
                    fps: 30,
                    qrbox: { width: qrboxSize, height: qrboxSize },
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

    // Reset search results and show all transactions
    function resetSearch() {
        const rows = document.querySelectorAll('#transactionTable tbody tr');
        rows.forEach(row => {
            row.classList.remove('table-primary');
            row.style.display = '';
        });
        qrReaderResults.innerHTML = '';
        
        // Show pagination again after reset
        if (paginationContainer) {
            paginationContainer.style.display = '';
        }
        
        // Reload the page to restore original pagination state
        window.location.reload();
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

    // Add button to reset search results
    const resetButton = document.createElement('button');
    resetButton.className = 'btn btn-secondary ms-2';
    resetButton.textContent = 'Reset';
    resetButton.addEventListener('click', function() {
        resetSearch();
        searchCode.value = '';
    });
    searchButton.parentNode.appendChild(resetButton);

    searchButton.addEventListener('click', function () {
        const code = searchCode.value.trim();
        if (code) {
            searchTransaction(code);
        } else {
            alert('Masukkan kode transaksi terlebih dahulu');
        }
    });

    // Add enter key support for search
    searchCode.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchButton.click();
        }
    });

    function searchTransaction(code) {
        const rows = document.querySelectorAll('#transactionTable tbody tr');
        let found = false;
        
        // First, show all rows to ensure we're searching through everything
        rows.forEach(row => {
            row.style.display = '';
            row.classList.remove('table-primary');
        });

        rows.forEach(row => {
            const transactionCode = row.getAttribute('data-transaction-code');
            if (!transactionCode) {
                return;
            }

            if (transactionCode === code) {
                row.classList.add('table-primary');
                row.style.display = '';
                // Use smoother scrolling with a delay for better UX
                setTimeout(() => {
                    row.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 300);
                found = true;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Hide pagination during search results
        if (paginationContainer) {
            paginationContainer.style.display = 'none';
        }

        qrReaderResults.innerHTML = found
            ? `<div class="alert alert-success">Transaksi dengan kode "${code}" ditemukan</div>`
            : `<div class="alert alert-danger">Kode transaksi "${code}" tidak ditemukan</div>`;
    }
});
</script>
@endsection