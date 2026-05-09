<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok - {{ \App\Models\Setting::get('site_name', 'MR LUX INDONESIA') }}</title>
    <style>
        @page { size: A4; margin: 0; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 11px; color: #333; margin: 0; padding: 40px; background: #fff; }
        .report-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 3px solid #d32f2f; padding-bottom: 15px; margin-bottom: 30px; }
        .company-info h1 { margin: 0; font-size: 22px; color: #d32f2f; font-weight: 800; }
        .company-info p { margin: 2px 0; font-size: 10px; color: #666; }
        .report-title { text-align: right; }
        .report-title h2 { margin: 0; font-size: 18px; text-transform: uppercase; color: #222; }
        .report-title p { margin: 5px 0 0 0; font-size: 11px; color: #777; }
        .filter-info { background: #f9f9f9; padding: 10px 15px; border-radius: 4px; border-left: 4px solid #d32f2f; margin-bottom: 20px; }
        .filter-info p { margin: 2px 0; font-weight: 600; }
        .content-table { width: 100%; border-collapse: collapse; }
        .content-table th { background-color: #f5f5f5; padding: 10px 8px; text-align: left; font-weight: 700; text-transform: uppercase; font-size: 9px; border-top: 2px solid #333; border-bottom: 2px solid #333; }
        .content-table td { padding: 8px; border-bottom: 1px solid #eee; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer-total { margin-top: 20px; padding: 15px; background-color: #f5f5f5; border: 1px solid #ddd; text-align: right; border-radius: 4px; }
        .total-label { font-size: 12px; color: #666; margin-right: 15px; }
        .total-value { font-size: 16px; font-weight: 800; color: #d32f2f; }
        .no-print { position: fixed; top: 20px; right: 20px; z-index: 9999; }
        .no-print button { padding: 8px 16px; background: #d32f2f; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 600; }
        @media print { .no-print { display: none; } body { padding: 30px; } }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()">Cetak Laporan</button>
    </div>

    <div class="report-header">
        <div class="company-info">
            <h1>{{ \App\Models\Setting::get('site_name', 'MR LUX INDONESIA') }}</h1>
            <p>{{ \App\Models\Setting::get('site_description', 'Sistem Management Penjualan Profesional') }}</p>
        </div>
        <div class="report-title">
            <h2>Laporan Stok Barang</h2>
            <p>Dicetak: {{ now()->translatedFormat('d F Y H:i') }}</p>
        </div>
    </div>

    @if($category)
    <div class="filter-info">
        <p>Kategori: {{ $category }}</p>
    </div>
    @endif

    <table class="content-table">
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th width="15%">SKU</th>
                <th width="30%">Nama Produk</th>
                <th class="text-center" width="10%">Stok</th>
                <th>Format (DUS + PCS)</th>
                <th class="text-right" width="15%">Harga/PCS</th>
                <th class="text-right" width="15%">Nilai Stok</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $index => $product)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td style="font-weight: 600; color: #d32f2f;">{{ $product->sku }}</td>
                    <td>{{ $product->name }}</td>
                    <td class="text-center">{{ number_format($product->stock, 0, ',', '.') }}</td>
                    <td>
                        @if($product->isi > 0)
                            {{ floor($product->stock / $product->isi) }} DUS
                            @if($product->stock % $product->isi > 0)
                                {{ $product->stock % $product->isi }} PCS
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-right">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="text-right" style="font-weight: 600;">Rp {{ number_format($product->stock * $product->price, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer-total">
        <span class="total-label">TOTAL NILAI ASET STOK:</span>
        <span class="total-value">Rp {{ number_format($totalValue, 0, ',', '.') }}</span>
    </div>
</body>
</html>
