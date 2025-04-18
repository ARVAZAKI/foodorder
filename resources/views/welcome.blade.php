<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Himar Coffee</title>
    <link rel="icon" type="image/png" href="assets/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <style type="text/tailwindcss">
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

        .success-toast {
            animation: fadeInOut 2s ease-in-out;
        }

        @keyframes fadeInOut {
            0% { opacity: 0; transform: translateY(20px); }
            15% { opacity: 1; transform: translateY(0); }
            85% { opacity: 1; transform: translateY(0); }
            100% { opacity: 0; transform: translateY(-20px); }
        }

        .loading-indicator {
            background: rgba(255, 255, 255, 0.8);
        }

        .success-popup {
            max-width: 400px;
        }

        @layer base {
            :root {
                --foreground-rgb: 0, 0, 0;
                --background-start-rgb: 255, 255, 255;
                --background-end-rgb: 255, 255, 255;
            }
            
            body {
                font-family: 'Inter', sans-serif;
                color: rgb(var(--foreground-rgb));
                background: linear-gradient(to bottom, transparent, rgb(var(--background-end-rgb))) rgb(var(--background-start-rgb));
            }
        }
        
        .text-olive-500 { color: #6b7c45; }
        .bg-green-50 { background-color: #f0f9f1; }
        .bg-green-700 { background-color: #23632a; }
        .bg-green-800 { background-color: #1e4f24; }
        .text-green-700 { color: #23632a; }
        .text-green-800 { color: #1e4f24; }
        .border-green-700 { border-color: #23632a; }
        .hover\:text-green-700:hover { color: #23632a; }
    </style>
</head>

<body class="min-h-screen">
    <!-- Navigation -->
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
                <a href="/menu" class="relative cursor-pointer p-2" id="cartButton">
                    <i class="fas fa-shopping-cart text-gray-700 text-lg transition"></i>
                </a>

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


    <!-- Hero Section -->
    <section x-data="carousel()" x-init="startAutoSlide()" class="relative overflow-hidden">
        <div class="container mx-auto px-4 md:px-8 py-12 md:py-20 flex flex-col md:flex-row items-center relative h-[600px]">
            <div class="relative w-full overflow-hidden">
                <div class="flex transform-gpu transition-transform duration-[1000ms] ease-in-out" :style="`transform: translateX(-${activeSlide * 100}%);`">
                    <template x-for="(slide, index) in slides" :key="index">
                        <div class="min-w-full flex flex-col md:flex-row items-center gap-8">
                            <div class="md:w-1/2 px-4 z-10">
                                <h1 class="text-4xl md:text-6xl font-bold text-[#36454F] leading-tight" x-html="slide.title"></h1>
                                <p class="mt-4 text-[#556B2F] max-w-md" x-text="slide.desc"></p>
                            </div>
                            <div class="w-full md:w-1/2 px-4">
                                <img :src="slide.image" alt="Coffee Image" class="object-contain w-full h-[400px] md:h-[500px]">
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <!-- Products Section -->
    <section class="bg-[#FAF0E6] py-16 md:py-24 relative overflow-hidden" x-data="bestProducts()">
        <div class="absolute inset-0 flex justify-center items-start pointer-events-none">
            <h2 class="text-[100px] md:text-[100px] font-bold text-transparent whitespace-nowrap select-none opacity-40"
                style="-webkit-text-stroke: 1px #556B2F;">
                our best products our best products our best products
            </h2>
        </div>

        <div class="container mx-auto px-4 md:px-8 relative z-10">
            <h2 class="text-4xl md:text-7xl font-bold text-[#556B2F] mb-12">Our Best Products</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <template x-for="(product, index) in products" :key="index">
                    <div class="relative rounded-2xl overflow-hidden w-full h-[300px] md:h-[400px] group">
                        <img :src="product.image" alt="" class="w-full h-full object-cover" />
                        <div class="absolute inset-0 bg-black transition-opacity duration-500 opacity-0 group-hover:opacity-30"></div>
                        <div class="absolute inset-0 flex flex-col justify-end p-4 text-white transition-opacity duration-500 opacity-0 group-hover:opacity-100">
                            <h3 class="text-lg font-semibold mb-3" x-text="product.title"></h3>
                            <button @click="openModal(product)"
                                class="bg-[#7c6a53] text-white rounded-xl px-3 py-2 font-medium text-sm w-32 cursor-pointer focus:outline-none">
                                See Detail
                            </button>
                        </div>
                    </div>
                </template>
            </div>
            <div x-show="showModal" class="fixed inset-0 bg-transparent z-100 flex justify-center items-center pointer-events-none" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
                <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12 relative pointer-events-auto" @click.away="showModal = false">
                    <button @click="showModal = false" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 focus:outline-none">
                        <svg class="h-6 w-6 fill-current" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M17.707 5.293a1 1 0 010 1.414L13.414 12l4.293 4.293a1 1 0 01-1.414 1.414L12 13.414l-4.293 4.293a1 1 0 01-1.414-1.414L10.586 12 6.293 7.707a1 1 0 011.414-1.414L12 10.586l4.293-4.293a1 1 0 011.414 0z" />
                        </svg>
                    </button>
                    <template x-if="selectedCard">
                        <div>
                            <img :src="selectedCard.detailImage" class="rounded-t-xl mb-4 object-cover w-full h-auto" />
                            <h3 class="text-xl font-bold text-[#556B2F]" x-text="selectedCard.title"></h3>
                            <p class="text-gray-700" x-text="selectedCard.description"></p>
                            <p class="mt-3 text-green-700 font-bold" x-text="`Rp${selectedCard.price.toLocaleString('id-ID')}`"></p>
                            <div class="mt-6 flex flex-col space-y-4">
                                <a href="/menu" class="flex-1 bg-green-700 text-white px-4 py-2 rounded-md text-sm hover:bg-green-600 text-center">
                                    Ke Halaman Menu
                                </a>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </section>

    <script>
        function bestProducts() {
            return {
                showModal: false,
                showCart: false,
                selectedCard: null,
                cart: [],
                products: [{
                        title: 'Merry Gloss',
                        image: 'assets/best1.svg',
                        detailImage: 'assets/best_detail1.svg',
                        description: 'Detail produk Merry Gloss...'
                    },
                    {
                        title: 'Java Aroma',
                        image: 'assets/best2.svg',
                        detailImage: 'assets/best_detail2.svg',
                        description: 'Kopi lokal dengan aroma kuat dan cita rasa khas Jawa.'
                    },
                    {
                        title: 'Bali Bloom',
                        image: 'assets/best3.svg',
                        detailImage: 'assets/best_detail3.svg',
                        description: 'Cita rasa tropikal dari dataran tinggi Bali.'
                    }
                ],
                openModal(product) {
                    this.selectedCard = product;
                    this.showModal = true;
                },
                closeModal() {
                    this.showModal = false;
                },
            }
        }
    </script>

    <!-- Other Menu Section -->
    <section x-data="carouselComponent()" class="py-12 md:py-12 bg-gradient-to-b from-[#FAF0E6] to-white relative">
        <div class="container mx-auto px-4 md:px-8">
            <div class="absolute inset-0 flex ml-12 mt-8 items-start pointer-events-none">
                <h2 class="text-5xl md:text-6xl font-bold text-transparent whitespace-nowrap select-none opacity-40"
                    style="-webkit-text-stroke: 1px #556B2F;">
                    Other Menu
                </h2>
            </div>
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-4xl md:text-5xl font-bold text-green-800">Other Menu</h2>
                <p class="text-sm md:text-md font-semibold text-green-700 text-right">Explore our additional offerings and <br>discover more exciting options.</p>
            </div>

            <div class="relative">
                <div id="productCarousel" x-ref="container"
                    class="flex space-x-6 md:space-x-8 overflow-x-auto scroll-smooth snap-x snap-mandatory touch-pan-x scrollbar-hide active:user-select-none"
                    style="-webkit-overflow-scrolling: touch; touch-action: pan-y;">
                    <template x-for="(item, index) in displayedItems" :key="index">
                        <div class="relative rounded-2xl overflow-hidden w-70 h-70 group flex-shrink-0 snap-start cursor-grab active:cursor-grabbing shadow-lg">
                            <img :src="item.image" :alt="item.title"
                                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105 z-0" />
                            <div class="absolute inset-0 flex flex-col justify-end p-4 text-white z-10">
                                <h3 class="text-lg font-semibold" x-text="item.title"></h3>
                                <p class="text-sm font-bold mt-1">Rp <span x-text="item.price.toLocaleString('id-ID')"></span></p>
                                <div class="mt-6 flex flex-col space-y-4">
                                    <a href="/menu" class="flex-1 bg-green-700 text-white px-4 py-2 rounded-md text-sm hover:bg-green-600 text-center">
                                        Ke Halaman Menu
                                    </a>
                                </div>
                            </div>
                        </div>
                    </template>
                    <div class="relative rounded-2xl overflow-hidden w-70 h-70 group flex-shrink-0 snap-start cursor-grab active:cursor-grabbing bg-white shadow-lg">
                        <div class="w-full h-full flex flex-col justify-center items-center text-center p-4 bg-[#7c6a53] text-white">
                            <h3 class="text-xl font-bold mb-4">Lihat Selengkapnya</h3>
                            <a href="/menu"
                                class="bg-white text-[#7c6a53] px-4 py-2 rounded-xl font-semibold text-sm transition hover:bg-gray-100">
                                Lihat Semua
                            </a>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <!-- Download App Section -->
    <section class="bg-white text-[#556B2F] py-16 md:py-24">
        <div class="container mx-auto px-4 md:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="md:w-1/2 mb-8 md:mb-0">
                    <h2 class="text-4xl md:text-5xl font-bold mb-6">Order Now</h2>
                    <p class="text-lg mb-8 max-w-md">
                        Pesan kopi favorit Anda langsung disini gapake ribet.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="/menu" class="bg-green-700 hover:bg-green-600 text-white px-8 py-4 rounded-full font-semibold flex items-center justify-center shadow-md transition duration-300 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-cart w-6 h-6 mr-3">
                                <circle cx="9" cy="21" r="1" />
                                <circle cx="20" cy="21" r="1" />
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                            </svg>
                            Order Now
                        </a>
                    </div>
                </div>
                <div class="md:w-1/2">
                    <img src="assets/decoration.svg" alt="Mobile App" class="mx-auto w-auto h-auto max-w-full">
                </div>
            </div>
        </div>
    </section>

    <!-- Location Section -->
    <section class="bg-white py-16 md:py-24" id="lokasi">
        <div class="container mx-auto px-4 md:px-8">
            <h2 class="text-4xl md:text-5xl font-bold text-[#556B2F] mb-8 text-center">Lokasi Kami</h2>
            <div class="w-full h-[400px] rounded-2xl overflow-hidden shadow-lg">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3958.2171983449894!2d107.90923987524693!3d-7.216047592789717!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68b1b1f85cc951%3A0x732d32e61e5a82e3!2sJl.%20Ahmad%20Yani%20No.202%2C%20Kota%20Wetan%2C%20Kec.%20Garut%20Kota%2C%20Kabupaten%20Garut%2C%20Jawa%20Barat%2044111!5e0!3m2!1sid!2sid!4v1744828643813!5m2!1sid!2sid" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <p class="mt-6 text-center text-lg text-gray-700">
                Jl. Ahmad Yani No.202, Kota Wetan, Kec. Garut Kota, Kabupaten Garut, Jawa Barat 44111
            </p>
        </div>
    </section>

    <!-- Success Toast -->
    <div id="successToast" class="fixed bottom-20 left-0 right-0 flex justify-center z-100">
        <div class="bg-green-500 text-white px-5 py-3 rounded-full shadow-lg flex items-center success-toast hidden">
            <i class="fas fa-check-circle mr-2"></i>
            <span id="successToastMessage">Item berhasil ditambahkan!</span>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div id="loadingIndicator" class="fixed inset-0 bg-transparent z-100 hidden flex items-center justify-center">
        <div class="text-center">
            <svg class="animate-spin h-10 w-10 text-[#7c6a53]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="mt-3 text-gray-700 font-medium">Memuat...</p>
        </div>
    </div>

    <!-- Success Popup -->
    <div id="successPopup" class="fixed inset-0 bg-transparent z-100 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 relative success-popup pointer-events-auto">
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
                <button id="successOkBtn" class="mt-6 w-full bg-[#7c6a53] text-white py-2 rounded-xl font-medium hover:bg-[#685a47]">
                    OK
                </button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t py-12">
        <div class="container mx-auto px-4 md:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-6">
                        <img src="assets/logo.png" alt="" class="w-20">
                        <span class="text-[#8B4513] text-xl font-medium">Himar Coffee</span>
                    </div>
                    <p class="">Kopi spesial untuk semua orang</p>
                </div>
                <div>
                    <h3 class="font-semibold text-lg text-[#8B4513] mb-4">Company</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-600 hover:text-green-700">About Us</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-green-700">Careers</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-green-700">Investor Relations</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-green-700">News</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold text-lg text-[#8B4513] mb-4">Products</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-600 hover:text-green-700">Coffee</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-green-700">Non-Coffee</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-green-700">Food</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-green-700">Merchandise</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold text-lg text-[#8B4513] mb-4">Contact</h3>
                    <ul class="space-y-2">
                        <li class="text-gray-600">info@forecoffee.com</li>
                        <li class="text-gray-600">+62 21 1234 5678</li>
                        <li class="text-gray-600">Jl. Ahmad Yani No.202, Kota Wetan, Kec. Garut Kota, Kabupaten Garut, Jawa Barat 44111</li>
                    </ul>
                    <div class="flex space-x-4 mt-4">
                        <a href="#" class="text-green-700 hover:text-green-800">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                            </svg>
                        </a>
                        <a href="#" class="text-green-700 hover:text-green-800">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                            </svg>
                        </a>
                        <a href="#" class="text-green-700 hover:text-green-800">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                                <path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t mt-12 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-600 text-sm">© <script>
                        document.write(new Date().getFullYear())
                    </script> Fore Coffee. All rights reserved.</p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-600 text-sm hover:text-green-700">Privacy Policy</a>
                    <a href="#" class="text-gray-600 text-sm hover:text-green-700">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        function carousel() {
            return {
                activeSlide: 0,
                slides: [{
                        title: 'Grind The<br>Essentials',
                        desc: 'Dibuat dari biji kopi Indonesia pilihan untuk pengalaman minum kopi terbaik setiap hari',
                        image: 'assets/preview1.svg'
                    },
                    {
                        title: 'Rasakan<br>Kelezatan Asli',
                        desc: 'Kopi lokal terbaik dengan cita rasa khas nusantara.',
                        image: 'assets/preview2.svg'
                    },
                    {
                        title: 'Nikmati Hari<br>Dengan Kopi',
                        desc: 'Teman setia saat bekerja, belajar, atau bersantai.',
                        image: 'assets/preview3.svg'
                    }
                ],
                next() {
                    this.activeSlide = (this.activeSlide + 1) % this.slides.length;
                },
                prev() {
                    this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length;
                },
                startAutoSlide() {
                    setInterval(() => {
                        this.next();
                    }, 5000);
                }
            }
        }
    </script>
    <script>
        function carouselComponent() {
            return {
                activeIndex: 0,
                items: Array.from({
                    length: 5
                }),
                displayedItems: [{
                        image: 'assets/best1.svg',
                        title: 'Produk A',
                        price: 'Rp 100.000',
                        description: 'Deskripsi produk A.'
                    },
                    {
                        image: 'assets/best2.svg',
                        title: 'Produk B',
                        price: 'Rp 120.000',
                        description: 'Deskripsi produk A.'
                    },
                    {
                        image: 'assets/best3.svg',
                        title: 'Produk C',
                        price: 'Rp 90.000',
                        description: 'Deskripsi produk A.'
                    },
                    {
                        image: 'assets/best4.svg',
                        title: 'Produk D',
                        price: 'Rp 110.000',
                        description: 'Deskripsi produk A.'
                    },
                    {
                        image: 'assets/best5.svg',
                        title: 'Produk E',
                        price: 'Rp 130.000',
                        description: 'Deskripsi produk A.'
                    },
                ],
                container: null,
                cardWidth: 0,
                cardsVisible: 0,
                dotsCount: 0,
                isDown: false,
                startX: 0,
                scrollLeft: 0,

                showModal: false,
                selectedCard: {},

                init() {
                    this.container = this.$refs.container;
                    this.$nextTick(() => {
                        this.cardWidth = this.container.children[0].offsetWidth + parseInt(getComputedStyle(this.container.children[0]).marginRight) || 0;
                        this.cardsVisible = Math.floor(this.container.offsetWidth / this.cardWidth) || 1;
                        this.dotsCount = Math.ceil(this.displayedItems.length / this.cardsVisible);
                    });

                    this.container.addEventListener('scroll', () => {
                        this.activeIndex = Math.round(this.container.scrollLeft / (this.cardWidth * this.cardsVisible));
                        this.activeIndex = Math.max(0, Math.min(this.activeIndex, this.dotsCount - 1));
                    });

                    this.container.addEventListener('mousedown', (e) => {
                        e.preventDefault();
                        this.isDown = true;
                        this.container.classList.add('active');
                        this.startX = e.pageX - this.container.offsetLeft;
                        this.scrollLeft = this.container.scrollLeft;
                    });
                    this.container.addEventListener('mouseleave', () => {
                        this.isDown = false;
                        this.container.classList.remove('active');
                    });
                    this.container.addEventListener('mouseup', () => {
                        this.isDown = false;
                        this.container.classList.remove('active');
                    });
                    this.container.addEventListener('mousemove', (e) => {
                        if (!this.isDown) return;
                        e.preventDefault();
                        const x = e.pageX - this.container.offsetLeft;
                        const walk = (x - this.startX) * 1.5;
                        this.container.scrollLeft = this.scrollLeft - walk;
                    });

                    this.container.addEventListener('touchstart', (e) => {
                        e.preventDefault();
                        this.isDown = true;
                        this.startX = e.touches[0].pageX;
                        this.scrollLeft = this.container.scrollLeft;
                    }, {
                        passive: false
                    });

                    this.container.addEventListener('touchend', () => {
                        this.isDown = false;
                    });

                    this.container.addEventListener('touchmove', (e) => {
                        if (!this.isDown) return;
                        e.preventDefault();
                        const x = e.touches[0].pageX;
                        const walk = (x - this.startX) * 1.5;
                        this.container.scrollLeft = this.scrollLeft - walk;
                    }, {
                        passive: false
                    });
                },

                scrollPrev() {
                    this.container.scrollLeft -= this.cardWidth * this.cardsVisible;
                },

                scrollNext() {
                    this.container.scrollLeft += this.cardWidth * this.cardsVisible;
                },

                openModal(card) {
                    this.quantity = 1;
                    this.selectedCard = card;
                    this.showModal = true;
                },

                closeModal() {
                    this.showModal = false;
                }
            }
        }
    </script>
</body>

</html>