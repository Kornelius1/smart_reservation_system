<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>


</head>

<body>
    <div class="min-h-screen bg-[#F8F4E8] flex items-center justify-center p-4 font-sans">
        <div class="bg-[#788869] text-white p-8 rounded-lg shadow-xl w-full max-w-md">
            <h1 class="text-3xl font-bold text-center mb-6">Konfirmasi Pesanan Anda</h1>

            <div>
                <h2 class="text-lg font-semibold mb-2">Detail Pesanan</h2>
                <div class="space-y-2 text-sm">
                    @foreach ($items as $item)
                    <div class="flex justify-between">
                        <span>{{ $item['name'] }}</span>
                        <span>{{ $item['quantity'] }}x</span>
                        <span>Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="divider before:bg-white/25 after:bg-white/25 my-6"></div>

            <div class="divider before:bg-white/25 after:bg-white/25 my-6"></div>

            <div>
                <h2 class="text-lg font-semibold mb-2">Rincian Pembayaran</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between font-bold text-base mt-2">
                        <span>Total Pembayaran</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <button class="btn bg-[#364132] hover:bg-[#2a3327] border-none text-white w-full">
                    Bayar Sekarang
                </button>
            </div>
        </div>
    </div>
</body>

</html>