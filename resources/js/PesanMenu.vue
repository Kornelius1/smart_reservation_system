<script setup>
import { ref, computed, onMounted, watch } from "vue";
import axios from "axios";
import ProductCard from "@/components/ProductCard.vue";

// --- STATE MANAGEMENT ---
const allProducts = ref([]);
// [PERBAIKAN 1] Set default isLoading ke false.
// Kita tidak tahu apakah kita perlu memuat, sampai validasi selesai.
const isLoading = ref(false);
const error = ref(null);
const searchQuery = ref("");
const debouncedSearchQuery = ref("");
let debounceTimer = null;

// --- STATE RESERVASI ---
const reservationType = ref(null);
const reservationDetail = ref(null);
const minimumOrder = ref(0);

const csrfToken = ref(
    document.querySelector('meta[name="csrf-token"]')?.getAttribute("content")
);

// --- PENGAMBILAN DATA API & BACA URL ---
onMounted(async () => {
    const urlParams = new URLSearchParams(window.location.search);
    const room = urlParams.get("room_name");
    const tableNumber = urlParams.get("table_number");
    const minOrder = urlParams.get("min_order");

    // --- LOGIKA VALIDASI ---
    if (tableNumber && minOrder) {
        reservationType.value = "meja";
        reservationDetail.value = tableNumber;
        minimumOrder.value = parseInt(minOrder, 10);
    } else if (room && minOrder) {
        reservationType.value = "ruangan";
        reservationDetail.value = room;
        minimumOrder.value = parseInt(minOrder, 10);
    } else {
        // [PERBAIKAN 2] Ganti alert() dengan SweetAlert

        // Hentikan eksekusi 'onMounted' karena kita akan redirect.
        // Tampilkan pop-up
        await Swal.fire({
            icon: "warning",
            title: "Oops... Anda Belum Memilih",
            text: "Silakan pilih meja atau ruangan terlebih dahulu.",
            confirmButtonText: "Pilih Reservasi",
            allowOutsideClick: false, // Mencegah user menutup pop-up
        });

        // Setelah pengguna mengklik "Pilih Reservasi", alihkan halaman.
        window.location.href = "/pilih-reservasi";
        return; // Hentikan sisa eksekusi 'onMounted'
    }

    // --- FETCH PRODUK ---
    // Jika lolos validasi di atas, BARU kita set isLoading ke true
    // dan mulai mengambil data.
    try {
        // [PERBAIKAN 3] Pindahkan isLoading.value = true ke sini.
        isLoading.value = true;

        const response = await axios.get("/api/products");
        allProducts.value = response.data.map((product) => ({
            ...product,
            quantity: 0,
        }));
    } catch (err) {
        console.error("Gagal mengambil data menu:", err);
        error.value = "Tidak dapat memuat menu. Silakan coba lagi nanti.";
    } finally {
        // Ini sekarang akan BISA dijangkau dan akan menyembunyikan
        // "Memuat menu..." setelah selesai.
        isLoading.value = false;
    }
});

// --- DEBOUNCE UNTUK PENCARIAN ---
watch(searchQuery, (newVal) => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        debouncedSearchQuery.value = newVal;
    }, 300);
});

// --- COMPUTED PROPERTIES UNTUK KATEGORI & FILTER ---
const getFilteredProductsByCategory = (category) => {
    return computed(() => {
        let products = allProducts.value.filter((p) => p.category === category);
        if (!debouncedSearchQuery.value) {
            return products;
        }
        return products.filter((p) =>
            p.name
                .toLowerCase()
                .includes(debouncedSearchQuery.value.toLowerCase())
        );
    });
};

// =================================================================
// --- KATEGORI ANDA (Tidak berubah) ---
const filteredCoffees = getFilteredProductsByCategory("coffee");
const filteredSnacks = getFilteredProductsByCategory("snack");
const filteredHeavyMeals = getFilteredProductsByCategory("heavy-meal");
const filteredTraditional = getFilteredProductsByCategory("traditional");
const filteredFreshDrinks = getFilteredProductsByCategory("fresh-drink");
const filteredJuice = getFilteredProductsByCategory("juice");
const filteredSpecialTastes = getFilteredProductsByCategory("special-taste");
const filteredIceCreams = getFilteredProductsByCategory("ice-cream");
// =================================================================

// --- METHODS (Tidak berubah) ---
function increaseQuantity(product) {
    if (product.quantity < product.stock) {
        product.quantity++;
    }
}

function decreaseQuantity(product) {
    if (product.quantity > 0) {
        product.quantity--;
    }
}

// --- LOGIKA DRAG-TO-SCROLL (Tidak berubah) ---
const isDown = ref(false);
const startX = ref(0);
const scrollLeft = ref(0);

function handleMouseDown(e) {
    const slider = e.currentTarget;
    isDown.value = true;
    slider.classList.add("active");
    startX.value = e.pageX - slider.offsetLeft;
    scrollLeft.value = slider.scrollLeft;
}
function handleMouseLeave(e) {
    isDown.value = false;
    e.currentTarget.classList.remove("active");
}
function handleMouseUp(e) {
    isDown.value = false;
    e.currentTarget.classList.remove("active");
}
function handleMouseMove(e) {
    if (!isDown.value) return;
    e.preventDefault();
    const slider = e.currentTarget;
    const x = e.pageX - slider.offsetLeft;
    const walk = (x - startX.value) * 2;
    slider.scrollLeft = scrollLeft.value - walk;
}

function clearSearch() {
    searchQuery.value = "";
    // Fokus akan otomatis kembali ke input jika perlu,
    // tapi biasanya mengosongkan saja sudah cukup.
}

// --- COMPUTED PROPERTIES UNTUK KERANJANG (Tidak berubah) ---
const totalItems = computed(() => {
    return allProducts.value.reduce((total, p) => total + p.quantity, 0);
});

const totalPriceRaw = computed(() => {
    return allProducts.value.reduce(
        (total, p) => total + p.price * p.quantity,
        0
    );
});

const totalPriceFormatted = computed(() => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(totalPriceRaw.value);
});

const isOrderMinimumMet = computed(() => {
    if (minimumOrder.value === 0) {
        return true;
    }
    return totalPriceRaw.value >= minimumOrder.value;
});
</script>

<template>
    <main class="text-[#738764] bg-[#ffffff]">
        <div class="md:px-12 py-8 px-4">
            <div v-if="reservationType" class="alert alert-info shadow-lg mb-8">
                <div>
                    <span>
                        <template v-if="reservationType === 'meja'">
                            Anda memesan untuk
                            <b>Meja {{ reservationDetail }}</b
                            >.
                        </template>
                        <template v-else-if="reservationType === 'ruangan'">
                            Anda memesan untuk <b>{{ reservationDetail }}</b
                            >.
                        </template>
                        <br />
                        Minimal pemesanan adalah
                        <b>Rp {{ minimumOrder.toLocaleString("id-ID") }}</b
                        >.
                    </span>
                </div>
            </div>

            <label class="input input-bordered flex items-center gap-2">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 16 16"
                    fill="currentColor"
                    class="h-4 w-4 opacity-70"
                >
                    <path
                        fill-rule="evenodd"
                        d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
                        clip-rule="evenodd"
                    />
                </svg>

                <input
                    type="text"
                    class="grow"
                    placeholder="Cari menu favoritmu..."
                    v-model="searchQuery"
                />

                <button
                    v-if="searchQuery"
                    @click="clearSearch"
                    type="button"
                    class="btn-clear"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 16 16"
                        fill="currentColor"
                        class="h-4 w-4"
                    >
                        <path
                            d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14Zm0-1A6 6 0 1 0 8 2a6 6 0 0 0 0 12Z"
                        />
                        <path
                            d="M10.854 4.854a.5.5 0 0 0-.708-.708L8 7.293 5.854 5.146a.5.5 0 1 0-.708.708L7.293 8l-2.147 2.146a.5.5 0 0 0 .708.708L8 8.707l2.146 2.147a.5.5 0 0 0 .708-.708L8.707 8l2.147-2.146Z"
                        />
                    </svg>
                </button>
            </label>

            <h1 class="text-4xl font-bold py-8 text-[#738764]">Daftar Menu</h1>

            <div v-if="isLoading" class="text-center p-8">Memuat menu...</div>
            <div v-if="error" class="text-center text-red-500 p-8">
                {{ error }}
            </div>

            <template v-if="!isLoading && !error">
                <div v-if="filteredCoffees.length > 0">
                    <h2 class="text-2xl font-semibold px-4 mt-8 mb-4">
                        Coffee
                    </h2>
                    <div
                        class="flex overflow-x-auto space-x-4 p-4 rounded-box no-scrollbar cursor-grab select-none"
                        @mousedown="handleMouseDown"
                        @mouseleave="handleMouseLeave"
                        @mouseup="handleMouseUp"
                        @mousemove="handleMouseMove"
                    >
                        <ProductCard
                            v-for="product in filteredCoffees"
                            :key="product.id"
                            :product="product"
                            @increase-quantity="increaseQuantity"
                            @decrease-quantity="decreaseQuantity"
                        />
                    </div>
                </div>

                <div v-if="filteredHeavyMeals.length > 0">
                    <h2 class="text-2xl font-semibold px-4 mt-8 mb-4">
                        Heavy Meals
                    </h2>
                    <div
                        class="flex overflow-x-auto space-x-4 p-4 rounded-box no-scrollbar cursor-grab select-none"
                        @mousedown="handleMouseDown"
                        @mouseleave="handleMouseLeave"
                        @mouseup="handleMouseUp"
                        @mousemove="handleMouseMove"
                    >
                        <ProductCard
                            v-for="product in filteredHeavyMeals"
                            :key="product.id"
                            :product="product"
                            @increase-quantity="increaseQuantity"
                            @decrease-quantity="decreaseQuantity"
                        />
                    </div>
                </div>

                <div v-if="filteredSnacks.length > 0">
                    <h2 class="text-2xl font-semibold px-4 mt-8 mb-4">
                        Snacks
                    </h2>
                    <div
                        class="flex overflow-x-auto space-x-4 p-4 rounded-box no-scrollbar cursor-grab select-none"
                        @mousedown="handleMouseDown"
                        @mouseleave="handleMouseLeave"
                        @mouseup="handleMouseUp"
                        @mousemove="handleMouseMove"
                    >
                        <ProductCard
                            v-for="product in filteredSnacks"
                            :key="product.id"
                            :product="product"
                            @increase-quantity="increaseQuantity"
                            @decrease-quantity="decreaseQuantity"
                        />
                    </div>
                </div>

                <div v-if="filteredSpecialTastes.length > 0">
                    <h2 class="text-2xl font-semibold px-4 mt-8 mb-4">
                        Special Taste
                    </h2>
                    <div
                        class="flex overflow-x-auto space-x-4 p-4 rounded-box no-scrollbar cursor-grab select-none"
                        @mousedown="handleMouseDown"
                        @mouseleave="handleMouseLeave"
                        @mouseup="handleMouseUp"
                        @mousemove="handleMouseMove"
                    >
                        <ProductCard
                            v-for="product in filteredSpecialTastes"
                            :key="product.id"
                            :product="product"
                            @increase-quantity="increaseQuantity"
                            @decrease-quantity="decreaseQuantity"
                        />
                    </div>
                </div>

                <div v-if="filteredFreshDrinks.length > 0">
                    <h2 class="text-2xl font-semibold px-4 mt-8 mb-4">
                        Fresh Drinks
                    </h2>
                    <div
                        class="flex overflow-x-auto space-x-4 p-4 rounded-box no-scrollbar cursor-grab select-none"
                        @mousedown="handleMouseDown"
                        @mouseleave="handleMouseLeave"
                        @mouseup="handleMouseUp"
                        @mousemove="handleMouseMove"
                    >
                        <ProductCard
                            v-for="product in filteredFreshDrinks"
                            :key="product.id"
                            :product="product"
                            @increase-quantity="increaseQuantity"
                            @decrease-quantity="decreaseQuantity"
                        />
                    </div>
                </div>

                <div v-if="filteredJuice.length > 0">
                    <h2 class="text-2xl font-semibold px-4 mt-8 mb-4">Juice</h2>
                    <div
                        class="flex overflow-x-auto space-x-4 p-4 rounded-box no-scrollbar cursor-grab select-none"
                        @mousedown="handleMouseDown"
                        @mouseleave="handleMouseLeave"
                        @mouseup="handleMouseUp"
                        @mousemove="handleMouseMove"
                    >
                        <ProductCard
                            v-for="product in filteredJuice"
                            :key="product.id"
                            :product="product"
                            @increase-quantity="increaseQuantity"
                            @decrease-quantity="decreaseQuantity"
                        />
                    </div>
                </div>

                <div v-if="filteredTraditional.length > 0">
                    <h2 class="text-2xl font-semibold px-4 mt-8 mb-4">
                        Traditional
                    </h2>
                    <div
                        class="flex overflow-x-auto space-x-4 p-4 rounded-box no-scrollbar cursor-grab select-none"
                        @mousedown="handleMouseDown"
                        @mouseleave="handleMouseLeave"
                        @mouseup="handleMouseUp"
                        @mousemove="handleMouseMove"
                    >
                        <ProductCard
                            v-for="product in filteredTraditional"
                            :key="product.id"
                            :product="product"
                            @increase-quantity="increaseQuantity"
                            @decrease-quantity="decreaseQuantity"
                        />
                    </div>
                </div>

                <div v-if="filteredIceCreams.length > 0">
                    <h2 class="text-2xl font-semibold px-4 mt-8 mb-4">
                        Ice Cream
                    </h2>
                    <div
                        class="flex overflow-x-auto space-x-4 p-4 rounded-box no-scrollbar cursor-grab select-none"
                        @mousedown="handleMouseDown"
                        @mouseleave="handleMouseLeave"
                        @mouseup="handleMouseUp"
                        @mousemove="handleMouseMove"
                    >
                        <ProductCard
                            v-for="product in filteredIceCreams"
                            :key="product.id"
                            :product="product"
                            @increase-quantity="increaseQuantity"
                            @decrease-quantity="decreaseQuantity"
                        />
                    </div>
                </div>

                <div
                    v-if="
                        allProducts.length > 0 &&
                        !filteredCoffees.length &&
                        !filteredHeavyMeals.length &&
                        !filteredSnacks.length &&
                        !filteredSpecialTastes.length &&
                        !filteredFreshDrinks.length &&
                        !filteredJuice.length &&
                        !filteredTraditional.length &&
                        !filteredIceCreams.length
                    "
                >
                    <p class="px-4 text-gray-500 text-center">
                        Menu '{{ debouncedSearchQuery }}' tidak ditemukan.
                    </p>
                </div>
            </template>
        </div>
    </main>

    <form
        v-if="totalItems > 0"
        action="/konfirmasi-pesanan"
        method="POST"
        class="sticky bottom-0"
    >
        <input type="hidden" name="_token" :value="csrfToken" />
        <template v-for="product in allProducts" :key="product.id">
            <input
                v-if="product.quantity > 0"
                type="hidden"
                :name="'items[' + product.id + ']'"
                :value="product.quantity"
            />
        </template>

        <input
            v-if="reservationType === 'meja'"
            type="hidden"
            name="reservation_table_number"
            :value="reservationDetail"
        />

        <input
            v-if="reservationType === 'ruangan'"
            type="hidden"
            name="reservation_room_name"
            :value="reservationDetail"
        />

        <div class="z-50 bg-base-100 p-4 shadow-[0_-2px_5px_rgba(0,0,0,0.1)]">
            <p
                v-if="!isOrderMinimumMet"
                class="text-center text-red-600 mb-2 text-sm font-semibold"
            >
                Total belanja belum memenuhi minimal pemesanan (Rp
                {{ minimumOrder.toLocaleString("id-ID") }})
            </p>

            <div class="grid grid-cols-2 items-center gap-4">
                <div class="stat p-0">
                    <div class="stat-title">Total Harga</div>
                    <div class="stat-value text-[#414939]">
                        {{ totalPriceFormatted }}
                    </div>
                    <div class="stat-desc">
                        {{ totalItems }} item ditambahkan
                    </div>
                </div>
                <div class="text-right">
                    <button
                        type="submit"
                        class="btn btn-gradient-card"
                        :disabled="!isOrderMinimumMet"
                        :class="{
                            'opacity-50 cursor-not-allowed': !isOrderMinimumMet,
                        }"
                    >
                        Lanjut ke Pembayaran 🛒
                    </button>
                </div>
            </div>
        </div>
    </form>
</template>

<style scoped>
/* CSS (Tidak berubah) */
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none; /* IE and Edge */
    scrollbar-width: none; /* Firefox */
}
.cursor-grab.active {
    cursor: grabbing;
    cursor: -webkit-grabbing;
}

/* =========================================
  KUSTOMISASI SEARCH BAR (DI SINI TEMPATNYA)
 =========================================
*/

/* Target <label> yang punya kelas 'input' dan 'input-bordered'.
*/
label.input.input-bordered {
    transition: all 0.3s ease-in-out, transform 0.2s ease, box-shadow 0.2s ease;

    border-width: 2px !important;
    border-color: #cfe1b9 !important; 
    box-shadow: none !important;
    background-color: #ffffff !important;
    border-radius: 9999px;
    padding-left: 1.25rem;
    padding-right: 1.25rem;
}

/* --- Efek Hover --- */
label.input.input-bordered:hover {
    border-color: #75a47f !important; /* Hijau tua (brand-primary) */
}

/* --- Efek Focus (saat diklik) --- 
  Gunakan :focus-within pada <label>
*/
label.input.input-bordered:focus-within {
    transform: scale(1.02);
    border-color: #75a47f !important; 
    box-shadow: 0 0 0 3px rgba(117, 164, 127, 0.3) !important;
    outline: none !important;
}

/* --- Ikon SVG di dalam search bar --- */
label.input.input-bordered svg {
    transition: color 0.3s ease-in-out;
    color: #9ca3af; /* Abu-abu netral */
}

/* --- Ikon saat search bar di-klik --- */
label.input.input-bordered:focus-within svg {
    color: #75a47f !important; /* Hijau tua */
}

/* --- Input di dalamnya --- */
label.input.input-bordered input.grow {
    border: none !important;
    outline: none !important;
    box-shadow: none !important;
    background-color: transparent !important; /* Pastikan inputnya transparan */
}

label.input.input-bordered input.grow::placeholder {
    color: #aab8a1; /* Warna abu-hijau yang lebih lembut */
    transition: opacity 0.3s ease;
}

label.input.input-bordered:focus-within input.grow::placeholder {
    opacity: 0.5; /* Redupkan placeholder saat fokus */
}

.btn-clear {
    padding: 0;
    margin: 0;
    background: none;
    border: none;
    cursor: pointer;
    color: #9ca3af; /* Warna abu-abu netral */
    transition: color 0.2s ease, transform 0.2s ease;
}

.btn-clear:hover {
    color: #75A47F; 
    transform: scale(1.2); 
}

</style>
