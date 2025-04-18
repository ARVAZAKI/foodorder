<!DOCTYPE html>
<html>
<head>
    <title>Test Transaction</title>
    <style>
        .error { color: red; margin-bottom: 10px; }
        .success { color: green; margin-bottom: 10px; }
        .item-group { margin-bottom: 10px; }
        button { padding: 8px 16px; margin: 5px; cursor: pointer; }
        input { margin: 5px; padding: 5px; }
        form { max-width: 600px; margin: 20px; }
    </style>
    <script>
        function addItem() {
            const container = document.getElementById('items-container');
            const index = container.children.length;
            const html = `
                <div class="item-group">
                    <label>Item ID: <input type="number" name="items[${index}][item_id]" min="1" required></label>
                    <label>Quantity: <input type="number" name="items[${index}][quantity]" min="1" required></label>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
        }

        function validateForm(event) {
            const form = document.querySelector('form');
            const items = form.querySelectorAll('input[name*="item_id"]');
            let valid = true;
            items.forEach((item, index) => {
                const quantity = form.querySelector(`input[name="items[${index}][quantity]"]`);
                if (!item.value || item.value < 1) {
                    alert('Item ID must be a positive number.');
                    valid = false;
                }
                if (!quantity.value || quantity.value < 1) {
                    alert('Quantity must be a positive number.');
                    valid = false;
                }
            });
            if (!valid) {
                event.preventDefault();
            }
        }
    </script>
</head>
<body>
    <h1>Test Transaction Input</h1>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="success">
            <p>{{ session('success') }}</p>
            @if (session('transaction'))
                <p><strong>Transaction Code:</strong> {{ session('transaction')->transaction_code }}</p>
                <p><strong>Nama Pembeli:</strong> {{ session('transaction')->buyer_name }}</p>
                <p><strong>Total Price:</strong> Rp {{ number_format(session('transaction')->total_price, 0, ',', '.') }}</p>
                <p><strong>QR Code:</strong><br>
                    <img src="{{ session('qr_url') }}" alt="QR Code">
                </p>
                <p><strong>Debug Info:</strong> Transaction saved with code '{{ session('transaction')->transaction_code }}'. Scan this QR code in the "Daftar Pesanan" page.</p>
            @endif
        </div>
    @endif

    {{-- Error Messages --}}
    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <form method="POST" action="{{ route('transactions.store') }}" onsubmit="validateForm(event)">
        @csrf

        {{-- Nama Pembeli --}}
        <div class="item-group">
            <label>Nama Pembeli:
                <input type="text" name="name" placeholder="Masukkan nama pembeli" required>
            </label>
        </div>

        {{-- Item Dinamis --}}
        <div id="items-container">
            <div class="item-group">
                <label>Item ID: <input type="number" name="items[0][item_id]" min="1" required></label>
                <label>Quantity: <input type="number" name="items[0][quantity]" min="1" required></label>
            </div>
        </div>

        <button type="button" onclick="addItem()">Add Item</button>
        <button type="submit">Create Transaction</button>
    </form>
</body>
</html>
