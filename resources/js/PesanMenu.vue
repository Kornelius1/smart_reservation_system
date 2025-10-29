<script setup>
import { ref, computed, onMounted, watch } from "vue";
import axios from "axios";
import ProductCard from "./components/ProductCard.vue"; 

// --- STATE MANAGEMENT ---
const allProducts = ref([]);
const isLoading = ref(true);
const error = ref(null);
const searchQuery = ref("");
const debouncedSearchQuery = ref("");
let debounceTimer = null;

// --- STATE BARU UNTUK RESERVASI ---
const reservationType = ref(null); 
const reservationDetail = ref(null); 
const minimumOrder = ref(0);

const csrfToken = ref(
    document.querySelector('meta[name="csrf-token"]')?.getAttribute("content")
);

// =================================================================
// 💡 OBJEK PEMETAAN GAMBAR LENGKAP (Dioptimalkan)
// =================================================================
const imageMap = {
    // COFFEE & COFFEE BASED
    "vietnam drip": "vietnam_drip_hot.webp",
    "affogato": "affogato.webp",
    "black coffee": "black_coffee_hot_ice.webp", 
    "brown sugar coffee": "brown_sugar_coffee_ice.webp", 
    "brown sugar pandan": "brown_sugar_pandan.webp",
    "butterscotch coffee": "butterscotch_coffee_ice.webp", 
    "caramel latte": "caramel_latte_ice.webp", 
    "kopmil": "kopmil_ice.webp", 
    "original milk coffee": "original_milk_coffee_ice.webp",
    "vanilla latte": "vanilla_latte_ice.webp", 
    "kopi ginseng": "kopi_telur.webp",
    "kopi telur": "kopi_telur.webp",
    "horney coffee": "homey_coffee_ice.webp",

    // HEAVY MEALS
    "nasi telur ceplok": "components/Nasi Telur.webp",
    "nasi telur dadar": "components/Nasi Telur dadar.webp",
    "nasi ayam krispi": "components/Nasi ayam krispi.webp",
    "nasi ayam penyet": "components/ayam_penyet.webp",
    "nasi ayam katsu": "components/Nasi ayam katsu.webp",
    "nasi goreng telur": "components/Nasi goreng.webp",
    "nasi goreng ayam krispi": "components/Nasi goreng ayam crispi.webp",
    "nasi goreng ayam katsu": "components/Nasi goreng ayam katsu.webp",
    "mie goreng reguler": "components/Mie goreng.webp",
    "mie goreng special": "components/mie_goreng_spesial.webp",
    "mie rebus reguler": "components/mie_rebus.webp",
    "mie rebus special": "components/mie_rebus_spesial.webp",
    "mie nyemek": "components/mie_nyemek.webp",
    "mie tiaw goreng": "components/mie_tiaw.webp",
    "mie nas": "components/minas.webp",
    "spaghetti reguler": "components/spaghetti.webp",
    "spaghetti ayam katsu": "components/spaghetti_katsu.webp",

    // SNACKS
    "burger telur": "components/Tolong buatkan gambar burger telur.webp",
    "burger daging": "components/Burger Daging.webp",
    "burger special": "components/Burger Spesial.webp",
    "roti bakar": "components/Roti Bakar.webp.webp",
    "toast cream": "components/Toast Cream.webp",
    "jamur krispi": "components/Jamur Crispi.webp",
    "lumpia baso": "components/Lumpia Bakso.webp",
    "tahu bakso": "components/Tahu Bakso.webp",
    "sosis bakar": "components/Sosis Bakar.webp",
    "nugget ayam": "components/Nugget ayam.webp",
    "ubi goreng": "components/Ubi goreng.webp",
    "french fries": "components/Kentang goreng.webp",
    "pisang krispi": "components/pISANG Goreng.webp",

    // FRESH DRINKS & TEA & TRADITIONAL
    "apple tea": "apple_tea.webp",
    "blackcurrant": "blackcurrant_tea.webp",
    "blue blood": "blue_blood_ice.webp",
    "blue squash": "blue_squash_ice.webp",
    "lemon tea": "lemon_tea.webp",
    "lychee tea": "lychee_tea_ice.webp",
    "orange sky": "orange_sky_ice.webp",
    "orange squash": "orange_squash_ice.webp",
    "peach tea": "peach_tea_ice.webp",
    "soda gembira": "soda_gembira_ice.webp",
    "homey tea": "homey_tea.webp",
    "jasmine tea": "jasmine_tea.webp",
    "teh tarik": "components/teh_tarik.webp",
    "teh telang": "components/teh_telang.webp",
    "teh telang horney": "components/teh_telang.webp", // Added for "Teh Telang Horney (hot)"
    "teh telur": "components/teh_telur.webp",
    "bandrek susu": "components/bandrek_susu.webp",
    "bandrek telor": "components/bandrek_susu.webp", // Added for "Bandrek Telor (hot)"
    "ginger milk": "components/ginger_milk.webp",
    "ginger horney": "components/ginger.webp", // Added for "Ginger Horney (hot)"
    "ginger": "components/ginger.webp",
    "wedang jahe": "components/wedang_jahe.webp",
    "kopi telor": "components/kopi_telur.webp",
    "kopi cingcong": "components/kopi_telur.webp",
    "horney tea": "homey_tea.webp",

    // JUICE (Dioptimalkan, ditambahkan keyword fallback)
    "jus apel": "jus_apel.webp",
    "apel": "jus_apel.webp", // Added for "Apel"
    "jus buah naga": "jus_buah_naga.webp",
    "buah naga": "jus_buah_naga.webp", // Added for "Buah Naga"
    "jus jambu biji": "jus_jambu_biji.webp",
    "jambu biji": "jus_jambu_biji.webp", // Added for "Jambu Biji"
    "jus mangga": "jus_mangga.webp",
    "mangga": "jus_mangga.webp", // Added for "Mangga"
    "jus pokat": "jus_pokat.webp",
    "alpukat": "jus_pokat.webp", // Added for "Alpukat"
    "pokat": "jus_pokat.webp", // Fallback keyword
    "jus sirsak": "jus_sirsak.webp",
    "sirsak": "jus_sirsak.webp", // Fallback keyword

    // FRAAPES & ICE CREAM
    "charcoal": "charcoal_ice.webp",
    "chocolate classic": "chocolate_classic_ice.webp",
    "chocolate coffee": "chocolate_coffee_ice.webp",
    "chocolate frappe": "chocolate_frappe_ice.webp",
    "cookies and cream": "cookies_and_cream_ice.webp",
    "ice cream chocolate": "ice_cream_chocolate.webp",
    "chocolate": "ice_cream_chocolate.webp", // Added for "Chocolate"
    "ice cream mix": "ice_cream_mix.webp",
    "mix": "ice_cream_mix.webp", // Added for "Mix"
    "matcha greentea frappe": "matcha_greentea_frappe_ice.webp",
    "matcha greentea": "matcha_greentea_ice.webp",
    "milo": "milo_ice.webp",
    "red velvet frappe": "red_velvet_frappe_ice.webp",
    "red velvet": "red_velvet_ice.webp",
    "taro frappe": "taro_frappe_ice.webp",
    "taro": "taro_ice.webp",
    "vanilla ice cream": "vanilla_ice_cream.webp",
    "vanilla": "vanilla_ice_cream.webp", // Added for "Vanilla"
    
    // SNACKS - Removed mappings for non-existent images, will use product.image_url
};

// =================================================================
// 💡 FUNGSI PEMETAAN GAMBAR DENGAN DEBUGGING
// =================================================================
function getProductNameBasedImage(productName, defaultImageUrl) {
    const productNameLower = productName.toLowerCase();

    // DEBUG 1: Melihat produk apa yang sedang dicari
    console.log("DEBUG: Looking up product:", productNameLower);

    // Mencari kecocokan kata kunci
    for (const keyword in imageMap) {
        if (productNameLower.includes(keyword)) {
            const filename = imageMap[keyword];
            // DEBUG 2: Melihat keyword apa yang cocok dan nama file-nya
            console.log(`DEBUG: ✅ Matched keyword '${keyword}'. Filename: ${filename}`);
            // Mengembalikan jalur lengkap ke gambar
            return `/images/menu/${filename}`;
        }
    }

    // DEBUG 3: Melihat jika tidak ada yang cocok
    console.log("DEBUG: ❌ NO MATCH found. Using default from product:", defaultImageUrl);
    return defaultImageUrl;
}


// --- PENGAMBILAN DATA API & BACA URL (Perbaikan di fetch logic) ---
onMounted(async () => {
    // ... (Logika reservasi tidak berubah) ...
    const urlParams = new URLSearchParams(window.location.search);
    const room = urlParams.get("room_name");
    const tableNumber = urlParams.get("table_number");
    const minOrder = urlParams.get("min_order");

    if (tableNumber && minOrder) {
        reservationType.value = "meja";
        reservationDetail.value = tableNumber;
        minimumOrder.value = parseInt(minOrder, 10);
    } else if (room && minOrder) {
        reservationType.value = "ruangan";
        reservationDetail.value = room;
        minimumOrder.value = parseInt(minOrder, 10);
    } else {
        alert("Silakan pilih jenis reservasi terlebih dahulu.");
        window.location.href = "/pilih-reservasi"; 
        return;
    }


    //fetch produk
    try {
        const response = await axios.get("/api/products");
        allProducts.value = response.data.map((product) => {
            // Panggil fungsi pemetaan gambar dengan default dari product
            const imageUrl = getProductNameBasedImage(product.name, product.image_url);

            // DEBUG 4: Melihat URL akhir yang ditetapkan ke produk
            console.log(`DEBUG: Final URL for ${product.name}: ${imageUrl}`);

            return {
                ...product,
                quantity: 0,
                // Menggunakan URL yang sudah dipetakan
                image_url: imageUrl,
            };
        });
    } catch (err) {
        console.error("Gagal mengambil data menu:", err);
        error.value = "Tidak dapat memuat menu. Silakan coba lagi nanti.";
    } finally {
        isLoading.value = false;
    }
});

// --- DEBOUNCE HINGGA AKHIR FILE (Tidak Berubah) ---
watch(searchQuery, (newVal) => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        debouncedSearchQuery.value = newVal;
    }, 300);
});

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
const filteredTraditional = getFilteredProductsByCategory("traditional");
const filteredFreshDrinks = getFilteredProductsByCategory("fresh-drink");
const filteredJuice = getFilteredProductsByCategory("juice");
const filteredSpecialTastes = getFilteredProductsByCategory("special-taste");
const filteredIceCreams = getFilteredProductsByCategory("ice-cream");

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