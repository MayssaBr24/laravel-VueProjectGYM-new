<template>
  <div class="min-h-screen bg-gray-900 text-white flex items-center justify-center p-6">
    <div class="bg-gray-800 rounded-xl p-6 shadow-xl max-w-lg w-full">
      <h2 class="text-xl font-bold mb-4 text-center">Scanner QR Code</h2>

      <!-- Scanner avec caméra -->
      <div v-if="!cameraError" class="mb-4 relative">
        <qrcode-stream 
          @decode="onDecode"
          @init="onInit"
          :track="paintBoundingBox"
          class="rounded-lg overflow-hidden border border-gray-600"
        />
        <div v-if="isLoadingCamera" class="absolute inset-0 flex items-center justify-center bg-black/50">
          <p class="animate-pulse">Chargement de la caméra...</p>
        </div>
      </div>

      <!-- Message d'erreur caméra -->
      <div v-else class="bg-red-900/50 text-red-300 p-3 rounded mb-4 text-sm">
        <p class="font-semibold">⚠ Impossible d'accéder à la caméra.</p>
        <ul class="list-disc pl-5 mt-2">
          <li>Permissions non accordées</li>
          <li>Pas de caméra disponible</li>
          <li>Connexion HTTPS requise</li>
        </ul>
      </div>

      <!-- Fallback manuel -->
      <div class="mb-6">
        <label class="block text-sm font-medium mb-1">Saisie manuelle :</label>
        <div class="grid grid-cols-2 gap-3 mb-2">
          <input type="number" v-model="manual.user_id" placeholder="User ID" class="p-2 rounded bg-gray-700 border border-gray-600 text-white" />
          <input type="number" v-model="manual.course_id" placeholder="Course ID" class="p-2 rounded bg-gray-700 border border-gray-600 text-white" />
        </div>
        <button 
          @click="onManualSubmit"
          class="w-full bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded transition disabled:opacity-50"
          :disabled="!manual.user_id || !manual.course_id"
        >
          Valider manuellement
        </button>
      </div>

      <!-- Résultat du scan -->
      <div v-if="scanResult" class="mt-4 p-4 bg-gray-700 rounded-lg border border-gray-600">
        <h3 class="font-bold mb-2 text-emerald-400">Données détectées</h3>
        <div class="grid grid-cols-2 gap-2 text-sm">
          <div>
            <p class="text-gray-400">Utilisateur</p>
            <p class="text-white font-semibold">{{ scanResult.user_id }}</p>
          </div>
          <div>
            <p class="text-gray-400">Cours</p>
            <p class="text-white font-semibold">{{ scanResult.course_id }}</p>
          </div>
        </div>

        <button 
          @click="markAttendance"
          class="mt-4 w-full bg-emerald-600 hover:bg-emerald-700 px-4 py-2 rounded text-white transition"
        >
          Confirmer la présence
        </button>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref } from 'vue'
import { QrcodeStream } from 'vue-qrcode-reader'
import { usePermission } from '@vueuse/core'

const isLoadingCamera = ref(true)
const cameraError = ref(false)
const scanResult = ref(null)
const manual = ref({ user_id: '', course_id: '' })

// Fonction pour dessiner un cadre vert autour du QR code détecté
const paintBoundingBox = (detectedCodes, ctx) => {
  for (const code of detectedCodes) {
    const [firstPoint, ...points] = code.cornerPoints
    ctx.strokeStyle = '#00ff00'
    ctx.lineWidth = 4
    ctx.beginPath()
    ctx.moveTo(firstPoint.x, firstPoint.y)
    for (const { x, y } of points) ctx.lineTo(x, y)
    ctx.lineTo(firstPoint.x, firstPoint.y)
    ctx.closePath()
    ctx.stroke()
  }
}

// Lorsqu’un QR code est détecté
const onDecode = (result) => {
  try {
    const data = JSON.parse(result)
    if (data.user_id && data.course_id) {
      scanResult.value = data
    } else {
      throw new Error()
    }
  } catch {
    alert('QR invalide. Format attendu : {"user_id":123,"course_id":456}')
  }
}

// Initialisation de la caméra
const onInit = async (promise) => {
  try {
    await promise
    cameraError.value = false
  } catch (err) {
    cameraError.value = true
    alert(err.message || 'Erreur lors de l’activation de la caméra.')
  } finally {
    isLoadingCamera.value = false
  }
}

// Validation manuelle
const onManualSubmit = () => {
  if (manual.value.user_id && manual.value.course_id) {
    scanResult.value = {
      user_id: Number(manual.value.user_id),
      course_id: Number(manual.value.course_id)
    }
  } else {
    alert('Veuillez remplir les deux champs.')
  }
}

// Confirmation de présence (via API Laravel)
const markAttendance = async () => {
  try {
    const response = await fetch('/admin/verify-attendance', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({
        user_id: scanResult.value.user_id,
        course_id: scanResult.value.course_id
      })
    })

    const result = await response.json()

    if (!response.ok) {
      alert(result.message || 'Erreur lors de la confirmation')
      return
    }

    alert(result.message)
    scanResult.value = null
    manual.value = { user_id: '', course_id: '' }
  } catch (err) {
    console.error(err)
    alert('Erreur de réseau ou serveur.')
  }
}
</script>
