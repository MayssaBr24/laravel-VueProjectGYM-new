<template>
  <div class="bg-white rounded-lg">
    <div class="flex items-center justify-between px-4 py-3">
      <h3 class="text-lg font-medium">Planning</h3>
    </div>
    <div class="px-4 pb-4">
      <div class="space-y-4">
        <div v-for="event in events" :key="event.id" class="border rounded-lg p-3">
          <p class="font-medium">{{ event.title }}</p>
          <p class="text-sm text-gray-500">{{ event.date }} - {{ event.time }}</p>
          <p class="text-sm">{{ event.coach }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  events: {
    type: Array,
    required: true
  }
});
</script>