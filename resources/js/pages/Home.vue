<template>
  <div class="min-h-screen flex items-center justify-center px-4">
    <div class="text-center max-w-2xl mx-auto">
      <!-- Logo/Brand Area -->
      <div class="mb-8">
        <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-teal-400 to-emerald-500 rounded-full flex items-center justify-center shadow-lg">
          <Sparkles class="w-12 h-12 text-white" />
        </div>
        <h1 class="text-5xl font-bold text-primary mb-4">
            Skintel
        </h1>
        <!-- <p class="text-xl text-gray-700 mb-2">
          Advanced AI-Powered Skin Analysis
        </p> -->
        <p class="text-gray-600">
            Get a free skin analysis and product recs in seconds
        </p>
      </div>

      <!-- CTA Button -->
      <div class="space-y-4">
        <Button
          @click="startScan"
          size="lg"
          class=""
        >
          <Camera class="w-6 h-6 mr-3" />
          Start Scan
        </Button>
        <p class="text-sm text-gray-500">
          Free • No signup required • Results in 30 seconds
        </p>
      </div>

      <!-- Trust Indicators -->
      <div class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-8 opacity-60">
        <div class="text-center">
          <div class="text-2xl font-bold text-primary">10K+</div>
          <div class="text-xs text-gray-600">Scans Completed</div>
        </div>
        <div class="text-center">
          <div class="text-2xl font-bold text-primary">98%</div>
          <div class="text-xs text-gray-600">Accuracy Rate</div>
        </div>
        <div class="text-center">
          <div class="text-2xl font-bold text-primary">24/7</div>
          <div class="text-xs text-gray-600">Available</div>
        </div>
        <div class="text-center">
          <div class="text-2xl font-bold text-primary">4.9★</div>
          <div class="text-xs text-gray-600">User Rating</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Button } from '@/components/ui/button'
import { Camera, Sparkles } from 'lucide-vue-next'

import { router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';


const startScan = () => {
  router.visit(route('scan'))
}
</script>
