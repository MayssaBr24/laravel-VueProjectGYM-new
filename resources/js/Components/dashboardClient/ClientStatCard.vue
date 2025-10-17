<template>
  <div
    class="group relative overflow-hidden border border-gray-700/50 rounded-2xl p-6 hover:shadow-2xl transition-all duration-500 transform hover:scale-105"
    :class="[
      bgFrom && bgTo ? `bg-gradient-to-br from-${bgFrom} to-${bgTo}` : 'bg-gradient-to-br from-gray-800 to-gray-900',
      hoverShadow
    ]"
  >
    <div class="absolute inset-0" :class="overlayClass"></div>
    <div class="relative flex items-center justify-between">
      <div>
        <p class="text-gray-400 text-sm font-medium">{{ title }}</p>
        <p class="text-4xl font-bold text-white mt-2">{{ value }}</p>
        <p class="text-sm mt-2 flex items-center font-medium" :class="highlightColor">
          <slot name="icon" />
          <span>{{ subtext }}</span>
        </p>
      </div>
      <div class="p-4 rounded-2xl backdrop-blur-sm" :class="badgeBg">
        <slot name="badge-icon" />
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: String,
  value: [String, Number],
  subtext: String,
  bgFrom: String,
  bgTo: String,
  overlayClass: String,
  highlightColor: String,
  badgeBg: String,
  hoverShadow: String
})
</script>
