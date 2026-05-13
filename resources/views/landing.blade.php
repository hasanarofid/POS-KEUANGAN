<!DOCTYPE html>
<html lang="id" class="scroll-smooth" x-data="{ darkMode: true, isScrolled: false, mobileMenuOpen: false }" @scroll.window="isScrolled = window.scrollY > 50" :class="{ 'light': !darkMode, 'overflow-hidden': mobileMenuOpen }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ArusKas — Ekosistem Finansial & Operasional Bisnis Terpadu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon-default.png') }}">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="noise bg-grid">
    <!-- Mesh Background -->
    <div class="blob blob-orange w-[600px] h-[600px] -top-40 -left-40"></div>
    <div class="blob blob-amber w-[800px] h-[800px] top-0 right-0"></div>

    <!-- Navbar -->
    <nav :class="isScrolled ? 'glass py-3 border-b shadow-lg' : 'py-5 bg-transparent border-transparent'" class="fixed top-0 left-0 right-0 z-[100] transition-all duration-500 px-4 md:px-10">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3 group">
                <div class="w-8 h-8 md:w-10 md:h-10 flex items-center justify-center transition-transform group-hover:scale-110">
                    <img src="{{ asset('images/favicon-default.png') }}" class="w-full h-full object-contain" alt="Logo">
                </div>
                <span class="text-xl md:text-2xl font-extrabold tracking-tighter">ArusKas.</span>
            </div>
            
            <div class="hidden lg:flex items-center gap-10 text-sm font-semibold opacity-60">
                <a href="#fitur" class="hover:text-accent-primary transition-colors">Fitur Utama</a>
                <a href="#solusi" class="hover:text-accent-primary transition-colors">Solusi Bisnis</a>
                <a href="#harga" class="hover:text-accent-primary transition-colors">Paket Layanan</a>
            </div>

            <div class="flex items-center gap-2">
                <button @click="darkMode = !darkMode" class="p-2 rounded-xl border border-border-glass hover:bg-accent-primary/10 transition-colors">
                    <span x-show="darkMode">☀️</span>
                    <span x-show="!darkMode">🌙</span>
                </button>
                
                <div class="flex items-center gap-2">
                    @guest
                        <a href="/admin/login" class="hidden sm:block text-sm font-bold px-2 hover:text-accent-primary transition-colors">Masuk</a>
                        <a href="#register" class="btn-glow text-[10px] md:text-sm px-4 md:px-6 py-2 md:py-3">Coba Gratis</a>
                    @else
                        <a href="/admin" class="btn-glow text-[10px] md:text-sm px-4 md:px-6 py-2 md:py-3">Dashboard</a>
                    @endguest
                </div>

                <!-- Mobile Toggle -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 text-xl ml-1 relative z-[110]">
                    <span x-show="!mobileMenuOpen">☰</span>
                    <span x-show="mobileMenuOpen">✕</span>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu Overlay -->
    <div x-show="mobileMenuOpen" 
         x-cloak 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-x-full"
         x-transition:enter-end="opacity-100 translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-x-0"
         x-transition:leave-end="opacity-0 translate-x-full"
         class="fixed inset-0 bg-background/98 backdrop-blur-2xl z-[105] lg:hidden flex flex-col items-center justify-center p-8 text-center overflow-y-auto">
        
        <!-- Dedicated Close Button for Overlay -->
        <button @click="mobileMenuOpen = false" class="absolute top-8 right-8 p-3 bg-white/5 border border-border-glass rounded-full text-2xl hover:bg-accent-primary/10 transition-all">
            ✕
        </button>

        <div class="flex flex-col gap-6 w-full max-w-sm">
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/favicon-default.png') }}" class="w-16 h-16 object-contain" alt="Logo">
            </div>
            
            <a @click="mobileMenuOpen = false" href="#fitur" class="text-2xl md:text-3xl font-black tracking-tighter hover:text-accent-primary transition-colors">Fitur Utama</a>
            <a @click="mobileMenuOpen = false" href="#harga" class="text-2xl md:text-3xl font-black tracking-tighter hover:text-accent-primary transition-colors">Paket Layanan</a>
            <a @click="mobileMenuOpen = false" href="#register" class="text-2xl md:text-3xl font-black tracking-tighter hover:text-accent-primary transition-colors">Daftar Sekarang</a>
            
            <div class="h-px bg-border-subtle w-full my-4"></div>
            
            @guest
                <a href="/admin/login" class="text-lg font-bold opacity-60">Masuk ke Akun</a>
                <a @click="mobileMenuOpen = false" href="#register" class="btn-glow text-xl py-5 w-full justify-center">Mulai Gratis</a>
            @else
                <a href="/admin" class="btn-glow text-xl py-5 w-full justify-center">Buka Dashboard</a>
            @endguest
        </div>
    </div>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 md:pt-44 md:pb-32 px-4 md:px-6 overflow-hidden text-center lg:text-left">
        <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-12 md:gap-20 items-center">
            <div class="relative z-10">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass text-accent-primary text-[10px] md:text-xs font-bold mb-6 md:mb-8 uppercase tracking-widest border-accent-primary/20 mx-auto lg:mx-0">
                    <span class="w-2 h-2 rounded-full bg-accent-primary anim-pulse"></span>
                    Solusi Ekosistem Bisnis Terpercaya
                </div>

                <h1 class="text-3xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold mb-6 md:mb-8 tracking-tighter leading-[1.15] md:leading-[1.05]">
                    Kendalikan <br class="hidden md:block"> 
                    <span class="gradient-text italic">Arus Kas Bisnis</span> <br class="hidden md:block">
                    Lebih Cerdas.
                </h1>

                <p class="text-sm md:text-lg lg:text-xl text-text-secondary mb-8 md:mb-12 leading-relaxed max-w-xl mx-auto lg:mx-0 px-4 md:px-0">
                    Sederhanakan manajemen keuangan, inventori, dan logistik dalam satu platform terintegrasi. Maksimalkan profitabilitas dengan data real-time.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start px-6 sm:px-0">
                    <a href="#register" class="btn-glow text-sm md:text-lg px-8 md:px-10 py-4 md:py-5">
                        Daftar Gratis Sekarang
                    </a>
                    <a href="#fitur" class="btn-ghost text-sm md:text-lg px-8 md:px-10 py-4 md:py-5">
                        Pelajari Fitur
                    </a>
                </div>

                <div class="mt-10 md:mt-12 flex flex-wrap items-center justify-center lg:justify-start gap-6 md:gap-8 opacity-40 grayscale scale-75 md:scale-100">
                    <span class="text-[10px] md:text-sm font-bold uppercase tracking-widest italic">Dipercaya oleh</span>
                    <div class="flex gap-4 md:gap-6 font-black text-lg md:text-xl italic">
                        <span>CORP.X</span>
                        <span>FINANCE.ID</span>
                        <span>UMKM.PLUS</span>
                    </div>
                </div>
            </div>

            <div class="relative mt-8 lg:mt-0">
                <div class="blob blob-orange w-[250px] h-[250px] md:w-[400px] md:h-[400px] top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 opacity-20"></div>
                
                <div class="mockup relative z-10 p-4 aspect-[4/3] flex flex-col gap-3 md:gap-4 group anim-float scale-90 md:scale-100">
                    <div class="flex items-center gap-2 border-b border-white/5 pb-2 md:pb-3">
                        <div class="flex gap-1.5">
                            <div class="w-2 h-2 md:w-3 md:h-3 rounded-full bg-red-500/50"></div>
                            <div class="w-2 h-2 md:w-3 md:h-3 rounded-full bg-amber-500/50"></div>
                            <div class="w-2 h-2 md:w-3 md:h-3 rounded-full bg-emerald-500/50"></div>
                        </div>
                        <div class="h-3 md:h-4 w-20 md:w-32 bg-white/5 rounded-full ml-4"></div>
                    </div>
                    <div class="grid grid-cols-3 gap-2 md:gap-4">
                        <div class="h-16 md:h-24 bg-accent-primary/10 rounded-xl md:rounded-2xl border border-accent-primary/10"></div>
                        <div class="h-16 md:h-24 bg-accent-secondary/10 rounded-xl md:rounded-2xl border border-accent-secondary/10"></div>
                        <div class="h-16 md:h-24 bg-accent-emerald/10 rounded-xl md:rounded-2xl border border-accent-emerald/10"></div>
                    </div>
                    <div class="flex-1 bg-white/5 rounded-xl md:rounded-2xl relative overflow-hidden p-4 md:p-6 border border-white/5">
                        <div class="absolute inset-0 bg-gradient-to-t from-accent-primary/10 to-transparent"></div>
                        <div class="h-full w-full flex items-end gap-2 md:gap-3">
                            <div class="flex-1 bg-accent-primary/40 rounded-t h-[40%]"></div>
                            <div class="flex-1 bg-accent-primary/60 rounded-t h-[75%]"></div>
                            <div class="flex-1 bg-accent-primary/40 rounded-t h-[55%]"></div>
                            <div class="flex-1 bg-accent-secondary/50 rounded-t h-[85%]"></div>
                            <div class="flex-1 bg-accent-primary/40 rounded-t h-[45%]"></div>
                        </div>
                    </div>
                </div>

                <!-- Floating Data Points -->
                <div class="absolute -top-5 -right-5 md:-top-10 md:-right-10 glass p-4 md:p-6 rounded-2xl md:rounded-3xl shadow-2xl anim-float-d z-20 scale-75 md:scale-100">
                    <div class="text-[8px] md:text-[10px] uppercase font-bold tracking-widest text-text-muted mb-1">Pertumbuhan</div>
                    <div class="text-2xl md:text-3xl font-extrabold text-accent-emerald">+124%</div>
                </div>

                <div class="absolute -bottom-5 -left-5 md:-bottom-10 md:-left-10 glass p-3 md:p-5 rounded-xl md:rounded-2xl shadow-2xl anim-float z-30 scale-75 md:scale-100 hidden sm:block">
                    <div class="flex items-center gap-4 text-left">
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-accent-secondary/20 flex items-center justify-center text-accent-secondary">⚡</div>
                        <div>
                            <div class="text-[10px] md:text-xs font-bold">Sinkronisasi Aktif</div>
                            <div class="text-[8px] md:text-[10px] text-text-muted">Data terupdate otomatis</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="py-16 md:py-24 border-y border-border-subtle glass">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-12 text-center">
            <div>
                <div class="text-2xl md:text-4xl font-extrabold mb-1 md:mb-2">500+</div>
                <div class="text-[8px] md:text-sm text-text-muted uppercase font-bold tracking-widest px-2">Bisnis Bergabung</div>
            </div>
            <div>
                <div class="text-2xl md:text-4xl font-extrabold mb-1 md:mb-2">Rp 2.4T</div>
                <div class="text-[8px] md:text-sm text-text-muted uppercase font-bold tracking-widest px-2">Total Transaksi</div>
            </div>
            <div>
                <div class="text-2xl md:text-4xl font-extrabold mb-1 md:mb-2">99.9%</div>
                <div class="text-[8px] md:text-sm text-text-muted uppercase font-bold tracking-widest px-2">Uptime Sistem</div>
            </div>
            <div>
                <div class="text-2xl md:text-4xl font-extrabold mb-1 md:mb-2">24/7</div>
                <div class="text-[8px] md:text-sm text-text-muted uppercase font-bold tracking-widest px-2">Dukungan Ahli</div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section id="fitur" class="py-20 md:py-32 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="mb-12 md:mb-20 max-w-3xl mx-auto text-center reveal">
                <h2 class="text-3xl md:text-6xl font-extrabold mb-6 md:mb-8 leading-tight">Satu Sistem,<br class="md:hidden"> Keunggulan Tanpa Batas.</h2>
                <p class="text-sm md:text-lg text-text-secondary leading-relaxed px-4 md:px-0">ArusKas menghadirkan ekosistem modular untuk efisiensi maksimal bisnis Anda.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-6 md:gap-8">
                <div class="feature-card reveal reveal-d1 p-6 md:p-8">
                    <div class="feature-icon text-xl md:text-2xl">📈</div>
                    <h3 class="text-xl md:text-2xl font-bold mb-3 md:mb-4">Analitik Finansial</h3>
                    <p class="text-xs md:text-base text-text-secondary leading-relaxed">Visualisasi data keuangan yang presisi. Pantau profitabilitas secara instan.</p>
                </div>
                <div class="feature-card reveal reveal-d2 p-6 md:p-8">
                    <div class="feature-icon text-xl md:text-2xl">📦</div>
                    <h3 class="text-xl md:text-2xl font-bold mb-3 md:mb-4">Manajemen Stok</h3>
                    <p class="text-xs md:text-base text-text-secondary leading-relaxed">Pelacakan stok cerdas multi-gudang dengan notifikasi otomatis.</p>
                </div>
                <div class="feature-card reveal reveal-d3 p-6 md:p-8">
                    <div class="feature-icon text-xl md:text-2xl">💳</div>
                    <h3 class="text-xl md:text-2xl font-bold mb-3 md:mb-4">Sistem POS Modern</h3>
                    <p class="text-xs md:text-base text-text-secondary leading-relaxed">Transaksi kasir cepat terintegrasi langsung dengan database inventori.</p>
                </div>
                <div class="md:col-span-2 feature-card reveal reveal-d4 p-6 md:p-8">
                    <div class="flex flex-col md:flex-row gap-6 md:gap-10 items-center text-center md:text-left">
                        <div class="flex-1">
                            <div class="feature-icon text-xl md:text-2xl mx-auto md:mx-0">🚚</div>
                            <h3 class="text-xl md:text-2xl font-bold mb-3 md:mb-4">Logistik & Surat Jalan</h3>
                            <p class="text-xs md:text-base text-text-secondary leading-relaxed">Kelola pengiriman dan armada secara terpadu dalam satu dasbor pusat.</p>
                        </div>
                        <div class="w-full md:w-64 h-24 md:h-40 bg-accent-primary/5 border border-accent-primary/10 rounded-xl md:rounded-2xl flex items-center justify-center font-bold text-accent-primary text-[8px] md:text-xs italic tracking-tighter">
                            [ Modul Logistik Terpadu ]
                        </div>
                    </div>
                </div>
                <div class="feature-card reveal reveal-d5 p-6 md:p-8">
                    <div class="feature-icon text-xl md:text-2xl">🛡️</div>
                    <h3 class="text-xl md:text-2xl font-bold mb-3 md:mb-4">Keamanan Data</h3>
                    <p class="text-xs md:text-base text-text-secondary leading-relaxed">Enkripsi AES-256 dengan backup otomatis harian yang terjamin.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing -->
    <section id="harga" class="py-20 md:py-32 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="mb-12 md:mb-20 max-w-2xl mx-auto text-center reveal">
                <h2 class="text-3xl md:text-4xl font-extrabold mb-4 md:mb-6">Investasi Masa Depan Bisnis.</h2>
                <p class="text-sm md:text-lg text-text-secondary leading-relaxed px-4 md:px-0">Pilih paket yang sesuai. Transparan, tanpa biaya tambahan.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 md:gap-8 items-end px-4 md:px-0">
                <div class="pricing-card reveal p-6 md:p-8">
                    <div class="text-[10px] font-bold uppercase tracking-widest text-text-muted mb-4 md:mb-6">Standard</div>
                    <div class="text-4xl md:text-5xl font-extrabold mb-4 md:mb-6">Gratis</div>
                    <ul class="space-y-3 md:space-y-5 mb-8 md:mb-10 text-xs md:text-sm font-medium">
                        <li class="flex items-center gap-2 md:gap-3"><span class="text-accent-emerald">✓</span> 100 Transaksi / Bulan</li>
                        <li class="flex items-center gap-2 md:gap-3"><span class="text-accent-emerald">✓</span> 1 Gudang & 1 User</li>
                    </ul>
                    <a href="#register" class="btn-ghost w-full justify-center py-3">Mulai Gratis</a>
                </div>
                
                <div class="pricing-card pricing-popular reveal p-8 md:p-10 scale-100 md:scale-105 my-8 md:my-0">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-accent-primary text-white text-[8px] md:text-[10px] font-black uppercase tracking-widest px-4 md:px-5 py-2 rounded-full">Paling Populer</div>
                    <div class="text-[10px] font-bold uppercase tracking-widest text-accent-primary mb-4 md:mb-6">Professional</div>
                    <div class="text-4xl md:text-6xl font-extrabold mb-2 text-accent-primary">Rp 199k</div>
                    <div class="text-[8px] md:text-[10px] font-bold text-text-muted mb-6 md:mb-8 italic">PER BULAN / FLAT RATE</div>
                    <ul class="space-y-4 md:space-y-5 mb-8 md:mb-10 text-xs md:text-sm font-semibold">
                        <li class="flex items-center gap-2 md:gap-3"><span class="text-accent-primary">✓</span> Transaksi Tanpa Batas</li>
                        <li class="flex items-center gap-2 md:gap-3"><span class="text-accent-primary">✓</span> Multi-Gudang & User</li>
                        <li class="flex items-center gap-2 md:gap-3"><span class="text-accent-primary">✓</span> Laporan Detail</li>
                    </ul>
                    <a href="#register" class="btn-glow w-full justify-center text-base md:text-lg py-4">Daftar Sekarang</a>
                </div>

                <div class="pricing-card reveal p-6 md:p-8">
                    <div class="text-[10px] font-bold uppercase tracking-widest text-text-muted mb-4 md:mb-6">Enterprise</div>
                    <div class="text-4xl md:text-5xl font-extrabold mb-4 md:mb-6">Custom</div>
                    <ul class="space-y-3 md:space-y-5 mb-8 md:mb-10 text-xs md:text-sm font-medium">
                        <li class="flex items-center gap-2 md:gap-3"><span class="text-accent-emerald">✓</span> Akses API Dedicated</li>
                        <li class="flex items-center gap-2 md:gap-3"><span class="text-accent-emerald">✓</span> Training On-site</li>
                    </ul>
                    <a href="#" class="btn-ghost w-full justify-center py-3">Hubungi Sales</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Registration Section -->
    <section id="register" class="py-20 md:py-32 px-4 md:px-6 relative overflow-hidden">
        <div class="max-w-4xl mx-auto glass p-8 md:p-16 rounded-[24px] md:rounded-[40px] shadow-2xl relative z-10 text-center">
            <div class="mb-10 md:mb-12">
                <h2 class="text-2xl md:text-5xl font-extrabold mb-4 md:mb-6 tracking-tighter">Mulai Transformasi Sekarang</h2>
                <p class="text-xs md:text-base text-text-secondary max-w-lg mx-auto px-4">Daftar gratis dan nikmati kemudahan kelola Arus Kas Anda dalam satu dasbor terpadu.</p>
            </div>
            
            <form action="/admin/register" method="GET" class="space-y-5 md:space-y-6 text-left max-w-2xl mx-auto px-2">
                <div class="grid md:grid-cols-2 gap-5 md:gap-6">
                    <div class="space-y-2">
                        <label class="text-xs md:text-sm font-bold opacity-60">Nama Lengkap</label>
                        <input type="text" placeholder="Nama Anda" class="w-full bg-white/5 border border-border-glass rounded-xl p-3 md:p-4 text-sm outline-none focus:border-accent-primary transition-colors">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs md:text-sm font-bold opacity-60">Nama Bisnis</label>
                        <input type="text" placeholder="Nama Bisnis" class="w-full bg-white/5 border border-border-glass rounded-xl p-3 md:p-4 text-sm outline-none focus:border-accent-primary transition-colors">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-xs md:text-sm font-bold opacity-60">Email Kerja</label>
                    <input type="email" placeholder="email@bisnis.com" class="w-full bg-white/5 border border-border-glass rounded-xl p-3 md:p-4 text-sm outline-none focus:border-accent-primary transition-colors">
                </div>
                <div class="pt-4">
                    <button type="submit" class="btn-glow w-full justify-center text-base md:text-xl py-4 md:py-5">
                        Buat Akun Gratis
                    </button>
                    <p class="text-center text-[10px] md:text-xs text-text-muted mt-6 italic px-4">
                        Dengan mendaftar, Anda menyetujui Ketentuan & Kebijakan kami.
                    </p>
                </div>
            </form>
        </div>
    </section>

    <!-- FAQ -->
    <section id="faq" class="py-20 md:py-32 px-6 border-t border-border-subtle">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-2xl md:text-3xl font-extrabold mb-10 md:mb-12 reveal tracking-tighter">Pertanyaan Umum.</h2>
            <div class="space-y-2 reveal">
                <div x-data="{ open: false }" class="faq-item">
                    <button @click="open = !open" class="faq-question py-4">
                        <span class="text-xs md:text-lg font-bold">Keamanan data bisnis saya?</span>
                        <span class="text-lg md:text-2xl transition-transform" :class="open ? 'rotate-45 text-accent-primary' : ''">+</span>
                    </button>
                    <div x-show="open" x-collapse class="faq-answer mt-2 text-[10px] md:text-sm text-text-secondary leading-relaxed px-1">
                        Kami mengadopsi standar enkripsi AES-256. Data disimpan dalam cloud terdistribusi dengan redundansi tinggi untuk menjamin ketersediaan 99.9%.
                    </div>
                </div>
                <div x-data="{ open: false }" class="faq-item">
                    <button @click="open = !open" class="faq-question py-4">
                        <span class="text-xs md:text-lg font-bold">Bantuan migrasi data?</span>
                        <span class="text-lg md:text-2xl transition-transform" :class="open ? 'rotate-45 text-accent-primary' : ''">+</span>
                    </button>
                    <div x-show="open" x-collapse class="faq-answer mt-2 text-[10px] md:text-sm text-text-secondary leading-relaxed px-1">
                        Tentu. Kami menyediakan alat impor otomatis untuk Excel/CSV. Tim kami siap membantu proses migrasi agar bisnis tetap berjalan lancar.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-16 md:py-20 px-6 border-t border-border-subtle bg-bg-secondary">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12 mb-16 md:mb-20">
            <div class="md:col-span-2 text-center md:text-left px-4">
                <div class="flex items-center justify-center md:justify-start gap-3 mb-6">
                    <div class="w-8 h-8 flex items-center justify-center">
                        <img src="{{ asset('images/favicon-default.png') }}" class="w-full h-full object-contain" alt="Logo">
                    </div>
                    <span class="text-xl font-extrabold tracking-tighter">ArusKas.</span>
                </div>
                <p class="text-xs md:text-sm text-text-secondary max-w-sm mx-auto md:mx-0 leading-relaxed">Solusi teknologi finansial terpadu untuk efisiensi operasional bisnis modern.</p>
            </div>
            <div class="text-center md:text-left px-4">
                <h4 class="text-xs md:text-sm font-bold mb-6">Navigasi</h4>
                <ul class="space-y-3 text-[10px] md:text-xs text-text-muted">
                    <li><a href="#fitur" class="hover:text-accent-primary transition-colors">Fitur Produk</a></li>
                    <li><a href="#harga" class="hover:text-accent-primary transition-colors">Paket Layanan</a></li>
                    <li><a href="#faq" class="hover:text-accent-primary transition-colors">Bantuan FAQ</a></li>
                </ul>
            </div>
            <div class="text-center md:text-left px-4">
                <h4 class="text-xs md:text-sm font-bold mb-6">Kontak</h4>
                <ul class="space-y-3 text-[10px] md:text-xs text-text-muted">
                    <li>support@aruskas.site</li>
                    <li>+62 (24) 7624-836</li>
                    <li>Semarang, Indonesia</li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto pt-10 border-t border-border-subtle flex flex-col md:flex-row justify-between items-center gap-6 text-[8px] md:text-[10px] font-bold uppercase tracking-[0.2em] text-text-muted text-center px-4">
            <div>&copy; 2026 ArusKas Ecosystem. Developed by Hasan Arofid.</div>
            <div class="flex gap-6 md:gap-8">
                <a href="#" class="hover:text-accent-primary transition-colors">Twitter</a>
                <a href="#" class="hover:text-accent-primary transition-colors">LinkedIn</a>
            </div>
        </div>
    </footer>

    <script>
        // Intersection Observer for reveal animations
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, { threshold: 0.1 });

            document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
        });
    </script>
</body>
</html>
