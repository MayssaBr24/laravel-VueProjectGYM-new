<template>
  <div class="full-screen-center">

  <div class="payment-container">
    <h2 class="payment-title">Ajouter un paiement</h2>

    <form @submit.prevent="submitPayment" class="payment-form">
      <!-- Sélection du client -->
      <div class="form-group">
        <label for="user" class="form-label">Client</label>
        <select 
          v-model="form.user_id" 
          class="form-select"
          required
        >
          <option value="" disabled>-- Choisir un client --</option>
          <option 
            v-for="user in clients" 
            :key="user.id" 
            :value="user.id"
          >
            {{ user.name }} 
          </option>
        </select>
      </div>

      <!-- Type de paiement -->
      <div class="form-group">
        <label class="form-label">Type de paiement</label>
        <select 
          v-model="form.payment_type" 
          class="form-select"
          required
          @change="resetTypeSelection"
        >
          <option value="" disabled>-- Choisir type --</option>
          <option value="course">Cours</option>
          <option value="subscription">Abonnement</option>
        </select>
      </div>

      <!-- Cours -->
      <div v-if="form.payment_type === 'course'" class="form-group">
        <label class="form-label">Type de cours</label>
        <select 
          v-model="form.course_type_id" 
          class="form-select"
          required
        >
          <option value="" disabled>-- Choisir un cours --</option>
          <option 
            v-for="course in courseTypes" 
            :key="course.id" 
            :value="course.id"
          >
            {{ course.name }} - {{ course.price }} €
          </option>
        </select>
      </div>

      <!-- Abonnement -->
      <template v-if="form.payment_type === 'subscription'">
  <div class="form-group">
    <label class="form-label">Type d'abonnement</label>
    <select 
      v-model="form.subscription_type_id" 
      class="form-select"
      required
    >
      <option value="" disabled>-- Choisir abonnement --</option>
      <option 
        v-for="sub in subscriptionTypes" 
        :key="sub.id" 
        :value="sub.id"
      >
        {{ sub.name }} - {{ sub.price }} € ({{ sub.duration_days }} jours)
      </option>
    </select>
  </div>

</template>



      <button 
        type="submit" 
        class="submit-btn"
        :disabled="processing"
      >
        <span v-if="!processing">Enregistrer le paiement</span>
        <span v-else class="flex items-center justify-center">
          <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          Enregistrement...
        </span>
      </button>
    </form>

    <!-- Messages d'erreur/succès -->
    <div v-if="error" class="error-message">
      {{ error }}
    </div>
    <div v-if="success" class="success-message">
      {{ successMessage }}
    </div>
  </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const clients = ref([])
const courseTypes = ref([])
const subscriptionTypes = ref([])

const form = ref({
  user_id: '',
  payment_type: '',
  course_type_id: '',
  subscription_type_id: '',

})

const error = ref(null)
const success = ref(false)
const successMessage = ref('')
const processing = ref(false)


onMounted(async () => {
  try {
    const res = await axios.get('/payment/create')
    console.log('Données reçues:', res.data) 
      // <=== Ajoute ça pour debug
    clients.value = res.data.clients
    courseTypes.value = res.data.courseTypes
    subscriptionTypes.value = res.data.subscriptionTypes
  } catch (e) {
    error.value = 'Erreur lors du chargement des données'
    console.error('Détails erreur:', e.response?.data || e.message)
  }
})


const resetTypeSelection = () => {
  form.value.course_type_id = ''
  form.value.subscription_type_id = ''
}
const submitPayment = async () => {
  error.value = null
  success.value = false
  processing.value = true

  try {
    const payload = {
      user_id: form.value.user_id,
      payment_type: form.value.payment_type
    }

    if (form.value.payment_type === 'course') {
      payload.course_type_id = form.value.course_type_id
    }

    if (form.value.payment_type === 'subscription') {
      payload.subscription_type_id = form.value.subscription_type_id
    }

    console.log('Payload envoyé:', payload)

    const response = await axios.post('/api/admin/payment', payload)

    success.value = true
    successMessage.value = response.data.message || 'Paiement enregistré avec succès'

    // Réinitialisation du formulaire
    resetTypeSelection()
    form.value.user_id = ''
    form.value.payment_type = ''

  } catch (e) {
    if (e.response?.data?.message) {
      error.value = e.response.data.message
    } else {
      error.value = 'Une erreur est survenue lors de l\'enregistrement'
    }
    console.error(e)
  } finally {
    processing.value = false
  }
}

</script>

<style scoped>
.payment-container {
 margin: 5rem auto; 
  max-width: 700px;
  margin: 0 auto;
  padding: 2rem;
  background-color: #1f2937; /* Gris foncé */
  border-radius: 1rem;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
  color: #f9fafb;
}

.payment-title {
  font-size: 1.75rem;
  font-weight: 700;
  color: #4ade80; /* Vert émeraude */
  margin-bottom: 2rem;
  text-align: center;
}

.payment-form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}
.full-screen-center {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background-color: #111827; /* noir bleuté */
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.form-label {
  font-weight: 600;
  color: #d1d5db;
}

.form-select {
  padding: 0.75rem 1rem;
  background-color: #374151; /* Gris moyen */
  border: 1px solid #4ade80;
  border-radius: 0.5rem;
  color: #f9fafb;
  font-weight: 500;
  transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.form-select:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(74, 222, 128, 0.5);
  border-color: #4ade80;
}

.submit-btn {
  padding: 0.75rem 1.25rem;
  background-color: #4ade80;
  color: #1f2937;
  font-weight: 700;
  border: none;
  border-radius: 0.5rem;
  cursor: pointer;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  transition: background-color 0.3s ease;
}

.submit-btn:hover {
  background-color: #22c55e;
}

.submit-btn:disabled {
  background-color: #9ca3af;
  cursor: not-allowed;
}

.error-message {
  margin-top: 1rem;
  padding: 0.75rem 1rem;
  background-color: #f87171;
  color: #fff;
  border-radius: 0.5rem;
  font-weight: 600;
  text-align: center;
}

.success-message {
  margin-top: 1rem;
  padding: 0.75rem 1rem;
  background-color: #4ade80;
  color: #1f2937;
  border-radius: 0.5rem;
  font-weight: 600;
  text-align: center;
}
</style>