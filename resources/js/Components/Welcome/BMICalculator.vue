<template>
  <section id="imc" class="py-20 bg-gray-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h2 class="text-4xl font-black text-white mb-4">CALCULATEUR <span class="text-green-400">IMC</span></h2>
        <p class="text-xl text-gray-300">Découvrez votre indice de masse corporelle et obtenez des conseils personnalisés</p>
      </div>

      <div class="bg-gray-800 rounded-2xl p-8 border border-gray-700">
        <form @submit.prevent="calculateBMI" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
          <div>
            <label class="block text-sm font-bold text-gray-300 mb-2">POIDS (KG)</label>
            <input v-model="weight" type="number" step="0.1" required 
                   class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
          </div>
          
          <div>
            <label class="block text-sm font-bold text-gray-300 mb-2">TAILLE (CM)</label>
            <input v-model="height" type="number" required 
                   class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
          </div>
          
          <div>
            <label class="block text-sm font-bold text-gray-300 mb-2">ÂGE</label>
            <input v-model="age" type="number" required 
                   class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
          </div>
          
          <div>
            <label class="block text-sm font-bold text-gray-300 mb-2">SEXE</label>
            <select v-model="gender" required 
                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
              <option value="">Sélectionner</option>
              <option value="male">Homme</option>
              <option value="female">Femme</option>
            </select>
          </div>
          
          <div class="md:col-span-2">
            <button type="submit" 
                    class="w-full bg-gradient-to-r from-green-500 to-green-400 text-white py-4 rounded-lg text-lg font-bold hover:from-green-600 hover:to-green-500 transform hover:scale-105 transition-all duration-300">
              CALCULER MON IMC
            </button>
          </div>
        </form>

        <div v-if="bmiResult" class="mt-8 p-6 bg-gray-700 rounded-xl border border-gray-600 transform transition-all duration-500" :class="resultAnimation">
          <div class="text-center mb-6">
            <div class="text-4xl font-black mb-2" :class="bmiColorClass">{{ bmiResult.toFixed(1) }}</div>
            <div class="text-xl font-bold" :class="bmiColorClass">{{ bmiCategory }}</div>
          </div>
          
          <div class="bg-gray-800 rounded-lg p-4 border border-gray-600">
            <h4 class="font-bold text-green-400 mb-2">CONSEILS PERSONNALISÉS :</h4>
            <p class="text-gray-300">{{ personalizedAdvice }}</p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

const weight = ref('')
const height = ref('')
const age = ref('')
const gender = ref('')
const bmiResult = ref(null)
const resultAnimation = ref('')

const bmiCategory = computed(() => {
  if (!bmiResult.value) return ''
  
  if (bmiResult.value < 18.5) return 'INSUFFISANCE PONDÉRALE'
  if (bmiResult.value < 25) return 'POIDS NORMAL'
  if (bmiResult.value < 30) return 'SURPOIDS'
  return 'OBÉSITÉ'
})

const bmiColorClass = computed(() => {
  if (!bmiResult.value) return ''
  
  if (bmiResult.value < 18.5) return 'text-blue-400'
  if (bmiResult.value < 25) return 'text-green-400'
  if (bmiResult.value < 30) return 'text-yellow-400'
  return 'text-red-400'
})

const personalizedAdvice = computed(() => {
  if (!bmiResult.value) return ''
  
  const category = bmiCategory.value
  const genderText = gender.value === 'male' ? 'homme' : 'femme'
  
  switch (category) {
    case 'INSUFFISANCE PONDÉRALE':
      return `En tant qu'${genderText} de ${age.value} ans, il serait bénéfique d'augmenter votre apport calorique avec des aliments nutritifs et de consulter un professionnel de santé.`
    case 'POIDS NORMAL':
      return `Félicitations ! Votre poids est dans la norme. Continuez avec une alimentation équilibrée et une activité physique régulière.`
    case 'SURPOIDS':
      return `Il serait recommandé de réduire légèrement votre apport calorique et d'augmenter votre activité physique. Consultez un nutritionniste pour un plan personnalisé.`
    case 'OBÉSITÉ':
      return `Il est important de consulter un professionnel de santé pour établir un plan de perte de poids sûr et efficace adapté à votre situation.`
    default:
      return ''
  }
})

const calculateBMI = () => {
  const heightInMeters = height.value / 100
  const bmi = weight.value / (heightInMeters * heightInMeters)
  bmiResult.value = bmi
  
  const bmiData = {
    weight: weight.value,
    height: height.value,
    age: age.value,
    gender: gender.value,
    bmi: bmi,
    date: new Date().toISOString()
  }
  localStorage.setItem('bmiData', JSON.stringify(bmiData))
  
  resultAnimation.value = 'animate-pulse'
  setTimeout(() => {
    resultAnimation.value = ''
  }, 1000)
}

onMounted(() => {
  const savedData = localStorage.getItem('bmiData')
  if (savedData) {
    const data = JSON.parse(savedData)
    weight.value = data.weight
    height.value = data.height
    age.value = data.age
    gender.value = data.gender
  }
})
</script>