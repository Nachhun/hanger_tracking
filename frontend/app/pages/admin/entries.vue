<template>
  <div class="min-h-screen pb-20 relative overflow-x-hidden bg-gray-50">
     <!-- Animated Background -->
     <div class="fixed inset-0 bg-gradient-to-br from-blue-50 via-white to-orange-50 -z-10">
        <div class="absolute top-0 right-0 w-[50%] h-[50%] bg-primary-600/5 rounded-full blur-[120px] animate-float"></div>
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
            <h1 class="text-lg font-bold text-gray-900 tracking-wide">All Entries</h1>
            <p class="text-[10px] text-blue-600 uppercase tracking-widest">Sales Records</p>
        </div>
        <div class="w-10"></div>
      </div>
    </div>

    <!-- Summary Stats -->
    <div class="px-4 mb-6">
      <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-4">
        <div class="grid grid-cols-3 gap-4">
          <div class="text-center">
            <p class="text-2xl font-bold text-gray-900">{{ totalEntries }}</p>
            <p class="text-[10px] text-gray-500 uppercase tracking-wider mt-1">Total</p>
          </div>
          <div class="text-center border-l border-gray-200">
            <p class="text-2xl font-bold text-blue-600">{{ totalHangers }}</p>
            <p class="text-[10px] text-gray-500 uppercase tracking-wider mt-1">Hangers</p>
          </div>
          <div class="text-center border-l border-gray-200">
            <p class="text-2xl font-bold text-orange-600">{{ uniqueSalesmen }}</p>
            <p class="text-[10px] text-gray-500 uppercase tracking-wider mt-1">Salesmen</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Search & Filter -->
    <div class="px-4 mb-4 sticky top-[72px] z-40">
       <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 flex gap-3">
          <!-- Search Input -->
          <div class="flex-1 relative">
             <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
             </div>
             <input 
                v-model="searchQuery" 
                type="text" 
                class="block w-full pl-9 pr-3 py-2 border-0 bg-gray-50 rounded-lg text-sm text-gray-900 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all placeholder-gray-400" 
                placeholder="Search salesman, outlet, hanger ID..."
             >
          </div>
          
           <!-- Province Filter -->
          <div class="relative min-w-[120px]">
             <select 
                v-model="selectedProvince"
                class="block w-full pl-3 pr-8 py-2 border-0 bg-gray-50 rounded-lg text-sm text-gray-900 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all appearance-none cursor-pointer"
             >
                <option value="">All Provinces</option>
                <option v-for="prov in uniqueProvinces" :key="prov" :value="prov">{{ prov }}</option>
             </select>
             <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
             </div>
          </div>

          <!-- Time Period Filter -->
          <div class="relative min-w-[120px]">
             <select 
                v-model="selectedTimeFilter"
                class="block w-full pl-3 pr-8 py-2 border-0 bg-gray-50 rounded-lg text-sm text-gray-900 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all appearance-none cursor-pointer"
             >
                <option value="all">All Time</option>
                <option value="today">Today</option>
                <option value="week">This Week</option>
                <option value="month">This Month</option>
                <option value="year">This Year</option>
             </select>
             <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
             </div>
          </div>
       </div>
    </div>

    <!-- Entries List -->
    <div class="px-4">
      <div v-if="loading" class="bg-white rounded-2xl shadow-md border border-gray-100 p-8 text-center">
        <div class="w-12 h-12 border-4 border-blue-500 border-t-transparent rounded-full animate-spin mx-auto mb-3"></div>
        <p class="text-gray-500 text-sm">Loading entries...</p>
      </div>

      <div v-else-if="entries.length === 0" class="bg-white rounded-2xl shadow-md border border-gray-100 p-8 text-center">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
          <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
        </div>
        <p class="text-gray-500 text-sm">No entries found</p>
        <p class="text-xs text-gray-400 mt-2">Submit an entry to see it here</p>
      </div>

      <div v-else class="space-y-4">
        <div 
          v-for="entry in filteredEntries"
          :key="entry.id"
          class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-lg"
          :class="{'ring-2 ring-blue-300 shadow-xl': expandedId === entry.id}"
        >
          <!-- Card Header: Click to Expand -->
          <div 
            @click="toggleExpand(entry.id)"
            class="px-5 py-4 flex items-center justify-between cursor-pointer hover:bg-gray-50/50 transition-colors"
          >
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white font-bold text-base shadow-md ring-4 ring-blue-100">
                {{ entry.salesman.charAt(0) }}
              </div>
              <div>
                <p class="font-bold text-gray-900 text-base">{{ entry.salesman }}</p>
                <p class="text-xs text-gray-500 flex items-center gap-2 mt-0.5">
                  <span>{{ entry.timeAgo }}</span>
                  <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                  <span class="text-gray-600 font-medium truncate max-w-[140px]">{{ entry.outletName }}</span>
                </p>
              </div>
            </div>
            <div class="flex items-center gap-3">
               <div v-if="expandedId !== entry.id" class="text-xs font-mono font-semibold text-blue-600 bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-200">
                  {{ entry.hangerId }}
               </div>
               <svg 
                class="w-5 h-5 text-gray-400 transition-all duration-300"
                :class="{'rotate-180 text-blue-600': expandedId === entry.id}"
                fill="none" stroke="currentColor" viewBox="0 0 24 24"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
              </svg>
            </div>
          </div>

          <!-- Expanded Details -->
          <div v-if="expandedId === entry.id" class="px-5 pb-5 pt-4 bg-gradient-to-b from-gray-50/80 to-white border-t border-gray-200 animate-fade-in-down">
            <!-- Details Grid -->
            <div class="grid grid-cols-2 gap-3 mb-4">
              <div class="bg-white p-3 rounded-xl border border-gray-200 shadow-sm">
                <p class="text-[10px] text-gray-500 uppercase tracking-wider font-bold mb-1.5 flex items-center gap-1">
                  <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                  Outlet Name
                </p>
                <p class="text-sm font-semibold text-gray-900">{{ entry.outletName }}</p>
              </div>
              <div class="bg-white p-3 rounded-xl border border-blue-200 shadow-sm">
                 <p class="text-[10px] text-blue-600 uppercase tracking-wider font-bold mb-1.5 flex items-center gap-1">
                   <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                   Hanger ID
                 </p>
                 <p class="text-sm font-mono font-bold text-blue-600">{{ entry.hangerId }}</p>
              </div>
              <div class="bg-white p-3 rounded-xl border border-gray-200 shadow-sm">
                 <p class="text-[10px] text-gray-500 uppercase tracking-wider font-bold mb-1.5 flex items-center gap-1">
                   <svg class="w-3 h-3 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                   Province
                 </p>
                 <p class="text-sm font-medium text-gray-900">
                   {{ entry.province }}
                 </p>
              </div>
              <div class="bg-white p-3 rounded-xl border border-gray-200 shadow-sm">
                 <p class="text-[10px] text-gray-500 uppercase tracking-wider font-bold mb-1.5 flex items-center gap-1">
                   <svg class="w-3 h-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                   Brand
                 </p>
                 <p class="text-sm font-medium text-gray-900">
                   {{ entry.brand || 'N/A' }}
                 </p>
              </div>
              <div class="bg-white p-3 rounded-xl border border-gray-200 shadow-sm">
                 <p class="text-[10px] text-gray-500 uppercase tracking-wider font-bold mb-1.5">Location</p>
                 <a v-if="entry.gpsLocation" :href="`https://www.google.com/maps?q=${entry.latitude},${entry.longitude}`" target="_blank" class="text-sm font-medium text-blue-600 hover:text-blue-700 flex items-center gap-1.5 group">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                    <span class="group-hover:underline">Open Map</span>
                 </a>
                 <span v-else class="text-sm text-gray-400 italic">No GPS</span>
              </div>
            </div>

            <!-- Notes -->
            <div v-if="entry.notes" class="mb-4 px-4 py-3 bg-amber-50 rounded-xl border-l-4 border-amber-400 flex gap-3">
               <svg class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
               <p class="text-sm text-gray-700">{{ entry.notes }}</p>
            </div>

            <!-- Photo -->
            <div v-if="entry.photoUrl" class="rounded-xl overflow-hidden bg-white border-2 border-gray-200 shadow-md relative group cursor-pointer" @click="openImage(entry.photoUrl)">
               <img :src="entry.photoUrl" class="w-full h-64 object-cover" alt="Entry photo">
               <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent flex items-end justify-center pb-5 opacity-0 group-hover:opacity-100 transition-all duration-300">
                  <div class="bg-white/90 backdrop-blur-sm text-gray-900 text-sm px-4 py-2 rounded-full font-semibold border border-white/50 shadow-lg flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    View Full Image
                  </div>
               </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const router = useRouter();

// Data
const loading = ref(true);
const entries = ref<any[]>([]);
const expandedId = ref<number | null>(null);
const searchQuery = ref('');
const selectedProvince = ref('');
const selectedTimeFilter = ref('all');

// Computed
const totalEntries = computed(() => entries.value.length);
const totalHangers = computed(() => entries.value.length); // Each entry is 1 hanger
const uniqueSalesmen = computed(() => new Set(entries.value.map(e => e.salesman)).size);
const uniqueProvinces = computed(() => [...new Set(entries.value.map(e => e.province).filter(p => p !== 'N/A'))].sort());

const filteredEntries = computed(() => {
  return entries.value.filter(entry => {
    // Search Query Filter
    const query = searchQuery.value.toLowerCase();
    const matchesSearch = 
      entry.salesman.toLowerCase().includes(query) ||
      entry.outletName.toLowerCase().includes(query) ||
      entry.hangerId.toLowerCase().includes(query) ||
      entry.notes?.toLowerCase().includes(query);

    // Province Filter
    const matchesProvince = selectedProvince.value ? entry.province === selectedProvince.value : true;

    // Time Filter
    let matchesTime = true;
    if (selectedTimeFilter.value !== 'all') {
      const entryDate = new Date(entry.createdAt);
      const now = new Date();
      now.setHours(0, 0, 0, 0); // Start of today
      
      switch (selectedTimeFilter.value) {
        case 'today': {
          const entryDay = new Date(entryDate);
          entryDay.setHours(0, 0, 0, 0);
          matchesTime = entryDay.getTime() === now.getTime();
          break;
        }
        case 'week': {
           // Get start of week (Sunday)
           const startOfWeek = new Date(now);
           startOfWeek.setDate(now.getDate() - now.getDay());
           matchesTime = entryDate >= startOfWeek;
           break;
        }
        case 'month': {
           matchesTime = 
              entryDate.getMonth() === now.getMonth() && 
              entryDate.getFullYear() === now.getFullYear();
           break;
        }
        case 'year': {
           matchesTime = entryDate.getFullYear() === now.getFullYear();
           break;
        }
      }
    }

    return matchesSearch && matchesProvince && matchesTime;
  });
});

// Methods
const toggleExpand = (id: number) => {
  expandedId.value = expandedId.value === id ? null : id;
};

const formatTimeAgo = (dateString: string) => {
  const date = new Date(dateString);
  const now = new Date();
  const diff = now.getTime() - date.getTime();
  const minutes = Math.floor(diff / 60000);
  
  if (minutes < 1) return 'Just now';
  if (minutes < 60) return `${minutes} min${minutes > 1 ? 's' : ''} ago`;
  const hours = Math.floor(minutes / 60);
  if (hours < 24) return `${hours} hour${hours > 1 ? 's' : ''} ago`;
  const days = Math.floor(hours / 24);
  if (days < 7) return `${days} day${days > 1 ? 's' : ''} ago`;
  return date.toLocaleDateString();
};

const openImage = (url: string) => {
  window.open(url, '_blank');
};

const fetchEntries = async () => {
  loading.value = true;
  try {
    const { apiCall } = useApi();
    const data = await apiCall('/admin/entries') as any[];
    
    if (!data || data.length === 0) {
      entries.value = [];
      return;
    }
    
    entries.value = data.map((entry: any) => ({
      id: entry.id,
      salesman: entry.user?.name || 'Unknown',
      outletName: entry.outlet_name || 'N/A',
      province: entry.province || 'N/A',
      hangerId: entry.hanger_id || 'N/A',
      latitude: entry.latitude,
      longitude: entry.longitude,
      gpsLocation: entry.latitude && entry.longitude ? `${parseFloat(entry.latitude).toFixed(4)}, ${parseFloat(entry.longitude).toFixed(4)}` : null,
      notes: entry.notes,
      photoUrl: entry.photo_url,
      createdAt: entry.created_at,
      timeAgo: formatTimeAgo(entry.created_at),
    }));
  } catch (error) {
    console.error('Failed to fetch entries:', error);
    entries.value = [];
  } finally {
    loading.value = false;
  }
};

// Load data on mount
onMounted(() => {
  fetchEntries();
});
</script>
