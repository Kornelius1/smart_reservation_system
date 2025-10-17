<script setup>
import { ref, computed, onMounted, watch } from "vue";
import axios from "axios";
import ProductCard from "@/components/ProductCard.vue"; // 1. Impor komponen ProductCard

// --- STATE MANAGEMENT ---
const allProducts = ref([]);
const isLoading = ref(true);
const error = ref(null);
const searchQuery = ref("");
const debouncedSearchQuery = ref("");
let debounceTimer = null;

const csrfToken = ref(
    document.querySelector('meta[name="csrf-token"]')?.getAttribute("content")
);

// --- PENGAMBILAN DATA API ---
onMounted(async () => {
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
// 2. Terapkan debounce pada input pencarian
watch(searchQuery, (newVal) => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        debouncedSearchQuery.value = newVal;
    }, 300); // Filter akan dijalankan 300ms setelah pengguna berhenti mengetik
});

// --- COMPUTED PROPERTIES UNTUK KATEGORI & FILTER ---
// 3. Fungsi filter dibuat generik agar bisa dipakai ulang
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

const filteredCoffees = getFilteredProductsByCategory("coffee");
const filteredSnacks = getFilteredProductsByCategory("snack");
const filteredHeavyMeals = getFilteredProductsByCategory("heavy-meal");

// --- COMPUTED PROPERTIES UNTUK KERANJANG ---
const totalItems = computed(() => {
    return allProducts.value.reduce((total, p) => total + p.quantity, 0);
});

const totalPrice = computed(() => {
    const total = allProducts.value.reduce(
        (total, p) => total + p.price * p.quantity,
        0
    );
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(total);
});

// --- METHODS ---
function increaseQuantity(product) {
    product.quantity++;
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
</script>

<template>
    <main class="text-[#738764] bg-[#ffffff]">
        <div class="md:px-12 py-8 px-4">
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
                <h2 class="text-2xl font-semibold px-4 mb-4">Coffee</h2>
                <div
                    v-if="filteredCoffees.length > 0"
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
                <p v-else class="px-4 text-gray-500">Menu tidak ditemukan.</p>

                <h2 class="text-2xl font-semibold px-4 mt-8 mb-4">Snacks</h2>
                <div
                    v-if="filteredSnacks.length > 0"
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
                <p v-else class="px-4 text-gray-500">Menu tidak ditemukan.</p>

                <h2 class="text-2xl font-semibold px-4 mt-8 mb-4">
                    Heavy Meals
                </h2>
                <div
                    v-if="filteredHeavyMeals.length > 0"
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
                <p v-else class="px-4 text-gray-500">Menu tidak ditemukan.</p>
            </template>
        </div>
    </main>

    <form
        v-if="totalItems > 0"
        action="/bayar"
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
        <div
            class="z-50 grid grid-cols-2 items-center gap-4 bg-base-100 p-4 shadow-[0_-2px_5px_rgba(0,0,0,0.1)]"
        >
            <div class="stat p-0">
                <div class="stat-title">Total Harga</div>
                <div class="stat-value text-[#414939]">{{ totalPrice }}</div>
                <div class="stat-desc">{{ totalItems }} item ditambahkan</div>
            </div>
            <div class="text-right">
                <button
                    type="submit"
                    class="btn bg-gradient-to-r from-[#9CAF88] to-[#414939] border-none text-white"
                >
                    Lanjut ke Pembayaran 🛒
                </button>
            </div>
        </div>
    </form>
</template>

<style scoped>
/* CSS untuk menyembunyikan scrollbar */
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none; /* IE and Edge */
    scrollbar-width: none; /* Firefox */
}
/* Mengubah kursor saat item ditarik */
.cursor-grab.active {
    cursor: grabbing;
    cursor: -webkit-grabbing;
}
</style>
