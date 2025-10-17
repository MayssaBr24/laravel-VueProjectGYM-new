<template>
  <div>
    <!-- Bouton flottant -->
    <div class="fixed bottom-6 right-6 z-50">
      <button @click="toggleChat"
              class="bg-gradient-to-r from-green-500 to-green-400 text-white p-4 rounded-full shadow-2xl hover:shadow-green-500/25 transform hover:scale-110 transition-all duration-300 animate-pulse">
        <svg v-if="!chatOpen" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
        <svg v-else class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
      <div class="absolute -top-2 -left-2 w-4 h-4 bg-red-500 rounded-full animate-ping"></div>
    </div>

    <!-- Chat ouvert -->
    <div v-if="chatOpen"
         class="fixed bottom-24 right-6 w-80 h-96 bg-gray-800 rounded-2xl shadow-2xl z-50 flex flex-col overflow-hidden transform transition-all duration-300 border border-gray-700">
      <div class="bg-gradient-to-r from-green-500 to-green-400 text-white p-4 flex items-center">
        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
          </svg>
        </div>
        <div>
          <h3 class="font-black">COACH SANTÉ IA</h3>
          <p class="text-sm opacity-90">Consultation gratuite 24/7</p>
        </div>
      </div>

      <div class="flex-1 p-4 overflow-y-auto space-y-4 bg-gray-900" ref="messagesContainer">
        <div v-for="(message, index) in messages" :key="index" :class="['flex', message.isUser ? 'justify-end' : 'justify-start']">
          <div
            :class="['max-w-xs px-4 py-2 rounded-2xl', message.isUser ? 'bg-green-500 text-white' : 'bg-gray-700 text-gray-100 border border-gray-600']">
            <p class="text-sm">{{ message.text }}</p>
            <span class="text-xs opacity-70">{{ formatTime(message.timestamp) }}</span>
          </div>
        </div>
        <div v-if="isTyping" class="flex justify-start">
          <div class="bg-gray-700 px-4 py-2 rounded-2xl border border-gray-600">
            <div class="flex space-x-1">
              <div class="w-2 h-2 bg-green-400 rounded-full animate-bounce"></div>
              <div class="w-2 h-2 bg-green-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
              <div class="w-2 h-2 bg-green-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
            </div>
          </div>
        </div>
      </div>

      <div v-if="showQuickActions" class="px-4 pb-2 bg-gray-900">
        <div class="flex flex-wrap gap-2">
          <button v-for="action in quickActions" :key="action" @click="sendQuickMessage(action)"
                  class="text-xs bg-gray-700 hover:bg-gray-600 text-gray-300 px-3 py-1 rounded-full transition-colors duration-200 border border-gray-600">
            {{ action }}
          </button>
        </div>
      </div>

      <div class="p-4 border-t border-gray-700 bg-gray-800">
        <div class="flex space-x-2">
          <input v-model="currentMessage" @keypress.enter="sendMessage" placeholder="Posez votre question..."
                 class="flex-1 px-3 py-2 bg-gray-700 border border-gray-600 rounded-full text-white placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm"/>
          <button @click="sendMessage"
                  class="bg-green-500 text-white p-2 rounded-full hover:bg-green-600 transition-colors duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, nextTick } from 'vue'
import axios from 'axios'

const chatOpen = ref(false)
const currentMessage = ref('')
const messages = ref([])
const isTyping = ref(false)
const showQuickActions = ref(true)
const messagesContainer = ref(null)


const toggleChat = () => {
  chatOpen.value = !chatOpen.value
  if (chatOpen.value && messages.value.length === 0) {
    setTimeout(() => {
      addBotMessage("Salut champion ! 💪 Je suis votre coach santé virtuel. Comment puis-je vous aider à atteindre vos objectifs aujourd'hui ? 🚀")
    }, 500)
  }
}

const addUserMessage = (text) => {
  messages.value.push({
    text,
    isUser: true,
    timestamp: new Date()
  })
  scrollToBottom()
}

const addBotMessage = (text) => {
  messages.value.push({
    text,
    isUser: false,
    timestamp: new Date()
  })
  scrollToBottom()
}

const scrollToBottom = () => {
  nextTick(() => {
    if (messagesContainer.value) {
      messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight
    }
  })
}

const formatTime = (date) => {
  return date.toLocaleTimeString('fr-FR', {
    hour: '2-digit',
    minute: '2-digit'
  })
}

const sendMessage = async () => {
  if (!currentMessage.value.trim()) return

  addUserMessage(currentMessage.value)
  const prompt = currentMessage.value
  currentMessage.value = ''
  isTyping.value = true
  showQuickActions.value = false

  try {
const response = await axios.post('http://localhost:8000/api/chat', { message: prompt });
    addBotMessage(response.data.reply)
  } catch (error) {
    addBotMessage("Désolé, une erreur est survenue. Merci de réessayer plus tard.")
  } finally {
    isTyping.value = false
    showQuickActions.value = true
  }
}

const sendQuickMessage = (action) => {
  addUserMessage(action)
  showQuickActions.value = false

  isTyping.value = true
  setTimeout(async () => {
    try {
      const response = await axios.post('http://localhost:8000/api/chat', { message: action })
      addBotMessage(response.data.reply)
    } catch {
      addBotMessage("Désolé, une erreur est survenue. Merci de réessayer plus tard.")
    }
    isTyping.value = false
    showQuickActions.value = true
  }, 1000)
}
</script>
