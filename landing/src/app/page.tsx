"use client";

import React, { useState, useEffect } from "react";
import { motion, useScroll, useTransform } from "framer-motion";
import { 
  ArrowRight, 
  BarChart3, 
  CreditCard, 
  LayoutDashboard, 
  ShieldCheck, 
  Zap, 
  Plus, 
  Minus,
  ChevronRight,
  TrendingUp,
  Package,
  Truck,
  Layers
} from "lucide-react";
import { clsx, type ClassValue } from "clsx";
import { twMerge } from "tailwind-merge";

/** Utility for Tailwind classes */
function cn(...inputs: ClassValue[]) {
  return twMerge(clsx(inputs));
}

// --- Components ---

const Navbar = () => {
  const [isScrolled, setIsScrolled] = useState(false);

  useEffect(() => {
    const handleScroll = () => setIsScrolled(window.scrollY > 20);
    window.addEventListener("scroll", handleScroll);
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  return (
    <nav className={cn(
      "fixed top-0 left-0 right-0 z-[100] transition-all duration-300 px-6 py-4",
      isScrolled ? "bg-background/80 backdrop-blur-xl border-b border-white/[0.05]" : "bg-transparent"
    )}>
      <div className="max-w-7xl mx-auto flex items-center justify-between">
        <div className="flex items-center gap-2">
          <div className="w-8 h-8 bg-accent rounded-lg flex items-center justify-center font-black text-white">A</div>
          <span className="text-xl font-black tracking-tighter">ArusKas.</span>
        </div>
        
        <div className="hidden md:flex items-center gap-10 text-sm font-medium opacity-70">
          <a href="#fitur" className="hover:opacity-100 transition-opacity">Fitur</a>
          <a href="#manfaat" className="hover:opacity-100 transition-opacity">Manfaat</a>
          <a href="#harga" className="hover:opacity-100 transition-opacity">Harga</a>
          <a href="#faq" className="hover:opacity-100 transition-opacity">FAQ</a>
        </div>

        <div className="flex items-center gap-4">
          <a href="/admin/login" className="text-sm font-medium px-4 py-2 hover:opacity-70 transition-opacity">Masuk</a>
          <a href="/admin/register" className="bg-foreground text-background text-sm font-bold px-5 py-2.5 rounded-full hover:scale-[1.02] active:scale-[0.98] transition-all">
            Coba Gratis
          </a>
        </div>
      </div>
    </nav>
  );
};

const SectionHeader = ({ title, subtitle, align = "center" }: { title: string, subtitle?: string, align?: "left" | "center" }) => (
  <div className={cn("mb-16 max-w-2xl", align === "center" ? "mx-auto text-center" : "text-left")}>
    <motion.h2 
      initial={{ opacity: 0, y: 20 }}
      whileInView={{ opacity: 1, y: 0 }}
      viewport={{ once: true }}
      className="text-3xl md:text-5xl font-extrabold mb-6"
    >
      {title}
    </motion.h2>
    {subtitle && (
      <motion.p 
        initial={{ opacity: 0, y: 20 }}
        whileInView={{ opacity: 1, y: 0 }}
        viewport={{ once: true }}
        transition={{ delay: 0.1 }}
        className="text-lg opacity-60 leading-relaxed"
      >
        {subtitle}
      </motion.p>
    )}
  </div>
);

const FeatureCard = ({ icon: Icon, title, desc, delay = 0, size = "md" }: { icon: any, title: string, desc: string, delay?: number, size?: "sm" | "md" | "lg" }) => (
  <motion.div
    initial={{ opacity: 0, y: 20 }}
    whileInView={{ opacity: 1, y: 0 }}
    viewport={{ once: true }}
    transition={{ delay }}
    className={cn(
      "p-8 border border-white/[0.05] bg-white/[0.02] asymmetric-border hover:bg-white/[0.04] hover:border-white/[0.1] transition-all group",
      size === "lg" ? "md:col-span-2" : "md:col-span-1"
    )}
  >
    <div className="w-12 h-12 bg-accent/10 rounded-2xl flex items-center justify-center mb-6 text-accent group-hover:scale-110 transition-transform">
      <Icon size={24} />
    </div>
    <h3 className="text-xl font-bold mb-4">{title}</h3>
    <p className="text-sm opacity-60 leading-relaxed">{desc}</p>
  </motion.div>
);

const FAQItem = ({ question, answer }: { question: string, answer: string }) => {
  const [isOpen, setIsOpen] = useState(false);
  return (
    <div className="border-b border-white/[0.05] py-6 last:border-0">
      <button 
        onClick={() => setIsOpen(!isOpen)}
        className="w-full flex items-center justify-between text-left group"
      >
        <span className="text-lg font-bold group-hover:opacity-70 transition-opacity">{question}</span>
        {isOpen ? <Minus size={20} /> : <Plus size={20} />}
      </button>
      <motion.div
        initial={false}
        animate={{ height: isOpen ? "auto" : 0, opacity: isOpen ? 1 : 0 }}
        className="overflow-hidden"
      >
        <p className="pt-4 text-sm opacity-60 leading-relaxed max-w-3xl">{answer}</p>
      </motion.div>
    </div>
  );
};

// --- Page Sections ---

export default function LandingPage() {
  const { scrollYProgress } = useScroll();
  const y = useTransform(scrollYProgress, [0, 1], [0, -100]);

  return (
    <main className="relative overflow-hidden">
      <Navbar />

      {/* Hero Section */}
      <section className="relative pt-40 pb-20 md:pt-60 md:pb-40 px-6 overflow-hidden">
        <div className="bg-blur-gradient absolute -top-40 -left-40 w-[600px] h-[600px] opacity-40" />
        <div className="bg-blur-gradient absolute top-0 right-0 w-[800px] h-[800px] opacity-20" />
        
        <div className="max-w-7xl mx-auto grid lg:grid-cols-2 gap-16 items-center">
          <div className="relative z-10">
            <motion.div
              initial={{ opacity: 0, x: -20 }}
              animate={{ opacity: 1, x: 0 }}
              className="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-accent/20 bg-accent/10 text-accent text-xs font-bold mb-8 uppercase tracking-widest"
            >
              <Zap size={14} />
              <span>Sistem Keuangan Masa Depan</span>
            </motion.div>

            <motion.h1 
              initial={{ opacity: 0, y: 30 }}
              animate={{ opacity: 1, y: 0 }}
              className="text-5xl md:text-7xl lg:text-8xl font-black mb-8 tracking-tight leading-[1.05]"
            >
              Bisnis lebih <br /> 
              <span className="text-accent underline decoration-white/[0.1] decoration-wavy underline-offset-8">terkontrol</span>, <br />
              Profit lebih maksimal.
            </motion.h1>

            <motion.p 
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ delay: 0.1 }}
              className="text-lg md:text-xl opacity-60 max-w-xl mb-12 leading-relaxed"
            >
              Hentikan pencatatan manual yang melelahkan. ArusKas memberikan Anda dashboard finansial real-time untuk memantau setiap rupiah dalam bisnis Anda.
            </motion.p>

            <motion.div 
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ delay: 0.2 }}
              className="flex flex-col sm:flex-row gap-4"
            >
              <a href="/admin/register" className="bg-accent text-white px-8 py-4 rounded-full font-bold text-lg hover:scale-[1.03] active:scale-[0.98] transition-all shadow-[0_20px_50px_rgba(99,102,241,0.3)] flex items-center justify-center gap-3 group">
                Coba Gratis Sekarang
                <ArrowRight size={20} className="group-hover:translate-x-1 transition-transform" />
              </a>
              <a href="#preview" className="px-8 py-4 border border-white/[0.1] rounded-full font-bold text-lg hover:bg-white/[0.05] transition-all flex items-center justify-center gap-3">
                Lihat Demo
              </a>
            </motion.div>
          </div>

          <motion.div 
            initial={{ opacity: 0, scale: 0.95, rotate: 2 }}
            animate={{ opacity: 1, scale: 1, rotate: 0 }}
            transition={{ delay: 0.3, duration: 0.8 }}
            className="relative lg:h-[600px] flex items-center justify-center group"
          >
            <div className="absolute inset-0 bg-accent/20 blur-[100px] rounded-full opacity-30 group-hover:opacity-50 transition-opacity" />
            <div className="relative p-2 bg-white/[0.02] border border-white/[0.05] rounded-[32px] overflow-hidden shadow-2xl asymmetric-border">
              <img 
                src="https://images.unsplash.com/photo-1551288049-bbbda540d3b9?auto=format&fit=crop&q=80&w=2000" 
                alt="ArusKas Dashboard"
                className="rounded-[24px] grayscale group-hover:grayscale-0 transition-all duration-700 w-full object-cover h-[400px] md:h-[500px]"
              />
              <div className="absolute top-8 left-8 bg-background/80 backdrop-blur-md border border-white/[0.1] p-6 rounded-2xl offset-slight shadow-xl">
                <div className="text-[10px] uppercase font-black tracking-widest opacity-40 mb-2">Revenue Bulanan</div>
                <div className="text-2xl font-black">+Rp 420.000.000</div>
                <div className="mt-2 flex items-center gap-1 text-emerald-400 text-xs font-bold">
                  <TrendingUp size={12} />
                  <span>24% dari bulan lalu</span>
                </div>
              </div>
            </div>
          </motion.div>
        </div>
      </section>

      {/* Social Proof */}
      <section className="py-12 border-y border-white/[0.03] bg-grid">
        <div className="max-w-7xl mx-auto px-6 flex flex-wrap justify-center gap-12 md:gap-24 opacity-30 grayscale hover:grayscale-0 transition-all">
          <div className="text-xl font-black italic tracking-tighter">FINANCE.CO</div>
          <div className="text-xl font-black italic tracking-tighter">UMKMHUB</div>
          <div className="text-xl font-black italic tracking-tighter">DISTROCORP</div>
          <div className="text-xl font-black italic tracking-tighter">TECHASIA</div>
          <div className="text-xl font-black italic tracking-tighter">MONEYLY</div>
        </div>
      </section>

      {/* Features Showcase */}
      <section id="fitur" className="py-32 px-6">
        <div className="max-w-7xl mx-auto">
          <SectionHeader 
            title="Satu Platform, Semua Kebutuhan Bisnis." 
            subtitle="Kami tidak hanya mencatat uang masuk. ArusKas dirancang untuk menjadi 'pusat komando' operasional bisnis Anda setiap harinya."
          />

          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <FeatureCard 
              icon={BarChart3} 
              title="Dashboard Analitik" 
              desc="Lihat performa bisnis secara visual. Laba rugi, pengeluaran terbesar, dan proyeksi cashflow dalam hitungan detik."
              delay={0.1}
            />
            <FeatureCard 
              icon={Package} 
              title="Manajemen Inventori" 
              desc="Kelola stok multi-gudang dengan mudah. Notifikasi otomatis saat stok menipis agar penjualan tidak terhambat."
              delay={0.2}
            />
            <FeatureCard 
              icon={CreditCard} 
              title="Point of Sale (POS)" 
              desc="Sistem kasir modern yang terintegrasi langsung dengan database stok dan laporan keuangan bulanan Anda."
              delay={0.3}
            />
            <FeatureCard 
              icon={Truck} 
              title="Surat Jalan & Logistik" 
              desc="Buat surat jalan otomatis dari setiap invoice. Pantau status pengiriman dan penerimaan barang secara real-time."
              delay={0.4}
              size="lg"
            />
            <FeatureCard 
              icon={ShieldCheck} 
              title="Keamanan Data" 
              desc="Data Anda terenkripsi standar bank dengan backup harian. Fokus pada bisnis, serahkan keamanan data pada kami."
              delay={0.5}
            />
          </div>
        </div>
      </section>

      {/* Dashboard Preview Section (Alternating) */}
      <section id="manfaat" className="py-32 bg-white/[0.01]">
        <div className="max-w-7xl mx-auto px-6">
          <div className="grid lg:grid-cols-2 gap-24 items-center mb-40">
            <div>
              <motion.div
                initial={{ opacity: 0, x: -20 }}
                whileInView={{ opacity: 1, x: 0 }}
                viewport={{ once: true }}
                className="w-10 h-10 bg-accent/20 rounded-xl flex items-center justify-center text-accent mb-6"
              >
                <TrendingUp size={20} />
              </motion.div>
              <SectionHeader 
                align="left"
                title="Tingkatkan Profit dengan Data Akurat."
                subtitle="Hilangkan bias dalam pengambilan keputusan. Gunakan laporan margin per produk untuk mengetahui mana yang benar-benar memberikan profit."
              />
              <ul className="space-y-4 mb-8">
                {["Laporan margin produk real-time", "Otomatisasi rekonsiliasi bank", "Tracking umur piutang"].map((item, idx) => (
                  <li key={idx} className="flex items-center gap-3 text-sm opacity-70">
                    <div className="w-1.5 h-1.5 rounded-full bg-accent" />
                    {item}
                  </li>
                ))}
              </ul>
              <a href="#" className="inline-flex items-center gap-2 text-accent font-bold group">
                Pelajari laporan keuangan
                <ChevronRight size={18} className="group-hover:translate-x-1 transition-transform" />
              </a>
            </div>
            <div className="relative">
              <div className="absolute inset-0 bg-accent/10 blur-[80px] rounded-full" />
              <div className="relative border border-white/[0.05] bg-white/[0.02] p-4 rounded-3xl asymmetric-border overflow-hidden">
                <img src="https://images.unsplash.com/photo-1551288049-bbbda540d3b9?auto=format&fit=crop&q=80&w=1200" alt="Finance Tracking" className="rounded-2xl grayscale" />
              </div>
            </div>
          </div>

          <div className="grid lg:grid-cols-2 gap-24 items-center">
            <div className="order-2 lg:order-1 relative">
              <div className="absolute inset-0 bg-violet-500/10 blur-[80px] rounded-full" />
              <div className="relative border border-white/[0.05] bg-white/[0.02] p-4 rounded-3xl asymmetric-border overflow-hidden">
                <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&q=80&w=1200" alt="Inventory Management" className="rounded-2xl grayscale" />
              </div>
            </div>
            <div className="order-1 lg:order-2">
              <motion.div
                initial={{ opacity: 0, x: 20 }}
                whileInView={{ opacity: 1, x: 0 }}
                viewport={{ once: true }}
                className="w-10 h-10 bg-violet-500/20 rounded-xl flex items-center justify-center text-violet-400 mb-6"
              >
                <Package size={20} />
              </motion.div>
              <SectionHeader 
                align="left"
                title="Manajemen Stok Tanpa Drama."
                subtitle="Lupakan hitung manual yang membosankan. ArusKas menyinkronkan stok di setiap titik penjualan dan gudang secara instan."
              />
              <ul className="space-y-4 mb-8">
                {["Sinkronisasi multi-gudang", "History mutasi barang detail", "Import/Export data Excel cepat"].map((item, idx) => (
                  <li key={idx} className="flex items-center gap-3 text-sm opacity-70">
                    <div className="w-1.5 h-1.5 rounded-full bg-violet-400" />
                    {item}
                  </li>
                ))}
              </ul>
            </div>
          </div>
        </div>
      </section>

      {/* Horizontal Showcase */}
      <section className="py-32 overflow-hidden border-y border-white/[0.03]">
        <div className="flex whitespace-nowrap gap-8 animate-scroll">
           {[1,2,3,4,5,6].map(i => (
             <div key={i} className="flex-shrink-0 w-[400px] p-8 border border-white/[0.05] bg-white/[0.01] rounded-3xl">
                <div className="flex items-center gap-4 mb-4">
                  <div className="w-10 h-10 bg-white/[0.05] rounded-full" />
                  <div>
                    <div className="text-sm font-bold">Owner Toko {i}</div>
                    <div className="text-xs opacity-40 italic">"Gak nyangka ngatur keuangan bisa se-asik ini."</div>
                  </div>
                </div>
             </div>
           ))}
        </div>
      </section>

      {/* Pricing Section */}
      <section id="harga" className="py-32 px-6">
        <div className="max-w-7xl mx-auto">
          <SectionHeader 
            title="Investasi yang Berbanding Lurus dengan Pertumbuhan." 
            subtitle="Pilih paket yang sesuai dengan skala bisnis Anda saat ini. Bisa upgrade kapan saja seiring perkembangan bisnis."
          />

          <div className="grid md:grid-cols-3 gap-8 items-end">
            {/* Basic */}
            <div className="p-8 border border-white/[0.05] bg-white/[0.01] rounded-[32px] h-fit">
              <div className="text-sm font-black uppercase tracking-widest opacity-40 mb-4">Starter</div>
              <div className="text-4xl font-black mb-2">Gratis</div>
              <div className="text-xs opacity-40 mb-8 italic">Untuk bisnis perorangan</div>
              <ul className="space-y-4 mb-10 text-sm opacity-60">
                {["Maks 100 transaksi/bulan", "1 User & 1 Gudang", "Laporan Penjualan Dasar"].map((f, i) => (
                  <li key={i} className="flex items-center gap-2">
                    <div className="w-1 h-1 rounded-full bg-white/20" />
                    {f}
                  </li>
                ))}
              </ul>
              <button className="w-full py-3 border border-white/[0.1] rounded-full font-bold hover:bg-white/[0.05] transition-all">Pilih Paket</button>
            </div>

            {/* Pro - Featured */}
            <div className="p-10 border-2 border-accent bg-accent/5 rounded-[40px] relative shadow-[0_30px_100px_rgba(99,102,241,0.2)] md:scale-105 z-10">
              <div className="absolute -top-4 left-1/2 -translate-x-1/2 bg-accent text-white text-[10px] font-black uppercase tracking-widest px-4 py-1.5 rounded-full">Paling Populer</div>
              <div className="text-sm font-black uppercase tracking-widest text-accent mb-4">Business Pro</div>
              <div className="text-5xl font-black mb-2">Rp 199k<span className="text-lg opacity-40 font-medium">/bln</span></div>
              <div className="text-xs opacity-60 mb-8 italic">Ideal untuk UMKM yang sedang berkembang</div>
              <ul className="space-y-4 mb-10 text-sm font-medium">
                {["Transaksi Tanpa Batas", "Multi-User & Multi-Gudang", "Laporan Laba Rugi Detail", "Integrasi Surat Jalan", "Support Prioritas 24/7"].map((f, i) => (
                  <li key={i} className="flex items-center gap-2">
                    <div className="w-1.5 h-1.5 rounded-full bg-accent" />
                    {f}
                  </li>
                ))}
              </ul>
              <button className="w-full py-4 bg-accent text-white rounded-full font-bold shadow-xl hover:scale-[1.02] transition-all">Mulai Langganan</button>
            </div>

            {/* Enterprise */}
            <div className="p-8 border border-white/[0.05] bg-white/[0.01] rounded-[32px] h-fit">
              <div className="text-sm font-black uppercase tracking-widest opacity-40 mb-4">Enterprise</div>
              <div className="text-4xl font-black mb-2">Custom</div>
              <div className="text-xs opacity-40 mb-8 italic">Untuk korporasi & distributor besar</div>
              <ul className="space-y-4 mb-10 text-sm opacity-60">
                {["Semua Fitur Pro", "Custom Dashboard", "Akses API & Integrasi", "Training On-site", "SLA & Account Manager"].map((f, i) => (
                  <li key={i} className="flex items-center gap-2">
                    <div className="w-1 h-1 rounded-full bg-white/20" />
                    {f}
                  </li>
                ))}
              </ul>
              <button className="w-full py-3 border border-white/[0.1] rounded-full font-bold hover:bg-white/[0.05] transition-all">Hubungi Sales</button>
            </div>
          </div>
        </div>
      </section>

      {/* FAQ Section */}
      <section id="faq" className="py-32 px-6 border-t border-white/[0.03]">
        <div className="max-w-4xl mx-auto">
          <SectionHeader align="left" title="Sering Ditanyakan." />
          <div className="space-y-2">
            <FAQItem 
              question="Apakah data saya aman jika berpindah ke ArusKas?" 
              answer="Tentu. Kami menggunakan enkripsi AES-256 dan server berbasis cloud dengan redundansi tinggi. Data Anda aman dan dapat diekspor kapan saja ke format Excel."
            />
            <FAQItem 
              question="Berapa lama proses setup awal aplikasi?" 
              answer="Hanya butuh waktu kurang dari 5 menit. Anda bisa langsung mengimpor data produk dari file Excel yang sudah ada untuk mempercepat proses onboarding."
            />
            <FAQItem 
              question="Apakah ada dukungan untuk sinkronisasi offline?" 
              answer="Saat ini ArusKas berbasis cloud untuk memastikan data stok sinkron real-time antar cabang. Namun, kami sedang mengembangkan mode hybrid untuk masa mendatang."
            />
          </div>
        </div>
      </section>

      {/* Final CTA */}
      <section className="py-32 px-6">
        <motion.div 
          initial={{ opacity: 0, y: 40 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="max-w-6xl mx-auto p-12 md:p-24 bg-accent rounded-[48px] relative overflow-hidden text-center group"
        >
          <div className="absolute top-0 right-0 w-96 h-96 bg-white/20 blur-[120px] rounded-full translate-x-1/2 -translate-y-1/2" />
          <div className="relative z-10">
            <h2 className="text-4xl md:text-7xl font-black text-white mb-8 leading-tight tracking-tighter">
              Siap Membawa Bisnis <br /> Anda ke Level Berikutnya?
            </h2>
            <p className="text-xl text-white/70 max-w-2xl mx-auto mb-12 font-medium leading-relaxed">
              Bergabunglah dengan ribuan pemilik bisnis yang telah berhasil mengoptimalkan operasional dan meningkatkan profit bersama ArusKas.
            </p>
            <div className="flex flex-col sm:flex-row justify-center gap-6">
              <a href="/admin/register" className="bg-white text-accent px-10 py-5 rounded-full font-bold text-xl shadow-2xl hover:scale-105 active:scale-95 transition-all">
                Daftar Sekarang — Gratis
              </a>
              <a href="#" className="bg-transparent text-white/80 border border-white/20 px-10 py-5 rounded-full font-bold text-xl hover:bg-white/10 transition-all">
                Jadwalkan Demo
              </a>
            </div>
          </div>
        </motion.div>
      </section>

      {/* Footer */}
      <footer className="py-20 px-6 border-t border-white/[0.05]">
        <div className="max-w-7xl mx-auto">
          <div className="grid md:grid-cols-4 gap-12 mb-20">
            <div className="col-span-1 md:col-span-2">
              <div className="flex items-center gap-2 mb-6">
                <div className="w-8 h-8 bg-accent rounded-lg flex items-center justify-center font-black text-white text-xs">A</div>
                <span className="text-2xl font-black tracking-tighter">ArusKas.</span>
              </div>
              <p className="text-sm opacity-40 max-w-sm leading-relaxed font-medium">
                Solusi manajemen keuangan dan operasional bisnis terintegrasi yang dirancang untuk membantu UMKM Indonesia bertumbuh lebih cepat dan efisien.
              </p>
            </div>
            <div>
              <h4 className="text-sm font-black uppercase tracking-widest mb-6">Produk</h4>
              <ul className="space-y-4 text-sm opacity-60 font-medium">
                <li><a href="#" className="hover:text-accent transition-colors">Fitur POS</a></li>
                <li><a href="#" className="hover:text-accent transition-colors">Inventori</a></li>
                <li><a href="#" className="hover:text-accent transition-colors">Laporan Keuangan</a></li>
                <li><a href="#" className="hover:text-accent transition-colors">Surat Jalan</a></li>
              </ul>
            </div>
            <div>
              <h4 className="text-sm font-black uppercase tracking-widest mb-6">Perusahaan</h4>
              <ul className="space-y-4 text-sm opacity-60 font-medium">
                <li><a href="#" className="hover:text-accent transition-colors">Tentang Kami</a></li>
                <li><a href="#" className="hover:text-accent transition-colors">Blog</a></li>
                <li><a href="#" className="hover:text-accent transition-colors">Karir</a></li>
                <li><a href="#" className="hover:text-accent transition-colors">Kebijakan Privasi</a></li>
              </ul>
            </div>
          </div>
          <div className="flex flex-col md:flex-row justify-between items-center gap-6 pt-12 border-t border-white/[0.03] opacity-30 text-[10px] font-black uppercase tracking-[0.2em]">
            <div>&copy; 2026 ArusKas by Hasan Arofid. All rights reserved.</div>
            <div className="flex gap-8">
              <a href="#">Twitter</a>
              <a href="#">Instagram</a>
              <a href="#">LinkedIn</a>
            </div>
          </div>
        </div>
      </footer>

      {/* Global CSS for Animations */}
      <style jsx global>{`
        @keyframes scroll {
          0% { transform: translateX(0); }
          100% { transform: translateX(-50%); }
        }
        .animate-scroll {
          animation: scroll 40s linear infinite;
        }
        .animate-scroll:hover {
          animation-play-state: paused;
        }
      `}</style>
    </main>
  );
}
