<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-900 to-gray-800 text-white flex items-center justify-center p-4">
    <div class="bg-gray-800 rounded-2xl p-6 shadow-2xl max-w-lg w-full border border-gray-700">
      <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-emerald-900/30 mb-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
          </svg>
        </div>
        <h2 class="text-2xl font-bold bg-gradient-to-r from-emerald-400 to-cyan-400 bg-clip-text text-transparent">Scanner QR Code</h2>
        <p class="text-gray-400 text-sm mt-1">Scannez le code QR ou saisissez manuellement</p>
      </div>

      <!-- Scanner avec caméra -->
      <div v-if="!cameraError" class="mb-6 relative">
        <div class="relative rounded-xl overflow-hidden border-2 border-gray-600 bg-black aspect-square flex items-center justify-center">
          <qrcode-stream 
            @decode="onDecode"
            @init="onInit"
            :track="paintBoundingBox"
            class="w-full h-full"
          />
          <div v-if="isLoadingCamera" class="absolute inset-0 flex items-center justify-center bg-black/80 backdrop-blur-sm z-10">
            <div class="text-center">
              <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-emerald-500 mb-2"></div>
              <p class="text-gray-300">Initialisation de la caméra...</p>
            </div>
          </div>
          <div class="absolute inset-0 border-2 border-emerald-400/30 rounded-xl pointer-events-none"></div>
          <div class="absolute top-3 left-3 right-3 flex justify-between">
            <span class="text-xs bg-black/70 text-emerald-300 px-2 py-1 rounded">Scanner actif</span>
            <span class="text-xs bg-black/70 text-gray-300 px-2 py-1 rounded">{{ scanCount }} scan(s)</span>
          </div>
        </div>
      </div>

      <!-- Message d'erreur caméra -->
      <div v-else class="bg-red-900/30 border border-red-800 text-red-200 p-4 rounded-xl mb-6">
        <div class="flex items-start">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-400 mt-0.5 mr-2 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
          </svg>
          <div>
            <p class="font-semibold">Impossible d'accéder à la caméra</p>
            <ul class="list-disc pl-5 mt-2 text-sm space-y-1">
              <li>Vérifiez les permissions de votre navigateur</li>
              <li>Assurez-vous qu'une caméra est disponible</li>
              <li>Connexion HTTPS requise pour cette fonctionnalité</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Séparateur -->
      <div class="relative flex items-center my-6">
        <div class="flex-grow border-t border-gray-700"></div>
        <span class="flex-shrink mx-4 text-gray-500 text-sm">OU</span>
        <div class="flex-grow border-t border-gray-700"></div>
      </div>

      <!-- Saisie manuelle -->
      <div class="mb-6">
        <h3 class="text-lg font-semibold mb-3 text-gray-300">Saisie manuelle</h3>
        <div class="grid grid-cols-1 gap-3 mb-4">
          <div>
            <label class="block text-sm font-medium mb-2 text-gray-400">ID du cours</label>
            <input 
              type="number" 
              v-model="manual.course_id" 
              placeholder="Ex: 123" 
              class="w-full p-3 rounded-lg bg-gray-700/50 border border-gray-600 text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition" 
            />
          </div>
          <div>
            <label class="block text-sm font-medium mb-2 text-gray-400">ID du coach</label>
            <input 
              type="number" 
              v-model="manual.coach_id" 
              placeholder="Ex: 456" 
              class="w-full p-3 rounded-lg bg-gray-700/50 border border-gray-600 text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition" 
            />
          </div>
        </div>
        <button 
          @click="onManualSubmit"
          class="w-full bg-gradient-to-r from-emerald-600 to-cyan-600 hover:from-emerald-700 hover:to-cyan-700 px-4 py-3 rounded-lg transition-all duration-300 font-medium disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
          :disabled="!manual.course_id || !manual.coach_id"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
          </svg>
          Valider manuellement
        </button>
      </div>

      <!-- Résultat du scan -->
      <div v-if="scanResult" class="mt-6 p-4 bg-gradient-to-br from-gray-700/50 to-gray-800/50 rounded-xl border border-gray-600 backdrop-blur-sm">
        <h3 class="font-bold mb-3 text-lg text-emerald-400 flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
          </svg>
          Données détectées
        </h3>
        <div class="grid grid-cols-2 gap-3 mb-4">
          <div class="bg-gray-700/30 p-3 rounded-lg">
            <p class="text-gray-400 text-sm">Cours</p>
            <p class="text-white font-semibold">{{ scanResult.course_id }}</p>
          </div>
          <div class="bg-gray-700/30 p-3 rounded-lg">
            <p class="text-gray-400 text-sm">Coach</p>
            <p class="text-white font-semibold">{{ scanResult.coach_id }}</p>
          </div>
        </div>

        <button 
          @click="markAttendance"
          class="w-full bg-gradient-to-r from-emerald-500 to-emerald-700 hover:from-emerald-600 hover:to-emerald-800 px-4 py-3 rounded-lg text-white font-medium transition-all duration-300 flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/20"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
          </svg>
          Confirmer la présence
        </button>
      </div>

      <!-- Liste des participants -->
      <div v-if="participants.length > 0" class="mt-6">
        <h3 class="font-bold mb-3 text-lg text-cyan-400 flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
          </svg>
          Participants
        </h3>
        <div class="max-h-60 overflow-y-auto space-y-2 pr-2">
          <div 
            v-for="participant in participants" 
            :key="participant.id" 
            class="p-3 rounded-lg bg-gray-700/30 border border-gray-600 flex justify-between items-center"
            :class="{'border-l-4 border-l-emerald-500': participant.intrus}"
          >
            <div>
              <p class="font-medium">{{ participant.user.name }}</p>
              <p class="text-sm text-gray-400">{{ participant.user.email }}</p>
            </div>
            <div class="flex items-center gap-2">
              <span class="text-xs px-2 py-1 rounded-full bg-emerald-900/30 text-emerald-300" v-if="participant.intrus">Intrus</span>
              <span class="text-sm text-emerald-400">{{ participant.attendance_status }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { QrcodeStream } from 'vue-qrcode-reader'

const isLoadingCamera = ref(true)
const cameraError = ref(false)
const scanResult = ref(null)
const manual = ref({ course_id: '', coach_id: '' })
const csrfToken = ref('')
const participants = ref([])
const scanCount = ref(0)

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
    ctx.strokeStyle = '#10b981'
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
      scanCount.value += 1
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
    console.error('Erreur caméra:', err)
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

// Surveiller les changements de scanResult pour charger les participants
watch(scanResult, (newValue) => {
  if (newValue && newValue.course_id) {
    fetchParticipants(newValue.course_id)
  }
})
</script>