<template>
  <Transition name="slide-down">
    <div v-if="show" class="fixed top-4 left-1/2 transform -translate-x-1/2 w-full max-w-md z-50">
      <div class="bg-gray-800 text-gray-100 px-4 py-3 rounded-lg shadow-xl border-l-4 border-green-500 flex items-start">
        <div class="flex-grow">
          {{ flash.message }}
        </div>
        <button @click="show = false" class="ml-3 text-gray-400 hover:text-white">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

const show = ref(false);
const flash = ref(usePage().props.flash);

watch(() => usePage().props.flash, (newFlash) => {
  if (newFlash.message) {
    flash.value = newFlash;
    show.value = true;
    setTimeout(() => show.value = false, 5000);
  }
});
</script>

<style scoped>
.slide-down-enter-active,
.slide-down-leave-active {
  transition: all 0.3s ease;
}

.slide-down-enter-from {
  opacity: 0;
  transform: translate(-50%, -20px);
}

.slide-down-leave-to {
  opacity: 0;
  transform: translate(-50%, -20px);
}
</style>