<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan - Helpdesk IT PDAM</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 5px 0 0; font-size: 12px; }
        .title { text-align: center; font-size: 16px; font-weight: bold; margin-bottom: 20px; text-decoration: underline;}
        table { w-full; border-collapse: collapse; margin-bottom: 20px; width: 100%; }
        table, th, td { border: 1px solid #333; }
        th { background-color: #f2f2f2; padding: 8px; text-align: left; }
        td { padding: 8px; }
        .footer { text-align: right; margin-top: 40px; }
        .signature-line { margin-top: 60px; border-bottom: 1px solid #000; width: 200px; display: inline-block; }
        /* Trik agar tombol print disembunyikan saat kertas dicetak */
        @media print { .no-print { display: none; } body { padding: 0; } }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #2563eb; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">🖨️ Cetak ke Printer / Simpan PDF</button>
    </div>

    <div class="header">
        <h1>Sistem Helpdesk IT - PDAM Tirta Mulia</h1>
        <p>Jl. Contoh Perusahaan No. 123, Kota Anda | Telp: (021) 12345678</p>
    </div>

    <div class="title">
        {{ $title }}
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">No Tiket</th>
                <th width="15%">Tanggal</th>
                <th width="20%">Pelapor</th>
                <th width="25%">Kategori & Kendala</th>
                <th width="20%">Status Akhir</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tickets as $index => $ticket)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $ticket->ticket_number }}</td>
                <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $ticket->customer->name }}</td>
                <td>
                    <strong>{{ $ticket->category->name }}</strong><br>
                    {{ $ticket->title }}
                </td>
                <td style="font-weight: bold;">{{ $ticket->status->name }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 20px;">Tidak ada data laporan pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Mengetahui,</p>
        <p><strong>Admin IT Helpdesk</strong></p>
        <br><br><br>
        <div class="signature-line"></div>
        <p>{{ Auth::user()->name }}</p>
    </div>

    <script>
        // Otomatis buka jendela print saat halaman dimuat
        window.onload = function() { window.print(); }
    </script>
</body>
</html>