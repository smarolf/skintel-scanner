<template>
    <Card
        class="bg-gradient-to-r from-teal-50 to-emerald-50 rounded-3xl p-8 border border-teal-100 mt-8"
    >
        <CardContent>
            <div class="max-w-2xl mx-auto">
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-semibold text-gray-800 mb-2">
                        Want a personalized routine?
                    </h3>
                    <p class="text-gray-600">Fill out the form below and we'll create a custom skincare routine just for you!</p>
                </div>

                <form
                    v-if="!showSuccess"
                    @submit.prevent="submitForm"
                    class="space-y-6"
                >
                    <!-- Name Field -->
                    <div>
                        <label
                            for="name"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Full Name *
                        </label>
                        <Input
                            id="name"
                            v-model="formData.name"
                            type="text"
                            required
                            class="w-full"
                            placeholder="Enter your full name"
                        />
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label
                            for="email"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Email Address *
                        </label>
                        <Input
                            id="email"
                            v-model="formData.email"
                            type="email"
                            required
                            class="w-full"
                            placeholder="Enter your email address"
                        />
                    </div>

                    <!-- Phone Field -->
                    <div>
                        <label
                            for="phone"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Phone Number (Optional)
                        </label>
                        <Input
                            id="phone"
                            v-model="formData.phone"
                            type="tel"
                            class="w-full"
                            placeholder="Enter your phone number"
                        />
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4 text-center">
                        <Button
                            type="submit"
                            :disabled="isSubmitting"
                        >
                            <Sparkles class="w-5 h-5 mr-2" />
                            {{
                                isSubmitting
                                    ? "Creating Your Routine..."
                                    : "Get Product Recommendations"
                            }}
                        </Button>
                    </div>
                </form>

                <!-- Success Message -->
                <div
                    v-if="showSuccess"
                    class="mt-6 p-4 bg-green-50 border border-green-200 rounded-xl"
                >
                    <div class="flex items-center">
                        <CheckCircle class="w-5 h-5 text-green-500 mr-2" />
                        <p class="text-green-700">
                            Thank you! Your information has been saved. We'll send your personalized routine to your
                            email within 24 hours.
                        </p>
                    </div>
                </div>

                <!-- Error Message -->
                <div
                    v-if="errorMessage"
                    class="mt-6 p-4 bg-red-50 border border-red-200 rounded-xl"
                >
                    <div class="flex items-center">
                        <AlertCircle class="w-5 h-5 text-red-500 mr-2" />
                        <p class="text-red-700">
                            {{ errorMessage }}
                        </p>
                    </div>
                </div>
            </div>
        </CardContent>
    </Card>
</template>

<script setup>
import { ref, reactive } from "vue";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Sparkles, CheckCircle, AlertCircle } from "lucide-vue-next";
import { router } from '@inertiajs/vue3';
import axios from 'axios';

// Props
const props = defineProps({
    submissionId: {
        type: [String, Number],
        required: true
    }
});

const formData = reactive({
    name: "",
    email: "",
    phone: "",
});

const isSubmitting = ref(false);
const showSuccess = ref(false);
const errorMessage = ref("");

const submitForm = async () => {
    isSubmitting.value = true;
    errorMessage.value = "";

    try {
        const submitData = {
            name: formData.name,
            email: formData.email,
            phone: formData.phone,
        };

        const response = await axios.put(`/api/submissions/${props.submissionId}/personalized-routine`, submitData);

        if (response.data.success) {
            // Show brief success message
            showSuccess.value = true;

            // Redirect to recommendations page using JavaScript
            setTimeout(() => {
                if (response.data.redirect_url) {
                    // Use Inertia router for smooth navigation
                    router.visit(response.data.redirect_url);
                }
            }, 1500); // Short delay to show success message
        } else {
            throw new Error(response.data.message || 'Failed to submit form');
        }

    } catch (error) {
        console.error("Form submission error:", error);
        errorMessage.value = error.response?.data?.message || error.message || "There was an error submitting your form. Please try again.";
    } finally {
        isSubmitting.value = false;
    }
};
</script>