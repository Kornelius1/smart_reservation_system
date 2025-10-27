<script setup>
import { ref, computed, onMounted, watch } from "vue";
import axios from "axios";
import ProductCard from "@/components/ProductCard.vue";

// --- STATE MANAGEMENT ---
const allProducts = ref([]);
const isLoading = ref(true);
const error = ref(null);
const searchQuery = ref("");
const debouncedSearchQuery = ref("");
let debounceTimer = null;

// --- STATE BARU UNTUK RESERVASI ---
const reservationType = ref(null); // Akan berisi 'meja' atau 'ruangan'
const reservationDetail = ref(null); // Akan berisi nomor meja atau nama ruangan
const minimumOrder = ref(0);

const csrfToken = ref(
    document.querySelector('meta[name="csrf-token"]')?.getAttribute("content")
);

// --- PENGAMBILAN DATA API & BACA URL ---
onMounted(async () => {
    const urlParams = new URLSearchParams(window.location.search);
    const room = urlParams.get("room_name");
    const tableNumber = urlParams.get("table_number");
    const minOrder = urlParams.get("min_order"); // Variabel ini sekarang bisa datang dari meja atau ruangan

    // --- LOGIKA CERDAS BARU DI SINI ---
    if (tableNumber && minOrder) {
        // Ini adalah reservasi MEJA
        reservationType.value = "meja";
        reservationDetail.value = tableNumber;
        minimumOrder.value = parseInt(minOrder, 10);
    } else if (room && minOrder) {
        // Ini adalah reservasi RUANGAN
        reservationType.value = "ruangan";
        reservationDetail.value = room;
        minimumOrder.value = parseInt(minOrder, 10);
    } else {
        // Jika tidak ada parameter reservasi sama sekali, alihkan
        alert("Silakan pilih jenis reservasi terlebih dahulu.");
        window.location.href = "/pilih-reservasi"; // Ganti dengan URL halaman pemilihan utama Anda
        return;
    }

    // roomName.value = room;
    // minimumOrder.value = parseInt(minOrder, 10);

    //fetch produk
    try {
        const response = await axios.get("/api/products");
        allProducts.value = response.data.map((product) => ({
            ...product,
            quantity: 0,
        }));
    } catch (err) {
        console.error("Gagal mengambil data menu:", err);
        error.value = "Tidak dapat memuat menu. Silakan coba lagi nanti.";
    } finally {
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
// --- TAMBAHKAN SEMUA KATEGORI DI SINI ---
const filteredCoffees = getFilteredProductsByCategory("coffee");
const filteredSnacks = getFilteredProductsByCategory("snack");
const filteredHeavyMeals = getFilteredProductsByCategory("heavy-meal");
const filteredTraditional = getFilteredProductsByCategory("traditional");
const filteredFreshDrinks = getFilteredProductsByCategory("fresh-drink");
const filteredJuice = getFilteredProductsByCategory("juice");
const filteredSpecialTastes = getFilteredProductsByCategory("special-taste");
const filteredIceCreams = getFilteredProductsByCategory("ice-cream");
// =================================================================

// --- METHODS ---
function increaseQuantity(product) {
    // ==================================================
    // --- PERBAIKAN DI SINI ---
    // Cek apakah kuantitas saat ini masih di bawah stok
    // Jika stok = 0, (0 < 0) adalah false, jadi tidak akan bertambah.
    // Jika stok = 5 & kuantitas = 5, (5 < 5) adalah false, jadi tidak akan bertambah.
    if (product.quantity < product.stock) {
        product.quantity++;
    } // ==================================================
}

function decreaseQuantity(product) {
    if (product.quantity > 0) {
        product.quantity--;
    }
}

// --- LOGIKA DRAG-TO-SCROLL ---
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

// --- COMPUTED PROPERTIES UNTUK KERANJANG ---
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
                <input
                    type="text"
                    class="grow"
                    placeholder="Cari menu favoritmu..."
                    v-model="searchQuery"
                />
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
</style>
