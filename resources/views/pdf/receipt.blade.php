<!DOCTYPE html>
<html>
<head>
    <title>Struk Pembayaran Digital</title>
    <style>
        body { font-family: sans-serif; color: #333; }
        .receipt-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; line-height: 24px; }
        .header { border-bottom: 2px solid #red; padding-bottom: 20px; margin-bottom: 20px; }
        .title { font-size: 24px; font-weight: bold; color: #d9534f; }
        .table { width: 100%; line-height: inherit; text-align: left; border-collapse: collapse; }
        .table td { padding: 8px; vertical-align: top; }
        .table tr.heading td { background: #f7f7f7; border-bottom: 1px solid #ddd; font-weight: bold; }
        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #777; border-top: 1px solid #eee; padding-top: 20px; }
        .status-badge { background: #5cb85c; color: white; padding: 5px 15px; border-radius: 4px; font-weight: bold; display: inline-block; }
    </style>
</head>
<body>
    <div class="receipt-box">
        <div class="header">
            <table style="width: 100%;">
                <tr>
                    <td>
                        <div class="title">KREDIT MOTOR</div>
                        <div>Struk Pembayaran Digital</div>
                    </td>
                    <td style="text-align: right;">
                        <div class="status-badge">{{ $status }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <table class="table">
            <tr class="heading">
                <td colspan="2">Informasi Transaksi</td>
            </tr>
            <tr>
                <td style="width: 40%;">Jenis Pembayaran</td>
                <td>: {{ $type }}</td>
            </tr>
            <tr>
                <td>Order ID</td>
                <td>: {{ $order_id }}</td>
            </tr>
            <tr>
                <td>Transaction ID</td>
                <td>: {{ $transaction_id }}</td>
            </tr>
            <tr>
                <td>Tanggal Bayar</td>
                <td>: {{ $tanggal }}</td>
            </tr>

            <tr class="heading">
                <td colspan="2">Detail Pelanggan & Unit</td>
            </tr>
            <tr>
                <td>Nama Pelanggan</td>
                <td>: {{ $nama_pelanggan }}</td>
            </tr>
            <tr>
                <td>Unit Motor</td>
                <td>: {{ $motor }}</td>
            </tr>

            <tr class="heading">
                <td colspan="2">Rincian Pembayaran</td>
            </tr>
            <tr>
                <td style="font-size: 18px; font-weight: bold;">TOTAL BAYAR</td>
                <td style="font-size: 18px; font-weight: bold; color: #d9534f;">: Rp {{ number_format($nominal, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="footer">
            <p>Terima kasih telah melakukan pembayaran.</p>
            <p>Struk ini adalah bukti pembayaran sah yang dihasilkan secara otomatis oleh sistem.</p>
        </div>
    </div>
</body>
</html>
