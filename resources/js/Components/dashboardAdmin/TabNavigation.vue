<template>
  <div class="border-b border-gray-700/50 bg-gray-800/50">
    <nav class="flex -mb-px">
      <button
        v-for="tab in tabs"
        :key="tab.id"
        @click="$emit('tab-change', tab.id)"
        :class="getTabClasses(tab.id)"
        class="relative whitespace-nowrap py-6 px-8 border-b-2 font-semibold text-sm transition-all duration-300"
      >
        <span class="flex items-center space-x-3">
          <component :is="tab.icon" class="h-5 w-5" />
          <span>{{ tab.label }}</span>
        </span>
      </button>
    </nav>
  </div>
</template>

<script setup>
const props = defineProps({
  activeTab: {
    type: String,
    required: true
  },
  tabs: {
    type: Array,
    required: true
  }
})

defineEmits(['tab-change'])

const getTabClasses = (tabId) => {
  return props.activeTab === tabId
    ? 'border-emerald-500 text-emerald-400 bg-emerald-500/10'
    : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-600'
}
</script>
