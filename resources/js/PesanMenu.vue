<script setup>
import { ref, computed, onMounted } from "vue";
import axios from "axios"; // 1. Import axios

// Tambahkan baris ini untuk menyimpan CSRF token
const csrfToken = ref(
    document.querySelector('meta[name="csrf-token"]').getAttribute("content")
);

// --- STATE MANAGEMENT BARU ---
const allProducts = ref([]); // 2. Satu state untuk semua produk
const isLoading = ref(true); // State untuk loading indicator
const error = ref(null); // State untuk pesan error
const searchQuery = ref("");

// --- PENGAMBILAN DATA DARI API ---
// 3. Fungsi untuk mengambil data saat komponen dimuat
onMounted(async () => {
    try {
        const response = await axios.get("/api/products");
        // Tambahkan properti 'quantity' ke setiap produk yang diterima dari API
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

// --- COMPUTED PROPERTIES UNTUK KATEGORI ---
// 4. Pisahkan produk berdasarkan kategori secara dinamis
const coffees = computed(() =>
    allProducts.value.filter((p) => p.category === "coffee")
);
const snacks = computed(() =>
    allProducts.value.filter((p) => p.category === "snack")
);
const heavyMeals = computed(() =>
    allProducts.value.filter((p) => p.category === "heavy-meal")
);

// --- COMPUTED PROPERTIES UNTUK FILTER PENCARIAN ---
// 5. Logika filter tidak perlu diubah, hanya sumber datanya
const filteredCoffees = computed(() => {
    if (!searchQuery.value) return coffees.value;
    return coffees.value.filter((p) =>
        p.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const filteredSnacks = computed(() => {
    if (!searchQuery.value) return snacks.value;
    return snacks.value.filter((p) =>
        p.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const filteredHeavyMeals = computed(() => {
    if (!searchQuery.value) return heavyMeals.value;
    return heavyMeals.value.filter((p) =>
        p.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

// --- COMPUTED PROPERTIES UNTUK KERANJANG (LEBIH SEDERHANA) ---
// 6. Kalkulasi total sekarang lebih rapi
const totalItems = computed(() => {
    return allProducts.value.reduce(
        (total, product) => total + product.quantity,
        0
    );
});

const totalPrice = computed(() => {
    const total = allProducts.value.reduce(
        (total, product) => total + product.price * product.quantity,
        0
    );
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(total);
});

// --- METHODS (TIDAK ADA PERUBAHAN) ---
// Logika ini sudah bagus dan tidak perlu diubah
function increaseQuantity(product) {
    product.quantity++;
}

function decreaseQuantity(product) {
    if (product.quantity > 0) {
        product.quantity--;
    }
}

// --- LOGIKA DRAG-TO-SCROLL (TIDAK ADA PERUBAHAN) ---
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

// // Tambahkan baris ini untuk menyimpan CSRF token
// const csrfToken = ref(
//     document.querySelector('meta[name="csrf-token"]').getAttribute("content")
// );
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

            <h2 class="text-2xl font-semibold px-4 mb-4">Coffee</h2>
            <div
                v-if="filteredCoffees.length > 0"
                class="flex overflow-x-auto space-x-4 p-4 rounded-box no-scrollbar cursor-grab select-none"
                @mousedown="handleMouseDown"
                @mouseleave="handleMouseLeave"
                @mouseup="handleMouseUp"
                @mousemove="handleMouseMove"
            >
                <div
                    v-for="product in filteredCoffees"
                    :key="product.id"
                    class="card w-56 shadow-md shadow-[#9CAF88] flex-shrink-0"
                >
                    <figure class="px-8 pt-8">
                        <img
                            :src="product.image_url"
                            alt="Product Image"
                            class="rounded-xl h-36 w-full object-cover pointer-events-none"
                        />
                    </figure>
                    <div class="card-body items-center text-center">
                        <h2 class="card-title">{{ product.name }}</h2>
                        <p>Rp {{ product.price.toLocaleString("id-ID") }}</p>
                        <div class="card-actions mt-2">
                            <button
                                v-if="product.quantity === 0"
                                class="btn bg-gradient border-none text-white"
                                @click="increaseQuantity(product)"
                            >
                                Add to Cart
                            </button>
                            <div v-else class="join">
                                <button
                                    class="join-item btn"
                                    @click="decreaseQuantity(product)"
                                >
                                    -
                                </button>
                                <span class="join-item btn btn-disabled px-5">{{
                                    product.quantity
                                }}</span>
                                <button
                                    class="join-item btn"
                                    @click="increaseQuantity(product)"
                                >
                                    +
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
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
                <div
                    v-for="product in filteredSnacks"
                    :key="product.id"
                    class="card w-56 shadow-md shadow-[#9CAF88] flex-shrink-0"
                >
                    <figure class="px-8 pt-8">
                        <img
                            :src="product.image_url"
                            alt="Product Image"
                            class="rounded-xl h-36 w-full object-cover pointer-events-none"
                        />
                    </figure>
                    <div class="card-body items-center text-center">
                        <h2 class="card-title">{{ product.name }}</h2>
                        <p>Rp {{ product.price.toLocaleString("id-ID") }}</p>
                        <div class="card-actions mt-2">
                            <button
                                v-if="product.quantity === 0"
                                class="btn bg-gradient-to-r from-[#9CAF88] to-[#414939] border-none text-white"
                                @click="increaseQuantity(product)"
                            >
                                Add to Cart
                            </button>
                            <div v-else class="join">
                                <button
                                    class="join-item btn"
                                    @click="decreaseQuantity(product)"
                                >
                                    -
                                </button>
                                <span class="join-item btn btn-disabled px-5">{{
                                    product.quantity
                                }}</span>
                                <button
                                    class="join-item btn"
                                    @click="increaseQuantity(product)"
                                >
                                    +
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <p v-else class="px-4 text-gray-500">Menu tidak ditemukan.</p>

            <h2 class="text-2xl font-semibold px-4 mt-8 mb-4">Heavy Meals</h2>
            <div
                v-if="filteredHeavyMeals.length > 0"
                class="flex overflow-x-auto space-x-4 p-4 rounded-box no-scrollbar cursor-grab select-none"
                @mousedown="handleMouseDown"
                @mouseleave="handleMouseLeave"
                @mouseup="handleMouseUp"
                @mousemove="handleMouseMove"
            >
                <div
                    v-for="product in filteredHeavyMeals"
                    :key="product.id"
                    class="card w-56 shadow-md shadow-[#9CAF88] flex-shrink-0"
                >
                    <figure class="px-8 pt-8">
                        <img
                            :src="product.imageUrl"
                            alt="Product Image"
                            class="rounded-xl h-36 w-full object-cover pointer-events-none"
                        />
                    </figure>
                    <div class="card-body items-center text-center">
                        <h2 class="card-title">{{ product.name }}</h2>
                        <p>Rp {{ product.price.toLocaleString("id-ID") }}</p>
                        <div class="card-actions mt-2">
                            <button
                                v-if="product.quantity === 0"
                                class="btn bg-gradient-to-r from-[#9CAF88] to-[#414939] border-none text-white"
                                @click="increaseQuantity(product)"
                            >
                                Add to Cart
                            </button>
                            <div v-else class="join">
                                <button
                                    class="join-item btn"
                                    @click="decreaseQuantity(product)"
                                >
                                    -
                                </button>
                                <span class="join-item btn btn-disabled px-5">{{
                                    product.quantity
                                }}</span>
                                <button
                                    class="join-item btn"
                                    @click="increaseQuantity(product)"
                                >
                                    +
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <p v-else class="px-4 text-gray-500">Menu tidak ditemukan.</p>
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
            class="z-50 grid grid-cols-2 items-center gap-4 bg-base-100 p-4 inset-shadow-sm"
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
.join-item.btn-disabled {
    background-color: white !important;
    color: black !important;
    border-color: #d2d6dc !important;
}

/* BARU: CSS untuk menyembunyikan scrollbar */
.no-scrollbar::-webkit-scrollbar {
    display: none;
}

.no-scrollbar {
    -ms-overflow-style: none; /* IE and Edge */
    scrollbar-width: none; /* Firefox */
}

/* BARU: Mengubah kursor saat item ditarik */
.cursor-grab.active {
    cursor: grabbing;
    cursor: -webkit-grabbing;
}
</style>
