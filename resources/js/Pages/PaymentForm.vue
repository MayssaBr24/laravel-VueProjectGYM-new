<template>
  <div class="app-container">
    <div class="payment-card">
      <!-- En-tête avec icône -->
      <div class="payment-header">
        
        <h2 class="payment-title">Nouveau Paiement</h2>
        <p class="payment-subtitle">Enregistrez un nouveau paiement client</p>
      </div>

      <form @submit.prevent="submitPayment" class="payment-form">
        <!-- Recherche et sélection du client -->
        <div class="form-group">
          <label class="form-label">
            <span>Client</span>
            <span class="required">*</span>
          </label>
          <div class="search-container">
            <div class="search-input-wrapper">
              <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              <input
                v-model="clientSearch"
                type="text"
                class="search-input"
                placeholder="Rechercher un client..."
                @focus="showClientDropdown = true"
              >
            </div>
            
            <!-- Dropdown des clients avec recherche -->
            <div v-if="showClientDropdown && filteredClients.length > 0" class="dropdown">
              <div 
                v-for="user in filteredClients" 
                :key="user.id" 
                class="dropdown-item"
                @click="selectClient(user)"
              >
                <div class="client-info">
                  <span class="client-name">{{ user.name }}</span>
                  <span class="client-email">{{ user.email || 'Aucun email' }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Client sélectionné -->
          <div v-if="selectedClient" class="selected-client">
            <div class="selected-client-content">
              <span class="client-badge">
                {{ selectedClient.name }}
                <button 
                  type="button" 
                  class="remove-client"
                  @click="clearClient"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </span>
            </div>
          </div>
        </div>

        <!-- Type de paiement -->
        <div class="form-group">
          <label class="form-label">
            <span>Type de paiement</span>
            <span class="required">*</span>
          </label>
          <div class="radio-group">
            <label class="radio-option" :class="{ active: form.payment_type === 'course' }">
              <input 
                type="radio" 
                v-model="form.payment_type" 
                value="course"
                @change="resetTypeSelection"
                class="radio-input"
              >
              <span class="radio-custom"></span>
              <span class="radio-label">Cours</span>
            </label>
            <label class="radio-option" :class="{ active: form.payment_type === 'subscription' }">
              <input 
                type="radio" 
                v-model="form.payment_type" 
                value="subscription"
                @change="resetTypeSelection"
                class="radio-input"
              >
              <span class="radio-custom"></span>
              <span class="radio-label">Abonnement</span>
            </label>
          </div>
        </div>

        <!-- Cours -->
        <div v-if="form.payment_type === 'course'" class="form-group">
          <label class="form-label">
            <span>Type de cours</span>
            <span class="required">*</span>
          </label>
          <div class="card-grid">
            <div 
              v-for="course in courseTypes" 
              :key="course.id" 
              class="card-option"
              :class="{ selected: form.course_type_id === course.id }"
              @click="form.course_type_id = course.id"
            >
              <div class="card-content">
                <h3 class="card-title">{{ course.name }}</h3>
                <p class="card-price">{{ course.price }} TND</p>
                <div class="card-badge" v-if="course.duration_days">
                  {{ course.duration_days }} jours
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Abonnement -->
        <div v-if="form.payment_type === 'subscription'" class="form-group">
          <label class="form-label">
            <span>Type d'abonnement</span>
            <span class="required">*</span>
          </label>
          <div class="card-grid">
            <div 
              v-for="sub in subscriptionTypes" 
              :key="sub.id" 
              class="card-option"
              :class="{ selected: form.subscription_type_id === sub.id }"
              @click="form.subscription_type_id = sub.id"
            >
              <div class="card-content">
                <h3 class="card-title">{{ sub.name }}</h3>
                <p class="card-price">{{ sub.price }} TND</p>
                <div class="card-badge">
                  {{ sub.duration_days }} jours
                </div>
                <div class="card-features" v-if="sub.features">
                  <span v-for="feature in sub.features" :key="feature" class="feature">
                    {{ feature }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Résumé du paiement -->
        <div v-if="selectedProduct" class="payment-summary">
          <h3 class="summary-title">Résumé du paiement</h3>
          <div class="summary-content">
            <div class="summary-item">
              <span>Client:</span>
              <span>{{ selectedClient.name }}</span>
            </div>
            <div class="summary-item">
              <span>Type:</span>
              <span>{{ form.payment_type === 'course' ? 'Cours' : 'Abonnement' }}</span>
            </div>
            <div class="summary-item">
              <span>Produit:</span>
              <span>{{ selectedProduct.name }}</span>
            </div>
            <div class="summary-item total">
              <span>Montant:</span>
              <span class="amount">{{ selectedProduct.price }} TND</span>
            </div>
          </div>
        </div>

        <button 
          type="submit" 
          class="submit-btn"
          :disabled="processing || !isFormValid"
          :class="{ disabled: !isFormValid }"
        >
          <span v-if="!processing" class="btn-content">
            <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Confirmer le paiement
          </span>
          <span v-else class="btn-content">
            <svg class="animate-spin btn-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Traitement en cours...
          </span>
        </button>
      </form>

      <!-- Messages d'erreur/succès -->
      <div v-if="error" class="message error-message">
        <svg class="message-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        {{ error }}
      </div>
      <div v-if="success" class="message success-message">
        <svg class="message-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        {{ successMessage }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
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

const clientSearch = ref('')
const selectedClient = ref(null)
const showClientDropdown = ref(false)
const error = ref(null)
const success = ref(false)
const successMessage = ref('')
const processing = ref(false)

// Computed properties
const filteredClients = computed(() => {
  if (!clientSearch.value) return clients.value
  return clients.value.filter(user => 
    user.name.toLowerCase().includes(clientSearch.value.toLowerCase()) ||
    (user.email && user.email.toLowerCase().includes(clientSearch.value.toLowerCase()))
  )
})

const selectedProduct = computed(() => {
  if (form.value.payment_type === 'course' && form.value.course_type_id) {
    return courseTypes.value.find(course => course.id === form.value.course_type_id)
  }
  if (form.value.payment_type === 'subscription' && form.value.subscription_type_id) {
    return subscriptionTypes.value.find(sub => sub.id === form.value.subscription_type_id)
  }
  return null
})

const isFormValid = computed(() => {
  return selectedClient.value && 
         form.value.payment_type && 
         ((form.value.payment_type === 'course' && form.value.course_type_id) ||
          (form.value.payment_type === 'subscription' && form.value.subscription_type_id))
})

onMounted(async () => {
  try {
    const res = await axios.get('/payment/create')
    clients.value = res.data.clients
    courseTypes.value = res.data.courseTypes
    subscriptionTypes.value = res.data.subscriptionTypes
  } catch (e) {
    error.value = 'Erreur lors du chargement des données'
    console.error('Détails erreur:', e.response?.data || e.message)
  }
})

// Méthodes
const selectClient = (user) => {
  selectedClient.value = user
  form.value.user_id = user.id
  clientSearch.value = ''
  showClientDropdown.value = false
}

const clearClient = () => {
  selectedClient.value = null
  form.value.user_id = ''
  clientSearch.value = ''
}

const resetTypeSelection = () => {
  form.value.course_type_id = ''
  form.value.subscription_type_id = ''
}

const submitPayment = async () => {
  if (!isFormValid.value) return
  
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

    const response = await axios.post('/api/admin/payment', payload)

    success.value = true
    successMessage.value = response.data.message || 'Paiement enregistré avec succès'

    // Réinitialisation du formulaire
    resetTypeSelection()
    clearClient()
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

// Fermer le dropdown quand on clique ailleurs
const handleClickOutside = (event) => {
  if (!event.target.closest('.search-container')) {
    showClientDropdown.value = false
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})
</script>

<style scoped>
.app-container {
  min-height: 100vh;
  background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem;
}

.payment-card {
  width: 100%;
  max-width: 800px;
  background: #1e293b;
  border-radius: 1.5rem;
  padding: 2.5rem;
  box-shadow: 
    0 25px 50px -12px rgba(0, 0, 0, 0.5),
    0 0 0 1px rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.payment-header {
  text-align: center;
  margin-bottom: 2.5rem;
}

.header-icon {
  width: 4rem;
  height: 4rem;
  background: linear-gradient(135deg, #4ade80 0%, #22c55e 100%);
  border-radius: 1rem;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 1rem;
}

.header-icon .icon {
  width: 2rem;
  height: 2rem;
  color: white;
}

.payment-title {
  font-size: 2rem;
  font-weight: 700;
  background: linear-gradient(135deg, #4ade80 0%, #22c55e 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  margin-bottom: 0.5rem;
}

.payment-subtitle {
  color: #94a3b8;
  font-size: 1rem;
}

.payment-form {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.form-label {
  font-weight: 600;
  color: #e2e8f0;
  display: flex;
  gap: 0.25rem;
}

.required {
  color: #ef4444;
}

/* Recherche client */
.search-container {
  position: relative;
}

.search-input-wrapper {
  position: relative;
}

.search-icon {
  position: absolute;
  left: 1rem;
  top: 50%;
  transform: translateY(-50%);
  width: 1.25rem;
  height: 1.25rem;
  color: #64748b;
}

.search-input {
  width: 100%;
  padding: 0.75rem 1rem 0.75rem 3rem;
  background: #334155;
  border: 1px solid #475569;
  border-radius: 0.75rem;
  color: #f1f5f9;
  font-size: 1rem;
  transition: all 0.2s ease;
}

.search-input:focus {
  outline: none;
  border-color: #4ade80;
  box-shadow: 0 0 0 3px rgba(74, 222, 128, 0.1);
}

.dropdown {
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
  background: #334155;
  border: 1px solid #475569;
  border-radius: 0.75rem;
  margin-top: 0.5rem;
  max-height: 200px;
  overflow-y: auto;
  z-index: 10;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
}

.dropdown-item {
  padding: 0.75rem 1rem;
  cursor: pointer;
  transition: background-color 0.2s ease;
  border-bottom: 1px solid #475569;
}

.dropdown-item:last-child {
  border-bottom: none;
}

.dropdown-item:hover {
  background: #475569;
}

.client-info {
  display: flex;
  flex-direction: column;
}

.client-name {
  font-weight: 600;
  color: #e2e8f0;
}

.client-email {
  font-size: 0.875rem;
  color: #94a3b8;
}

.selected-client {
  margin-top: 0.5rem;
}

.client-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  background: #4ade80;
  color: #1e293b;
  padding: 0.5rem 1rem;
  border-radius: 2rem;
  font-weight: 600;
  font-size: 0.875rem;
}

.remove-client {
  background: none;
  border: none;
  color: inherit;
  cursor: pointer;
  padding: 0.125rem;
  border-radius: 50%;
  transition: background-color 0.2s ease;
}

.remove-client:hover {
  background: rgba(0, 0, 0, 0.1);
}

/* Radio group */
.radio-group {
  display: flex;
  gap: 1rem;
}

.radio-option {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1rem;
  background: #334155;
  border: 2px solid #475569;
  border-radius: 0.75rem;
  cursor: pointer;
  transition: all 0.2s ease;
  flex: 1;
}

.radio-option.active {
  border-color: #4ade80;
  background: rgba(74, 222, 128, 0.1);
}

.radio-input {
  display: none;
}

.radio-custom {
  width: 1.25rem;
  height: 1.25rem;
  border: 2px solid #64748b;
  border-radius: 50%;
  position: relative;
  transition: all 0.2s ease;
}

.radio-option.active .radio-custom {
  border-color: #4ade80;
  background: #4ade80;
}

.radio-option.active .radio-custom::after {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 0.5rem;
  height: 0.5rem;
  background: white;
  border-radius: 50%;
}

.radio-label {
  font-weight: 600;
  color: #e2e8f0;
}

/* Card grid */
.card-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
}

.card-option {
  background: #334155;
  border: 2px solid #475569;
  border-radius: 1rem;
  padding: 1.5rem;
  cursor: pointer;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.card-option:hover {
  border-color: #4ade80;
  transform: translateY(-2px);
}

.card-option.selected {
  border-color: #4ade80;
  background: rgba(74, 222, 128, 0.1);
  box-shadow: 0 10px 25px rgba(74, 222, 128, 0.2);
}

.card-content {
  text-align: center;
}

.card-title {
  font-weight: 700;
  color: #e2e8f0;
  margin-bottom: 0.5rem;
  font-size: 1.125rem;
}

.card-price {
  font-size: 1.5rem;
  font-weight: 800;
  color: #4ade80;
  margin-bottom: 0.5rem;
}

.card-badge {
  display: inline-block;
  background: #4ade80;
  color: #1e293b;
  padding: 0.25rem 0.75rem;
  border-radius: 1rem;
  font-size: 0.75rem;
  font-weight: 600;
}

.card-features {
  margin-top: 1rem;
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.feature {
  font-size: 0.875rem;
  color: #94a3b8;
}

/* Payment summary */
.payment-summary {
  background: #334155;
  border-radius: 1rem;
  padding: 1.5rem;
  border: 1px solid #475569;
}

.summary-title {
  font-weight: 700;
  color: #e2e8f0;
  margin-bottom: 1rem;
  font-size: 1.125rem;
}

.summary-content {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.summary-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-bottom: 0.75rem;
  border-bottom: 1px solid #475569;
}

.summary-item:last-child {
  border-bottom: none;
}

.summary-item.total {
  font-weight: 700;
  font-size: 1.125rem;
}

.amount {
  color: #4ade80;
  font-weight: 800;
}

/* Bouton de soumission */
.submit-btn {
  width: 100%;
  padding: 1rem 2rem;
  background: linear-gradient(135deg, #4ade80 0%, #22c55e 100%);
  color: #1e293b;
  border: none;
  border-radius: 1rem;
  font-weight: 700;
  font-size: 1.125rem;
  cursor: pointer;
  transition: all 0.3s ease;
  margin-top: 1rem;
}

.submit-btn:hover:not(.disabled) {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(74, 222, 128, 0.3);
}

.submit-btn.disabled {
  background: #475569;
  color: #94a3b8;
  cursor: not-allowed;
  transform: none;
}

.btn-content {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
}

.btn-icon {
  width: 1.25rem;
  height: 1.25rem;
}

/* Messages */
.message {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 1rem 1.5rem;
  border-radius: 1rem;
  margin-top: 1.5rem;
  font-weight: 600;
}

.message-icon {
  width: 1.25rem;
  height: 1.25rem;
}

.error-message {
  background: rgba(239, 68, 68, 0.1);
  color: #fca5a5;
  border: 1px solid rgba(239, 68, 68, 0.3);
}

.success-message {
  background: rgba(74, 222, 128, 0.1);
  color: #86efac;
  border: 1px solid rgba(74, 222, 128, 0.3);
}

/* Animations */
.animate-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

/* Responsive */
@media (max-width: 768px) {
  .app-container {
    padding: 1rem;
  }
  
  .payment-card {
    padding: 1.5rem;
  }
  
  .card-grid {
    grid-template-columns: 1fr;
  }
  
  .radio-group {
    flex-direction: column;
  }
}
</style>