<template>
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-2xl mx-auto text-center">
            <!-- Header -->
            <div class="mb-8">
                <Button
                    @click="goBack"
                    variant="ghost"
                    class="absolute top-6 left-6 text-gray-600 hover:text-gray-800"
                >
                    <ArrowLeft class="w-5 h-5 mr-2" />
                    Back
                </Button>

                <h1 class="text-3xl font-bold text-gray-800 mb-2">Face Scan</h1>
                <p class="text-gray-600">
                    Position your face in the center and ensure good lighting
                </p>
            </div>

            <!-- Webcam Container -->
            <Card class="mb-4">
                <CardContent>
                    <div class="relative w-full max-w-md mx-auto h-80">
                        <!-- Video element -->
                        <video
                            ref="videoRef"
                            :class="[
                                'w-full h-full bg-gray-100 rounded-2xl border-4 transition-all duration-300',
                                isStreaming
                                    ? 'border-primary shadow-lg'
                                    : 'border-gray-200',
                            ]"
                            playsinline
                            muted
                        ></video>

                        <canvas
                            ref="canvasRef"
                            class="absolute top-0 left-0 w-full h-full"
                        ></canvas>

                        <!-- Face outline guide -->
                        <div
                            v-if="isStreaming"
                            class="absolute inset-0 flex items-center justify-center pointer-events-none"
                        >
                            <div
                                class="w-64 h-80 border-2 border-primary rounded-full opacity-50 animate-pulse"
                            ></div>
                        </div>

                        <!-- Placeholder when not streaming -->
                        <div
                            v-if="!isStreaming"
                            class="absolute inset-0 flex items-center justify-center bg-gray-50 rounded-2xl"
                        >
                            <div class="text-center">
                                <User
                                    class="w-16 h-16 mx-auto mb-4 text-gray-300"
                                />
                                <p class="text-gray-500">Camera not active</p>
                            </div>
                        </div>

                        <!-- Scanning animation overlay -->
                        <div
                            v-if="isScanning"
                            class="absolute inset-0 bg-primary/20 rounded-2xl flex items-center justify-center"
                        >
                            <div class="text-center">
                                <div
                                    class="w-16 h-16 border-4 border-primary border-t-transparent rounded-full animate-spin mx-auto mb-4"
                                ></div>
                                <p class="text-white font-semibold">
                                    Analyzing your skin...
                                </p>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Action Buttons -->
            <div class="space-y-4">
                <Button
                    v-if="!isStreaming && !isScanning"
                    @click="startWebcam"
                    :disabled="isLoading"
                    size="lg"
                >
                    <Loader2
                        v-if="isLoading"
                        class="w-5 h-5 mr-2 animate-spin"
                    />
                    <Camera v-else class="w-5 h-5 mr-2" />
                    {{ isLoading ? "Starting Camera..." : "Start Camera" }}
                </Button>

                <Button
                    v-if="isStreaming && !isScanning"
                    @click="captureAndAnalyze"
                    size="lg"
                    class=""
                >
                    <Scan class="w-5 h-5 mr-2" />
                    Capture & Analyze
                </Button>
                <br>
                <Button
                    v-if="isStreaming"
                    @click="stopWebcam"
                    variant="outline"
                >
                    <Square class="w-4 h-4 mr-2" />
                    Stop Camera
                </Button>
            </div>

            <!-- Error Message -->
            <div
                v-if="errorMessage"
                class="mt-4 p-4 bg-red-50 border border-teal-200 rounded-lg text-primary"
            >
                <AlertCircle class="w-5 h-5 inline mr-2" />
                {{ errorMessage }}
            </div>

            <!-- Instructions -->
            <div v-if="!isStreaming && !isScanning" class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div
                        class="flex items-center justify-center p-3 bg-teal-50 rounded-lg"
                    >
                        <Sun class="w-5 h-5 text-primary mr-2" />
                        <span>Good lighting</span>
                    </div>
                    <div
                        class="flex items-center justify-center p-3 bg-teal-50 rounded-lg"
                    >
                        <User class="w-5 h-5 text-primary mr-2" />
                        <span>Face centered</span>
                    </div>
                    <div
                        class="flex items-center justify-center p-3 bg-teal-50 rounded-lg"
                    >
                        <Eye class="w-5 h-5 text-primary mr-2" />
                        <span>Look directly</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { ref, onUnmounted, onMounted } from "vue";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import axios from 'axios';
import {
    Camera,
    ArrowLeft,
    User,
    Sun,
    Eye,
    Loader2,
    Scan,
    Square,
    AlertCircle,
} from "lucide-vue-next";
import * as faceapi from "face-api.js";

// Refs
const videoRef = ref(null);
const canvasRef = ref(null);
const isStreaming = ref(false);
const isLoading = ref(false);
const isScanning = ref(false);
const errorMessage = ref("");
const mediaStream = ref(null);

// Methods
const goBack = () => {
    stopWebcam();
    router.visit(route('home'));
};

const startWebcam = async () => {
    try {
        isLoading.value = true;
        errorMessage.value = "";

        const stream = await navigator.mediaDevices.getUserMedia({
            video: {
                width: { ideal: 640 },
                height: { ideal: 480 },
                facingMode: "user",
            },
            audio: false,
        });

        if (videoRef.value) {
            videoRef.value.srcObject = stream;
            mediaStream.value = stream;
            isStreaming.value = true;
            videoRef.value.onloadedmetadata = () => {
                videoRef.value.play();
            };
        }
    } catch (error) {
        console.error("Error accessing webcam:", error);
        if (error.name === "NotAllowedError") {
            errorMessage.value =
                "Camera access denied. Please allow camera permission and try again.";
        } else if (error.name === "NotFoundError") {
            errorMessage.value =
                "No camera found. Please connect a camera and try again.";
        } else {
            errorMessage.value = "Error accessing camera: " + error.message;
        }
    } finally {
        isLoading.value = false;
    }
};

const stopWebcam = () => {
    if (mediaStream.value) {
        mediaStream.value.getTracks().forEach((track) => track.stop());
        mediaStream.value = null;
    }

    if (videoRef.value) {
        videoRef.value.srcObject = null;
    }

    if (intervalId) {
        clearInterval(intervalId);
        intervalId = null;
    }

    isStreaming.value = false;
    isScanning.value = false;
    errorMessage.value = "";
};

const captureAndAnalyze = async () => {
    if (!videoRef.value || !canvasRef.value || !isStreaming.value) return;

    isScanning.value = true;
    errorMessage.value = "";

    try {
        // Capture image
        const video = videoRef.value;
        const canvas = canvasRef.value;
        const ctx = canvas.getContext("2d");

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        const capturedImage = canvas.toDataURL("image/png");

        // Send image to backend API
        const response = await axios.post('/api/scan/capture-and-analyze', {
            image: capturedImage
        });

        if (response.data.success) {
            const submissionUuid = response.data.submission_uuid;
            
            // Stop webcam
            stopWebcam();
            
            // Redirect to results page with submission UUID
            router.visit(route('results', { uuid: submissionUuid }));
        } else {
            throw new Error(response.data.message || 'Failed to analyze image');
        }

    } catch (error) {
        console.error("Error analyzing image:", error);
        errorMessage.value = error.response?.data?.message || error.message || "Failed to analyze image. Please try again.";
        isScanning.value = false;
    }
};

const loadModels = async () => {
    const MODEL_URL = "/models/";
    await Promise.all([
        faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
        faceapi.nets.faceLandmark68TinyNet.loadFromUri(MODEL_URL),
    ]);
};

let intervalId = null;
let handlePlay = null;

onMounted(async () => {
    await loadModels();
    handlePlay = () => {
        const canvas = canvasRef.value;
        const video = videoRef.value;

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        const displaySize = {
          width: video.videoWidth,
          height: video.videoHeight,
        };
        faceapi.matchDimensions(canvas, displaySize);

        intervalId = setInterval(async () => {
          if (!video || video.paused || video.ended) return;

          const detections = await faceapi
            .detectAllFaces(video, new faceapi.TinyFaceDetectorOptions())
            .withFaceLandmarks(true);

          const resizedDetections = faceapi.resizeResults(detections, displaySize);

          const ctx = canvas.getContext("2d");
          ctx.clearRect(0, 0, canvas.width, canvas.height);
          faceapi.draw.drawFaceLandmarks(canvas, resizedDetections);
        }, 100);
      };

    videoRef.value.addEventListener("play", handlePlay);
});

</script>