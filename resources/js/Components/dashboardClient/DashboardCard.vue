<template>

  <div
    class="group relative overflow-hidden rounded-2xl p-6 border border-gray-700/50 bg-gray-800"
  >
    <!-- Titre de la carte -->
    <p class="text-gray-400 text-sm font-medium">{{ title }}</p>

    <!-- Valeur principale -->
    <p class="text-4xl font-bold text-white mt-2">{{ value }}</p>

    <!-- Footer -->
    <p v-if="footer" class="text-sm mt-2 text-emerald-400 font-medium">
      <slot name="footer">{{ footer }}</slot>
    </p>

    <!-- Icône à droite -->
    <div class="absolute top-6 right-6">
      <component v-if="iconComponent" :is="iconComponent" class="h-8 w-8 text-white/70" />
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

import {
  CalendarIcon,
  ClockIcon,
  CheckCircleIcon,
  CalendarDaysIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
  title: { type: String, required: true },
  value: [String, Number],
  footer: String,
  icon: String,
});

const iconsMap = {
  calendar: CalendarIcon,
  'calendar-check': CalendarDaysIcon,
  clock: ClockIcon,
  'check-circle': CheckCircleIcon,
};

const iconComponent = computed(() => iconsMap[props.icon] || null);
</script>

<style scoped>
/* Tu peux ajouter ici des styles si besoin */
</style>
