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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style type="text/tailwindcss">
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
        
        .text-olive-500 {
            color: #6b7c45;
        }
        
        .bg-green-50 {
            background-color: #f0f9f1;
        }
        
        .bg-green-700 {
            background-color: #23632a;
        }
        
        .bg-green-800 {
            background-color: #1e4f24;
        }
        
        .text-green-700 {
            color: #23632a;
        }
        
        .text-green-800 {
            color: #1e4f24;
        }
        
        .border-green-700 {
            border-color: #23632a;
        }
        
        .hover\:text-green-700:hover {
            color: #23632a;
        }
    </style>
</head>

<body class="min-h-screen">
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

    <!-- Hero Section -->
    <section class="bg-white py-16 md:py-24">
        <div class="container mx-auto px-4 md:px-8">
            <div>
                <img src="assets/image_banner.svg" alt="Section Image" class="rounded-lg shadow-md w-full h-auto object-cover">
            </div>
        </div>
    </section>

    <!-- Download App Section -->
    <section class="bg-white text-[#556B2F] py-16 md:py-24"
        x-data="{
        paragraphs: [
            'Terbentuknya brand ini diawali dari ketidaksengajaan. Dari latar belakang yang berbeda-beda namun memiliki keresahan yang sama, dan ingin menciptakan sesuatu yang baru dan inovatif. Kami melihat adanya potensi untuk dikembangkan dalam dunia perkopian, di mana kualitas kopi garut sendiri sudah banyak dikenal dan mampu bersaing di beberapa negara, dari situlah kami mantapkan tekad menjadikan kopi sebagai pilihan untuk mengawali langkah ini.',
            'Saat ini pergaulan anak muda tidak terlepas dari gaya hidup bebas, dan minuman keras masuk di antaranya. Kami setuju untuk membahas lebih lanjut persoalan yang terjadi dan menemukan ide untuk bisa mengambil kesempatan dalam pemecahan masalah tersebut.',
            'Kami mencetuskan HIMAR untuk diangkat menjadi nama brand, arti HIMAR itu sendiri diambil dari plesetan yaitu KHAMR yang secara tidak langsung mendeskripsikan produk yang relevan dengan permasalahan yang kami temui (minuman keras).',
            'Kami menawarkan gaya baru dalam penyajiannya produk dengan kesan eksklusif menggunakan kemasan botol kaca serta visual yang menampilkan identitas kota garut.',
            'Produk yang akan kami kembangkan yaitu kopi olahan yang beraroma dan memiliki sensasi fermentasi buah-buahan, ditujukan sebagai alternatif untuk memenuhi kebutuhan gaya hidup anak muda, juga bisa menjadi media nostalgia bagi para peminum yang sudah tidak mengonsumsi minuman keras namun ingin tetap merasakan sensasinya.',
            'Dengan adanya terobosan ini, kami harap bisa memberikan impact baik untuk anak muda tanpa merusak generasinya. Menjadi pilihan bagi penikmat kopi yang datang dari mana saja dan membawa HIMAR sebagai buah tangannya. Besar harapan HIMAR dikenal dan punya tempat di hati penikmatnya.',
            'HIMAR, a daily ritual of delight'
        ],
        currentIndex: 0,
        intervalId: null,
        init() {
            this.intervalId = setInterval(() => {
                this.nextParagraph();
            }, 5000); // Interval waktu pergantian (dalam milidetik)
        },
        nextParagraph() {
            this.$refs.paragraph.classList.add('opacity-0');
            setTimeout(() => {
                this.currentIndex = (this.currentIndex + 1) % this.paragraphs.length;
                this.$refs.paragraph.classList.remove('opacity-0');
            }, 500); // Durasi transisi fade (dalam milidetik)
        }
    }">
        <div class="container mx-auto px-4 md:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="md:w-1/2">
                    <img src="assets/preview1.svg" alt="Mobile App" class="rounded-xl mx-auto w-auto h-auto max-w-full">
                </div>
                <div class="md:w-1/2 mb-8 md:mb-0 flex flex-col items-center">
                    <h2 class="text-4xl md:text-5xl font-bold mb-6 text-center">Tentang<br>Himar Dan Filosofi</h2>
                    <p class="text-lg mb-8 max-w-md transition-opacity duration-500 text-center" x-text="paragraphs[currentIndex]" x-ref="paragraph"></p>
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
            </div>
        </div>
    </section>

    <section class="bg-white py-16 md:py-24" id="lokasi">
        <div class="container mx-auto px-4 md:px-8">
            <h2 class="text-4xl md:text-5xl font-bold text-[#556B2F] mb-8 text-center">Lokasi Kami</h2>
            <div class="w-full h-[400px] rounded-2xl overflow-hidden shadow-lg">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3958.2171983449894!2d107.90923987524693!3d-7.216047592789717!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68b1b1f85cc951%3A0x732d32e61e5a82e3!2sJl.%20Ahmad%20Yani%20No.202%2C%20Kota%20Wetan%2C%20Kec.%20Garut%20Kota%2C%20Kabupaten%20Garut%2C%20Jawa%20Barat%2044111!5e0!3m2!1sid!2sid!4v1744828643813!5m2!1sid!2sid"
                    width="100%"
                    height="400"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
            <p class="mt-6 text-center text-lg text-gray-700">
                Jl. Ahmad Yani No.202, Kota Wetan, Kec. Garut Kota, Kabupaten Garut, Jawa Barat 44111
            </p>
        </div>
    </section>

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
</body>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('rotatingText', () => ({
            paragraphs: [
                'Terbentuknya brand ini diawali dari ketidaksengajaan. Dari latar belakang yang berbeda-beda namun memiliki keresahan yang sama, dan ingin menciptakan sesuatu yang baru dan inovatif. Kami melihat adanya potensi untuk dikembangkan dalam dunia perkopian, di mana kualitas kopi garut sendiri sudah banyak dikenal dan mampu bersaing di beberapa negara, dari situlah kami mantapkan tekad menjadikan kopi sebagai pilihan untuk mengawali langkah ini.',
                'Saat ini pergaulan anak muda tidak terlepas dari gaya hidup bebas, dan minuman keras masuk di antaranya. Kami setuju untuk membahas lebih lanjut persoalan yang terjadi dan menemukan ide untuk bisa mengambil kesempatan dalam pemecahan masalah tersebut.',
                'Kami mencetuskan HIMAR untuk diangkat menjadi nama brand, arti HIMAR itu sendiri diambil dari plesetan yaitu KHAMR yang secara tidak langsung mendeskripsikan produk yang relevan dengan permasalahan yang kami temui (minuman keras).',
                'Kami menawarkan gaya baru dalam penyajiannya produk dengan kesan eksklusif menggunakan kemasan botol kaca serta visual yang menampilkan identitas kota garut.',
                'Produk yang akan kami kembangkan yaitu kopi olahan yang beraroma dan memiliki sensasi fermentasi buah-buahan, ditujukan sebagai alternatif untuk memenuhi kebutuhan gaya hidup anak muda, juga bisa menjadi media nostalgia bagi para peminum yang sudah tidak mengonsumsi minuman keras namun ingin tetap merasakan sensasinya.',
                'Dengan adanya terobosan ini, kami harap bisa memberikan impact baik untuk anak muda tanpa merusak generasinya. Menjadi pilihan bagi penikmat kopi yang datang dari mana saja dan membawa HIMAR sebagai buah tangannya. Besar harapan HIMAR dikenal dan punya tempat di hati penikmatnya.',
                'HIMAR, a daily ritual of delight'
            ],
            currentIndex: 0,
            intervalId: null,
            init() {
                this.intervalId = setInterval(() => {
                    this.nextParagraph();
                }, 5000);
            },
            nextParagraph() {
                this.$refs.paragraph.classList.add('opacity-0');
                setTimeout(() => {
                    this.currentIndex = (this.currentIndex + 1) % this.paragraphs.length;
                    this.$refs.paragraph.classList.remove('opacity-0');
                }, 500);
            }
        }));
    });
</script>

</html>