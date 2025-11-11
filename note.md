catatan: gambar pada card menu pake data dari link seperti yang dilihat dibawah:
<img
    :src="
        product.image_url.startsWith('http')
            ? product.image_url
            : `/storage/${product.image_url}`
    "
    alt="Product Image"
    class="rounded-xl h-36 w-full object-cover pointer-events-none"
/>
kalau aplikasi nantinya digunakan, gambar yang dipakai untuk ditampilkan di card pasti gambar yang di upload admin di manajemen menu. jadi kode ini bisa diubah menjadi seperti dibawah agar lebih sederhana:
<img
    :src="`/storage/${product.image_url}`"
    alt="Product Image"
    class="rounded-xl h-36 w-full object-cover pointer-events-none"
/>