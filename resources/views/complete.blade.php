<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Selesai - RasaNusantara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }
        .qr-image { max-width: 200px; }
    </style>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8 max-w-md">
        <h1 class="text-2xl font-bold text-orange-600 mb-4">Pembayaran Selesai</h1>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <p class="text-green-600 font-semibold mb-4">Pembayaran Anda telah berhasil!</p>
            <p><strong>Kode Transaksi:</strong> {{ $transaction->transaction_code }}</p>
            <p><strong>Nama Pemesan:</strong> {{ $transaction->name }}</p>
            <p><strong>Total Harga:</strong> Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</p>
            <div class="mt-6">
                <p class="font-semibold">QR Code untuk Kasir</p>
                <div class="flex justify-center my-4">
                    <img src="{{ $qrDataUrl }}" alt="QR Code" class="qr-image">
                </div>
                <p class="text-gray-600 text-sm text-center">Tunjukkan QR code ini ke kasir untuk verifikasi pesanan Anda.</p>
            </div>
            <a href="{{ url('/menu') }}" class="block w-full bg-orange-500 hover:bg-orange-600 text-white py-3 rounded-xl font-bold text-lg text-center mt-6">
                Kembali ke Menu
            </a>
        </div>
    </div>
</body>
</html>
