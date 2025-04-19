<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil - Himar Coffee</title>
    <link rel="icon" type="image/png" href="assets/logo.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            scroll-behavior: smooth;
            background-color: #f8fafc;
        }

        .card-shadow {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: scale(1.05);
        }

        .btn-primary:active {
            transform: scale(0.95);
        }

        .container {
            max-width: 640px;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">
    <!-- Header -->
    <nav class="bg-[#FAF0E6] text-white px-6 py-3 mx-4 mt-4 rounded-full shadow-lg">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <img src="assets/logo.png" alt="Logo" class="h-12 w-auto rounded-full" />
            </div>
            <div class="flex items-center space-x-4">
                <a href="/" class="bg-[#8B4513] text-white font-semibold px-4 py-2 rounded-full border border-black hover:bg-[#556B2F] flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </nav>

    <!-- Order Success Section -->
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-2xl p-6 card-shadow">
            <div class="text-center">
                <i class="fas fa-check-circle text-green-500 text-5xl mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-800">Pembayaran Berhasil!</h2>
                <p class="text-gray-600 mt-2">Terima kasih atas pesanan Anda.</p>
            </div>
            <div class="mt-6 text-left space-y-4">
                <p><strong>Kode Transaksi:</strong> <span id="transactionCode" class="text-gray-700"></span></p>
                <p><strong>Nama Pemesan:</strong> <span id="customerName" class="text-gray-700"></span></p>
                <p><strong>Total:</strong> <span id="totalPrice" class="text-gray-700"></span></p>
                <div class="mt-4">
                    <p class="font-semibold">QR Code:</p>
                    <img id="qrCode" src="" alt="QR Code" class="mx-auto mt-2 w-48 h-48">
                    <p class="text-sm text-gray-500 mt-2 text-center">Simpan kode QR ini untuk mengambil pesanan di kasir</p>
                </div>
            </div>
            <a href="/" class="mt-8 block w-full bg-green-700 hover:bg-green-900 text-white py-3 rounded-xl font-medium text-center btn-primary">
                Kembali ke Menu
            </a>
        </div>
    </div>

    <script>
        // Function to get URL parameters
        function getUrlParameter(name) {
            name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
            const regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
            const results = regex.exec(location.search);
            return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
        }

        // Populate order details from URL parameters
        document.addEventListener('DOMContentLoaded', () => {
            const transactionCode = getUrlParameter('order_id');
            const customerName = getUrlParameter('customer_name');
            const totalPrice = getUrlParameter('total');
            const qrCodeUrl = getUrlParameter('qr_url') || `/storage/qrcodes/${transactionCode}.png`;

            document.getElementById('transactionCode').textContent = transactionCode || 'N/A';
            document.getElementById('customerName').textContent = customerName || 'N/A';
            document.getElementById('totalPrice').textContent = totalPrice ? `Rp${parseInt(totalPrice).toLocaleString('id-ID')}` : 'N/A';
            document.getElementById('qrCode').src = qrCodeUrl;
        });
    </script>
</body>
</html>