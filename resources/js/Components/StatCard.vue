<template>
  <div class="bg-white p-4 rounded-lg shadow" :class="`border-t-4 border-${color}-500`">
    <div class="flex items-center">
      <div class="p-3 rounded-full bg-opacity-20" :class="`bg-${color}-100 text-${color}-600`">
        <component :is="resolveIcon(icon)" class="h-6 w-6" />
      </div>
      <div class="ml-4">
        <p class="text-sm font-medium text-gray-500">{{ title }}</p>
        <div class="flex items-center">
          <p class="text-2xl font-semibold text-gray-900">
            {{ format === 'currency' ? '€' + value : value }}
          </p>
          <span v-if="trend" class="ml-2 text-xs flex items-center" :class="trend.startsWith('+') ? 'text-green-500' : 'text-red-500'">
            {{ trend }}
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
  title: String,
  value: [String, Number],
  icon: String,
  trend: String,
  color: {
    type: String,
    default: 'blue'
  },
  format: String
});

const resolveIcon = (iconName) => {
  const icons = {
    users: 'UsersIcon',
    calendar: 'CalendarIcon',
    'currency-euro': 'CurrencyEuroIcon',
    ticket: 'TicketIcon',
    'user-add': 'UserAddIcon'
  };
  return icons[iconName] || 'QuestionMarkCircleIcon';
};
</script>