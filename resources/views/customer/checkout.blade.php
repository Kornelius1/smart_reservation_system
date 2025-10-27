<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses Pembayaran</title>
    
    <script type="text/javascript"
            src="httpsa://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"></script>
    
    @vite('resources/css/app.css')
    
    <style>
        /* Sembunyikan tombol bayar asli, kita akan trigger otomatis */
        #pay-button { display: none; }
    </style>
</head>
<body class="bg-[#F8F4E8] flex items-center justify-center min-h-screen font-sans">

    <div class="text-center p-8">
        <h1 class="text-2xl font-bold text-[#364132] mb-4">Mempersiapkan Pembayaran...</h1>
        <p class="text-gray-600 mb-6">Anda akan segera diarahkan ke halaman pembayaran.</p>
        
        <span class="loading loading-spinner loading-lg text-[#788869]"></span>
        
        <button id="pay-button">Bayar Sekarang</button>
    </div>

    <script type="text/javascript">
      // Ambil snapToken dari controller
      var snapToken = "{{ $snapToken }}";
      
      // Fungsi untuk mentrigger pembayaran
      function startPayment() {
        snap.pay(snapToken, {
          // Callback saat pembayaran SUKSES/SELESAI
          onSuccess: function(result){
            /* Anda akan diarahkan ke halaman 'finish' Midtrans, 
               lalu Midtrans akan redirect ke halaman /sukses kita */
            console.log('Payment Success:', result);
            redirectToStatusPage('success');
          },
          // Callback saat pembayaran PENDING
          onPending: function(result){
            /* User mungkin menutup popup sebelum bayar (misal: milih bayar di Indomaret) */
            console.log('Payment Pending:', result);
            redirectToStatusPage('pending');
          },
          // Callback saat pembayaran ERROR
          onError: function(result){
            /* Terjadi error saat proses pembayaran */
            console.log('Payment Error:', result);
            redirectToStatusPage('error');
          },
          // Callback saat popup DITUTUP oleh user
          onClose: function(){
            /* User menutup popup tanpa menyelesaikan/memilih metode */
            console.log('Popup closed by user');
            // Arahkan kembali ke halaman keranjang/konfirmasi
            window.location.href = "{{ url()->previous() ?? '/' }}";
          }
        });
      }

      // Fungsi helper untuk redirect ke halaman sukses
      function redirectToStatusPage(status) {
        // Kita kirim order_id dan status via query string
        // (Kita perlu $orderId dari controller)
        window.location.href = "{{ route('payment.success') }}?order_id={{ $orderId }}&status=" + status;
      }

      // Langsung panggil fungsi pembayaran saat halaman dimuat
      window.onload = function() {
        startPayment();
      };
    </script>
</body>
</html>