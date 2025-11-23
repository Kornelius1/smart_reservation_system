1. catatan: gambar pada card menu pake data dari link seperti yang dilihat dibawah:
<img
    :src="
        product.image_url.startsWith('http')
            ? product.image_url
            : `/storage/${product.image_url}`
    "
    alt="Product Image"
    class="rounded-xl h-36 w-full object-cover pointer-events-none"
/>
kalau aplikasi nantinya digunakan, gambar yang dipakai untuk ditampilkan di card pasti gambar yang di upload admin di manajemen menu. jadi kode ini bisa diubah menjadi seperti dibawah agar lebih sederhana: gosah lah
<img
    :src="`/storage/${product.image_url}`"
    alt="Product Image"
    class="rounded-xl h-36 w-full object-cover pointer-events-none"
/>


2. DokuNotificationController.php -> mengambil notifikasi sukses doku, dan otomatis mengirimkan email struk ke email    
                                    customer
3. PaymentReceiptMail.php -> mencetak struk pdf untuk dikirim ke email
4. PaymentController.php -> mencetak struk pdf untuk langsung di download dari halaman
