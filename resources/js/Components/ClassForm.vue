<template>
  <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
      <div class="px-6 py-4">
        <div class="flex justify-between items-center">
          <h3 class="text-lg font-medium text-gray-900">Ajouter un Cours</h3>
          <button @click="$emit('close')" class="text-gray-400 hover:text-gray-500">
            <XMarkIcon class="h-6 w-6" />
          </button>
        </div>
      </div>
      <div class="px-6 py-4">
        <form class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Nom du cours</label>
            <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Coach</label>
            <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
          </div>
          <div class="flex justify-end space-x-3">
            <button type="button" @click="$emit('close')" class="px-4 py-2 text-sm font-medium text-gray-700">
              Annuler
            </button>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md">
              Enregistrer
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { XMarkIcon } from '@heroicons/vue/24/outline';
</script>