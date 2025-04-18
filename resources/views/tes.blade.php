<!DOCTYPE html>
<html>
<head>
    <title>Test Transaction</title>
    <script>
        function addItem() {
            const container = document.getElementById('items-container');
            const index = container.children.length;
            const html = `
                <div class="item-group" style="margin-bottom: 10px;">
                    <label>Item ID: <input type="number" name="items[${index}][item_id]" required></label>
                    <label>Quantity: <input type="number" name="items[${index}][quantity]" min="1" required></label>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
        }
    </script>
</head>
<body>
    <h1>Test Transaction Input</h1>

    @if (session('success'))
        <div style="color: green;">
            <p>{{ session('success') }}</p>
            @if (session('transaction'))
            <p><strong>Transaction Code:</strong> {{ session('transaction')->transaction_code }}</p>
                <p><strong>Total Price:</strong> {{ session('transaction')->total_price }}</p>
                <p><strong>QR Code:</strong><br>
                    <img src="{{ session('qr_url') }}" alt="QR Code">
                </p>
            @endif
        </div>
    @endif

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('transactions.store') }}">
        @csrf

        <div id="items-container">
            <div class="item-group" style="margin-bottom: 10px;">
                <label>Item ID: <input type="number" name="items[0][item_id]" required></label>
                <label>Quantity: <input type="number" name="items[0][quantity]" min="1" required></label>
            </div>
        </div>

        <button type="button" onclick="addItem()">Add Item</button><br><br>
        <button type="submit">Create Transaction</button>
    </form>
</body>
</html>
