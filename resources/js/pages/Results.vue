<template>
    <div class="min-h-screen px-4 py-8">
        <div class="mx-auto max-w-6xl">
            <!-- Loading State -->
            <div v-if="isLoading" class="text-center py-12">
                <div class="w-16 h-16 border-4 border-primary border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
                <p class="text-gray-600">Loading your results...</p>
            </div>

            <!-- Error State -->
            <div v-else-if="error" class="text-center py-12">
                <div class="text-red-500 mb-4">
                    <AlertCircle class="w-16 h-16 mx-auto mb-4" />
                    <h2 class="text-xl font-semibold mb-2">Error Loading Results</h2>
                    <p class="text-gray-600">{{ error }}</p>
                </div>
                <Button @click="goHome" variant="outline">
                    <Home class="mr-2 h-4 w-4" />
                    Go Home
                </Button>
            </div>

            <!-- Results Content -->
            <div v-else-if="submission && scanResults">
                <!-- Header -->
                <div class="mb-8 text-center">
                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-teal-400 to-emerald-500">
                        <CheckCircle class="h-8 w-8 text-white" />
                    </div>
                    <h1 class="mb-2 text-3xl font-bold text-gray-800">Your Skin Analysis Results</h1>
                    <p class="text-gray-600">AI-powered analysis of your skin health</p>
                </div>

                <!-- Captured Image -->
                <div v-if="submission.scanned_photo_url" class="mb-8 text-center">
                    <img
                        :src="submission.scanned_photo_url"
                        alt="Your scan"
                        class="mx-auto h-32 w-32 rounded-full border-4 border-teal-400 object-cover shadow-lg"
                    />
                </div>

                <!-- Overall Score -->
                <Card class="border-teal-200">
                    <CardContent>
                        <h2 class="text mb-4 text-xl font-semibold text-gray-800">Overall Skin Health Score</h2>
                        <div class="mb-2 text-3xl font-bold text-primary">{{ overallScore }}/5</div>
                        <div class="text-secondary">{{ getOverallDescription(overallScore) }}</div>
                        <div class="mt-4">
                            <div class="h-3 w-full rounded-full bg-gray-200">
                                <div
                                    class="h-3 rounded-full bg-gradient-to-r from-teal-400 to-primary transition-all duration-1000"
                                    :style="{ width: (overallScore / 5) * 100 + '%' }"
                                ></div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Skin Concerns and Product Recommendations -->
                <div class="my-6 grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <Card v-for="(concern, key) in scanResults" :key="key" class="border-teal-200 shadow-lg">
                        <CardContent>
                            <div class="mb-4 flex items-center justify-between">
                                <h3 class="font-semibold text-gray-800">{{ concern.name }}</h3>
                                <component :is="getConcernIcon(key)" class="h-6 w-6 text-primary" />
                            </div>

                            <!-- Score Display -->
                            <div class="mb-3">
                                <div class="mb-2 flex items-center justify-between">
                                    <span class="text-2xl font-bold" :class="getScoreColor(concern.score)"> {{ concern.score }}/5 </span>
                                    <span class="text-sm text-gray-500">{{ getScoreLevel(concern.score) }}</span>
                                </div>

                                <!-- Score Bar -->
                                <div class="h-2 w-full rounded-full bg-gray-200">
                                    <div
                                        class="h-2 rounded-full transition-all duration-1000"
                                        :class="getScoreBarColor(concern.score)"
                                        :style="{ width: (concern.score / 5) * 100 + '%' }"
                                    ></div>
                                </div>
                            </div>

                            <p class="text-sm text-gray-600">{{ concern.description }}</p>
                        </CardContent>
                    </Card>
                </div>

                <!-- Action Form -->
                <div class="mt-6">
                    <!-- Personalized Routine Form -->
                    <PersonalizedRoutineForm :submission-id="submissionUuid" />
                </div>

                <!-- Action Buttons -->
                <div class="text-center">
                    <div class="mt-10 flex justify-center space-x-4">
                        <Button @click="retakeScan" variant="outline">
                            <RotateCcw class="mr-2 h-4 w-4" />
                            Retake Scan
                        </Button>

                        <Button @click="goHome" variant="outline">
                            <Home class="mr-2 h-4 w-4" />
                            New Scan
                        </Button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import PersonalizedRoutineForm from '@/components/PersonalizedRoutineForm.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { router } from '@inertiajs/vue3';
import { computed, ref, onMounted } from 'vue';
import { route } from 'ziggy-js';
import axios from 'axios';

import { AlertCircle, CheckCircle, Circle, Droplets, Eye, Home, RotateCcw, Sparkles, Sun, Target, Waves, Zap } from 'lucide-vue-next';

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
const scanResults = ref(null);

// Computed
const overallScore = computed(() => {
    if (!scanResults.value) return 0;
    const scores = Object.values(scanResults.value).map((concern) => parseInt(concern.score));
    return Math.round((scores.reduce((sum, score) => sum + score, 0) / scores.length) * 10) / 10;
});

// Methods
const getConcernIcon = (key) => {
    const icons = {
        redness: Zap,
        texture: Waves,
        oiliness: Droplets,
        dryness: Sun,
        poreVisibility: Circle,
        darkCircles: Eye,
        acneScarring: Target,
    };
    return icons[key] || Sparkles;
};

const getScoreColor = (score) => {
    if (score <= 2) return 'text-green-600';
    if (score <= 3) return 'text-yellow-600';
    return 'text-red-600';
};

const getScoreBarColor = (score) => {
    if (score <= 2) return 'bg-gradient-to-r from-green-400 to-green-500';
    if (score <= 3) return 'bg-gradient-to-r from-yellow-400 to-yellow-500';
    return 'bg-gradient-to-r from-red-400 to-red-500';
};

const getScoreLevel = (score) => {
    if (score <= 2) return 'Excellent';
    if (score <= 3) return 'Good';
    if (score <= 4) return 'Fair';
    return 'Needs Attention';
};

const getOverallDescription = (score) => {
    if (score <= 2) return 'Your skin is in excellent condition!';
    if (score <= 3) return 'Your skin is in good condition with minor areas to improve';
    if (score <= 4) return 'Your skin needs some attention';
    return 'Your skin requires immediate care and attention';
};

const retakeScan = () => {
    router.visit(route('scan'));
};

const goHome = () => {
    router.visit(route('home'));
};

// Load results from API
const loadResults = async () => {
    try {
        isLoading.value = true;
        error.value = null;

        const response = await axios.get(`/api/scan/results/${props.submissionUuid}`);

        if (response.data.success) {
            submission.value = response.data.submission;
            scanResults.value = response.data.scan_results;
        } else {
            throw new Error(response.data.message || 'Failed to load results');
        }
    } catch (err) {
        console.error('Error loading results:', err);
        error.value = err.response?.data?.message || err.message || 'Failed to load results';
    } finally {
        isLoading.value = false;
    }
};

// Load results on component mount
onMounted(() => {
    if (props.submissionUuid) {
        // Check if this is a different submission and reset localStorage if needed
        const storedSubmissionId = localStorage.getItem('submissionUuid');
        if (storedSubmissionId && storedSubmissionId !== props.submissionUuid) {
            // Different submission detected, reset localStorage
            localStorage.removeItem('cartItems');
            localStorage.removeItem('submissionUuid');
            console.log('Different submission detected, localStorage cleared');
        }
        
        // Store current submission ID
        localStorage.setItem('submissionUuid', props.submissionUuid);
        
        loadResults();
    } else {
        error.value = 'No submission ID provided';
        isLoading.value = false;
    }
});
</script>
