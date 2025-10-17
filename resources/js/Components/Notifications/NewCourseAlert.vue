<template>
  <Transition name="slide-down">
    <div v-if="show" class="fixed top-4 left-1/2 transform -translate-x-1/2 w-full max-w-md z-50">
      <div class="bg-gray-800 text-gray-100 px-4 py-3 rounded-lg shadow-xl border-l-4 border-green-500">
        <div class="flex items-start">
          <div class="flex-shrink-0 pt-0.5">
            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div class="ml-3 flex-grow">
            <p class="text-sm font-medium">Nouveau cours disponible</p>
            <p class="text-sm text-gray-300">{{ notification.course.name }}</p>
          </div>
          <button @click="show = false" class="ml-3 text-gray-400 hover:text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const show = ref(false);
const notification = ref({ course: {} });

onMounted(() => {
  window.Echo.channel('public.courses')
    .listen('.NewCoursePublished', (data) => {
      notification.value = data.flash;
      show.value = true;
      setTimeout(() => show.value = false, 10000);
    });
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