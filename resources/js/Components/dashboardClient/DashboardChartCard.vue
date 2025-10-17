<template>
  <div class="group relative overflow-hidden rounded-2xl p-6 border border-gray-700/50 bg-gray-800">
    <!-- Titre de la carte -->
    <p class="text-gray-400 text-sm font-medium">{{ title }}</p>

    <!-- Graphique -->
    <div class="mt-4 h-40">
      <canvas ref="chartCanvas"></canvas>
    </div>

    <!-- Footer -->
    <p v-if="footer" class="text-sm mt-4 text-emerald-400 font-medium">
      <slot name="footer">{{ footer }}</slot>
    </p>

    <!-- Icône à droite -->
    <div class="absolute top-6 right-6">
      <component v-if="iconComponent" :is="iconComponent" class="h-8 w-8 text-white/70" />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, computed } from 'vue';
import Chart from 'chart.js/auto';
import {
  CalendarIcon,
  ClockIcon,
  CheckCircleIcon,
  CalendarDaysIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
  title: { type: String, required: true },
  data: Object, // { labels: [], datasets: [] }
  footer: String,
  icon: String,
  chartType: { type: String, default: 'bar' },
});

const iconsMap = {
  calendar: CalendarIcon,
  'calendar-check': CalendarDaysIcon,
  clock: ClockIcon,
  'check-circle': CheckCircleIcon,
};

const iconComponent = computed(() => iconsMap[props.icon] || null);
const chartCanvas = ref(null);
let chartInstance = null;

const initChart = () => {
  if (!props.data || !chartCanvas.value) return;

  if (chartInstance) {
    chartInstance.destroy();
  }

  const ctx = chartCanvas.value.getContext('2d');
  chartInstance = new Chart(ctx, {
    type: props.chartType,
    data: props.data,
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false,
        },
      },
      scales: {
        y: {
          beginAtZero: true,
          grid: {
            color: 'rgba(55, 65, 81, 0.5)',
          },
          ticks: {
            color: '#9CA3AF',
          },
        },
        x: {
          grid: {
            display: false,
          },
          ticks: {
            color: '#9CA3AF',
          },
        },
      },
    },
  });
};

onMounted(initChart);
watch(() => props.data, initChart, { deep: true });
</script>