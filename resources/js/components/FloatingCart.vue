<template>
    <Transition
        enter-active-class="transition-all duration-300 ease-out"
        enter-from-class="transform translate-x-full opacity-0"
        enter-to-class="transform translate-x-0 opacity-100"
        leave-active-class="transition-all duration-300 ease-in"
        leave-from-class="transform translate-x-0 opacity-100"
        leave-to-class="transform translate-x-full opacity-0"
    >
        <div v-if="cartItems.length > 0" class="fixed bottom-6 right-6 z-50">
            <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 p-4 max-w-sm">
                <!-- Cart Header -->
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-2">
                        <div class="bg-teal-100 rounded-full p-2">
                            <ShoppingBag class="w-5 h-5 text-teal-600" />
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">My Routine</h3>
                            <p class="text-xs text-gray-500">{{ cartItems.length }} {{ cartItems.length === 1 ? 'item' : 'items' }}</p>
                        </div>
                    </div>
                    <Button @click="toggleCart" variant="ghost" size="sm" class="h-8 w-8 p-0">
                        <ChevronUp v-if="isExpanded" class="w-4 h-4" />
                        <ChevronDown v-else class="w-4 h-4" />
                    </Button>
                </div>

                <!-- Cart Items (Expandable) -->
                <Transition
                    enter-active-class="transition-all duration-300 ease-out"
                    enter-from-class="max-h-0 opacity-0"
                    enter-to-class="max-h-96 opacity-100"
                    leave-active-class="transition-all duration-300 ease-in"
                    leave-from-class="max-h-96 opacity-100"
                    leave-to-class="max-h-0 opacity-0"
                >
                    <div v-if="isExpanded" class="overflow-hidden">
                        <div class="space-y-2 mb-4 max-h-48 overflow-y-auto">
                            <div v-for="item in cartItems" :key="item.id" class="flex items-center space-x-3 p-2 bg-gray-50 rounded-lg">
                                <img :src="item.image" :alt="item.name" class="w-10 h-10 object-cover rounded" />
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-800 truncate">{{ item.name }}</p>
                                    <p class="text-xs text-gray-500">${{ item.price }} each</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <!-- Quantity Controls -->
                                    <div class="flex items-center space-x-1 bg-white rounded border">
                                        <Button @click="decreaseQuantity(item.id)" variant="ghost" size="sm" class="h-6 w-6 p-0 hover:bg-gray-100">
                                            <Minus class="w-3 h-3" />
                                        </Button>
                                        <span class="text-sm font-medium px-2 min-w-[20px] text-center">{{ item.quantity }}</span>
                                        <Button @click="increaseQuantity(item.id)" variant="ghost" size="sm" class="h-6 w-6 p-0 hover:bg-gray-100">
                                            <Plus class="w-3 h-3" />
                                        </Button>
                                    </div>
                                    <!-- Remove Button -->
                                    <Button @click="removeFromCart(item.id)" variant="ghost" size="sm" class="h-6 w-6 p-0 text-red-500 hover:text-red-700">
                                        <X class="w-3 h-3" />
                                    </Button>
                                </div>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="border-t pt-3 mb-4">
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-gray-800">Total:</span>
                                <span class="font-bold text-lg text-teal-600">${{ totalPrice.toFixed(2) }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-2">
                            <Button @click="viewCart" class="w-full bg-teal-600 hover:bg-teal-700">
                                <ShoppingCart class="w-4 h-4 mr-2" />
                                Checkout
                            </Button>
                            <Button @click="clearCart" variant="outline" class="w-full text-gray-600">
                                <Trash2 class="w-4 h-4 mr-2" />
                                Clear All
                            </Button>
                        </div>
                    </div>
                </Transition>

                <!-- Collapsed View -->
                <div v-if="!isExpanded" class="flex items-center justify-between">
                    <div class="flex -space-x-2">
                        <img v-for="(item, index) in cartItems.slice(0, 3)" :key="item.id" 
                             :src="item.image" :alt="item.name"
                             class="w-8 h-8 object-cover rounded-full border-2 border-white"
                             :style="{ zIndex: 10 - index }" />
                        <div v-if="cartItems.length > 3" 
                             class="w-8 h-8 bg-gray-200 rounded-full border-2 border-white flex items-center justify-center">
                            <span class="text-xs font-medium text-gray-600">+{{ cartItems.length - 3 }}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-teal-600">${{ totalPrice.toFixed(2) }}</p>
                        <Button @click="viewCart" size="sm" class="mt-1 bg-teal-600 hover:bg-teal-700">
                            Checkout
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>

<script setup>
import { Button } from '@/components/ui/button';
import { computed, ref } from 'vue';
import { 
    ChevronDown, 
    ChevronUp, 
    Minus,
    Plus,
    ShoppingBag, 
    ShoppingCart,
    Trash2, 
    X 
} from 'lucide-vue-next';

// Props
const props = defineProps({
    items: {
        type: Array,
        default: () => []
    }
});

// Emits
const emit = defineEmits(['remove-item', 'clear-cart', 'view-cart', 'update-quantity']);

// Reactive data
const isExpanded = ref(false);

// Computed
const cartItems = computed(() => props.items);

const totalPrice = computed(() => {
    return cartItems.value.reduce((total, item) => total + (parseFloat(item.price) * item.quantity), 0);
});

// Methods
const toggleCart = () => {
    isExpanded.value = !isExpanded.value;
};

const removeFromCart = (itemId) => {
    emit('remove-item', itemId);
};

const increaseQuantity = (itemId) => {
    emit('update-quantity', { itemId, action: 'increase' });
};

const decreaseQuantity = (itemId) => {
    emit('update-quantity', { itemId, action: 'decrease' });
};

const clearCart = () => {
    emit('clear-cart');
    isExpanded.value = false;
};

const viewCart = () => {
    emit('view-cart');
};
</script>

<style scoped>
.max-h-0 {
    max-height: 0;
}

.max-h-96 {
    max-height: 24rem;
}

.max-h-48 {
    max-height: 12rem;
}
</style>