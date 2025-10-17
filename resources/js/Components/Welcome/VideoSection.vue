<template>
  <section class="py-20 bg-gray-800">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h2 class="text-4xl font-black text-white mb-4">NOTRE <span class="text-green-400">SALLE DE SPORT</span></h2>
        <p class="text-xl text-gray-300">Découvrez l'ambiance et les équipements haut de gamme</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div class="relative">
          <div class="absolute inset-0 bg-gradient-to-r from-green-500/20 to-green-400/20 rounded-2xl transform rotate-2"></div>
          <div class="relative bg-gray-900 rounded-2xl overflow-hidden transform -rotate-2 hover:rotate-0 transition-transform duration-500 shadow-2xl">
            <!-- Video Container -->
            <div class="aspect-video bg-black relative group cursor-pointer" @click="playVideo">
              <!-- Video Element - Remplacez par votre propre vidéo -->
              <video 
                ref="videoPlayer"
                poster="https://images.unsplash.com/photo-1571902943202-507ec2618e8f?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80"
                class="w-full h-full object-cover"
                loop
                preload="metadata"
                muted
              >
                <source src="https://assets.mixkit.co/videos/preview/mixkit-people-training-in-a-modern-gym-15803-large.mp4" type="video/mp4">
                Votre navigateur ne supporte pas les vidéos HTML5.
              </video>
              
              <!-- Play Button Overlay -->
              <div 
                v-if="!isPlaying" 
                class="absolute inset-0 flex items-center justify-center bg-black/30 transition-all duration-300 group-hover:bg-black/10"
              >
                <div class="text-center p-6 bg-black/50 rounded-xl">
                  <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center mb-4 mx-auto transform group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-8 h-8 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M8 5v14l11-7z"/>
                    </svg>
                  </div>
                  <p class="text-white font-bold text-lg">VISITE GUIDÉE</p>
                  <p class="text-gray-300 text-sm mt-1">Découvrez nos installations</p>
                </div>
              </div>
              
              <!-- Video Controls -->
              <div 
                v-show="isPlaying"
                class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
              >
                <div class="flex items-center space-x-4">
                  <button @click.stop="togglePlay" class="text-white hover:text-green-400 transition-colors">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                      <path v-if="isPlaying" d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                      <path v-else d="M8 5v14l11-7z"/>
                    </svg>
                  </button>
                  <div class="flex-1 bg-gray-600 rounded-full h-1.5">
                    <div 
                      class="bg-green-500 h-1.5 rounded-full" 
                      :style="{ width: progress + '%' }"
                    ></div>
                  </div>
                  <button @click.stop="toggleMute" class="text-white hover:text-green-400 transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                      <path v-if="isMuted" d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/>
                      <path v-else d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/>
                    </svg>
                  </button>
                  <button @click.stop="toggleFullscreen" class="text-white hover:text-green-400 transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z"/>
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="space-y-6">
          <div class="flex items-start space-x-4">
            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
              </svg>
            </div>
            <div>
              <h3 class="text-xl font-bold text-white mb-2">ÉQUIPEMENTS PRO</h3>
              <p class="text-gray-300">Matériel Technogym dernier cri pour des entraînements optimaux.</p>
            </div>
          </div>

          <div class="flex items-start space-x-4">
            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <div>
              <h3 class="text-xl font-bold text-white mb-2">OUVERTURE 7J/7</h3>
              <p class="text-gray-300">Accès illimité de 6h à minnit pour s'entraîner quand vous voulez.</p>
            </div>
          </div>

          <div class="flex items-start space-x-4">
            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
              </svg>
            </div>
            <div>
              <h3 class="text-xl font-bold text-white mb-2">COACHING COLLECTIF</h3>
              <p class="text-gray-300">Séances en petit groupe pour une motivation maximale.</p>
            </div>
          </div>

          <div class="pt-6">
            <button class="bg-gradient-to-r from-green-500 to-green-400 text-white px-8 py-3 rounded-full font-bold hover:from-green-600 hover:to-green-500 transform hover:scale-105 transition-all duration-300">
              RÉSERVER UNE VISITE
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const videoPlayer = ref(null)
const isPlaying = ref(false)
const isMuted = ref(true)
const progress = ref(0)
let progressInterval = null

const playVideo = () => {
  if (videoPlayer.value.paused) {
    videoPlayer.value.play()
    isPlaying.value = true
    startProgressTracker()
  }
}

const togglePlay = () => {
  if (videoPlayer.value.paused) {
    videoPlayer.value.play()
    isPlaying.value = true
    startProgressTracker()
  } else {
    videoPlayer.value.pause()
    isPlaying.value = false
    stopProgressTracker()
  }
}

const toggleMute = () => {
  videoPlayer.value.muted = !videoPlayer.value.muted
  isMuted.value = videoPlayer.value.muted
}

const toggleFullscreen = () => {
  if (videoPlayer.value.requestFullscreen) {
    videoPlayer.value.requestFullscreen()
  } else if (videoPlayer.value.webkitRequestFullscreen) {
    videoPlayer.value.webkitRequestFullscreen()
  }
}

const startProgressTracker = () => {
  progressInterval = setInterval(() => {
    progress.value = (videoPlayer.value.currentTime / videoPlayer.value.duration) * 100
  }, 200)
}

const stopProgressTracker = () => {
  clearInterval(progressInterval)
}

onMounted(() => {
  videoPlayer.value.addEventListener('ended', () => {
    isPlaying.value = false
    stopProgressTracker()
  })
})

onUnmounted(() => {
  stopProgressTracker()
  if (videoPlayer.value) {
    videoPlayer.value.removeEventListener('ended')
  }
})
</script>