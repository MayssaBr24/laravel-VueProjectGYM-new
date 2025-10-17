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
        <div class="grid grid-cols-1 gap-3 mb-2">
          
          <input 
            type="number" 
            v-model="manual.course_id" 
            placeholder="Course ID" 
            class="p-2 rounded bg-gray-700 border border-gray-600 text-white" 
          />
          <input 
            type="number" 
            v-model="manual.coach_id" 
            placeholder="Coach ID" 
            class="p-2 rounded bg-gray-700 border border-gray-600 text-white" 
          />
          
        </div>
        <button 
          @click="onManualSubmit"
          class="w-full bg-green-700 hover:bg-green-800 px-4 py-2 rounded transition disabled:opacity-50 mt-2"
          :disabled="!manual.course_id || !manual.coach_id"
        >
          Valider manuellement
        </button>
      </div>

      <!-- Résultat du scan -->
      <div v-if="scanResult" class="mt-4 p-4 bg-gray-700 rounded-lg border border-gray-600">
        <h3 class="font-bold mb-2 text-emerald-400">Données détectées</h3>
        <div class="grid grid-cols-2 gap-2 text-sm">
          
          <div>
            <p class="text-gray-400">Cours</p>
            <p class="text-white font-semibold">{{ scanResult.course_id }}</p>
          </div>
          <div>
            <p class="text-gray-400">Coach</p>
            <p class="text-white font-semibold">{{ scanResult.coach_id }}</p>
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
import { ref, onMounted } from 'vue'
import { QrcodeStream } from 'vue-qrcode-reader'

const isLoadingCamera = ref(true)
const cameraError = ref(false)
const scanResult = ref(null)
const manual = ref({ course_id: '', coach_id: '' })
const csrfToken = ref('') 
const participants = ref([]) // 🆕 Liste des participants à afficher

// Récupération du token CSRF après le rendu du DOM
onMounted(() => {
  const meta = document.querySelector('meta[name="csrf-token"]')
  if (meta) {
    csrfToken.value = meta.getAttribute('content')
  } else {
    console.error('Meta CSRF token introuvable !')
  }
})

// Récupérer la liste des participants depuis l'API
const fetchParticipants = async (course_id) => {
  try {
    const res = await fetch(`/coach/courses/${course_id}/participants-json`, {
      headers: { 'Accept': 'application/json' },
      credentials: 'include'
    })
    const data = await res.json()
    participants.value = data.participants
  } catch (err) {
    console.error('Erreur récupération participants', err)
  }
}

// QR Code: dessiner le bounding box
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

// QR Code: décodage
const onDecode = (result) => {
  try {
    const data = JSON.parse(result)
    if (data.course_id && data.coach_id) {
      scanResult.value = data
    } else {
      throw new Error()
    }
  } catch {
    alert('QR invalide. Format attendu : {"course_id":456,"coach_id":789}')
  }
}

// QR Code: initialisation
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

// Saisie manuelle
const onManualSubmit = () => {
  if (manual.value.course_id && manual.value.coach_id) {
    scanResult.value = {
      course_id: Number(manual.value.course_id),
      coach_id: Number(manual.value.coach_id),
      user_id: Number(manual.value.user_id),

    }
  } else {
    alert('Veuillez remplir tous les champs.')
  }
}

const markAttendance = async () => {
  if (!scanResult.value) return alert('Aucune donnée à confirmer.')

  try {
    const response = await fetch('/client/scan-qr', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken.value
      },
      credentials: 'include',
      body: JSON.stringify(scanResult.value)
    })

    const result = await response.json()

    if (!response.ok) {
      alert(result.message || 'Erreur lors de la confirmation')
      return
    }

    alert(result.message)

    // 🔹 Ajouter intrus directement à la liste participants
    const exists = participants.value.find(p => p.id === result.user_id)
    if (!exists) {
      participants.value.push({
        id: result.user_id,
        user: { name: result.user_name, email: result.user_email },
        attendance_status: 'Présent',
        intrus: true // 👈 marque qu'il s'agit d'un intrus
      })
    }

    scanResult.value = null
    manual.value = { course_id: '', coach_id: '' }

  } catch (err) {
    console.error(err)
    alert('Erreur réseau ou serveur.')
  }
}

</script>
