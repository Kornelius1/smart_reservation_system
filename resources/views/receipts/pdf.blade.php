<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Struk - {{ $reservation->id_transaksi }}</title>
    <style>
        @page {
            margin: 0px;
        }


        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 8pt;
            margin: 0;
            padding: 5pt;
            width: 95%;
            box-sizing: border-box;
            overflow: hidden;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .divider {
            border-bottom: 1px dashed #000;
            margin: 4pt 0;
        }

        .double-divider {
            border-bottom: 1px double #000;
            margin: 4pt 0;   
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        td {
            vertical-align: top;
            word-wrap: break-word;
            word-break: break-all;
            overflow-wrap: break-word;
        }

        .header h2 {
            font-size: 20pt;
            margin-bottom: 2pt;
        }

        .header p {
            font-size: 8.3pt;
            line-height: 1.1;
            padding-top: 2pt;
        }

        .item-name {
            width: 35%;
        }

        .item-qty {
            width: 10%;
            text-align: center;
        }

        .item-price {
            width: 25%;
            text-align: right;
        }

        .item-total {
            width: 30%;
            text-align: right;
        }

        .footer-note {
            font-size: 7pt;
            margin-top: 4pt;
            line-height: 1.2;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
    </style>
</head>

<body>

    <div class="header center">
        <h2>{{ $app_name }}</h2>
        <p>
            {{ $alamat }}<br>
            {{ $ig }}
        </p>
    </div>

    <div class="double-divider"></div>
    <div class="center bold">STRUK PEMBAYARAN</div>
    <div class="double-divider"></div>

    <table>
        <tr class="info-row">
            <td>No. Invoice</td>
            <td class="right">{{ $reservation->id_transaksi }}</td>
        </tr>
        <tr class="info-row">
            <td>Pelanggan</td>
            <td class="right">{{ $reservation->nama ?? '-' }}</td>
        </tr>
        <tr class="info-row">
            <td>Tanggal</td>
            <td class="right">
                @php
                    $dt = \Carbon\Carbon::parse($reservation->tanggal->toDateString() . ' ' . $reservation->waktu);
                @endphp
                {{ $dt->format('d/m/Y H:i') }}
            </td>
        </tr>
        <tr class="info-row">
            <td>Lokasi</td>
            <td class="right">
                @if($reservation->nomor_meja && $reservation->nomor_meja !== '0')
                    Meja {{ $reservation->nomor_meja }}
                @elseif($reservation->nomor_ruangan && $reservation->nomor_ruangan !== '0')
                    Ruang {{ $reservation->nomor_ruangan }}
                @else
                    Umum
                @endif
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    {{-- 📋 DAFTAR PESANAN --}}
    <table>
        <tbody>
            @foreach($reservation->products as $product)
                @php
                    $qty = $product->pivot->quantity;
                    $price = $product->pivot->price;
                    $subtotal = $qty * $price;
                    $name = substr($product->name, 0, 26);
                @endphp
                <tr>
                    <td class="item-name">{{ $name }}</td>
                    <td class="item-qty">x{{ $qty }}</td>
                    <td class="item-price">{{ number_format($price, 0, ',', '.') }}</td>
                    <td class="item-total">{{ number_format($subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    <table>
        <tr>
            <td>TOTAL</td>
            <td class="right bold">Rp {{ number_format($reservation->total_price, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="double-divider"></div>

    <div class="center footer-note">
        Terima kasih atas kunjungan Anda!<br>
        Simpan struk ini sebagai bukti transaksi.<br>
        {{ now()->format('d/m/Y H:i') }} WIB
    </div>

</body>

</html>