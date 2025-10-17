<script setup>
import { onMounted, ref } from 'vue'
import QRCode from 'qrcode'

const props = defineProps({
  userId: Number,
  courseId: Number
})

const qrcodeCanvas = ref(null)

onMounted(() => {
  const data = JSON.stringify({
    user_id: props.userId,
    course_id: props.courseId
  })

  QRCode.toCanvas(qrcodeCanvas.value, data, {
    width: 250,
    color: {
      dark: '#ffffff',
      light: '#00000000'
    }
  }, (error) => {
    if (error) console.error(error)
  })
})
</script>

<template>
  <div class="flex justify-center items-center min-h-screen bg-gray-900 text-white">
    <div class="bg-gray-800 p-6 rounded-xl shadow-xl">
      <h1 class="text-2xl font-bold mb-4 text-center">Mon QR Code</h1>
      <canvas ref="qrcodeCanvas" class="mx-auto" />
    </div>
  </div>
</template>
