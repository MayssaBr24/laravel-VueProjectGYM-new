<template>
  <div class="min-h-screen bg-gray-900">
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
      <div class="absolute -top-40 -right-40 w-80 h-80 bg-green-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob"></div>
      <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-green-400 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000"></div>
    </div>

    <HeaderComponent />
    <HeroSection />
    <VideoSection />
    <BMICalculator />
    <CalorieCalculator />
    <ExerciseCarousel />
    <HealthTips />
    <Chatbot />
    <FooterComponent />
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import VideoSection from '../Components/Welcome/VideoSection.vue'

import HeaderComponent from '../Components/Welcome/Header.vue'
import HeroSection from '../Components/Welcome/Hero.vue'
import BMICalculator from '../Components/Welcome/BMICalculator.vue'
import CaloriesCalculator from '../Components/Welcome/CaloriesCalculator.vue'
import ExerciseCarousel from '../Components/Welcome/ExerciseCarousel.vue'
import HealthTips from '../Components/Welcome/HealthTips.vue'
import Chatbot from '../Components/Welcome/Chatbot.vue'
import FooterComponent from '../Components/Welcome/Footer.vue'

onMounted(() => {
  console.log('Welcome page loaded')
})
</script>

<style scoped>
@keyframes blob {
  0% { transform: translate(0px, 0px) scale(1); }
  33% { transform: translate(30px, -50px) scale(1.1); }
  66% { transform: translate(-20px, 20px) scale(0.9); }
  100% { transform: translate(0px, 0px) scale(1); }
}

.animate-blob {
  animation: blob 7s infinite;
}

.animation-delay-2000 {
  animation-delay: 2s;
}
</style>