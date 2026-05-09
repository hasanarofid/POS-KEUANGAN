# Manual Book: Sistem Manajemen Keuangan & Operasional (Lux Indonesia)

Dokumentasi ini disusun sebagai panduan penggunaan dan materi presentasi untuk penawaran sistem manajemen keuangan terintegrasi.

---

## 1. Pendahuluan
Sistem ini adalah solusi Enterprise Resource Planning (ERP) berbasis web yang dirancang khusus untuk mempermudah pencatatan transaksi, pengelolaan stok, dan pelaporan keuangan secara real-time. Dibangun dengan teknologi modern (Laravel & Filament), sistem ini menawarkan antarmuka yang intuitif, cepat, dan aman.

## 2. Fitur Utama
Sistem terbagi menjadi beberapa modul utama:
- **Manajemen Penjualan**: Pencatatan invoice, pengelolaan diskon, dan perhitungan PPN otomatis.
- **Manajemen Pembelian**: Tracking stok masuk dari supplier dan biaya pengadaan.
- **Inventori & Stok**: Monitoring stok real-time, mutasi barang, dan laporan opname.
- **Pengeluaran (Expenses)**: Pencatatan biaya operasional kantor dan lainnya.
- **Surat Jalan (Delivery Notes)**: Pembuatan dokumen pengiriman otomatis yang terintegrasi dengan data penjualan.
- **Laporan Komprehensif**: Laporan penjualan, pembelian, stok, dan biaya dalam format yang mudah dibaca dan dapat diekspor.

---

## 3. Panduan Penggunaan Modul

### 3.1 Dashboard
Halaman utama yang memberikan ringkasan visual mengenai performa bisnis, termasuk total penjualan bulanan, pengeluaran, dan status stok kritis.

### 3.2 Master Data (Produk & Pelanggan)
- **Produk**: Mengelola katalog barang, harga jual, harga beli, dan kategori produk. Mendukung pengaturan SKU dan kategori untuk pengelompokan yang rapi.
- **Pelanggan**: Database pelanggan lengkap dengan informasi penagihan dan pengiriman.

### 3.3 Modul Penjualan (Sales)
- **Buat Pesanan**: Masukkan data pelanggan, pilih produk, dan sistem akan menghitung subtotal secara otomatis.
- **PPN & Diskon**: Mendukung pengaturan PPN (11%) dan diskon (persentase atau nominal) per item atau per transaksi. Sistem secara otomatis mendeteksi status PPN pelanggan saat dipilih.
- **Fleksibilitas Satuan**: Mendukung berbagai jenis satuan seperti PCS, DUS, SET, dan KG dengan konversi harga otomatis.
- **Cetak Invoice**: Fitur cetak nota profesional langsung dari daftar penjualan.
- **Tracking Umur Piutang**: Menampilkan kolom "Umur (hr)" invoice, memudahkan pemantauan lama tagihan belum dibayar sejak tanggal transaksi.

### 3.4 Modul Stok (Inventory)
- **Stok Masuk/Keluar**: Mencatat setiap perubahan stok dengan alasan yang jelas (Penjualan, Pembelian, atau Penyesuaian).
- **Update Stok Otomatis**: Stok akan berkurang otomatis saat terjadi penjualan dan bertambah saat pembelian dikonfirmasi.

### 3.5 Modul Surat Jalan (Delivery Notes)
Tersedia tiga metode pembuatan Surat Jalan:
1. **Otomatis**: Berdasarkan data invoice yang sudah ada untuk efisiensi.
2. **Manual**: Input data pengiriman secara bebas untuk kebutuhan darurat.
3. **Custom**: Kombinasi data sistem dengan catatan tambahan khusus.

### 3.6 Laporan Keuangan (Reports)
- **Laporan Penjualan**: Filter berdasarkan rentang tanggal untuk melihat omzet dan profit.
- **Laporan Biaya**: Memantau pengeluaran operasional secara detail.
- **Laporan Stok**: Menampilkan nilai aset inventori saat ini.

---

## 4. Keamanan & Hak Akses
Sistem dilengkapi dengan Role-Based Access Control (RBAC):
- **Admin**: Akses penuh ke seluruh fitur dan pengaturan sistem.
- **Kasir**: Terfokus pada pembuatan invoice dan transaksi harian.
- **Gudang**: Mengelola stok dan surat jalan pengiriman.

---

## 5. Kontak Pengembang
Untuk informasi lebih lanjut mengenai implementasi, custom fitur, atau demo sistem, silakan hubungi:

**Hasan Arofid**
- **Website**: [hasanarofid.site](https://hasanarofid.site)
- **WhatsApp**: [0881-4959-247](http://Wa.me/628814959247)
- **Email**: hasan@amtechev.com

---
*© 2026 Lux Indonesia Management System. Developed by Hasan Arofid.*
