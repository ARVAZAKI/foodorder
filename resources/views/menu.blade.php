<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RasaNusantara - Pesan Makanan Online</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
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
            0% { transform: scale(1); }
            50% { transform: scale(1.3); }
            100% { transform: scale(1); }
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
            from { transform: translateY(100%); }
            to { transform: translateY(0); }
        }
        
        @keyframes slideDown {
            from { transform: translateY(0); }
            to { transform: translateY(100%); }
        }
        
        .success-toast {
            animation: fadeInOut 2s ease-in-out;
        }
        
        @keyframes fadeInOut {
            0% { opacity: 0; transform: translateY(20px); }
            15% { opacity: 1; transform: translateY(0); }
            85% { opacity: 1; transform: translateY(0); }
            100% { opacity: 0; transform: translateY(-20px); }
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
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        .heart-button.active {
            color: #EF4444;
            animation: heartBeat 0.3s ease-in-out;
        }
        
        @keyframes heartBeat {
            0% { transform: scale(1); }
            50% { transform: scale(1.4); }
            100% { transform: scale(1); }
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
            background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-30">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <i class="fas fa-utensils text-orange-500 mr-2"></i>
                    <h1 class="text-xl font-bold text-orange-600">Nama Cafe</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative cursor-pointer p-2" id="cartButton">
                        <i class="fas fa-shopping-cart text-gray-700 text-lg hover:text-orange-500 transition"></i>
                        <span id="cartCount" class="cart-count">0</span>
                    </div>
                </div>
            </div>
        </div>
    </header>
<!-- Categories Section -->
<div class="container mx-auto px-4 py-3">
    <div class="flex overflow-x-auto space-x-3 pb-2 category-scroll">
        <button class="category-btn bg-orange-500 text-white px-5 py-2 rounded-full whitespace-nowrap text-sm font-medium" data-category="all">
            <i class="fas fa-utensils mr-2"></i>Semua
        </button>
        @foreach($categories as $category)
        <button class="category-btn bg-white text-gray-700 px-5 py-2 rounded-full whitespace-nowrap text-sm font-medium shadow-sm" data-category="{{ $category->id }}">
            <i class="fas fa-drumstick-bite  mr-2"></i>{{ $category->name }}
        </button>
        @endforeach
    </div>
</div>

<!-- Food Menu Section -->
<div id="menu" class="container mx-auto px-4 py-4 pb-24">
    <div class="flex justify-between items-center mb-3">
        <h2 class="text-lg font-bold">Menu</h2>
        <div class="flex items-center space-x-2">
            <button id="gridViewBtn" class="text-orange-500 p-1 rounded-md bg-orange-100">
                <i class="fas fa-th-large"></i>
            </button>
            <button id="listViewBtn" class="text-gray-400 p-1 rounded-md hover:bg-gray-100">
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
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= rand(4, 5))
                                    <i class="fas fa-star"></i>
                                @elseif($i - 0.5 <= rand(3, 5))
                                    <i class="fas fa-star-half-alt"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="text-xs text-gray-500 ml-1">{{ number_format(rand(35, 49) / 10, 1) }} ({{ rand(40, 200) }})</span>
                    </div>
                    <p class="text-gray-500 text-xs mt-1 line-clamp-2">{{ $item->description }}</p>
                </div>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-orange-600 font-bold">Rp{{ number_format($item->price, 0, ',', '.') }}</span>
                    <button class="add-to-cart btn-add bg-orange-500 hover:bg-orange-600 text-white py-1 px-3 rounded-full text-sm flex items-center" 
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
            <img src="/api/placeholder/400/250" alt="Food Detail" id="foodDetailImage" class="w-full h-48 object-cover rounded-t-2xl">
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
                <span id="foodDetailBadge" class="bg-orange-100 text-orange-600 text-xs px-2 py-1 rounded-full">Populer</span>
            </div>
            
            <div class="flex items-center mt-2">
                <div class="rating text-sm text-yellow-400">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <span class="text-sm text-gray-500 ml-1">4.5 (120 ulasan)</span>
            </div>
            
            <div class="mt-4">
                <h3 class="font-semibold text-gray-800">Deskripsi</h3>
                <p class="text-gray-600 text-sm mt-1" id="foodDetailDescription"></p>
            </div>
            
            <div class="mt-4">
                <h3 class="font-semibold text-gray-800">Pilihan Level Pedas</h3>
                <div class="flex space-x-2 mt-2">
                    <button class="spice-level-btn border px-3 py-1 rounded-full text-sm hover:bg-orange-50 active:bg-orange-100">Tidak Pedas</button>
                    <button class="spice-level-btn border px-3 py-1 rounded-full text-sm hover:bg-orange-50 active:bg-orange-100">Sedang</button>
                    <button class="spice-level-btn border px-3 py-1 rounded-full text-sm hover:bg-orange-50 active:bg-orange-100">Pedas</button>
                    <button class="spice-level-btn border px-3 py-1 rounded-full text-sm hover:bg-orange-50 active:bg-orange-100">Sangat Pedas</button>
                </div>
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
            
            <div class="mt-6">
                <button id="addToCartDetail" class="w-full bg-orange-500 hover:bg-orange-600 text-white py-3 rounded-xl font-bold text-lg flex items-center justify-center">
                    <i class="fas fa-shopping-cart mr-2"></i> Tambahkan ke Keranjang
                </button>
            </div>
        </div>
    </div>
</div>

    <!-- Cart Sheet -->
    <div id="cartModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div id="cartSheet" class="bg-white rounded-t-2xl absolute bottom-0 left-0 right-0 max-h-[80vh] overflow-y-auto transform transition-transform duration-300">
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
                <!-- Cart items will be added here dynamically -->
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
                
                <button id="checkoutBtn" class="w-full bg-orange-500 hover:bg-orange-600 text-white py-3 rounded-xl font-bold text-lg disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center">
                    Lanjutkan ke Pembayaran
                    <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>
    </div>

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
        const taxAmount = document.getElementById('taxAmount');
        const deliveryFee = document.getElementById('deliveryFee');
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
        const gridViewBtn = document.getElementById('gridViewBtn');
        const listViewBtn = document.getElementById('listViewBtn');
        const gridView = document.getElementById('gridView');
        const listView = document.getElementById('listView');
        
        let cart = [];
        let currentDetailItem = null;
        let detailQty = 1;
        
        // Toggle view buttons
        gridViewBtn.addEventListener('click', () => {
            gridView.classList.remove('hidden');
            listView.classList.add('hidden');
            gridViewBtn.classList.add('bg-orange-100', 'text-orange-500');
            gridViewBtn.classList.remove('text-gray-400');
            listViewBtn.classList.remove('bg-orange-100', 'text-orange-500');
            listViewBtn.classList.add('text-gray-400');
        });
        
        listViewBtn.addEventListener('click', () => {
            gridView.classList.add('hidden');
            listView.classList.remove('hidden');
            listViewBtn.classList.add('bg-orange-100', 'text-orange-500');
            listViewBtn.classList.remove('text-gray-400');
            gridViewBtn.classList.remove('bg-orange-100', 'text-orange-500');
            gridViewBtn.classList.add('text-gray-400');
            
            // Populate list view if empty
            if (listView.children.length === 0) {
                populateListView();
            }
        });
        
        function populateListView() {
            // Clone food items to list view in a more compact format
            const foodItems = document.querySelectorAll('.food-item');
            foodItems.forEach(item => {
                const img = item.querySelector('img').src;
                const title = item.querySelector('h3').textContent;
                const price = item.querySelector('.text-orange-600').textContent;
                const description = item.querySelector('.text-gray-500.text-xs.mt-1').textContent;
                const id = item.querySelector('.add-to-cart').getAttribute('data-id');
                const dataName = item.querySelector('.add-to-cart').getAttribute('data-name');
                const dataPrice = item.querySelector('.add-to-cart').getAttribute('data-price');
                
                const listItem = document.createElement('div');
                listItem.className = 'food-item bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition';
                listItem.innerHTML = `
                    <div class="flex items-center p-3">
                        <img src="${img}" alt="${title}" class="w-16 h-16 object-cover rounded-lg">
                        <div class="ml-3 flex-1">
                            <h3 class="font-semibold">${title}</h3>
                            <p class="text-xs text-gray-500 line-clamp-1">${description}</p>
                            <div class="flex justify-between items-center mt-1">
                                <span class="text-orange-600 font-bold">${price}</span>
                                <button class="add-to-cart btn-add bg-orange-500 hover:bg-orange-600 text-white py-1 px-2 rounded-full text-xs" data-id="${id}" data-name="${dataName}" data-price="${dataPrice}">
                                    + Tambah
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                listView.appendChild(listItem);
            });
            
            // Add event listeners to newly created buttons
            listView.querySelectorAll('.add-to-cart').forEach(button => {
                button.addEventListener('click', handleAddToCart);
            });
        }
        
        // Food detail modal functions
        function openFoodDetail(item) {
            const id = item.querySelector('.add-to-cart').getAttribute('data-id');
            const name = item.querySelector('.add-to-cart').getAttribute('data-name');
            const price = item.querySelector('.add-to-cart').getAttribute('data-price');
            const formattedPrice = `Rp${parseInt(price).toLocaleString('id-ID')}`;
            
            currentDetailItem = { id, name, price };
            foodDetailTitle.textContent = name;
            foodDetailPrice.textContent = formattedPrice;
            detailQuantity.textContent = '1';
            detailQty = 1;
            
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
        
        // Add click event to food items to open detail modal
        document.querySelectorAll('.food-item').forEach(item => {
            item.addEventListener('click', (e) => {
                // Only open modal if not clicking on buttons
                if (!e.target.closest('.add-to-cart') && !e.target.closest('.heart-button')) {
                    openFoodDetail(item);
                }
            });
        });
        
        // Spice level selection
        const spiceLevelBtns = document.querySelectorAll('.spice-level-btn');
        spiceLevelBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                spiceLevelBtns.forEach(b => b.classList.remove('bg-orange-500', 'text-white'));
                btn.classList.add('bg-orange-500', 'text-white');
            });
        });
        
        // Detail quantity controls
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
        
        // Add to cart from detail modal
        addToCartDetail.addEventListener('click', () => {
            if (currentDetailItem) {
                const { id, name, price } = currentDetailItem;
                
                // Check if item is already in cart
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
                
                // Show success toast
                showSuccessToast(`${name} (${detailQty}x) ditambahkan ke keranjang`);
            }
        });
        
        // Cart toggle
        cartButton.addEventListener('click', () => {
            cartModal.classList.remove('hidden');
            setTimeout(() => {
                cartSheet.classList.add('translate-y-0');
                cartSheet.classList.remove('translate-y-full');
            }, 10);
        });
        
        function closeCart() {
            cartSheet.classList.remove('translate-y-0');
            cartSheet.classList.add('translate-y-full');
            setTimeout(() => {
                cartModal.classList.add('hidden');
            }, 300);
        }
        
        closeCartBtn.addEventListener('click', closeCart);
        
        cartModal.addEventListener('click', (e) => {
            if (e.target === cartModal) {
                closeCart();
            }
        });
        
        // Add to cart functionality
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', handleAddToCart);
        });
        
        function handleAddToCart(e) {
            e.stopPropagation(); // Prevent opening food detail when clicking add button
            
            const button = this || e.currentTarget;
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const price = parseInt(button.getAttribute('data-price'));
            
            // Check if item is already in cart
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
            
            // Show success toast
            showSuccessToast(`${name} ditambahkan ke keranjang`);
        }
        
        function updateCart() {
    // Update cart count
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
    
    // Update cart items
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
    
    // Add event listeners to new buttons
    cartItems.querySelectorAll('.decrease-quantity').forEach(button => {
        button.addEventListener('click', decreaseQuantity);
    });
    
    cartItems.querySelectorAll('.increase-quantity').forEach(button => {
        button.addEventListener('click', increaseQuantity);
    });
    
    cartItems.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', removeItem);
    });
    
    // Update subtotal and total (removing tax and delivery fee calculations)
    subtotalAmount.textContent = `Rp${subtotal.toLocaleString('id-ID')}`;
    
    // Set total equal to subtotal
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
        
        // Heart button toggle
        document.querySelectorAll('.heart-button').forEach(button => {
            button.addEventListener('click', (e) => {
                e.stopPropagation();
                button.classList.toggle('text-red-500');
                button.classList.toggle('text-gray-300');
            });
        });
        
        // Simulate loading on checkout
        checkoutBtn.addEventListener('click', () => {
            loadingIndicator.classList.remove('hidden');
            
            setTimeout(() => {
                loadingIndicator.classList.add('hidden');
                closeCart();
                
                // Clear cart
                cart = [];
                updateCart();
                
                // Show order success message
                showSuccessToast('Pesanan berhasil dibuat!');
            }, 2000);
        });
        
        // Initialize cart
        updateCart();
        // Add this after the cart initialization in your script
// Category filter functionality
const categoryButtons = document.querySelectorAll('.category-btn');

categoryButtons.forEach(button => {
    button.addEventListener('click', () => {
        // Update active category button
        categoryButtons.forEach(btn => {
            btn.classList.remove('bg-orange-500', 'text-white');
            btn.classList.add('bg-white', 'text-gray-700');
        });
        
        button.classList.remove('bg-white', 'text-gray-700');
        button.classList.add('bg-orange-500', 'text-white');
        
        const selectedCategory = button.getAttribute('data-category');
        
        // Filter food items based on category
        const foodItems = document.querySelectorAll('.food-item');
        
        foodItems.forEach(item => {
            const itemCategory = item.getAttribute('data-category');
            
            if (selectedCategory === 'all' || selectedCategory === itemCategory) {
                item.classList.remove('hidden');
            } else {
                item.classList.add('hidden');
            }
        });
        
        // Also update the list view if it's currently shown
        if (!listView.classList.contains('hidden')) {
            // Clear and repopulate list view with filtered items
            listView.innerHTML = '';
            
            foodItems.forEach(item => {
                const itemCategory = item.getAttribute('data-category');
                
                if (selectedCategory === 'all' || selectedCategory === itemCategory) {
                    const clonedItem = createListViewItem(item);
                    listView.appendChild(clonedItem);
                }
            });
            
            // Re-add event listeners to newly created buttons in list view
            listView.querySelectorAll('.add-to-cart').forEach(button => {
                button.addEventListener('click', handleAddToCart);
            });
        }
    });
});

// Helper function to create list view items
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
                    <button class="add-to-cart btn-add bg-orange-500 hover:bg-orange-600 text-white py-1 px-2 rounded-full text-xs" data-id="${id}" data-name="${dataName}" data-price="${dataPrice}">
                        + Tambah
                    </button>
                </div>
            </div>
        </div>
    `;
    
    return listItem;
}

// Modify the populateListView function to use createListViewItem
function populateListView() {
    // Clear any existing items
    listView.innerHTML = '';
    
    // Get all visible food items (respecting current category filter)
    const foodItems = document.querySelectorAll('.food-item:not(.hidden)');
    
    foodItems.forEach(item => {
        const listItem = createListViewItem(item);
        listView.appendChild(listItem);
    });
    
    // Add event listeners to newly created buttons
    listView.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', handleAddToCart);
    });
}
    </script>
</body>
</html>
                            