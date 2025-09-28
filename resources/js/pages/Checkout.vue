<template>
    <!-- Invoice Success Page -->
    <div v-if="showInvoice" class="min-h-screen bg-gray-50 px-4 py-8 mb-20">
        <div class="mx-auto max-w-4xl">
            <!-- Success Header -->
            <div class="mb-8 text-center">
                <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-br from-green-400 to-emerald-500">
                    <CheckCircle class="h-10 w-10 text-white" />
                </div>
                <h1 class="mb-2 text-3xl font-bold text-gray-800">Payment Successful!</h1>
                <p class="text-gray-600">Thank you for your order. Your skincare routine is on its way!</p>
            </div>

            <!-- Invoice Card -->
            <Card class="mb-6">
                <CardHeader class="pb-4">
                    <div class="flex justify-between items-center">
                        <CardTitle class="text-2xl font-bold text-gray-800">Invoice</CardTitle>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Order Number</p>
                            <p class="text-lg font-bold text-teal-600">{{ orderNumber }}</p>
                        </div>
                    </div>
                    <div class="mt-4 text-sm text-gray-500">
                        <p>Date: {{ new Date().toLocaleDateString() }}</p>
                        <p>Time: {{ new Date().toLocaleTimeString() }}</p>
                    </div>
                </CardHeader>
                <CardContent>
                    <!-- Customer & Shipping Info -->
                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Customer Information</h3>
                            <div class="text-sm space-y-1">
                                <p><strong>Name:</strong> {{ submission?.name }}</p>
                                <p><strong>Email:</strong> {{ submission?.email }}</p>
                                <p v-if="submission?.phone"><strong>Phone:</strong> {{ submission.phone }}</p>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Shipping Address</h3>
                            <div class="text-sm space-y-1">
                                <p><strong>Name:</strong> {{ shippingForm.firstName }} {{ shippingForm.lastName }}</p>
                                <p>{{ shippingForm.address }}</p>
                                <p>{{ shippingForm.city }}, {{ shippingForm.state }} {{ shippingForm.zipCode }}</p>
                                <p v-if="shippingForm.phone"><strong>Phone:</strong> {{ shippingForm.phone }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-800 mb-3">Order Items</h3>
                        <div class="space-y-3">
                            <div v-for="item in cartItems" :key="item.id"
                                 class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <img :src="item.image" :alt="item.name"
                                         class="w-12 h-12 object-cover rounded-md" />
                                    <div>
                                        <h4 class="font-medium text-gray-800">{{ item.name }}</h4>
                                        <p class="text-sm text-gray-500">Qty: {{ item.quantity }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium text-gray-800">${{ (parseFloat(item.price) * item.quantity).toFixed(2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="border-t pt-4">
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal ({{ totalItems }} items)</span>
                                <span class="font-medium">${{ subtotal.toFixed(2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Shipping</span>
                                <span class="font-medium text-green-600">Free</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tax</span>
                                <span class="font-medium">${{ tax.toFixed(2) }}</span>
                            </div>
                            <hr class="my-3">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total Paid</span>
                                <span class="text-green-600">${{ total.toFixed(2) }}</span>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </div>

    <!-- Checkout Page -->
    <div v-else class="min-h-screen bg-gray-50 px-4 py-8">
        <div class="mx-auto max-w-4xl">
            <!-- Header -->
            <div class="mb-8 text-center">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-teal-400 to-emerald-500">
                    <ShoppingCart class="h-8 w-8 text-white" />
                </div>
                <h1 class="mb-2 text-3xl font-bold text-gray-800">Checkout</h1>
                <p class="text-gray-600">Review your personalized skincare routine</p>
            </div>

            <div class="grid lg:grid-cols-[2fr_1fr] gap-8">
                <!-- Order Summary -->
                <div>
                    <Card class="mb-4 gap-0">
                        <CardHeader class="pb-3">
                            <CardTitle class="flex items-center space-x-2 text-lg">
                                <Package class="w-4 h-4" />
                                <span>Order Summary</span>
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="pt-0">
                            <div v-if="cartItems.length === 0" class="text-center py-8">
                                <ShoppingCart class="w-16 h-16 mx-auto mb-4 text-gray-400" />
                                <p class="text-gray-500 mb-4">Your cart is empty</p>
                                <Button @click="goBack" variant="outline">
                                    <ArrowLeft class="w-4 h-4 mr-2" />
                                    Continue Shopping
                                </Button>
                            </div>

                            <div v-else class="space-y-2">
                                <div v-for="item in cartItems" :key="item.id"
                                     class="flex flex-wrap items-center space-x-3 border-b py-3">
                                    <img :src="item.image" :alt="item.name"
                                         class="w-12 h-12 object-cover rounded-md flex-shrink-0" />

                                    <div class="flex-1 min-w-0 w-full">
                                        <h3 class="font-medium text-gray-800 text-sm truncate">{{ item.name }}</h3>
                                        <div class="flex items-center space-x-1 mt-1">
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-teal-100 text-teal-800">
                                                <component :is="getConcernIcon(item.concern_type)" class="w-2.5 h-2.5 mr-1" />
                                                {{ formatConcernType(item.concern_type) }}
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between mt-1">
                                            <span class="text-sm font-bold text-gray-800">${{ item.price }} × {{ item.quantity }}</span>
                                            <span class="text-sm font-semibold text-teal-600 pr-10">
                                                ${{ (parseFloat(item.price) * item.quantity).toFixed(2) }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Compact Controls -->
                                    <div class="flex items-center space-x-1">
                                        <!-- Quantity Controls -->
                                        <div class="flex items-center bg-white rounded border">
                                            <Button @click="decreaseQuantity(item.id)" variant="ghost" size="sm"
                                                    class="h-6 w-6 p-0 hover:bg-gray-100">
                                                <Minus class="w-2.5 h-2.5" />
                                            </Button>
                                            <span class="text-xs font-medium px-2 min-w-[20px] text-center">{{ item.quantity }}</span>
                                            <Button @click="increaseQuantity(item.id)" variant="ghost" size="sm"
                                                    class="h-6 w-6 p-0 hover:bg-gray-100">
                                                <Plus class="w-2.5 h-2.5" />
                                            </Button>
                                        </div>

                                        <!-- Remove Button -->
                                        <Button @click="removeFromCart(item.id)" variant="ghost" size="sm"
                                                class="h-6 w-6 p-0 text-red-500 hover:text-red-700">
                                            <Trash2 class="w-3 h-3" />
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Shipping Information -->
                    <Card class="gap-0">
                        <CardHeader class="pb-3">
                            <CardTitle class="flex items-center space-x-2 text-lg">
                                <Truck class="w-4 h-4" />
                                <span>Shipping Information</span>
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="pt-0">
                            <form class="space-y-4">
                                <div class="grid md:grid-cols-2 gap-3">
                                    <div>
                                        <label for="firstName" class="block text-xs font-medium text-gray-700 mb-1">First Name *</label>
                                        <Input
                                            v-model="shippingForm.firstName"
                                            :class="[
                                                shippingErrors.firstName ? 'border-red-500 bg-red-50' : ''
                                            ]"
                                            id="firstName"
                                            required
                                            placeholder="Enter first name"
                                        />
                                    </div>
                                    <div>
                                        <label for="lastName" class="block text-xs font-medium text-gray-700 mb-1">Last Name *</label>
                                        <Input
                                            v-model="shippingForm.lastName"
                                            :class="[
                                                shippingErrors.lastName ? 'border-red-500 bg-red-50' : ''
                                            ]"
                                            id="lastName"
                                            required
                                            placeholder="Enter last name"
                                        />
                                    </div>
                                </div>

                                <div>
                                    <label for="address" class="block text-xs font-medium text-gray-700 mb-1">Street Address *</label>
                                    <Input
                                        v-model="shippingForm.address"
                                        :class="[
                                            shippingErrors.address ? 'border-red-500 bg-red-50' : ''
                                        ]"
                                        id="address"
                                        required
                                        placeholder="Enter street address"
                                    />
                                </div>

                                <div class="grid md:grid-cols-3 gap-3">
                                    <div>
                                        <label for="city" class="block text-xs font-medium text-gray-700 mb-1">City *</label>
                                        <Input
                                            v-model="shippingForm.city"
                                            :class="[
                                                shippingErrors.city ? 'border-red-500 bg-red-50' : ''
                                            ]"
                                            id="city"
                                            required
                                            placeholder="Enter city"
                                        />
                                    </div>
                                    <div>
                                        <label for="state" class="block text-xs font-medium text-gray-700 mb-1">State *</label>
                                        <Select
                                            v-model="shippingForm.state"
                                            id="state"
                                            required
                                        >
                                            <SelectTrigger
                                                class="w-full"
                                                :class="[
                                                    shippingErrors.state ? 'border-red-500 bg-red-50' : ''
                                                ]"
                                            >
                                              <SelectValue placeholder="Select state" />
                                            </SelectTrigger>
                                            <SelectContent>
                                              <SelectGroup>
                                                <SelectItem value="AL">Alabama</SelectItem>
                                                <SelectItem value="AK">Alaska</SelectItem>
                                                <SelectItem value="AZ">Arizona</SelectItem>
                                                <SelectItem value="AR">Arkansas</SelectItem>
                                                <SelectItem value="CA">California</SelectItem>
                                                <SelectItem value="CO">Colorado</SelectItem>
                                                <SelectItem value="CT">Connecticut</SelectItem>
                                                <SelectItem value="DE">Delaware</SelectItem>
                                                <SelectItem value="FL">Florida</SelectItem>
                                                <SelectItem value="GA">Georgia</SelectItem>
                                              </SelectGroup>
                                            </SelectContent>
                                        </Select>
                                    </div>
                                    <div>
                                        <label for="zipCode" class="block text-xs font-medium text-gray-700 mb-1">ZIP Code *</label>
                                        <Input
                                            v-model="shippingForm.zipCode"
                                            :class="[
                                                shippingErrors.zipCode ? 'border-red-500 bg-red-50' : ''
                                            ]"
                                            id="zipCode"
                                            required
                                            pattern="[0-9]{5}(-[0-9]{4})?"
                                            placeholder="12345"
                                        />
                                    </div>
                                </div>

                                <div>
                                    <label for="phone" class="block text-xs font-medium text-gray-700 mb-1">Phone Number</label>
                                    <Input
                                        v-model="shippingForm.phone"
                                        :class="[
                                            shippingErrors.phone ? 'border-red-500 bg-red-50' : ''
                                        ]"
                                        required
                                        id="phone"
                                        placeholder="(555) 123-4567"
                                    />
                                </div>

                                <div class="flex items-center space-x-2">
                                    <Input
                                        v-model="shippingForm.saveAsDefault"
                                        id="saveAsDefault"
                                        class="w-4 h-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500"
                                    />
                                    <label for="saveAsDefault" class="text-xs text-gray-700">Save as default shipping address</label>
                                </div>
                            </form>
                        </CardContent>
                    </Card>
                </div>

                <!-- Order Total & Actions -->
                <div>
                    <Card class="sticky top-8 gap-0 mb-20">
                        <CardHeader class="pb-3">
                            <CardTitle class="flex items-center space-x-2 text-lg">
                                <Calculator class="w-4 h-4" />
                                <span>Order Total</span>
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="pt-0">
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Subtotal ({{ totalItems }} items)</span>
                                    <span class="text-sm font-medium">${{ subtotal.toFixed(2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Shipping</span>
                                    <span class="text-sm font-medium text-green-600">Free</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Tax</span>
                                    <span class="text-sm font-medium">${{ tax.toFixed(2) }}</span>
                                </div>
                                <hr class="my-3">
                                <div class="flex justify-between text-base font-bold">
                                    <span>Total</span>
                                    <span class="text-teal-600">${{ total.toFixed(2) }}</span>
                                </div>
                            </div>

                            <div class="mt-4 space-y-2">
                                <Button @click="proceedToPayment" class="w-full bg-teal-600 hover:bg-teal-700"
                                        :disabled="cartItems.length === 0">
                                    <CreditCard class="w-4 h-4 mr-2" />
                                    Proceed to Payment
                                </Button>

                                <Button @click="goBack" variant="outline" class="w-full">
                                    <ArrowLeft class="w-4 h-4 mr-2" />
                                    Continue Shopping
                                </Button>

                                <Button @click="clearCart" variant="ghost" class="w-full text-red-600 hover:text-red-700">
                                    <Trash2 class="w-4 h-4 mr-2" />
                                    Clear Cart
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from "@/components/ui/input";
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import { router } from '@inertiajs/vue3';
import { computed, ref, onMounted } from 'vue';
import { route } from 'ziggy-js';
import axios from 'axios';

import {
    ArrowLeft,
    Calculator,
    CheckCircle,
    Circle,
    CreditCard,
    Droplets,
    Eye,
    Minus,
    Package,
    Plus,
    Printer,
    ShoppingCart,
    Sun,
    Target,
    Trash2,
    Truck,
    User,
    Waves,
    Zap
} from 'lucide-vue-next';

// Reactive data
const cartItems = ref([]);
const submission = ref(null);
const isLoading = ref(true);
const showInvoice = ref(false);
const orderNumber = ref('');
const shippingForm = ref({
    firstName: '',
    lastName: '',
    address: '',
    city: '',
    state: '',
    zipCode: '',
    phone: '',
    saveAsDefault: false
});

const shippingErrors = ref({
    firstName: false,
    lastName: false,
    address: false,
    city: false,
    state: false,
    zipCode: false
});

// Computed
const totalItems = computed(() => {
    return cartItems.value.reduce((total, item) => total + item.quantity, 0);
});

const subtotal = computed(() => {
    return cartItems.value.reduce((total, item) => total + (parseFloat(item.price) * item.quantity), 0);
});

const tax = computed(() => {
    return subtotal.value * 0.08; // 8% tax
});

const total = computed(() => {
    return subtotal.value + tax.value;
});

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
    return icons[concernType] || Zap;
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

const increaseQuantity = (itemId) => {
    const item = cartItems.value.find(item => item.id === itemId);
    if (item) {
        item.quantity += 1;
        saveCartToLocalStorage();
    }
};

const decreaseQuantity = (itemId) => {
    const item = cartItems.value.find(item => item.id === itemId);
    if (item) {
        if (item.quantity > 1) {
            item.quantity -= 1;
        } else {
            removeFromCart(itemId);
        }
        saveCartToLocalStorage();
    }
};

const removeFromCart = (itemId) => {
    cartItems.value = cartItems.value.filter(item => item.id !== itemId);
    saveCartToLocalStorage();
};

const clearCart = () => {
    cartItems.value = [];
    localStorage.removeItem('cartItems');
    localStorage.removeItem('submissionUuid');
};

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

const loadSubmissionData = async () => {
    const submissionUuid = localStorage.getItem('submissionUuid');
    if (submissionUuid) {
        try {
            const response = await axios.get(`/api/submissions/${submissionUuid}`);
            if (response.data.success) {
                submission.value = response.data.submission;
            }
        } catch (error) {
            console.error('Error loading submission data:', error);
        }
    }
};

const goBack = () => {
    window.history.back();
};

const validateShippingForm = () => {
    const requiredFields = ['firstName', 'lastName', 'address', 'city', 'state', 'zipCode', 'phone'];
    const missingFields = [];

    // Reset all errors
    Object.keys(shippingErrors.value).forEach(key => {
        shippingErrors.value[key] = false;
    });

    for (const field of requiredFields) {
        if (!shippingForm.value[field] || shippingForm.value[field].trim() === '') {
            missingFields.push(field);
            shippingErrors.value[field] = true;
        }
    }

    // Validate ZIP code format
    const zipCodePattern = /^[0-9]{5}(-[0-9]{4})?$/;
    if (shippingForm.value.zipCode && !zipCodePattern.test(shippingForm.value.zipCode)) {
        missingFields.push('zipCode (invalid format)');
        shippingErrors.value.zipCode = true;
    }

    return {
        isValid: missingFields.length === 0,
        missingFields
    };
};

const generateOrderNumber = () => {
    const timestamp = Date.now();
    const random = Math.floor(Math.random() * 1000);
    return `ORD-${timestamp}-${random}`;
};

const proceedToPayment = () => {
    // Validate shipping form before proceeding
    const validation = validateShippingForm();

    if (!validation.isValid) {
        return;
    }

    // Save shipping information to localStorage or send to server
    localStorage.setItem('shippingInfo', JSON.stringify(shippingForm.value));
    console.log('Shipping information saved:', shippingForm.value);

    // Generate order number and show invoice
    orderNumber.value = generateOrderNumber();
    showInvoice.value = true;

    // Scroll to top to show invoice
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

// Load data on component mount
onMounted(async () => {
    // Check if there's a submission ID mismatch and clear if needed
    const submissionUuid = localStorage.getItem('submissionUuid');
    if (!submissionUuid) {
        // No submission ID in localStorage, clear cart as well
        localStorage.removeItem('cartItems');
        console.log('No submission ID found, cart cleared');
    }

    loadCartFromLocalStorage();
    await loadSubmissionData();
    isLoading.value = false;
});
</script>
