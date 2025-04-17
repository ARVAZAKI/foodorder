<x-app-layout>
    <div class="container mx-auto py-8 px-4">
        <h1 class="text-2xl font-bold mb-6">Create New Transaction</h1>
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
            
            @if(session('transaction'))
                <div class="bg-white p-6 rounded shadow-md mb-6">
                    <h2 class="text-xl font-bold mb-4">Transaction Details</h2>
                    <p><strong>Transaction Code:</strong> {{ session('transaction')->transaction_code }}</p>
                    <p><strong>Total Price:</strong> Rp{{ number_format(session('transaction')->total_price, 0, ',', '.') }}</p>
                    
                    @if(session('qr_url'))
                        <div class="mt-4">
                            <h3 class="font-bold mb-2">QR Code:</h3>
                            <img src="{{ session('qr_url') }}" alt="QR Code for Transaction {{ session('transaction')->transaction_code }}" class="border p-2 bg-white">
                            <p class="text-sm text-gray-600 mt-2">Scan QR code to view transaction details</p>
                        </div>
                    @endif
                </div>
            @endif
        @endif
        
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('transactions.store') }}" method="POST" class="bg-white p-6 rounded shadow-md">
            @csrf
            
            <div id="items-container">
                <div class="item-row mb-4 flex flex-col md:flex-row md:space-x-4">
                    <div class="w-full md:w-1/2 mb-4 md:mb-0">
                        <label class="block text-gray-700 mb-2">Item ID:</label>
                        <input type="number" name="items[0][item_id]" value="{{ old('items.0.item_id', 1) }}" class="w-full border rounded p-2">
                    </div>
                    <div class="w-full md:w-1/2">
                        <label class="block text-gray-700 mb-2">Quantity:</label>
                        <input type="number" name="items[0][quantity]" value="{{ old('items.0.quantity', 1) }}" min="1" class="w-full border rounded p-2">
                    </div>
                </div>
            </div>
            
            <div class="mb-4">
                <button type="button" id="add-item" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition">
                    Add Another Item
                </button>
            </div>
            
            <div class="mt-6">
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded font-bold transition">
                    Create Transaction
                </button>
            </div>
        </form>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let itemCount = 1;
            const container = document.getElementById('items-container');
            const addButton = document.getElementById('add-item');
            
            addButton.addEventListener('click', function() {
                const newRow = document.createElement('div');
                newRow.className = 'item-row mb-4 flex flex-col md:flex-row md:space-x-4';
                newRow.innerHTML = `
                    <div class="w-full md:w-1/2 mb-4 md:mb-0">
                        <label class="block text-gray-700 mb-2">Item ID:</label>
                        <input type="number" name="items[${itemCount}][item_id]" value="1" class="w-full border rounded p-2">
                    </div>
                    <div class="w-full md:w-1/2">
                        <label class="block text-gray-700 mb-2">Quantity:</label>
                        <input type="number" name="items[${itemCount}][quantity]" value="1" min="1" class="w-full border rounded p-2">
                    </div>
                    <div class="mt-2 md:mt-8">
                        <button type="button" class="remove-item bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-sm transition">
                            Remove
                        </button>
                    </div>
                `;
                container.appendChild(newRow);
                
                // Add event listener to the remove button
                const removeButton = newRow.querySelector('.remove-item');
                removeButton.addEventListener('click', function() {
                    container.removeChild(newRow);
                });
                
                itemCount++;
            });
        });
    </script>
</x-app-layout>