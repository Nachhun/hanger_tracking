<template>
  <div class="min-h-screen pb-20 relative overflow-x-hidden bg-gray-50">
     <!-- Animated Background -->
     <div class="fixed inset-0 bg-gradient-to-br from-blue-50 via-white to-orange-50 -z-10">
        <div class="absolute top-0 right-0 w-[50%] h-[50%] bg-blue-600/5 rounded-full blur-[120px] animate-float"></div>
        <div class="absolute bottom-0 left-0 w-[40%] h-[40%] bg-orange-600/5 rounded-full blur-[100px] animate-float" style="animation-delay: -4s;"></div>
     </div>

    <!-- Header -->
    <div class="bg-white/80 backdrop-blur-lg border-b border-gray-200 sticky top-0 z-50 px-4 py-4 mb-6 shadow-sm">
      <div class="flex items-center justify-between">
        <button @click="router.push('/admin/dashboard')" class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-full transition-all">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
          </svg>
        </button>
        <div class="text-center">
            <h1 class="text-lg font-bold text-gray-900 tracking-wide">Brand Management</h1>
            <p class="text-[10px] text-blue-600 uppercase tracking-widest">Admin Panel</p>
        </div>
        <button @click="showAddModal = true" class="p-2 text-white bg-orange-500 hover:bg-orange-600 rounded-lg transition-all">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
          </svg>
        </button>
      </div>
    </div>

    <!-- Summary Stats -->
    <div class="px-4 mb-6">
      <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-4">
        <div class="grid grid-cols-3 gap-4">
          <div class="text-center">
            <p class="text-2xl font-bold text-gray-900">{{ brands.length }}</p>
            <p class="text-[10px] text-gray-500 uppercase tracking-wider mt-1">Total</p>
          </div>
          <div class="text-center border-l border-gray-200">
            <p class="text-2xl font-bold text-blue-600">{{ activeBrands }}</p>
            <p class="text-[10px] text-gray-500 uppercase tracking-wider mt-1">Active</p>
          </div>
          <div class="text-center border-l border-gray-200">
            <p class="text-2xl font-bold text-orange-600">{{ inactiveBrands }}</p>
            <p class="text-[10px] text-gray-500 uppercase tracking-wider mt-1">Inactive</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Search Bar -->
    <div class="px-4 mb-4">
       <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
             <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
          </div>
          <input 
             v-model="searchQuery" 
             type="text" 
             class="block w-full pl-9 pr-3 py-3 border-0 bg-white rounded-xl text-sm text-gray-900 focus:ring-2 focus:ring-blue-500 shadow-sm transition-all placeholder-gray-400" 
             placeholder="Search brands..."
          >
       </div>
    </div>

    <!-- Brands List -->
    <div class="px-4">
      <div v-if="loading" class="bg-white rounded-2xl shadow-md border border-gray-100 p-8 text-center">
        <div class="w-12 h-12 border-4 border-blue-500 border-t-transparent rounded-full animate-spin mx-auto mb-3"></div>
        <p class="text-gray-500 text-sm">Loading brands...</p>
      </div>

      <div v-else-if="filteredBrands.length === 0" class="bg-white rounded-2xl shadow-md border border-gray-100 p-8 text-center">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
          <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
          </svg>
        </div>
        <p class="text-gray-500 text-sm">No brands found</p>
        <button @click="showAddModal = true" class="mt-4 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg text-sm font-medium transition-all">
          Add First Brand
        </button>
      </div>

      <div v-else class="space-y-3">
        <div 
          v-for="brand in filteredBrands"
          :key="brand.id"
          class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 transition-all hover:shadow-md"
        >
          <div class="flex items-center justify-between">
            <div class="flex-1">
              <div class="flex items-center gap-3">
                <h3 class="text-base font-bold text-gray-900">{{ brand.name }}</h3>
                <span 
                  :class="brand.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'"
                  class="px-2 py-0.5 rounded-full text-xs font-medium"
                >
                  {{ brand.is_active ? 'Active' : 'Inactive' }}
                </span>
              </div>
              <p v-if="brand.description" class="text-sm text-gray-600 mt-1">{{ brand.description }}</p>
            </div>
            
            <div class="flex items-center gap-2">
              <!-- Toggle Active -->
              <button 
                @click="toggleActive(brand)"
                class="p-2 rounded-lg transition-all"
                :class="brand.is_active ? 'text-green-600 hover:bg-green-50' : 'text-gray-400 hover:bg-gray-100'"
                :title="brand.is_active ? 'Deactivate' : 'Activate'"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </button>
              
              <!-- Edit -->
              <button 
                @click="editBrand(brand)"
                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
              </button>
              
              <!-- Delete -->
              <button 
                @click="deleteBrand(brand)"
                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-all"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Add/Edit Modal -->
    <div v-if="showAddModal || showEditModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" @click.self="closeModal">
      <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 animate-scale-in">
        <h2 class="text-xl font-bold text-gray-900 mb-4">{{ showEditModal ? 'Edit Brand' : 'Add New Brand' }}</h2>
        
        <form @submit.prevent="showEditModal ? updateBrand() : createBrand()" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Brand Name *</label>
            <input 
              v-model="formData.name"
              type="text" 
              required
              class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-all outline-none"
              placeholder="e.g. Nike"
            >
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea 
              v-model="formData.description"
              rows="3"
              class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-all outline-none resize-none"
              placeholder="Brief description..."
            ></textarea>
          </div>

          <div class="flex items-center gap-3">
            <input 
              v-model="formData.is_active"
              type="checkbox"
              id="is_active"
              class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
            >
            <label for="is_active" class="text-sm font-medium text-gray-700">Active (visible to salesmen)</label>
          </div>

          <div class="flex gap-3 pt-2">
            <button 
              type="button"
              @click="closeModal"
              class="flex-1 px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-all"
            >
              Cancel
            </button>
            <button 
              type="submit"
              :disabled="submitting"
              class="flex-1 px-4 py-3 bg-orange-500 hover:bg-orange-600 text-white rounded-xl font-medium transition-all disabled:opacity-50"
            >
              {{ submitting ? 'Saving...' : (showEditModal ? 'Update' : 'Create') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const router = useRouter();

// State
const brands = ref<any[]>([]);
const loading = ref(true);
const searchQuery = ref('');
const showAddModal = ref(false);
const showEditModal = ref(false);
const submitting = ref(false);
const editingBrand = ref<any>(null);

const formData = ref({
  name: '',
  description: '',
  is_active: true,
});

// Computed
const filteredBrands = computed(() => {
  if (!searchQuery.value) return brands.value;
  return brands.value.filter(brand => 
    brand.name.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

const activeBrands = computed(() => 
  brands.value.filter(b => b.is_active).length
);

const inactiveBrands = computed(() => 
  brands.value.filter(b => !b.is_active).length
);

// Methods
const fetchBrands = async () => {
  loading.value = true;
  try {
    const { apiCall } = useApi();
    const data = await apiCall('/admin/brands');
    brands.value = data as any[];
  } catch (error) {
    console.error('Failed to fetch brands:', error);
  } finally {
    loading.value = false;
  }
};

const createBrand = async () => {
  submitting.value = true;
  try {
    const { apiCall } = useApi();
    await apiCall('/admin/brands', {
      method: 'POST',
      body: JSON.stringify(formData.value),
    });
    
    await fetchBrands();
    closeModal();
  } catch (error: any) {
    console.error('Failed to create brand:', error);
    alert(error.data?.message || 'Failed to create brand');
  } finally {
    submitting.value = false;
  }
};

const editBrand = (brand: any) => {
  editingBrand.value = brand;
  formData.value = {
    name: brand.name,
    description: brand.description || '',
    is_active: brand.is_active,
  };
  showEditModal.value = true;
};

const updateBrand = async () => {
  submitting.value = true;
  try {
    const { apiCall } = useApi();
    await apiCall(`/admin/brands/${editingBrand.value.id}`, {
      method: 'PUT',
      body: JSON.stringify(formData.value),
    });
    
    await fetchBrands();
    closeModal();
  } catch (error: any) {
    console.error('Failed to update brand:', error);
    alert(error.data?.message || 'Failed to update brand');
  } finally {
    submitting.value = false;
  }
};

const deleteBrand = async (brand: any) => {
  if (!confirm(`Are you sure you want to delete "${brand.name}"?`)) return;
  
  try {
    const { apiCall } = useApi();
    await apiCall(`/admin/brands/${brand.id}`, {
      method: 'DELETE',
    });
    
    await fetchBrands();
  } catch (error: any) {
    console.error('Failed to delete brand:', error);
    alert(error.data?.message || 'Failed to delete brand. It may be in use.');
  }
};

const toggleActive = async (brand: any) => {
  try {
    const { apiCall } = useApi();
    await apiCall(`/admin/brands/${brand.id}`, {
      method: 'PUT',
      body: JSON.stringify({
        ...brand,
        is_active: !brand.is_active,
      }),
    });
    
    await fetchBrands();
  } catch (error) {
    console.error('Failed to toggle brand status:', error);
  }
};

const closeModal = () => {
  showAddModal.value = false;
  showEditModal.value = false;
  editingBrand.value = null;
  formData.value = {
    name: '',
    description: '',
    is_active: true,
  };
};

// Initialize
onMounted(() => {
  fetchBrands();
});
</script>

<style scoped>
@keyframes scale-in {
  from {
    opacity: 0;
    transform: scale(0.9);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

.animate-scale-in {
  animation: scale-in 0.2s ease-out;
}

@keyframes float {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-20px);
  }
}

.animate-float {
  animation: float 6s ease-in-out infinite;
}
</style>
