<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 via-blue-100 to-indigo-100 pb-20">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-6 shadow-lg sticky top-0 z-10">
      <div class="flex items-center justify-between mb-2">
        <button @click="router.push('/admin/dashboard')" class="p-2 hover:bg-blue-500 rounded-full transition-colors flex items-center gap-2">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
          </svg>
          <span class="font-semibold">Dashboard</span>
        </button>
        <h1 class="text-xl font-bold">Manage Provinces</h1>
        <div class="w-10"></div>
      </div>
    </div>

    <div class="p-4 max-w-4xl mx-auto space-y-6">
      <!-- Add New Province -->
      <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
          <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
          </svg>
          Add New Province
        </h2>

        <form @submit.prevent="createProvince" class="space-y-4">
          <div class="flex gap-4">
              <input 
                v-model="newProvince"
                type="text" 
                placeholder="Province Name"
                required
                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              />
              <button 
                type="submit"
                :disabled="isSubmitting"
                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-md transition-all disabled:opacity-50 flex items-center"
              >
                <span v-if="isSubmitting" class="mr-2">
                  <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                </span>
                {{ isSubmitting ? 'Adding...' : 'Add' }}
              </button>
          </div>
          <p v-if="error" class="text-red-500 text-sm mt-2">{{ error }}</p>
          <p v-if="success" class="text-green-500 text-sm mt-2">{{ success }}</p>
        </form>
      </div>

      <!-- Province List -->
      <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-4 border-b border-gray-100">
           <h2 class="text-lg font-bold text-gray-800">
            Registered Provinces ({{ provinces.length }})
           </h2>
        </div>
        <div class="max-h-[600px] overflow-y-auto">
            <ul class="divide-y divide-gray-100">
                <li v-for="province in provinces" :key="province.id" class="p-4 flex items-center justify-between hover:bg-gray-50">
                    <span class="font-medium text-gray-800">
                      <span class="text-gray-400 font-mono text-sm mr-2">#{{ province.id }}</span>
                      {{ province.name }}
                    </span>
                    <div class="flex items-center gap-2">
                        <template v-if="editingId === province.id">
                           <input 
                                v-model="editName"
                                type="text"
                                class="px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                @keyup.enter="updateProvince(province.id)"
                           />
                           <button @click="updateProvince(province.id)" class="text-green-600 hover:bg-green-50 p-1 rounded transition-colors" title="Save">
                               <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                           </button>
                           <button @click="cancelEditing" class="text-gray-500 hover:bg-gray-100 p-1 rounded transition-colors" title="Cancel">
                               <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                           </button>
                        </template>
                        <template v-else>
                            <button 
                                @click="startEditing(province)"
                                class="text-blue-600 hover:bg-blue-50 p-2 rounded-full transition-colors"
                                title="Edit"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button 
                                @click="deleteProvince(province.id)"
                                class="text-red-500 hover:bg-red-50 p-2 rounded-full transition-colors"
                                title="Delete"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </template>
                    </div>
                </li>
            </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useApi } from '~/composables/useApi';

const router = useRouter();
const { apiCall } = useApi();

const provinces = ref<any[]>([]);
const newProvince = ref('');
const editingId = ref<number | null>(null);
const editName = ref('');
const isSubmitting = ref(false);
const error = ref<string | null>(null);
const success = ref<string | null>(null);

const fetchProvinces = async () => {
  try {
    const data = await apiCall('/provinces');
    provinces.value = data as any[];
  } catch (err) {
    console.error('Failed to fetch provinces', err);
  }
};

const createProvince = async () => {
  isSubmitting.value = true;
  error.value = null;
  success.value = null;

  try {
    await apiCall('/admin/provinces', {
      method: 'POST',
      body: { name: newProvince.value }
    });
    
    success.value = 'Province added successfully!';
    newProvince.value = '';
    await fetchProvinces();
    
    setTimeout(() => success.value = null, 3000);
  } catch (err: any) {
    console.error('Failed to create province', err);
    if (err.data?.message) {
      error.value = err.data.message;
    } else {
      error.value = 'Failed to create province.';
    }
  } finally {
    isSubmitting.value = false;
  }
};

const deleteProvince = async (id: number) => {
    if(!confirm('Are you sure you want to delete this province?')) return;

    try {
        await apiCall(`/admin/provinces/${id}`, {
            method: 'DELETE'
        });
        await fetchProvinces();
    } catch (err) {
        console.error('Failed to delete province', err);
        alert('Failed to delete province');
    }
}

const startEditing = (province: any) => {
    editingId.value = province.id;
    editName.value = province.name;
};

const cancelEditing = () => {
    editingId.value = null;
    editName.value = '';
};

const updateProvince = async (id: number) => {
    if (!editName.value.trim()) return;

    try {
        await apiCall(`/admin/provinces/${id}`, {
            method: 'PUT',
            body: { name: editName.value }
        });
        
        await fetchProvinces();
        cancelEditing();
        success.value = 'Province updated successfully!';
        setTimeout(() => success.value = null, 3000);
    } catch (err: any) {
        console.error('Failed to update province', err);
        alert(err.data?.message || 'Failed to update province');
    }
};

onMounted(() => {
  fetchProvinces();
});
</script>
