<template>
  <AdminLayout>
    <div class="px-6 py-8">
      <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Gestion des membres</h1>
        <Link 
          href="/admin/members/create" 
          class="btn-primary"
        >
          Ajouter un membre
        </Link>
      </div>

      <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left">Nom</th>
              <th class="px-6 py-3 text-left">Email</th>
              <th class="px-6 py-3 text-left">Rôle</th>
              <th class="px-6 py-3 text-left">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="member in members.data" :key="member.id">
              <td class="px-6 py-4">{{ member.name }}</td>
              <td class="px-6 py-4">{{ member.email }}</td>
              <td class="px-6 py-4">
                <span :class="roleBadgeClass(member.role)" class="px-2 py-1 rounded-full text-xs">
                  {{ member.role }}
                </span>
              </td>
              <td class="px-6 py-4 flex space-x-2">
                <Link 
                  :href="`/admin/members/${member.id}/edit`" 
                  class="btn-edit"
                >
                  Éditer
                </Link>
                <button 
                  @click="confirmDelete(member)" 
                  class="btn-danger"
                >
                  Supprimer
                </button>
              </td>
            </tr>
          </tbody>
        </table>

        <Pagination :links="members.links" class="px-6 py-4" />
      </div>

      <!-- Confirmation de suppression -->
      <ConfirmationDialog
        :show="showDeleteDialog"
        @close="showDeleteDialog = false"
        @confirm="deleteMember"
      >
        Êtes-vous sûr de vouloir supprimer ce membre ?
      </ConfirmationDialog>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import ConfirmationDialog from '@/Components/ConfirmationDialog.vue'

const props = defineProps({
  members: Object
})

const showDeleteDialog = ref(false)
const memberToDelete = ref(null)

const roleBadgeClass = (role) => {
  return {
    'bg-green-100 text-green-800': role === 'member',
    'bg-blue-100 text-blue-800': role === 'coach',
    'bg-red-100 text-red-800': role === 'admin'
  }
}

const confirmDelete = (member) => {
  memberToDelete.value = member
  showDeleteDialog.value = true
}

const deleteMember = () => {
  router.delete(`/admin/members/${memberToDelete.value.id}`, {
    onSuccess: () => {
      showDeleteDialog.value = false
    }
  })
}
</script>