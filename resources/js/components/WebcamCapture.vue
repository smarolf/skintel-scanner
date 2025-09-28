<template>
  <div class="flex flex-col items-center space-y-4 p-6">
    <div class="relative">
      <!-- Video element untuk webcam stream -->
      <video
        ref="videoRef"
        :class="[
          'w-96 h-72 bg-gray-200 rounded-lg border-2',
          isStreaming ? 'border-green-500' : 'border-gray-300'
        ]"
        autoplay
        playsinline
        muted
      ></video>

      <!-- Overlay ketika webcam belum aktif -->
      <div
        v-if="!isStreaming"
        class="absolute inset-0 flex items-center justify-center bg-gray-100 rounded-lg"
      >
        <div class="text-center">
          <Camera class="w-12 h-12 mx-auto mb-2 text-gray-400" />
          <p class="text-gray-500">Webcam belum aktif</p>
        </div>
      </div>
    </div>

    <!-- Canvas tersembunyi untuk capture -->
    <canvas
      ref="canvasRef"
      class="hidden"
      width="640"
      height="480"
    ></canvas>

    <!-- Tombol Start/Capture -->
    <Button
      @click="toggleWebcam"
      :disabled="isLoading"
      :class="[
        'min-w-32',
        isStreaming ? 'bg-blue-600 hover:bg-blue-700' : 'bg-green-600 hover:bg-green-700'
      ]"
    >
      <Loader2 v-if="isLoading" class="w-4 h-4 mr-2 animate-spin" />
      <Camera v-else-if="!isStreaming" class="w-4 h-4 mr-2" />
      <ImageIcon v-else class="w-4 h-4 mr-2" />
      {{ buttonText }}
    </Button>

    <!-- Tombol Stop (hanya muncul saat streaming) -->
    <Button
      v-if="isStreaming"
      @click="stopWebcam"
      variant="outline"
      class="min-w-32"
    >
      <Square class="w-4 h-4 mr-2" />
      Stop
    </Button>

    <!-- Error message -->
    <div
      v-if="errorMessage"
      class="p-3 bg-red-100 border border-red-300 rounded-lg text-red-700"
    >
      {{ errorMessage }}
    </div>

    <!-- Hasil capture -->
    <div v-if="capturedImage" class="mt-4">
      <h3 class="text-lg font-semibold mb-2">Hasil Capture:</h3>
      <img
        :src="capturedImage"
        alt="Captured"
        class="w-96 h-72 object-cover rounded-lg border-2 border-gray-300"
      />
      <div class="mt-2 space-x-2">
        <Button @click="downloadImage" variant="outline" size="sm">
          <Download class="w-4 h-4 mr-2" />
          Download
        </Button>
        <Button @click="clearCapture" variant="outline" size="sm">
          <Trash2 class="w-4 h-4 mr-2" />
          Hapus
        </Button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onUnmounted } from 'vue'
import { Button } from '@/components/ui/button'
import { Camera, ImageIcon, Loader2, Square, Download, Trash2 } from 'lucide-vue-next'

// Refs
const videoRef = ref(null)
const canvasRef = ref(null)
const isStreaming = ref(false)
const isLoading = ref(false)
const errorMessage = ref('')
const capturedImage = ref('')
const mediaStream = ref(null)

// Computed
const buttonText = computed(() => {
  if (isLoading.value) return 'Loading...'
  return isStreaming.value ? 'Capture' : 'Start Webcam'
})

// Methods
const startWebcam = async () => {
  try {
    isLoading.value = true
    errorMessage.value = ''

    // Request webcam access
    const stream = await navigator.mediaDevices.getUserMedia({
      video: {
        width: { ideal: 640 },
        height: { ideal: 480 },
        facingMode: 'user'
      },
      audio: false
    })
    console.log('Webcam access granted')
    // Set stream to video element
    if (videoRef.value) {
      videoRef.value.srcObject = stream
      mediaStream.value = stream
      isStreaming.value = true
    }
  } catch (error) {
    console.error('Error accessing webcam:', error)
    if (error.name === 'NotAllowedError') {
      errorMessage.value = 'Akses webcam ditolak. Silakan berikan izin untuk menggunakan kamera.'
    } else if (error.name === 'NotFoundError') {
      errorMessage.value = 'Webcam tidak ditemukan. Pastikan kamera terhubung dengan benar.'
    } else {
      errorMessage.value = 'Terjadi kesalahan saat mengakses webcam: ' + error.message
    }
  } finally {
    isLoading.value = false
  }
}

const stopWebcam = () => {
  if (mediaStream.value) {
    mediaStream.value.getTracks().forEach(track => track.stop())
    mediaStream.value = null
  }

  if (videoRef.value) {
    videoRef.value.srcObject = null
  }

  isStreaming.value = false
  errorMessage.value = ''
}

const captureImage = () => {
  if (!videoRef.value || !canvasRef.value || !isStreaming.value) return

  const video = videoRef.value
  const canvas = canvasRef.value
  const ctx = canvas.getContext('2d')

  // Set canvas dimensions to match video
  canvas.width = video.videoWidth
  canvas.height = video.videoHeight

  // Draw current video frame to canvas
  ctx.drawImage(video, 0, 0, canvas.width, canvas.height)

  // Convert canvas to image data URL
  capturedImage.value = canvas.toDataURL('image/png')
}

const toggleWebcam = () => {
  if (isStreaming.value) {
    captureImage()
  } else {
    startWebcam()
  }
}

const downloadImage = () => {
  if (!capturedImage.value) return

  const link = document.createElement('a')
  link.download = `webcam-capture-${Date.now()}.png`
  link.href = capturedImage.value
  link.click()
}

const clearCapture = () => {
  capturedImage.value = ''
}

// Cleanup on component unmount
onUnmounted(() => {
  stopWebcam()
})
</script>
