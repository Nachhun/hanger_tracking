<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 via-blue-100 to-indigo-100 pb-20">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-6 shadow-lg sticky top-0 z-10">
      <div class="flex items-center gap-3">
        <button @click="router.back()" class="p-2 hover:bg-white/10 rounded-full transition-colors">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
          </svg>
        </button>
        <h1 class="text-xl font-bold">User Profile</h1>
      </div>
    </div>

    <div v-if="loading" class="flex justify-center items-center h-64">
        <div class="animate-spin rounded-full h-12 w-12 border-4 border-blue-500 border-t-transparent"></div>
    </div>

    <div v-else-if="userData" class="p-4 max-w-4xl mx-auto space-y-6">
      <!-- Profile Card -->
      <div class="bg-white rounded-2xl shadow-lg p-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/10 rounded-full blur-2xl"></div>
        <div class="flex items-center gap-6 relative z-10">
          <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-3xl font-bold shadow-lg">
            {{ userData.user.name.charAt(0) }}
          </div>
          <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ userData.user.name }}</h2>
            <div class="flex items-center gap-2 mt-1 text-gray-600">
               <span class="font-mono bg-gray-100 px-2 py-0.5 rounded text-sm">ID: {{ userData.user.telegram_id || 'N/A' }}</span>
               <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
               <span :class="userData.user.role === 'admin' ? 'text-purple-600 font-medium' : 'text-green-600 font-medium'">
                  {{ userData.user.role === 'admin' ? 'Administrator' : 'Salesman' }}
               </span>
            </div>
            <div class="mt-4 flex gap-3">
                <span :class="userData.user.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-2 py-0.5 rounded-full text-xs font-bold uppercase tracking-wide">
                    {{ userData.user.is_active ? 'Active' : 'Inactive' }}
                </span>
                
                <button 
                  v-if="userData.user.id !== 1" 
                  @click="toggleUserStatus"
                  :disabled="toggling"
                  class="text-xs px-3 py-1 rounded-full font-semibold border transition-all disabled:opacity-50"
                  :class="userData.user.is_active ? 'border-red-200 text-red-600 hover:bg-red-50' : 'border-green-200 text-green-600 hover:bg-green-50'"
                >
                  {{ toggling ? 'Updating...' : (userData.user.is_active ? 'Deactivate Account' : 'Activate Account') }}
                </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Stats Grid -->
      <div class="grid grid-cols-2 gap-4">
        <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-blue-500">
          <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Total Entries</p>
          <p class="text-3xl font-bold text-gray-900 mt-1">{{ userData.stats.total_entries }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-green-500">
          <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Today's Entries</p>
          <p class="text-3xl font-bold text-gray-900 mt-1">{{ userData.stats.today_entries }}</p>
        </div>
      </div>

      <!-- Recent History -->
      <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gray-50">
          <h3 class="font-bold text-gray-800 flex items-center">
            <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Recent Entries History
          </h3>
        </div>
        
        <div v-if="userData.recent_entries.length === 0" class="p-8 text-center text-gray-500">
            No entries found for this user.
        </div>

        <div v-else class="divide-y divide-gray-100">
            <div 
                v-for="entry in userData.recent_entries" 
                :key="entry.id"
                @click="selectedEntry = entry"
                class="p-4 hover:bg-gray-50 transition-colors cursor-pointer active:bg-gray-100"
            >
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h4 class="font-bold text-gray-900">{{ entry.outlet_name }}</h4>
                        <div class="flex items-center text-xs text-gray-500 gap-2 mt-1">
                            <span class="flex items-center"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> {{ entry.province || 'N/A' }}</span>
                            <span>•</span>
                            <span class="font-mono bg-gray-100 px-1 rounded">{{ entry.hanger_id }}</span>
                        </div>
                    </div>
                    <span class="text-xs text-gray-400 font-medium">{{ new Date(entry.created_at).toLocaleDateString() }}</span>
                </div>
                
                <div v-if="entry.notes" class="bg-yellow-50 text-yellow-800 text-xs p-2 rounded mb-2 border border-yellow-100">
                    <span class="font-bold">Note:</span> {{ entry.notes }}
                </div>

                <div v-if="entry.photo_url" class="mt-2">
                    <img :src="entry.photo_url" class="h-20 w-auto rounded-lg border border-gray-200 shadow-sm object-cover" loading="lazy" />
                </div>
            </div>
        </div>
      </div>
    </div>
    <!-- Entry Detail Modal -->
    <div v-if="selectedEntry" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" @click="selectedEntry = null">
      <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full overflow-hidden" @click.stop>
        <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
          <h3 class="font-bold text-gray-900">Entry Details</h3>
          <button @click="selectedEntry = null" class="text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
          </button>
        </div>
        
        <div class="p-6 space-y-4 max-h-[80vh] overflow-y-auto">
          <!-- Photo -->
          <div v-if="selectedEntry.photo_url" class="rounded-xl overflow-hidden border border-gray-200">
            <img :src="selectedEntry.photo_url" class="w-full h-auto object-cover" />
          </div>

          <!-- Info Grid -->
          <div class="grid grid-cols-2 gap-4">
            <div>
              <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Outlet</p>
              <p class="font-bold text-gray-900">{{ selectedEntry.outlet_name }}</p>
            </div>
             <div>
              <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Hanger ID</p>
              <p class="font-bold text-gray-900 font-mono">{{ selectedEntry.hanger_id }}</p>
            </div>
             <div>
              <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Province</p>
              <p class="font-medium text-gray-900">{{ selectedEntry.province || 'N/A' }}</p>
            </div>
             <div>
              <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Date</p>
              <p class="font-medium text-gray-900">{{ new Date(selectedEntry.created_at).toLocaleString() }}</p>
            </div>
          </div>

          <!-- Notes -->
          <div v-if="selectedEntry.notes" class="bg-yellow-50 p-3 rounded-lg border border-yellow-100">
             <p class="text-xs text-yellow-800 font-bold uppercase mb-1">Notes</p>
             <p class="text-sm text-yellow-900">{{ selectedEntry.notes }}</p>
          </div>

          <!-- Location Actions -->
          <div v-if="selectedEntry.latitude && selectedEntry.longitude" class="pt-2">
             <a 
              :href="`https://www.google.com/maps?q=${selectedEntry.latitude},${selectedEntry.longitude}`" 
              target="_blank"
              class="flex items-center justify-center gap-2 w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold shadow-sm transition-colors"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
              View on Google Maps
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const route = useRoute();
const router = useRouter();
const { apiCall } = useApi();

const userId = route.params.id;
const loading = ref(true);
const userData = ref<any>(null);
const toggling = ref(false);
const selectedEntry = ref<any>(null);

const toggleUserStatus = async () => {
  if (!confirm(`Are you sure you want to ${userData.value.user.is_active ? 'deactivate' : 'activate'} this user?`)) return;

  toggling.value = true;
  try {
    const response = await apiCall(`/admin/users/${userId}/toggle-status`, { method: 'PATCH' }) as any;
    userData.value.user.is_active = response.is_active;
  } catch (error) {
    console.error('Failed to update status', error);
    alert('Failed to update user status');
  } finally {
    toggling.value = false;
  }
};

onMounted(async () => {
    try {
        const data = await apiCall(`/admin/users/${userId}`);
        userData.value = data;
    } catch (error) {
        console.error('Failed to load user profile', error);
        alert('Failed to load user profile.');
        router.back();
    } finally {
        loading.value = false;
    }
});
</script>
