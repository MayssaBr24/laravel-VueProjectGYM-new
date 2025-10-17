<template>
  <section class="py-20 bg-gray-800">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h2 class="text-4xl font-black text-white mb-4">CALCULATEUR <span class="text-green-400">CALORIES</span></h2>
        <p class="text-xl text-gray-300">Découvrez vos besoins énergétiques journaliers personnalisés</p>
      </div>

      <div class="bg-gray-900 rounded-2xl p-8 border border-gray-700">
        <form @submit.prevent="calculateCalories" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
          <div>
            <label class="block text-sm font-bold text-gray-300 mb-2">NIVEAU D'ACTIVITÉ</label>
            <select v-model="activityLevel" required 
                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
              <option value="">Sélectionner</option>
              <option value="1.2">Sédentaire</option>
              <option value="1.375">Légèrement actif</option>
              <option value="1.55">Modérément actif</option>
              <option value="1.725">Très actif</option>
              <option value="1.9">Extrêmement actif</option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-bold text-gray-300 mb-2">OBJECTIF</label>
            <select v-model="goal" required 
                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
              <option value="">Sélectionner</option>
              <option value="lose">Perdre du poids</option>
              <option value="maintain">Maintenir le poids</option>
              <option value="gain">Prendre du poids</option>
            </select>
          </div>
          
          <div class="md:col-span-2">
            <button type="submit" 
                    class="w-full bg-gradient-to-r from-green-500 to-green-400 text-white py-4 rounded-lg text-lg font-bold hover:from-green-600 hover:to-green-500 transform hover:scale-105 transition-all duration-300">
              CALCULER MES BESOINS
            </button>
          </div>
        </form>

        <div v-if="calorieResult" class="mt-8 space-y-6">
          <div class="bg-gray-800 rounded-xl p-6 border border-gray-600">
            <div class="text-center mb-6">
              <div class="text-4xl font-black text-green-400 mb-2">{{ calorieResult }} CAL/JOUR</div>
              <div class="text-lg text-gray-300">BESOINS CALORIQUES RECOMMANDÉS</div>
            </div>
            
            <div class="bg-gray-700 rounded-lg p-4 mb-6 border border-gray-600">
              <h4 class="font-bold text-green-400 mb-4 text-center">RÉPARTITION DES MACRONUTRIMENTS</h4>
              <div class="grid grid-cols-3 gap-4 text-center">
                <div class="p-4 bg-gray-800 rounded-lg border border-gray-600">
                  <div class="text-2xl font-black text-red-400">{{ Math.round(calorieResult * 0.5 / 4) }}G</div>
                  <div class="text-sm text-gray-400">GLUCIDES (50%)</div>
                </div>
                <div class="p-4 bg-gray-800 rounded-lg border border-gray-600">
                  <div class="text-2xl font-black text-blue-400">{{ Math.round(calorieResult * 0.2 / 4) }}G</div>
                  <div class="text-sm text-gray-400">PROTÉINES (20%)</div>
                </div>
                <div class="p-4 bg-gray-800 rounded-lg border border-gray-600">
                  <div class="text-2xl font-black text-yellow-400">{{ Math.round(calorieResult * 0.3 / 9) }}G</div>
                  <div class="text-sm text-gray-400">LIPIDES (30%)</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const activityLevel = ref('')
const goal = ref('')
const calorieResult = ref(null)

const calculateCalories = () => {
  const bmiData = JSON.parse(localStorage.getItem('bmiData') || '{}')
  
  if (!bmiData.weight || !bmiData.height || !bmiData.age || !bmiData.gender) {
    alert('Veuillez d\'abord calculer votre IMC')
    return
  }
  
  let bmr
  if (bmiData.gender === 'male') {
    bmr = 88.362 + (13.397 * bmiData.weight) + (4.799 * bmiData.height) - (5.677 * bmiData.age)
  } else {
    bmr = 447.593 + (9.247 * bmiData.weight) + (3.098 * bmiData.height) - (4.330 * bmiData.age)
  }
  
  let tdee = bmr * parseFloat(activityLevel.value)
  
  if (goal.value === 'lose') {
    tdee -= 500
  } else if (goal.value === 'gain') {
    tdee += 500
  }
  
  calorieResult.value = Math.round(tdee)
  
  const calorieData = {
    calories: calorieResult.value,
    activityLevel: activityLevel.value,
    goal: goal.value,
    date: new Date().toISOString()
  }
  localStorage.setItem('calorieData', JSON.stringify(calorieData))
}

onMounted(() => {
  const savedData = localStorage.getItem('calorieData')
  if (savedData) {
    const data = JSON.parse(savedData)
    activityLevel.value = data.activityLevel
    goal.value = data.goal
    calorieResult.value = data.calories
  }
})
</script>