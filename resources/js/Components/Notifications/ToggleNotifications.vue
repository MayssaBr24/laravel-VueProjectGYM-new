<template>
  <div class="flex items-center">
    <span class="mr-3 text-sm font-medium text-gray-700">Notifications</span>
    <button 
      @click="toggleNotifications"
      :class="{'bg-green-600': enabled, 'bg-gray-400': !enabled}"
      class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
    >
      <span 
        :class="{'translate-x-5': enabled, 'translate-x-0': !enabled}"
        class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200"
      />
    </button>
  </div>
</template>

<script>
export default {
    data() {
        return {
            enabled: false
        };
    },
    mounted() {
        axios.get('/api/user/notification-preference')
            .then(response => {
                this.enabled = response.data.enabled;
            });
    },
    methods: {
        toggleNotifications() {
            this.enabled = !this.enabled;
            axios.post('/api/user/notification-preference', {
                enabled: this.enabled
            });
        }
    }
};
</script>