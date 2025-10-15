<script setup>
import { ref, computed, onMounted } from "vue";
import axios from "axios"; // 1. Import axios

const logoUrl = "/images/homey.png";

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
</script>

<template>
    <nav
        class="text-[#738764] bg-gradient-to-r from-[#e5eedb] to-[#414939] border-none text-white"
    >
        <div class="navbar shadow-sm">
            <div class="flex-1">
                <a href="/" tabindex="0" class="btn btn-ghost">
                    <img :src="logoUrl" alt="Homey Logo" class="h-6" />
                </a>
            </div>
            <div class="flex-none">
                <ul class="menu menu-horizontal">
                    <li><a>Home</a></li>
                    <li><a href="">Reservasi</a></li>
                    <li><a href="">Tentang Kami</a></li>
                </ul>
            </div>
        </div>
    </nav>

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

    <!-- form hidden untuk mengirim data pesanan -->
    <!-- <form :action="'/checkout'" method="POST">
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
            v-if="totalItems > 0"
            class="sticky bottom-0 z-50 grid grid-cols-2 items-center gap-4 bg-base-100 p-4 inset-shadow-sm"
        >
            <div class="stat p-0">
                <div class="stat-title">Total Harga</div>
                <div class="stat-value text-[#414939]">{{ totalPrice }}</div>
                <div class="stat-desc">{{ totalItems }} item ditambahkan</div>
            </div>
            <div class="text-right">
                <button type="submit" class="btn bg-gradient-to-r from-[#9CAF88] to-[#414939] border-none text-white">
                    Lanjut ke Pembayaran 🛒
                </button>
            </div>
        </div>
    </form> -->

    <div
        v-if="totalItems > 0"
        class="sticky bottom-0 z-50 grid grid-cols-2 items-center gap-4 bg-base-100 p-4 inset-shadow-sm"
    >
        <div class="stat p-0">
            <div class="stat-title">Total Harga</div>
            <div class="stat-value text-[#414939]">{{ totalPrice }}</div>
            <div class="stat-desc">{{ totalItems }} item ditambahkan</div>
        </div>
        <div class="text-right">
            <button
                class="btn bg-gradient-to-r from-[#9CAF88] to-[#414939] border-none text-white"
            >
                Lanjut ke Pembayaran 🛒
            </button>
        </div>
    </div>

    <footer class="footer bg-neutral text-neutral-content p-10">
        <aside>
            <svg
                width="50"
                height="50"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
                fill-rule="evenodd"
                clip-rule="evenodd"
                class="fill-current"
            >
                <path
                    d="M22.672 15.226l-2.432.811.841 2.515c.33 1.019-.209 2.127-1.23 2.456-1.15.325-2.148-.321-2.463-1.226l-.84-2.518-5.013 1.677.84 2.517c.391 1.203-.434 2.542-1.831 2.542-.88 0-1.601-.564-1.86-1.314l-.842-2.516-2.431.809c-1.135.328-2.145-.317-2.463-1.229-.329-1.018.211-2.127 1.231-2.456l2.432-.809-1.621-4.823-2.432.808c-1.355.384-2.558-.59-2.558-1.839 0-.817.509-1.582 1.327-1.846l2.433-.809-.842-2.515c-.33-1.02.211-2.129 1.232-2.458 1.02-.329 2.13.209 2.461 1.229l.842 2.515 5.011-1.677-.839-2.517c-.403-1.238.484-2.553 1.843-2.553.819 0 1.585.509 1.85 1.326l.841 2.517 2.431-.81c1.02-.33 2.131.211 2.461 1.229.332 1.018-.21 2.126-1.23 2.456l-2.433.809 1.622 4.823 2.433-.809c1.242-.401 2.557.484 2.557 1.838 0 .819-.51 1.583-1.328 1.847m-8.992-6.428l-5.01 1.675 1.619 4.828 5.011-1.674-1.62-4.829z"
                ></path>
            </svg>
            <p>Homey Cafe<br />Menyediakan kenyamanan sejak 2024</p>
        </aside>
        <nav>
            <h6 class="footer-title">Social</h6>
            <div class="grid grid-flow-col gap-4">
                <a
                    ><svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        class="fill-current"
                    >
                        <path
                            d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"
                        ></path></svg
                ></a>
                <a
                    ><svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        class="fill-current"
                    >
                        <path
                            d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"
                        ></path></svg
                ></a>
                <a
                    ><svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        class="fill-current"
                    >
                        <path
                            d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"
                        ></path></svg
                ></a>
            </div>
        </nav>
    </footer>
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
