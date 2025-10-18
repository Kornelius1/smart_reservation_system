<script setup>
// Mendefinisikan properti yang diterima dari komponen induk
defineProps({
    product: {
        type: Object,
        required: true,
    },
});

// Mendefinisikan event yang akan dikirim ke komponen induk
const emit = defineEmits(["increase-quantity", "decrease-quantity"]);
</script>

<template>
    <div class="card w-56 shadow-md shadow-[#9CAF88] flex-shrink-0">
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
                    class="btn btn-gradient-card"
                    @click="emit('increase-quantity', product)"
                >
                    Add to Cart
                </button>
                <div v-else class="join">
                    <button
                        class="join-item btn"
                        @click="emit('decrease-quantity', product)"
                    >
                        -
                    </button>
                    <span class="join-item btn btn-disabled px-5">{{
                        product.quantity
                    }}</span>
                    <button
                        class="join-item btn"
                        @click="emit('increase-quantity', product)"
                    >
                        +
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Styling khusus untuk tombol disabled di dalam join */
.join-item.btn-disabled {
    background-color: white !important;
    color: black !important;
    border-color: #d2d6dc !important;
}
</style>
