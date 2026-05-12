<!DOCTYPE html>
<html lang="id" class="scroll-smooth" x-data="{ darkMode: true }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ArusKas — Kelola Keuangan Bisnis Tanpa Ribet</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter+Tight:wght@400;500;600;800;900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon-default.png') }}">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(1deg); }
        }
        @keyframes float-delayed {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(-1deg); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-float-delayed { animation: float-delayed 8s ease-in-out infinite; }
    </style>
</head>
<body x-data="{ isScrolled: false, mobileMenuOpen: false }" @scroll.window="isScrolled = window.scrollY > 20" class="font-sans min-h-screen bg-background text-foreground transition-colors duration-300">
    <div class="bg-blur-gradient absolute -top-40 -left-40 w-[600px] h-[600px] opacity-40"></div>
    <div class="bg-blur-gradient absolute top-0 right-0 w-[800px] h-[800px] opacity-20"></div>

    <!-- Navbar -->
    <nav :class="isScrolled ? 'bg-background/80 backdrop-blur-xl border-b border-border-subtle' : 'bg-transparent'" class="fixed top-0 left-0 right-0 z-[100] transition-all duration-300 px-4 md:px-6 py-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/favicon-default.png') }}" class="w-8 h-8 rounded-lg shadow-lg shadow-accent/20" alt="ArusKas Logo">
                <span class="text-xl font-black tracking-tighter">ArusKas.</span>
            </div>
            
            <div class="hidden lg:flex items-center gap-10 text-sm font-medium opacity-70">
                <a href="#fitur" class="hover:text-accent transition-colors">Fitur</a>
                <a href="#harga" class="hover:text-accent transition-colors">Harga</a>
                <a href="#faq" class="hover:text-accent transition-colors">FAQ</a>
            </div>

            <div class="flex items-center gap-2 md:gap-4">
                <button @click="darkMode = !darkMode" class="p-2 opacity-70 hover:opacity-100 transition-opacity">
                    <span x-show="!darkMode">🌙</span>
                    <span x-show="darkMode">☀️</span>
                </button>
                @guest
                    <a href="/admin/login" class="text-sm font-medium px-2 hover:text-accent transition-colors">Masuk</a>
                    <a href="/admin/register" class="bg-foreground text-background text-sm font-bold px-6 py-3 rounded-full hover:scale-[1.02] active:scale-[0.98] transition-all">
                        Coba Gratis
                    </a>
                @else
                    <a href="/admin" class="bg-accent text-white text-sm font-bold px-6 py-3 rounded-full hover:scale-[1.02] active:scale-[0.98] transition-all">
                        Dashboard
                    </a>
                @endguest
                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 text-2xl">
                    <span x-show="!mobileMenuOpen">☰</span>
                    <span x-show="mobileMenuOpen">✕</span>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Overlay -->
        <div x-show="mobileMenuOpen" x-cloak x-transition class="fixed inset-0 top-[72px] bg-background z-[90] lg:hidden p-6 flex flex-col gap-8 text-center pt-20">
            <a @click="mobileMenuOpen = false" href="#fitur" class="text-2xl font-bold">Fitur</a>
            <a @click="mobileMenuOpen = false" href="#harga" class="text-2xl font-bold">Harga</a>
            <a @click="mobileMenuOpen = false" href="#faq" class="text-2xl font-bold">FAQ</a>
            <hr class="border-border-subtle">
            @guest
                <a href="/admin/login" class="text-xl font-medium">Masuk</a>
                <a href="/admin/register" class="bg-accent text-white py-4 rounded-full text-xl font-bold">Mulai Sekarang</a>
            @else
                <a href="/admin" class="bg-accent text-white py-4 rounded-full text-xl font-bold">Ke Dashboard</a>
            @endguest
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 md:pt-60 md:pb-40 px-6 overflow-hidden">
        <div class="max-w-7xl mx-auto grid lg:grid-cols-[1.1fr_0.9fr] gap-16 lg:gap-20 items-center">
            <div class="relative z-10 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-accent/20 bg-accent-soft text-accent text-xs font-bold mb-8 uppercase tracking-widest">
                    <span>⚡ Sistem Keuangan Masa Depan</span>
                </div>

                <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black mb-8 tracking-tight leading-[1.1]">
                    Bisnis lebih <br> 
                    <span class="text-accent underline decoration-accent/20 decoration-wavy underline-offset-8">terkontrol</span>, <br>
                    Profit lebih maksimal.
                </h1>

                <p class="text-base sm:text-lg opacity-70 mb-12 leading-relaxed max-w-xl mx-auto lg:mx-0">
                    Hentikan pencatatan manual yang melelahkan. ArusKas memberikan Anda dashboard finansial real-time untuk memantau setiap rupiah dalam bisnis Anda.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    @guest
                        <a href="/admin/register" class="bg-accent text-white px-8 py-4 rounded-full font-bold text-lg hover:scale-[1.03] active:scale-[0.98] transition-all shadow-[0_20px_50px_rgba(245,158,11,0.3)] flex items-center justify-center gap-3 group">
                            Coba Gratis Sekarang
                        </a>
                    @else
                        <a href="/admin" class="bg-accent text-white px-8 py-4 rounded-full font-bold text-lg hover:scale-[1.03] active:scale-[0.98] transition-all shadow-[0_20px_50px_rgba(245,158,11,0.3)] flex items-center justify-center gap-3 group">
                            Masuk ke Dashboard
                        </a>
                    @endguest
                    <a href="#fitur" class="px-8 py-4 border border-border-subtle rounded-full font-bold text-lg hover:bg-accent-soft transition-all flex items-center justify-center gap-3">
                        Lihat Fitur
                    </a>
                </div>
            </div>

            <div class="relative w-full aspect-square md:h-[600px] flex items-center justify-center group min-w-0 mt-12 lg:mt-0">
                <!-- Main Glow -->
                <div class="absolute inset-0 bg-accent/10 blur-[100px] rounded-full opacity-30"></div>
                
                <!-- Floating Element 1: Main Image (CSS Mockup) -->
                <div class="relative w-full max-w-[500px] aspect-[4/3] bg-slate-900 border border-white/10 rounded-[32px] overflow-hidden shadow-2xl animate-float p-4 flex flex-col gap-4">
                    <div class="flex items-center gap-2 border-b border-white/5 pb-2">
                        <div class="w-2 h-2 rounded-full bg-red-500"></div>
                        <div class="w-2 h-2 rounded-full bg-amber-500"></div>
                        <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                        <div class="h-2 w-20 bg-white/10 rounded-full ml-4"></div>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <div class="h-20 bg-white/5 rounded-xl"></div>
                        <div class="h-20 bg-white/5 rounded-xl"></div>
                        <div class="h-20 bg-white/5 rounded-xl"></div>
                    </div>
                    <div class="flex-1 bg-white/5 rounded-xl relative overflow-hidden p-4">
                        <div class="absolute inset-0 bg-gradient-to-t from-accent/20 to-transparent"></div>
                        <div class="h-full w-full flex items-end">
                            <div class="w-full h-1/2 flex items-end gap-1">
                                <div class="flex-1 bg-accent/40 rounded-t-sm h-[40%]"></div>
                                <div class="flex-1 bg-accent/40 rounded-t-sm h-[70%]"></div>
                                <div class="flex-1 bg-accent/40 rounded-t-sm h-[50%]"></div>
                                <div class="flex-1 bg-accent/60 rounded-t-sm h-[90%]"></div>
                                <div class="flex-1 bg-accent/40 rounded-t-sm h-[60%]"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Floating Element 2: Stats Card -->
                <div class="absolute top-0 right-0 md:-right-10 bg-background/90 backdrop-blur-xl border border-accent/20 p-4 md:p-6 rounded-2xl shadow-2xl animate-float-delayed z-20 scale-75 md:scale-100">
                    <div class="text-[10px] uppercase font-black tracking-widest opacity-40 mb-2">Revenue</div>
                    <div class="text-xl md:text-2xl font-black text-accent">+420M</div>
                    <div class="mt-1 text-emerald-500 text-[10px] font-bold">📈 +24.8%</div>
                </div>

                <!-- Floating Element 3: User Activity -->
                <div class="absolute bottom-10 -left-5 md:-left-20 bg-background/90 backdrop-blur-xl border border-white/10 p-4 rounded-xl shadow-2xl animate-float z-30 scale-75 md:scale-100 hidden sm:block">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-accent/20 flex items-center justify-center text-accent text-xs font-bold">JD</div>
                        <div>
                            <div class="text-[10px] font-bold">Order Baru</div>
                            <div class="text-[8px] opacity-50">Baru saja • Rp 2.500.000</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Social Proof -->
    <section class="py-12 border-y border-border-subtle bg-grid">
        <div class="max-w-7xl mx-auto px-6 flex flex-wrap justify-center gap-12 md:gap-24 opacity-30 grayscale hover:grayscale-0 transition-all uppercase text-xl font-black italic tracking-tighter">
            <div>FINANCE.CO</div>
            <div>UMKMHUB</div>
            <div>DISTROCORP</div>
            <div>TECHASIA</div>
            <div>MONEYLY</div>
        </div>
    </section>

    <!-- Features -->
    <section id="fitur" class="py-32 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="mb-16 max-w-2xl mx-auto text-center">
                <h2 class="text-3xl md:text-5xl font-extrabold mb-6">Satu Platform, Semua Kebutuhan Bisnis.</h2>
                <p class="text-lg opacity-70 leading-relaxed">Kami tidak hanya mencatat uang masuk. ArusKas dirancang untuk menjadi 'pusat komando' operasional bisnis Anda setiap harinya.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Feature Cards -->
                <div class="p-8 border border-border-subtle bg-white/[0.05] dark:bg-white/[0.02] asymmetric-border hover:bg-accent-soft hover:border-accent/30 transition-all group">
                    <div class="w-12 h-12 bg-accent-soft rounded-2xl flex items-center justify-center mb-6 text-accent font-bold group-hover:scale-110 transition-transform text-2xl">💰</div>
                    <h3 class="text-xl font-bold mb-4">Dashboard Analitik</h3>
                    <p class="text-sm opacity-70 leading-relaxed">Lihat performa bisnis secara visual. Laba rugi, pengeluaran terbesar, dan proyeksi cashflow dalam hitungan detik.</p>
                </div>
                <div class="p-8 border border-border-subtle bg-white/[0.05] dark:bg-white/[0.02] asymmetric-border hover:bg-accent-soft hover:border-accent/30 transition-all group">
                    <div class="w-12 h-12 bg-accent-soft rounded-2xl flex items-center justify-center mb-6 text-accent font-bold group-hover:scale-110 transition-transform text-2xl">📦</div>
                    <h3 class="text-xl font-bold mb-4">Manajemen Inventori</h3>
                    <p class="text-sm opacity-70 leading-relaxed">Kelola stok multi-gudang dengan mudah. Notifikasi otomatis saat stok menipis agar penjualan tidak terhambat.</p>
                </div>
                <div class="p-8 border border-border-subtle bg-white/[0.05] dark:bg-white/[0.02] asymmetric-border hover:bg-accent-soft hover:border-accent/30 transition-all group">
                    <div class="w-12 h-12 bg-accent-soft rounded-2xl flex items-center justify-center mb-6 text-accent font-bold group-hover:scale-110 transition-transform text-2xl">💳</div>
                    <h3 class="text-xl font-bold mb-4">Point of Sale (POS)</h3>
                    <p class="text-sm opacity-70 leading-relaxed">Sistem kasir modern yang terintegrasi langsung dengan database stok dan laporan keuangan bulanan Anda.</p>
                </div>
                <div class="md:col-span-2 p-8 border border-border-subtle bg-white/[0.05] dark:bg-white/[0.02] asymmetric-border hover:bg-accent-soft hover:border-accent/30 transition-all group">
                    <div class="w-12 h-12 bg-accent-soft rounded-2xl flex items-center justify-center mb-6 text-accent font-bold group-hover:scale-110 transition-transform text-2xl">🚚</div>
                    <h3 class="text-xl font-bold mb-4">Surat Jalan & Logistik</h3>
                    <p class="text-sm opacity-70 leading-relaxed">Buat surat jalan otomatis dari setiap invoice. Pantau status pengiriman dan penerimaan barang secara real-time.</p>
                </div>
                <div class="p-8 border border-border-subtle bg-white/[0.05] dark:bg-white/[0.02] asymmetric-border hover:bg-accent-soft hover:border-accent/30 transition-all group">
                    <div class="w-12 h-12 bg-accent-soft rounded-2xl flex items-center justify-center mb-6 text-accent font-bold group-hover:scale-110 transition-transform text-2xl">🛡️</div>
                    <h3 class="text-xl font-bold mb-4">Keamanan Data</h3>
                    <p class="text-sm opacity-70 leading-relaxed">Data Anda terenkripsi standar bank dengan backup harian. Fokus pada bisnis, serahkan keamanan data pada kami.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing -->
    <section id="harga" class="py-32 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="mb-16 max-w-2xl mx-auto text-center">
                <h2 class="text-3xl md:text-5xl font-extrabold mb-6">Investasi yang Berbanding Lurus dengan Pertumbuhan.</h2>
                <p class="text-lg opacity-70 leading-relaxed">Pilih paket yang sesuai dengan skala bisnis Anda saat ini. Bisa upgrade kapan saja.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 items-end">
                <div class="p-8 border border-border-subtle bg-white/[0.05] dark:bg-white/[0.01] rounded-[32px]">
                    <div class="text-sm font-black uppercase tracking-widest opacity-40 mb-4">Starter</div>
                    <div class="text-4xl font-black mb-2">Gratis</div>
                    <ul class="space-y-4 my-8 text-sm opacity-60">
                        <li>• Maks 100 transaksi/bln</li>
                        <li>• 1 User & 1 Gudang</li>
                    </ul>
                    <button class="w-full py-3 border border-border-subtle rounded-full font-bold hover:bg-accent-soft transition-all">Pilih Paket</button>
                </div>
                <div class="p-10 border-2 border-accent bg-accent-soft rounded-[40px] relative shadow-[0_30px_100px_rgba(245,158,11,0.1)] md:scale-105 z-10">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-accent text-white text-[10px] font-black uppercase tracking-widest px-4 py-1.5 rounded-full">Populer</div>
                    <div class="text-sm font-black uppercase tracking-widest text-accent mb-4">Business Pro</div>
                    <div class="text-5xl font-black mb-2 text-accent">Rp 199k<span class="text-lg opacity-40 font-medium text-foreground">/bln</span></div>
                    <ul class="space-y-4 my-8 text-sm font-medium">
                        <li>• Transaksi Tanpa Batas</li>
                        <li>• Multi-User & Multi-Gudang</li>
                        <li>• Laporan Laba Rugi Detail</li>
                    </ul>
                    <button class="w-full py-4 bg-accent text-white rounded-full font-bold shadow-xl hover:scale-[1.02] transition-all">Mulai Langganan</button>
                </div>
                <div class="p-8 border border-border-subtle bg-white/[0.05] dark:bg-white/[0.01] rounded-[32px]">
                    <div class="text-sm font-black uppercase tracking-widest opacity-40 mb-4">Enterprise</div>
                    <div class="text-4xl font-black mb-2">Custom</div>
                    <ul class="space-y-4 my-8 text-sm opacity-60">
                        <li>• Akses API & Integrasi</li>
                        <li>• Training On-site</li>
                    </ul>
                    <button class="w-full py-3 border border-border-subtle rounded-full font-bold hover:bg-accent-soft transition-all">Hubungi Sales</button>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section id="faq" class="py-32 px-6 border-t border-border-subtle">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl font-extrabold mb-12">Sering Ditanyakan.</h2>
            <div class="space-y-2">
                <div x-data="{ open: false }" class="border-b border-border-subtle py-6">
                    <button @click="open = !open" class="w-full flex items-center justify-between text-left group">
                        <span class="text-lg font-bold group-hover:text-accent transition-colors">Apakah data saya aman jika berpindah ke ArusKas?</span>
                        <span x-text="open ? '-' : '+'" class="text-2xl text-accent"></span>
                    </button>
                    <div x-show="open" x-cloak class="pt-4 text-sm opacity-70 leading-relaxed">
                        Tentu. Kami menggunakan enkripsi AES-256 dan server berbasis cloud dengan redundansi tinggi. Data Anda aman dan dapat diekspor kapan saja ke format Excel.
                    </div>
                </div>
                <div x-data="{ open: false }" class="border-b border-border-subtle py-6">
                    <button @click="open = !open" class="w-full flex items-center justify-between text-left group">
                        <span class="text-lg font-bold group-hover:text-accent transition-colors">Berapa lama proses setup awal aplikasi?</span>
                        <span x-text="open ? '-' : '+'" class="text-2xl text-accent"></span>
                    </button>
                    <div x-show="open" x-cloak class="pt-4 text-sm opacity-70 leading-relaxed">
                        Hanya butuh waktu kurang dari 5 menit. Anda bisa langsung mengimpor data produk dari file Excel yang sudah ada untuk mempercepat proses onboarding.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-20 px-6 border-t border-border-subtle">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6 opacity-40 text-[10px] font-black uppercase tracking-[0.2em]">
            <div>&copy; 2026 ArusKas by Hasan Arofid. All rights reserved.</div>
            <div class="flex gap-8">
                <a href="#" class="hover:text-accent transition-colors">Twitter</a>
                <a href="#" class="hover:text-accent transition-colors">Instagram</a>
                <a href="#" class="hover:text-accent transition-colors">LinkedIn</a>
            </div>
        </div>
    </footer>
</body>
</html>
