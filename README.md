# POS-KEUANGAN: Sistem Manajemen Keuangan & Operasional

Sistem POS (Point of Sale) dan pencatatan keuangan terintegrasi yang dirancang untuk efisiensi operasional bisnis. Dibangun menggunakan **Laravel** dan **Filament**, sistem ini menawarkan performa tinggi, keamanan data, dan antarmuka yang sangat intuitif.

## 🚀 Fitur Utama

- **Manajemen Penjualan (Sales)**:
    - Pencatatan invoice real-time.
    - Perhitungan otomatis PPN (11%) dan diskon.
    - Tracking umur piutang (menampilkan durasi tagihan belum dibayar).
    - Cetak Nota/Invoice profesional.
- **Manajemen Pembelian (Purchase)**:
    - Pengadaan barang dari supplier.
    - Update stok otomatis setelah konfirmasi pembelian.
- **Inventori & Stok**:
    - Monitoring stok real-time.
    - Dukungan multi-satuan (PCS, DUS, SET, KG).
    - Laporan mutasi barang dan opname.
- **Manajemen Pengeluaran (Expense)**:
    - Pencatatan biaya operasional kantor dan lainnya.
    - Laporan biaya bulanan.
- **Modul Surat Jalan (Delivery Notes)**:
    - Pembuatan surat jalan otomatis berdasarkan data penjualan.
    - Pilihan metode manual dan custom untuk fleksibilitas pengiriman.
- **Laporan Komprehensif**:
    - Laporan Penjualan, Pembelian, Stok, dan Biaya yang dapat diekspor.
    - Dashboard analitik untuk ringkasan performa bisnis.

## 🛠️ Tech Stack

- **Framework**: [Laravel 11](https://laravel.com)
- **Admin Panel**: [Filament v3](https://filamentphp.com)
- **Database**: MySQL / PostgreSQL
- **UI/UX**: Tailwind CSS

## 💻 Instalasi

1. Clone repositori:
    ```bash
    git clone git@github.com:hasanarofid/POS-KEUANGAN.git
    ```
2. Instal dependensi:
    ```bash
    composer install
    npm install && npm run build
    ```
3. Konfigurasi `.env`:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
4. Jalankan migrasi dan seeder:
    ```bash
    php artisan migrate --seed
    ```
5. Akses dashboard di `/admin`.

---

## 👨‍💻 Pengembang

**Hasan Arofid**

- **Website**: [hasanarofid.site](https://hasanarofid.site)
- **WhatsApp**: [0881-4959-247](http://Wa.me/628814959247)
- **LinkedIn**: [Hasan Arofid](https://linkedin.com/in/hasanarofid)

---

_© 2026 Lux Indonesia Management System. Developed by Hasan Arofid._
