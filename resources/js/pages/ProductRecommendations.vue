<template>
    <div class="min-h-screen px-4 py-8">
        <div class="mx-auto max-w-6xl">
            <!-- Loading State -->
            <div v-if="isLoading" class="text-center py-12">
                <div class="w-16 h-16 border-4 border-primary border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
                <p class="text-gray-600">Loading your personalized recommendations...</p>
            </div>

            <!-- Error State -->
            <div v-else-if="error" class="text-center py-12">
                <div class="text-red-500 mb-4">
                    <AlertCircle class="w-16 h-16 mx-auto mb-4" />
                    <h2 class="text-xl font-semibold mb-2">Error Loading Recommendations</h2>
                    <p class="text-gray-600">{{ error }}</p>
                </div>
                <Button @click="goHome" variant="outline">
                    <Home class="mr-2 h-4 w-4" />
                    Go Home
                </Button>
            </div>

            <!-- Recommendations Content -->
            <div v-else-if="submission && recommendations">
                <!-- Header -->
                <div class="mb-8 text-center">
                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-teal-400 to-emerald-500">
                        <Sparkles class="h-8 w-8 text-white" />
                    </div>
                    <h1 class="mb-2 text-3xl font-bold text-gray-800">Your Personalized Product Recommendations</h1>
                    <p class="text-gray-600">Curated skincare products based on your skin analysis and preferences</p>
                </div>

                <!-- User Info Summary -->
                <Card class="mb-8 border-teal-200">
                    <CardContent>
                        <h3 class="font-semibold text-gray-800 mb-2">Personal Information</h3>
                        <p class="text-gray-600"><strong>Name:</strong> {{ submission.name }}</p>
                        <p class="text-gray-600"><strong>Email:</strong> {{ submission.email }}</p>
                        <p class="text-gray-600" v-if="submission.phone"><strong>Phone:</strong> {{ submission.phone }}</p>
                    </CardContent>
                </Card>

                <!-- Product Recommendations Grid -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Recommended Products for Your Skin</h2>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <Card v-for="recommendation in recommendations" :key="recommendation.id" class="border-teal-200 pt-0 pb-2 gap-0 shadow-lg hover:shadow-xl transition-shadow overflow-hidden">
                            <!-- Product Image - No padding -->
                            <div class="relative">
                                <img
                                    :src="recommendation.product.image"
                                    :alt="recommendation.product.name"
                                    class="w-full h-48 object-cover"
                                    @error="handleImageError"
                                />
                                <!-- Concern Badge - Overlay on image -->
                                <div class="absolute bottom-3 left-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-white/90 text-teal-800 backdrop-blur-sm">
                                        <component :is="getConcernIcon(recommendation.product.concern_type)" class="w-3 h-3 mr-1" />
                                        {{ formatConcernType(recommendation.product.concern_type) }}
                                    </span>
                                </div>
                            </div>

                            <CardContent class="p-4">
                                <!-- Product Info -->
                                <div class="mb-4">
                                    <h3 class="font-semibold text-gray-800 mb-2 text-lg">{{ recommendation.product.name }}</h3>
                                    <!-- Description limited to 2 lines -->
                                    <p class="text-sm text-gray-600 mb-3 line-clamp-2 leading-5">{{ recommendation.product.description }}</p>

                                    <!-- Price -->
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-lg font-bold text-gray-800">${{ recommendation.product.price }}</span>
                                            <span v-if="recommendation.product.original_price && recommendation.product.original_price > recommendation.product.price"
                                                  class="text-sm text-gray-500 line-through">${{ recommendation.product.original_price }}</span>
                                        </div>
                                        <div v-if="recommendation.product.rating" class="flex items-center">
                                            <Star class="w-4 h-4 text-yellow-400 fill-current" />
                                            <span class="text-sm text-gray-600 ml-1">{{ recommendation.product.rating }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="space-y-2">
                                    <Button @click="openProductModal(recommendation.product)" class="w-full" variant="outline">
                                        <Info class="w-4 h-4 mr-2" />
                                        Learn More
                                    </Button>
                                    <Button @click="addToRoutine(recommendation.product)" class="w-full bg-teal-600 hover:bg-teal-700">
                                        <Plus class="w-4 h-4 mr-2" />
                                        Add to Routine
                                    </Button>
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="text-center">
                    <div class="flex justify-center space-x-4">
                        <Button @click="goToResults" variant="outline">
                            <ArrowLeft class="mr-2 h-4 w-4" />
                            Back to Results
                        </Button>

                        <Button @click="goHome" variant="outline">
                            <Home class="mr-2 h-4 w-4" />
                            New Scan
                        </Button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Detail Modal -->
        <Dialog v-model:open="isModalOpen">
            <DialogContent class="max-w-2xl max-h-[90vh] overflow-y-auto">
                <DialogHeader>
                    <DialogTitle class="text-xl font-bold text-gray-800">{{ selectedProduct?.name }}</DialogTitle>
                    <DialogDescription class="text-gray-600">
                        Product details and information
                    </DialogDescription>
                </DialogHeader>

                <div v-if="selectedProduct" class="space-y-6">
                    <!-- Product Image -->
                    <div class="w-full">
                        <img
                            :src="selectedProduct.image"
                            :alt="selectedProduct.name"
                            class="w-full h-64 object-cover rounded-lg"
                            @error="handleImageError"
                        />
                    </div>

                    <!-- Product Info -->
                    <div class="space-y-4">
                        <!-- Concern Badge -->
                        <div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-teal-100 text-teal-800">
                                <component :is="getConcernIcon(selectedProduct.concern_type)" class="w-4 h-4 mr-2" />
                                {{ formatConcernType(selectedProduct.concern_type) }}
                            </span>
                        </div>

                        <!-- Description -->
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-2">Description</h4>
                            <p class="text-gray-600 leading-relaxed">{{ selectedProduct.description }}</p>
                        </div>

                        <!-- Benefits -->
                        <div v-if="selectedProduct.benefits && selectedProduct.benefits.length">
                            <h4 class="font-semibold text-gray-800 mb-2">Key Benefits</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                <div v-for="benefit in selectedProduct.benefits" :key="benefit"
                                     class="flex items-center space-x-2">
                                    <CheckCircle class="w-4 h-4 text-teal-600" />
                                    <span class="text-sm text-gray-700">{{ benefit }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Price and Rating -->
                        <div class="flex items-center justify-between py-4 border-t border-gray-200">
                            <div class="flex items-center space-x-3">
                                <span class="text-2xl font-bold text-gray-800">${{ selectedProduct.price }}</span>
                                <span v-if="selectedProduct.original_price && selectedProduct.original_price > selectedProduct.price"
                                      class="text-lg text-gray-500 line-through">${{ selectedProduct.original_price }}</span>
                            </div>
                            <div v-if="selectedProduct.rating" class="flex items-center space-x-1">
                                <Star class="w-5 h-5 text-yellow-400 fill-current" />
                                <span class="text-lg font-medium text-gray-700">{{ selectedProduct.rating }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <DialogFooter class="flex space-x-3">
                    <Button @click="isModalOpen = false" variant="outline">
                        Cancel
                    </Button>
                    <Button @click="addToRoutine(selectedProduct)" class="bg-teal-600 hover:bg-teal-700">
                        <Plus class="w-4 h-4 mr-2" />
                        Add to Routine
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Floating Cart -->
        <FloatingCart
            :items="cartItems"
            @remove-item="removeFromCart"
            @clear-cart="clearCart"
            @view-cart="goToCheckout"
            @update-quantity="updateQuantity"
        />
    </div>
</template>

<script setup>
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle
} from '@/components/ui/dialog';
import FloatingCart from '@/components/FloatingCart.vue';
import { router } from '@inertiajs/vue3';
import { computed, ref, onMounted } from 'vue';
import { route } from 'ziggy-js';
import axios from 'axios';

import {
    AlertCircle,
    ArrowLeft,
    CheckCircle,
    Circle,
    Droplets,
    Eye,
    Home,
    Info,
    Plus,
    Sparkles,
    Star,
    Sun,
    Target,
    Waves,
    Zap
} from 'lucide-vue-next';

// Props
const props = defineProps({
    submissionUuid: {
        type: [String, Number],
        required: true
    }
});

// Reactive data
const isLoading = ref(true);
const error = ref(null);
const submission = ref(null);
const recommendations = ref([]);
const isModalOpen = ref(false);
const selectedProduct = ref(null);
const cartItems = ref([]);

// Load cart from localStorage on mount
onMounted(() => {
    if (props.submissionUuid) {
        // Check if this is a different submission and reset localStorage if needed
        const storedSubmissionId = localStorage.getItem('submissionUuid');
        if (storedSubmissionId && storedSubmissionId !== props.submissionUuid) {
            // Different submission detected, reset localStorage
            localStorage.removeItem('cartItems');
            localStorage.removeItem('submissionUuid');
            console.log('Different submission detected in ProductRecommendations, localStorage cleared');
        }

        // Store current submission ID
        localStorage.setItem('submissionUuid', props.submissionUuid);

        loadRecommendations();
        loadCartFromLocalStorage();
    } else {
        error.value = 'No submission ID provided';
        isLoading.value = false;
    }
});

// Save cart to localStorage whenever it changes
const saveCartToLocalStorage = () => {
    localStorage.setItem('cartItems', JSON.stringify(cartItems.value));
};

const loadCartFromLocalStorage = () => {
    const savedCart = localStorage.getItem('cartItems');
    if (savedCart) {
        try {
            cartItems.value = JSON.parse(savedCart);
        } catch (error) {
            console.error('Error parsing cart from localStorage:', error);
            cartItems.value = [];
        }
    }
};

// Methods
const getConcernIcon = (concernType) => {
    const icons = {
        redness: Zap,
        texture: Waves,
        oiliness: Droplets,
        dryness: Sun,
        poreVisibility: Circle,
        darkCircles: Eye,
        acneScarring: Target,
    };
    return icons[concernType] || Sparkles;
};

const formatConcernType = (concernType) => {
    const formatted = {
        redness: 'Redness',
        texture: 'Texture',
        oiliness: 'Oiliness',
        dryness: 'Dryness',
        poreVisibility: 'Pore Visibility',
        darkCircles: 'Dark Circles',
        acneScarring: 'Acne Scarring',
    };
    return formatted[concernType] || concernType;
};

const formatSkinType = (skinType) => {
    return skinType.charAt(0).toUpperCase() + skinType.slice(1);
};

const handleImageError = (event) => {
    event.target.src = 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=400&h=300&fit=crop';
};

const openProductModal = (product) => {
    selectedProduct.value = product;
    isModalOpen.value = true;
};

const addToRoutine = (product) => {
    // Check if product already exists in cart
    const existingItem = cartItems.value.find(item => item.id === product.id);

    if (existingItem) {
        // If exists, increase quantity
        existingItem.quantity += 1;
    } else {
        // If new, add with quantity 1
        cartItems.value.push({
            id: product.id,
            name: product.name,
            price: product.price,
            image: product.image,
            concern_type: product.concern_type,
            quantity: 1
        });
    }

    // Store both cart items and submission ID
    saveCartToLocalStorage();
    localStorage.setItem('submissionUuid', props.submissionUuid);

    isModalOpen.value = false;
};

const removeFromCart = (productId) => {
    cartItems.value = cartItems.value.filter(item => item.id !== productId);
    saveCartToLocalStorage();
};

const updateQuantity = ({ itemId, action }) => {
    const item = cartItems.value.find(item => item.id === itemId);
    if (item) {
        if (action === 'increase') {
            item.quantity += 1;
        } else if (action === 'decrease') {
            if (item.quantity > 1) {
                item.quantity -= 1;
            } else {
                // Remove item if quantity becomes 0
                removeFromCart(itemId);
                return; // Exit early since removeFromCart already saves to localStorage
            }
        }
        saveCartToLocalStorage();
    }
};

const clearCart = () => {
    cartItems.value = [];
    localStorage.removeItem('cartItems');
};

const goToCheckout = () => {
    // Save cart items and submission ID to localStorage
    saveCartToLocalStorage();
    localStorage.setItem('submissionUuid', props.submissionUuid);

    // Navigate to checkout page
    router.visit(route('checkout'));
};

const goToResults = () => {
    router.visit(route('results', { uuid: props.submissionUuid }));
};

const goHome = () => {
    router.visit(route('home'));
};

// Load recommendations from API
const loadRecommendations = async () => {
    try {
        isLoading.value = true;
        error.value = null;

        const response = await axios.get(`/api/submissions/${props.submissionUuid}/recommendations`);

        if (response.data.success) {
            submission.value = response.data.submission;
            recommendations.value = response.data.recommendations;
        } else {
            throw new Error(response.data.message || 'Failed to load recommendations');
        }
    } catch (err) {
        console.error('Error loading recommendations:', err);
        error.value = err.response?.data?.message || err.message || 'Failed to load recommendations';
    } finally {
        isLoading.value = false;
    }
};
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
