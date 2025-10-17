<template>
  <div :class="cardClasses" class="group relative overflow-hidden bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700/50 rounded-2xl p-6 hover:shadow-2xl transition-all duration-500 transform hover:scale-105">
    <div :class="gradientClasses" class="absolute inset-0 bg-gradient-to-br opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
    <div class="relative flex items-center justify-between">
      <div>
        <p class="text-gray-400 text-sm font-medium">{{ title }}</p>
        <p class="text-4xl font-bold text-white mt-2">{{ value }}</p>
        <p v-if="subtitle" :class="subtitleClasses" class="text-sm mt-2 flex items-center font-medium">
          <svg v-if="showTrend" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
          </svg>
          {{ subtitle }}
        </p>
        <div v-if="additionalInfo" class="space-y-1 mt-2">
          <div v-for="info in additionalInfo" :key="info.label" class="flex items-center space-x-2">
            <span class="text-gray-500 text-sm font-medium">{{ info.label }}:</span>
            <span :class="info.valueClass" class="font-semibold">{{ info.value }}</span>
          </div>
        </div>
      </div>
      <div :class="iconBgClasses" class="p-4 rounded-2xl backdrop-blur-sm">
        <component :is="iconComponent" :class="iconClasses" class="h-8 w-8" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  value: {
    type: [String, Number],
    required: true
  },
  subtitle: {
    type: String,
    default: ''
  },
  color: {
    type: String,
    default: 'emerald',
    validator: (value) => ['emerald', 'blue', 'purple', 'orange'].includes(value)
  },
  iconComponent: {
    type: Object,
    required: true
  },
  showTrend: {
    type: Boolean,
    default: false
  },
  additionalInfo: {
    type: Array,
    default: () => []
  }
})

const colorClasses = {
  emerald: {
    hover: 'hover:shadow-emerald-500/10',
    gradient: 'from-emerald-500/10 to-transparent',
    iconBg: 'bg-emerald-500/20',
    iconColor: 'text-emerald-400',
    subtitle: 'text-emerald-400'
  },
  blue: {
    hover: 'hover:shadow-blue-500/10',
    gradient: 'from-blue-500/10 to-transparent',
    iconBg: 'bg-blue-500/20',
    iconColor: 'text-blue-400',
    subtitle: 'text-gray-500'
  },
  purple: {
    hover: 'hover:shadow-purple-500/10',
    gradient: 'from-purple-500/10 to-transparent',
    iconBg: 'bg-purple-500/20',
    iconColor: 'text-purple-400',
    subtitle: 'text-emerald-400'
  },
  orange: {
    hover: 'hover:shadow-orange-500/10',
    gradient: 'from-orange-500/10 to-transparent',
    iconBg: 'bg-orange-500/20',
    iconColor: 'text-orange-400',
    subtitle: 'text-orange-400'
  }
}

const cardClasses = computed(() => colorClasses[props.color].hover)
const gradientClasses = computed(() => colorClasses[props.color].gradient)
const iconBgClasses = computed(() => colorClasses[props.color].iconBg)
const iconClasses = computed(() => colorClasses[props.color].iconColor)
const subtitleClasses = computed(() => colorClasses[props.color].subtitle)
</script>
