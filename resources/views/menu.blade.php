<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Himar Coffee</title>
    <link rel="icon" type="image/png" href="assets/logo.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            scroll-behavior: smooth;
        }

        .food-item {
            transition: all 0.3s ease;
        }

        .food-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: #EF4444;
            color: white;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            transform-origin: center;
            animation: pulse 1s ease-in-out;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.3);
            }

            100% {
                transform: scale(1);
            }
        }

        .category-scroll::-webkit-scrollbar {
            display: none;
        }

        .category-scroll {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .bottom-nav {
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        }

        .card-shadow {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-add {
            transition: all 0.3s ease;
        }

        .btn-add:hover {
            transform: scale(1.05);
        }

        .btn-add:active {
            transform: scale(0.95);
        }

        .cart-slide-up {
            animation: slideUp 0.3s ease-out forwards;
        }

        .cart-slide-down {
            animation: slideDown 0.3s ease-out forwards;
        }

        @keyframes slideUp {
            from {
                transform: translateY(100%);
            }

            to {
                transform: translateY(0);
            }
        }

        @keyframes slideDown {
            from {
                transform: translateY(0);
            }

            to {
                transform: translateY(100%);
            }
        }

        .success-toast {
            animation: fadeInOut 2s ease-in-out;
        }

        @keyframes fadeInOut {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            15% {
                opacity: 1;
                transform: translateY(0);
            }

            85% {
                opacity: 1;
                transform: translateY(0);
            }

            100% {
                opacity: 0;
                transform: translateY(-20px);
            }
        }

        .rating span {
            color: #FCD34D;
        }

        .skeleton-loading {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: skeleton-loading 1.5s infinite;
        }

        @keyframes skeleton-loading {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        .heart-button.active {
            color: #EF4444;
            animation: heartBeat 0.3s ease-in-out;
        }

        @keyframes heartBeat {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.4);
            }

            100% {
                transform: scale(1);
            }
        }

        .badge-new {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: #EF4444;
            color: white;
            font-size: 10px;
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 12px;
            z-index: 10;
        }

        .badge-discount {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: #10B981;
            color: white;
            font-size: 10px;
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 12px;
            z-index: 10;
        }

        .quantity-input::-webkit-inner-spin-button,
        .quantity-input::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .food-card:hover .food-card-overlay {
            opacity: 1;
        }

        .food-card-overlay {
            background: linear-gradient(to top, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
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
            <div class="hidden md:flex space-x-8 font-medium text-[#36454F]">
                <a href="/">Beranda</a>
                <a href="/about-us">About Us</a>
                <a href="/menu">Menu</a>
            </div>
            <div class="flex items-center space-x-4">
                <!-- Ikon keranjang -->
                <div class="relative cursor-pointer p-2" id="cartButton">
                    <i class="fas fa-shopping-cart text-gray-700 text-lg hover:text-orange-500 transition"></i>
                    <span id="cartCount" class="cart-count hidden">0</span>
                </div>
                <a href="/login" class="hidden md:flex bg-[#8B4513] text-white font-semibold w-24 px-4 py-2 rounded-full border border-black hover:bg-[#556B2F] justify-center items-center">
                    Login
                </a>
                <button onclick="toggleDropdown()" class="md:hidden text-[#36454F] focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <div id="dropdownMenu" class="fixed top-0 left-0 w-full h-full bg-[#F0FAF7] z-50 hidden">
        <div class="flex justify-between items-start p-6">
            <button onclick="toggleDropdown()" class="text-[#004225] text-3xl font-bold">×</button>
        </div>
        <div class="flex flex-col items-start px-10 space-y-6 text-[#004225] font-semibold text-lg">
            <a href="/" onclick="toggleDropdown()">Beranda</a>
            <a href="/about-us" onclick="toggleDropdown()">About Us</a>
            <a href="/menu" onclick="toggleDropdown()">Menu</a>
            <a href="/login" class="mt-4 bg-[#8B4513] text-white px-6 py-2 rounded-full border border-black hover:bg-[#556B2F]">Login</a>
        </div>
    </div>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownMenu');
            dropdown.classList.toggle('hidden');
        }
    </script>

    <!-- Categories Section -->
    <div class="container mx-auto px-4 py-3">
        <div class="flex overflow-x-auto space-x-3 pb-2 category-scroll">
            <button class="category-btn bg-[#7c6a53] text-white px-5 py-2 rounded-full whitespace-nowrap text-sm font-medium" data-category="all">
                <i class="fas fa-utensils mr-2"></i>Semua
            </button>
            @foreach($categories as $category)
            <button class="category-btn bg-white text-gray-700 px-5 py-2 rounded-full whitespace-nowrap text-sm font-medium shadow-sm" data-category="{{ $category->id }}">
                <i class="fas fa-drumstick-bite mr-2"></i>{{ $category->name }}
            </button>
            @endforeach
        </div>
    </div>

    <!-- Food Menu Section -->
    <div id="menu" class="container mx-auto px-4 py-4 pb-24">
        <div class="flex justify-between items-center mb-3">
            <h2 class="text-lg font-bold">Menu</h2>
            <div class="flex items-center space-x-2">
                <button id="gridViewBtn" class="text-[#7c6a53] p-1 rounded-md bg-gray-100">
                    <i class="fas fa-th-large"></i>
                </button>
                <button id="listViewBtn" class="text-[#7c6a53] p-1 rounded-md hover:bg-gray-100">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>

        <div id="gridView" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($items as $item)
            <div class="food-item bg-white rounded-xl overflow-hidden shadow-sm zoom-on-hover flex" data-category="{{ $item->category_id }}">
                <div class="relative">
                    <img src="{{asset('storage/' . $item->item_image)}}" alt="{{ $item->name }}" class="w-24 h-24 object-cover">
                </div>
                <div class="p-3 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start">
                            <h3 class="font-semibold text-base">{{ $item->name }}</h3>
                        </div>
                        <div class="flex items-center mt-1 mb-1">
                            <div class="rating text-xs text-yellow-400">
                                @for ($i = 0; $i < 4; $i++)
                                    <i class="fas fa-star"></i>
                                    @endfor
                                    <i class="fas fa-star-half-alt"></i>
                            </div>
                        </div>
                        <p class="text-gray-500 text-xs mt-1 line-clamp-2">{{ $item->description }}</p>
                    </div>
                    <div class="flex justify-between items-center mt-2">
                        <span class="text-orange-600 font-bold">Rp{{ number_format($item->price, 0, ',', '.') }}</span>
                        <button class="add-to-cart btn-add bg-green-700 hover:bg-green-900 text-white py-1 px-3 rounded-full text-sm flex items-center"
                            data-id="{{ $item->id }}"
                            data-name="{{ $item->name }}"
                            data-price="{{ $item->price }}">
                            <i class="fas fa-plus mr-1"></i> Tambah
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- List View (initially hidden) -->
        <div id="listView" class="space-y-3 hidden">
            <!-- Food items will be dynamically populated via JavaScript -->
        </div>
    </div>

    <!-- Food Details Modal -->
    <div id="foodDetailModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="relative">
                <img src="" alt="Food Detail" id="foodDetailImage" class="w-full h-48 object-cover rounded-t-2xl">
                <button id="closeDetailBtn" class="absolute top-3 right-3 bg-white p-2 rounded-full shadow-md text-gray-700 hover:text-gray-900">
                    <i class="fas fa-times"></i>
                </button>
                <button class="heart-button absolute top-3 left-3 bg-white p-2 rounded-full shadow-md text-gray-300 hover:text-red-500">
                    <i class="fas fa-heart"></i>
                </button>
            </div>
            <div class="p-4">
                <div class="flex justify-between items-start">
                    <h2 id="foodDetailTitle" class="text-xl font-bold"></h2>
                    <span id="foodDetailBadge" class="bg-white text-orange-600 text-xs px-2 py-1 rounded-full">Populer</span>
                </div>

                <div class="flex items-center mt-2">
                    <div class="rating text-sm text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>

                <div class="mt-4">
                    <h3 class="font-semibold text-gray-800">Deskripsi</h3>
                    <p class="text-gray-600 text-sm mt-1" id="foodDetailDescription"></p>
                </div>

                <div class="mt-4">
                    <label for="modalCustomerName" class="block text-sm font-medium text-gray-700 mb-1">Nama Pemesan</label>
                    <input type="text" id="modalCustomerName" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="Masukkan nama Anda">
                </div>

                <div class="mt-6 flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Harga</p>
                        <p id="foodDetailPrice" class="text-orange-600 font-bold text-xl"></p>
                    </div>
                    <div class="flex items-center">
                        <button class="bg-gray-100 hover:bg-gray-200 p-2 rounded-full" id="decreaseQtyDetail">
                            <i class="fas fa-minus text-gray-600 text-xs"></i>
                        </button>
                        <span class="mx-4 font-medium" id="detailQuantity">1</span>
                        <button class="bg-gray-100 hover:bg-gray-200 p-2 rounded-full" id="increaseQtyDetail">
                            <i class="fas fa-plus text-gray-600 text-xs"></i>
                        </button>
                    </div>
                </div>

                <div class="mt-6 flex space-x-4">
                    <button id="addToCartDetail" class="flex-1 bg-[#7c6a53] hover:bg-[#695842] text-white py-2 rounded-xl font-medium flex items-center justify-center">
                        <i class="fas fa-shopping-cart ml-4"></i> Tambahkan ke Keranjang
                    </button>
                    <button id="payNowDetail" class="flex-1 bg-green-700 hover:bg-green-900 text-white py-2 rounded-xl font-medium flex items-center justify-center">
                        <i class="fas fa-credit-card mr-1"></i> Bayar Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Cart Sheet -->
    <div id="cartModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div id="cartSheet" class="bg-white rounded-t-2xl absolute bottom-0 left-0 right-0 max-h-[80vh] overflow-y-auto transform transition-transform duration-300 translate-y-full">
            <div class="px-4 py-3 border-b">
                <div class="mb-3">
                    <label for="customerName" class="block text-sm font-medium text-gray-700 mb-1">Nama Pemesan</label>
                    <input type="text" id="customerName" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="Masukkan nama Anda">
                </div>
            </div>
            <div class="sticky top-0 bg-white pt-4 pb-3 px-4 border-b">
                <div class="w-12 h-1 bg-gray-300 rounded-full mx-auto mb-4"></div>
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold">Keranjang Belanja</h2>
                    <button id="closeCartBtn" class="text-gray-500 hover:text-gray-700 p-1">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <div id="cartItems" class="p-4">
                <div class="text-center text-gray-500 py-12" id="emptyCartMessage">
                    <i class="fas fa-shopping-basket text-5xl mb-4 text-gray-300"></i>
                    <p class="text-lg">Keranjang belanja masih kosong</p>
                    <p class="text-sm text-gray-400 mt-2">Tambahkan beberapa item untuk melanjutkan</p>
                </div>
            </div>

            <div class="px-4 pt-2 pb-4">
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal</span>
                        <span id="subtotalAmount">Rp 0</span>
                    </div>
                </div>

                <div class="flex justify-between font-bold text-lg mb-4 pt-2 border-t">
                    <span>Total:</span>
                    <span id="cartTotal">Rp 0</span>
                </div>

                <button id="checkoutBtn" class="w-full bg-green-700 hover:bg-green-900 text-white py-3 rounded-xl font-bold text-lg disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center">
                    Lanjutkan ke Pembayaran
                    <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>
    </div>

    <div id="successPopup" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 relative">
            <button id="closeSuccessPopup" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
            <div class="text-center">
                <i class="fas fa-check-circle text-green-500 text-4xl mb-4"></i>
                <h2 class="text-xl font-bold text-gray-800">Pembayaran Berhasil!</h2>
                <p class="text-gray-600 mt-2">Terima kasih atas pesanan Anda.</p>
                <div class="mt-4 text-left">
                    <p><strong>Kode Transaksi:</strong> <span id="successTransactionCode"></span></p>
                    <p><strong>Nama Pemesan:</strong> <span id="successCustomerName"></span></p>
                    <p><strong>Total:</strong> <span id="successTotalPrice"></span></p>
                    <p class="mt-4"><strong>QR Code:</strong></p>
                    <img id="successQrCode" src="" alt="QR Code" class="mx-auto mt-2 w-40 h-40">
                    <p class="text-sm text-gray-500 mt-2">Simpan kode QR ini untuk mengambil pesanan di kasir</p>
                </div>
                <button id="successOkBtn" class="mt-6 w-full bg-green-700 hover:bg-green-900 text-white py-2 rounded-xl font-medium">
                    OK
                </button>
            </div>
        </div>
    </div>

    <!-- Checkout Form -->
    <form id="checkoutForm" action="{{ route('transactions.store') }}" method="POST" class="hidden">
        @csrf
        <input type="text" name="name" id="checkoutName">
        <div id="checkoutItems"></div>
    </form>

    <!-- Order Success Toast -->
    <div id="successToast" class="fixed bottom-20 left-0 right-0 flex justify-center hidden">
        <div class="bg-green-500 text-white px-5 py-3 rounded-full shadow-lg flex items-center success-toast">
            <i class="fas fa-check-circle mr-2"></i>
            <span id="successToastMessage">Item berhasil ditambahkan!</span>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div id="loadingIndicator" class="fixed inset-0 bg-white bg-opacity-80 z-50 hidden flex items-center justify-center">
        <div class="text-center">
            <div class="inline-block">
                <svg class="animate-spin h-10 w-10 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            <p class="mt-3 text-gray-700 font-medium">Memuat...</p>
        </div>
    </div>

    <script>
        // Cart functionality
        const cartButton = document.getElementById('cartButton');
        const cartModal = document.getElementById('cartModal');
        const cartSheet = document.getElementById('cartSheet');
        const closeCartBtn = document.getElementById('closeCartBtn');
        const cartItems = document.getElementById('cartItems');
        const cartTotal = document.getElementById('cartTotal');
        const subtotalAmount = document.getElementById('subtotalAmount');
        const cartCount = document.getElementById('cartCount');
        const checkoutBtn = document.getElementById('checkoutBtn');
        const emptyCartMessage = document.getElementById('emptyCartMessage');
        const successToast = document.getElementById('successToast');
        const successToastMessage = document.getElementById('successToastMessage');
        const loadingIndicator = document.getElementById('loadingIndicator');
        const foodDetailModal = document.getElementById('foodDetailModal');
        const closeDetailBtn = document.getElementById('closeDetailBtn');
        const foodDetailTitle = document.getElementById('foodDetailTitle');
        const foodDetailPrice = document.getElementById('foodDetailPrice');
        const detailQuantity = document.getElementById('detailQuantity');
        const decreaseQtyDetail = document.getElementById('decreaseQtyDetail');
        const increaseQtyDetail = document.getElementById('increaseQtyDetail');
        const addToCartDetail = document.getElementById('addToCartDetail');
        const payNowDetail = document.getElementById('payNowDetail');
        const modalCustomerName = document.getElementById('modalCustomerName');
        const gridViewBtn = document.getElementById('gridViewBtn');
        const listViewBtn = document.getElementById('listViewBtn');
        const gridView = document.getElementById('gridView');
        const listView = document.getElementById('listView');
        const customerName = document.getElementById('customerName');
        const successPopup = document.getElementById('successPopup');
        const closeSuccessPopup = document.getElementById('closeSuccessPopup');
        const successOkBtn = document.getElementById('successOkBtn');
        const successTransactionCode = document.getElementById('successTransactionCode');
        const successCustomerName = document.getElementById('successCustomerName');
        const successTotalPrice = document.getElementById('successTotalPrice');
        const successQrCode = document.getElementById('successQrCode');

        let cart = [];
        let currentDetailItem = null;
        let detailQty = 1;

        // Toggle view buttons
        gridViewBtn.addEventListener('click', () => {
            gridView.classList.remove('hidden');
            listView.classList.add('hidden');
            gridViewBtn.classList.add('bg-orange-100', 'text-green-900');
            gridViewBtn.classList.remove('text-gray-400');
            listViewBtn.classList.remove('bg-orange-100', 'text-green-900');
            listViewBtn.classList.add('text-gray-400');
        });

        listViewBtn.addEventListener('click', () => {
            gridView.classList.add('hidden');
            listView.classList.remove('hidden');
            listViewBtn.classList.add('bg-orange-100', 'text-green-900');
            listViewBtn.classList.remove('text-gray-400');
            gridViewBtn.classList.remove('bg-orange-100', 'text-green-900');
            gridViewBtn.classList.add('text-gray-400');

            if (listView.children.length === 0) {
                populateListView();
            }
        });

        function populateListView() {
            listView.innerHTML = '';
            const foodItems = document.querySelectorAll('.food-item:not(.hidden)');

            foodItems.forEach(item => {
                const listItem = createListViewItem(item);
                listView.appendChild(listItem);
            });

            listView.querySelectorAll('.add-to-cart').forEach(button => {
                button.addEventListener('click', handleAddToCart);
            });
        }

        function createListViewItem(item) {
            const img = item.querySelector('img').src;
            const title = item.querySelector('h3').textContent;
            const price = item.querySelector('.text-orange-600').textContent;
            const description = item.querySelector('.text-gray-500.text-xs.mt-1').textContent;
            const id = item.querySelector('.add-to-cart').getAttribute('data-id');
            const dataName = item.querySelector('.add-to-cart').getAttribute('data-name');
            const dataPrice = item.querySelector('.add-to-cart').getAttribute('data-price');
            const dataCategory = item.getAttribute('data-category');

            const listItem = document.createElement('div');
            listItem.className = 'food-item bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition';
            listItem.setAttribute('data-category', dataCategory);
            listItem.innerHTML = `
                <div class="flex items-center p-3">
                    <img src="${img}" alt="${title}" class="w-16 h-16 object-cover rounded-lg">
                    <div class="ml-3 flex-1">
                        <h3 class="font-semibold">${title}</h3>
                        <p class="text-xs text-gray-500 line-clamp-1">${description}</p>
                        <div class="flex justify-between items-center mt-1">
                            <span class="text-orange-600 font-bold">${price}</span>
                            <button class="add-to-cart btn-add bg-green-700 hover:bg-green-900 text-white py-1 px-2 rounded-full text-xs" data-id="${id}" data-name="${dataName}" data-price="${dataPrice}">
                                + Tambah
                            </button>
                        </div>
                    </div>
                </div>
            `;

            return listItem;
        }

        function openFoodDetail(item) {
            const id = item.querySelector('.add-to-cart').getAttribute('data-id');
            const name = item.querySelector('.add-to-cart').getAttribute('data-name');
            const price = item.querySelector('.add-to-cart').getAttribute('data-price');
            const formattedPrice = `Rp${parseInt(price).toLocaleString('id-ID')}`;
            const image = item.querySelector('img').src;
            const description = item.querySelector('.text-gray-500.text-xs.mt-1').textContent;

            currentDetailItem = {
                id,
                name,
                price
            };
            foodDetailTitle.textContent = name;
            foodDetailPrice.textContent = formattedPrice;
            document.getElementById('foodDetailImage').src = image;
            document.getElementById('foodDetailDescription').textContent = description;

            detailQuantity.textContent = '1';
            detailQty = 1;
            modalCustomerName.value = customerName.value; // Sync with cart customer name

            foodDetailModal.classList.remove('hidden');
        }

        closeDetailBtn.addEventListener('click', () => {
            foodDetailModal.classList.add('hidden');
        });

        foodDetailModal.addEventListener('click', (e) => {
            if (e.target === foodDetailModal) {
                foodDetailModal.classList.add('hidden');
            }
        });

        document.querySelectorAll('.food-item').forEach(item => {
            item.addEventListener('click', (e) => {
                if (!e.target.closest('.add-to-cart') && !e.target.closest('.heart-button')) {
                    openFoodDetail(item);
                }
            });
        });

        decreaseQtyDetail.addEventListener('click', () => {
            if (detailQty > 1) {
                detailQty--;
                detailQuantity.textContent = detailQty;
            }
        });

        increaseQtyDetail.addEventListener('click', () => {
            detailQty++;
            detailQuantity.textContent = detailQty;
        });

        addToCartDetail.addEventListener('click', () => {
            if (currentDetailItem) {
                const {
                    id,
                    name,
                    price
                } = currentDetailItem;
                const existingItem = cart.find(item => item.id === id);

                if (existingItem) {
                    existingItem.quantity += detailQty;
                } else {
                    cart.push({
                        id,
                        name,
                        price: parseInt(price),
                        quantity: detailQty
                    });
                }

                updateCart();
                foodDetailModal.classList.add('hidden');
                showSuccessToast(`${name} (${detailQty}x) ditambahkan ke keranjang`);
            }
        });

        payNowDetail.addEventListener('click', async () => {
            if (!modalCustomerName.value.trim()) {
                showSuccessToast('Harap masukkan nama Anda!');
                modalCustomerName.focus();
                return;
            }
            if (!currentDetailItem) {
                showSuccessToast('Tidak ada item yang dipilih!');
                return;
            }
            loadingIndicator.classList.remove('hidden');
            const data = {
                name: modalCustomerName.value,
                items: [{
                    item_id: currentDetailItem.id,
                    quantity: detailQty
                }],
                _token: '{{ csrf_token() }}'
            };
            try {
                console.log('Mengirim data transaksi dari modal:', data);
                if (typeof snap === 'undefined') {
                    throw new Error('Midtrans Snap tidak tersedia. Pastikan script Snap dimuat dan client key valid.');
                }
                const response = await fetch('{{ route("transactions.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                console.log('Status response:', response.status);
                if (!response.ok) {
                    const errorText = await response.text();
                    throw new Error(`HTTP error! Status: ${response.status}, Detail: ${errorText}`);
                }
                const result = await response.json();
                loadingIndicator.classList.add('hidden');
                console.log('Response dari server:', result);
                if (result.error) {
                    showSuccessToast(result.error);
                    console.error('Server Error:', result.error);
                    return;
                }
                if (!result.snap_token) {
                    showSuccessToast('Gagal mendapatkan Snap Token, silakan coba lagi.');
                    console.error('Missing Snap Token:', result);
                    return;
                }
                console.log('Snap Token diterima:', result.snap_token);
                foodDetailModal.classList.add('hidden');
                console.log('Memanggil snap.pay dengan token:', result.snap_token);
                snap.pay(result.snap_token, {
                    onSuccess: function(result) {
                        console.log('Pembayaran berhasil:', result);
                        window.lastTransactionResponse = result;
                        showSuccessPopup(result); // Pass just the result object
                    },
                    onPending: function(result) {
                        console.log('Pembayaran tertunda:', result);
                        showSuccessToast('Pembayaran tertunda, silakan selesaikan pembayaran.');
                    },
                    onError: function(result) {
                        console.error('Pembayaran gagal:', result);
                        showSuccessToast('Pembayaran gagal, silakan coba lagi.');
                    },
                    onClose: function() {
                        console.log('Popup pembayaran ditutup');
                        showSuccessToast('Anda menutup popup pembayaran.');
                    }
                });
            } catch (error) {
                loadingIndicator.classList.add('hidden');
                showSuccessToast('Terjadi kesalahan: ' + error.message);
                console.error('Checkout Error:', error.message);
            }
        });

        cartButton.addEventListener('click', () => {
            cartModal.classList.remove('hidden');
            cartSheet.classList.remove('translate-y-full');
            cartSheet.classList.add('cart-slide-up');
        });

        function closeCart() {
            cartSheet.classList.remove('cart-slide-up');
            cartSheet.classList.add('cart-slide-down');
            setTimeout(() => {
                cartModal.classList.add('hidden');
                cartSheet.classList.remove('cart-slide-down');
                cartSheet.classList.add('translate-y-full');
            }, 300);
        }

        closeCartBtn.addEventListener('click', closeCart);

        cartModal.addEventListener('click', (e) => {
            if (e.target === cartModal) {
                closeCart();
            }
        });

        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', handleAddToCart);
        });

        function handleAddToCart(e) {
            e.stopPropagation();
            const button = e.currentTarget;
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const price = parseInt(button.getAttribute('data-price'));

            const existingItem = cart.find(item => item.id === id);

            if (existingItem) {
                existingItem.quantity++;
            } else {
                cart.push({
                    id,
                    name,
                    price,
                    quantity: 1
                });
            }

            updateCart();
            showSuccessToast(`${name} ditambahkan ke keranjang`);
        }

        function updateCart() {
            const totalItems = cart.reduce((acc, item) => acc + item.quantity, 0);
            cartCount.textContent = totalItems;

            if (totalItems > 0) {
                cartCount.classList.remove('hidden');
                checkoutBtn.removeAttribute('disabled');
                emptyCartMessage.classList.add('hidden');
            } else {
                cartCount.classList.add('hidden');
                checkoutBtn.setAttribute('disabled', 'disabled');
                emptyCartMessage.classList.remove('hidden');
            }

            cartItems.innerHTML = cart.length === 0 ? emptyCartMessage.outerHTML : '';

            let subtotal = 0;

            cart.forEach(item => {
                const itemTotal = item.price * item.quantity;
                subtotal += itemTotal;

                const cartItem = document.createElement('div');
                cartItem.className = 'flex items-center justify-between py-3 border-b last:border-b-0';
                cartItem.innerHTML = `
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-utensils text-gray-500"></i>
                        </div>
                        <div>
                            <h4 class="font-medium">${item.name}</h4>
                            <p class="text-orange-600 text-sm">Rp${item.price.toLocaleString('id-ID')}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <button class="decrease-quantity bg-gray-100 hover:bg-gray-200 p-1 rounded-full" data-id="${item.id}">
                            <i class="fas fa-minus text-gray-600 text-xs"></i>
                        </button>
                        <span class="mx-3 font-medium">${item.quantity}</span>
                        <button class="increase-quantity bg-gray-100 hover:bg-gray-200 p-1 rounded-full" data-id="${item.id}">
                            <i class="fas fa-plus text-gray-600 text-xs"></i>
                        </button>
                        <button class="remove-item ml-4 text-gray-400 hover:text-red-500" data-id="${item.id}">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                `;

                if (cart.length > 0) {
                    cartItems.appendChild(cartItem);
                }
            });

            cartItems.querySelectorAll('.decrease-quantity').forEach(button => {
                button.addEventListener('click', decreaseQuantity);
            });

            cartItems.querySelectorAll('.increase-quantity').forEach(button => {
                button.addEventListener('click', increaseQuantity);
            });

            cartItems.querySelectorAll('.remove-item').forEach(button => {
                button.addEventListener('click', removeItem);
            });

            subtotalAmount.textContent = `Rp${subtotal.toLocaleString('id-ID')}`;
            cartTotal.textContent = `Rp${subtotal.toLocaleString('id-ID')}`;
        }

        function decreaseQuantity() {
            const id = this.getAttribute('data-id');
            const itemIndex = cart.findIndex(item => item.id === id);

            if (itemIndex !== -1) {
                if (cart[itemIndex].quantity > 1) {
                    cart[itemIndex].quantity--;
                } else {
                    cart.splice(itemIndex, 1);
                }

                updateCart();
            }
        }

        function increaseQuantity() {
            const id = this.getAttribute('data-id');
            const item = cart.find(item => item.id === id);

            if (item) {
                item.quantity++;
                updateCart();
            }
        }

        function removeItem() {
            const id = this.getAttribute('data-id');
            const itemIndex = cart.findIndex(item => item.id === id);

            if (itemIndex !== -1) {
                const itemName = cart[itemIndex].name;
                cart.splice(itemIndex, 1);
                updateCart();
                showSuccessToast(`${itemName} dihapus dari keranjang`);
            }
        }

        function showSuccessToast(message) {
            successToastMessage.textContent = message;
            successToast.classList.remove('hidden');

            setTimeout(() => {
                successToast.classList.add('hidden');
            }, 3000);
        }

        document.querySelectorAll('.heart-button').forEach(button => {
            button.addEventListener('click', (e) => {
                e.stopPropagation();
                button.classList.toggle('text-red-500');
                button.classList.toggle('text-gray-300');
            });
        });

        checkoutBtn.addEventListener('click', async () => {
            if (!customerName.value.trim()) {
                showSuccessToast('Harap masukkan nama Anda!');
                customerName.focus();
                return;
            }
            if (cart.length === 0) {
                showSuccessToast('Keranjang kosong, tambahkan item terlebih dahulu');
                return;
            }
            loadingIndicator.classList.remove('hidden');
            const data = {
                name: customerName.value,
                items: cart.map(item => ({
                    item_id: item.id,
                    quantity: item.quantity
                })),
                _token: '{{ csrf_token() }}'
            };
            try {
                console.log('Mengirim data transaksi:', data);
                if (typeof snap === 'undefined') {
                    throw new Error('Midtrans Snap tidak tersedia. Pastikan script Snap dimuat dan client key valid.');
                }
                const response = await fetch('{{ route("transactions.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                console.log('Status response:', response.status);
                if (!response.ok) {
                    const errorText = await response.text();
                    throw new Error(`HTTP error! Status: ${response.status}, Detail: ${errorText}`);
                }
                const result = await response.json();
                loadingIndicator.classList.add('hidden');
                console.log('Response dari server:', result);
                if (result.error) {
                    showSuccessToast(result.error);
                    console.error('Server Error:', result.error);
                    return;
                }
                if (!result.snap_token) {
                    showSuccessToast('Gagal mendapatkan Snap Token, silakan coba lagi.');
                    console.error('Missing Snap Token:', result);
                    return;
                }
                console.log('Snap Token diterima:', result.snap_token);
                closeCart();
                console.log('Memanggil snap.pay dengan token:', result.snap_token);
                snap.pay(result.snap_token, {
                    onSuccess: function(result) {
                        console.log('Pembayaran berhasil:', result);
                        window.lastTransactionResponse = result;
                        showSuccessPopup(result); // Pass just the result object
                        // Clear cart
                        cart = [];
                        customerName.value = '';
                        updateCart();
                    },
                    onPending: function(result) {
                        console.log('Pembayaran tertunda:', result);
                        showSuccessToast('Pembayaran tertunda, silakan selesaikan pembayaran.');
                    },
                    onError: function(result) {
                        console.error('Pembayaran gagal:', result);
                        showSuccessToast('Pembayaran gagal, silakan coba lagi.');
                    },
                    onClose: function() {
                        console.log('Popup pembayaran ditutup');
                        showSuccessToast('Anda menutup popup pembayaran.');
                    }
                });
            } catch (error) {
                loadingIndicator.classList.add('hidden');
                showSuccessToast('Terjadi kesalahan: ' + error.message);
                console.error('Checkout Error:', error.message);
            }
        });

        const categoryButtons = document.querySelectorAll('.category-btn');

        categoryButtons.forEach(button => {
            button.addEventListener('click', () => {
                categoryButtons.forEach(btn => {
                    btn.classList.remove('bg-[#7c6a53]', 'text-white');
                    btn.classList.add('bg-white', 'text-gray-700');
                });

                button.classList.remove('bg-white', 'text-gray-700');
                button.classList.add('bg-[#7c6a53]', 'text-white');

                const selectedCategory = button.getAttribute('data-category');

                const foodItems = document.querySelectorAll('.food-item');

                foodItems.forEach(item => {
                    const itemCategory = item.getAttribute('data-category');

                    if (selectedCategory === 'all' || selectedCategory === itemCategory) {
                        item.classList.remove('hidden');
                    } else {
                        item.classList.add('hidden');
                    }
                });

                if (!listView.classList.contains('hidden')) {
                    listView.innerHTML = '';
                    foodItems.forEach(item => {
                        const itemCategory = item.getAttribute('data-category');
                        if (selectedCategory === 'all' || selectedCategory === itemCategory) {
                            const clonedItem = createListViewItem(item);
                            listView.appendChild(clonedItem);
                        }
                    });

                    listView.querySelectorAll('.add-to-cart').forEach(button => {
                        button.addEventListener('click', handleAddToCart);
                    });
                }
            });
        });

        // Perbaiki fungsi showSuccessPopup
        // Replace your current showSuccessPopup function with this:
        function showSuccessPopup(result) {
            console.log('Menampilkan popup sukses dengan data:', result);

            // Make sure result is defined before proceeding
            if (!result) {
                console.error('Data transaksi kosong');
                showSuccessToast('Terjadi kesalahan saat memproses transaksi');
                return;
            }

            // Handle different data structures that might be passed
            const orderId = result.order_id || '';
            const customerNameValue = customerName.value || modalCustomerName.value || '';
            const amount = result.gross_amount || 0;

            successTransactionCode.textContent = orderId;
            successCustomerName.textContent = customerNameValue;
            successTotalPrice.textContent = `Rp${parseInt(amount).toLocaleString('id-ID')}`;

            // Handle QR code image
            if (result.qr_url) {
                successQrCode.src = result.qr_url;
            } else {
                // Fall back to a generated URL if qr_url is not available
                successQrCode.src = `/storage/qrcodes/${orderId}.png`; // Placeholder image
            }

            successPopup.classList.remove('hidden');
        }

        document.getElementById('closeSuccessPopup').addEventListener('click', function() {
            const popup = document.getElementById('successPopup');
            popup.classList.add('hidden');
        });

        updateCart();
    </script>
</body>

</html>