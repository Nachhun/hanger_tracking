<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 via-blue-100 to-indigo-100 pb-24">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-6 shadow-lg sticky top-0 z-10">
      <div class="flex items-center justify-between mb-4">
        <button @click="router.push('/entry')" class="p-2 hover:bg-white/10 rounded-full transition-colors flex items-center gap-2">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
          </svg>
          <span class="font-semibold">Back</span>
        </button>
        <h1 class="text-xl font-bold">My Performance</h1>
        <div class="w-10"></div>
      </div>

      <!-- Tabs -->
      <div class="flex p-1 bg-white/10 rounded-xl backdrop-blur-sm">
        <button 
          @click="activeTab = 'dashboard'"
          :class="[
            'flex-1 py-2 text-sm font-semibold rounded-lg transition-all',
            activeTab === 'dashboard' 
              ? 'bg-white text-blue-700 shadow-md transform scale-[1.02]' 
              : 'text-white/80 hover:bg-white/5'
          ]"
        >
          Dashboard
        </button>
        <button 
          @click="activeTab = 'history'"
          :class="[
            'flex-1 py-2 text-sm font-semibold rounded-lg transition-all',
            activeTab === 'history' 
              ? 'bg-white text-blue-700 shadow-md transform scale-[1.02]' 
              : 'text-white/80 hover:bg-white/5'
          ]"
        >
          History
        </button>
      </div>
    </div>

    <!-- Active Tab Content -->
    <div class="p-4 max-w-2xl mx-auto">
      
      <!-- DASHBOARD TAB -->
      <div v-if="activeTab === 'dashboard'" class="space-y-6">
        
        <!-- Loading State -->
        <div v-if="loadingDashboard" class="flex justify-center py-10">
           <div class="animate-spin rounded-full h-10 w-10 border-4 border-blue-500 border-t-transparent"></div>
        </div>

        <div v-else class="space-y-6">
          <!-- Stats Grid -->
          <div class="grid grid-cols-3 gap-3">
            <div class="bg-white p-3 rounded-2xl shadow-sm border border-blue-100 text-center">
               <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Today</p>
               <p class="text-2xl font-bold text-blue-600 mt-1">{{ dashboardData.stats.today }}</p>
            </div>
            <div class="bg-white p-3 rounded-2xl shadow-sm border border-purple-100 text-center">
               <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Month</p>
               <p class="text-2xl font-bold text-purple-600 mt-1">{{ dashboardData.stats.this_month }}</p>
            </div>
            <div class="bg-white p-3 rounded-2xl shadow-sm border border-indigo-100 text-center">
               <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Total</p>
               <p class="text-2xl font-bold text-indigo-600 mt-1">{{ dashboardData.stats.total }}</p>
            </div>
          </div>

          <!-- Weekly Trend Chart -->
          <div class="bg-white p-5 rounded-2xl shadow-lg border border-gray-100">
             <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                Weekly Trend
             </h3>
             <div class="h-40 flex items-end justify-between gap-2">
                <div v-for="(day, index) in dashboardData.chart_data" :key="index" class="flex flex-col items-center flex-1 group">
                   <div class="relative w-full flex justify-end flex-col items-center">
                      <div 
                        class="w-full bg-gradient-to-t from-blue-500 to-blue-400 rounded-t-lg transition-all duration-500 group-hover:from-blue-600 group-hover:to-blue-500"
                        :style="{ height: `${Math.max(day.count * 10, 4)}px`, maxHeight: '120px' }"
                      ></div>
                      <span class="absolute -top-6 text-xs font-bold text-blue-600 opacity-0 group-hover:opacity-100 transition-opacity">{{ day.count }}</span>
                   </div>
                   <span class="text-[10px] text-gray-400 mt-2 font-medium">{{ day.date.split(',')[0] }}</span>
                </div>
             </div>
          </div>

          <!-- Top Brands -->
          <div class="bg-white p-5 rounded-2xl shadow-lg border border-gray-100">
             <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                Top Brands
             </h3>
             <div class="space-y-4">
                <div v-for="brand in dashboardData.top_brands" :key="brand.name" class="space-y-1">
                   <div class="flex justify-between text-sm">
                      <span class="font-semibold text-gray-700">{{ brand.name }}</span>
                      <span class="font-bold text-gray-900">{{ brand.count }}</span>
                   </div>
                   <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                      <div 
                        class="h-full bg-gradient-to-r from-purple-500 to-pink-500 rounded-full"
                        :style="{ width: `${brand.percentage}%` }"
                      ></div>
                   </div>
                </div>
                <div v-if="dashboardData.top_brands.length === 0" class="text-center text-gray-400 text-sm py-4">
                   No brand data available yet.
                </div>
             </div>
          </div>
        </div>
      </div>

      <!-- HISTORY TAB -->
      <div v-else class="space-y-4">
        <!-- Search & Export -->
        <div class="flex gap-2 mb-4">
          <div class="relative flex-1">
            <input 
              v-model="searchQuery" 
              @input="debounceSearch"
              type="text" 
              placeholder="Search outlet or ID..." 
              class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-gray-700 focus:ring-2 focus:ring-blue-500 focus:outline-none shadow-sm transition-all"
            >
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
          </div>
          <button 
            @click="exportCSV"
            :disabled="isExporting"
            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-xl font-semibold shadow-md flex items-center gap-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <svg v-if="!isExporting" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <svg v-else class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
          </button>
        </div>

        <!-- History Loading -->
        <div v-if="loadingHistory" class="flex justify-center py-10">
          <div class="animate-spin rounded-full h-10 w-10 border-4 border-blue-500 border-t-transparent"></div>
        </div>

        <!-- Empty State -->
        <div v-else-if="entries.length === 0" class="text-center py-10 text-gray-500 bg-white/50 rounded-2xl border border-white/60">
          <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
          <p class="text-lg font-medium">No entries found</p>
          <p class="text-sm">Submit your first entry to see it here.</p>
        </div>

        <!-- Entry List -->
        <div v-else class="space-y-4">
          <div 
            v-for="entry in entries" 
            :key="entry.id"
            class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 transform transition-all hover:scale-[1.01]"
          >
            <div class="p-4">
              <div class="flex justify-between items-start">
                <div>
                  <h3 class="font-bold text-gray-900 text-lg">{{ entry.outlet_name }}</h3>
                  <div class="flex items-center gap-2 mt-1 text-sm text-gray-500 flex-wrap">
                    <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded font-mono font-medium text-xs">{{ entry.hanger_id }}</span>
                    <span>•</span>
                    <span>{{ entry.province }}</span>
                    <span v-if="entry.brand">•</span>
                    <span v-if="entry.brand" class="bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded font-medium text-xs">{{ entry.brand }}</span>
                  </div>
                </div>
                <div class="text-right">
                   <p class="text-xs font-bold text-gray-400">{{ formatDate(entry.created_at) }}</p>
                   <div class="flex gap-2 mt-2 justify-end">
                      <button @click="openEditModal(entry)" class="p-1.5 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                      </button>
                      <button @click="deleteEntry(entry)" class="p-1.5 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                      </button>
                   </div>
                </div>
              </div>
              
              <div v-if="entry.notes" class="mt-3 bg-yellow-50 p-3 rounded-lg border border-yellow-100 text-sm text-yellow-800">
                  <span class="font-bold">Note:</span> {{ entry.notes }}
              </div>
            </div>
          </div>

          <!-- Pagination -->
          <div v-if="pagination.last_page > 1" class="flex justify-center gap-2 pt-4">
               <button 
                  @click="changePage(pagination.current_page - 1)"
                  :disabled="pagination.current_page === 1"
                  class="px-4 py-2 bg-white rounded-lg shadow disabled:opacity-50 text-sm font-medium"
               >
                  Previous
               </button>
               <span class="px-4 py-2 text-sm text-gray-600 font-medium self-center">
                   Page {{ pagination.current_page }} of {{ pagination.last_page }}
               </span>
               <button 
                  @click="changePage(pagination.current_page + 1)"
                  :disabled="pagination.current_page === pagination.last_page"
                  class="px-4 py-2 bg-white rounded-lg shadow disabled:opacity-50 text-sm font-medium"
               >
                  Next
               </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Modal -->
    <div v-if="editingEntry" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-900">Edit Entry</h3>
                <button @click="editingEntry = null" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form @submit.prevent="updateEntry" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Outlet Name</label>
                    <input v-model="editForm.outlet_name" type="text" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Province</label>
                    <select v-model="editForm.province_id" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                         <option v-for="prov in provinces" :key="prov.id" :value="prov.id">{{ prov.name }}</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Hanger ID</label>
                    <input v-model="editForm.hanger_id" type="text" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Notes</label>
                    <textarea v-model="editForm.notes" rows="3" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
                </div>

                <div class="pt-2">
                    <button 
                        type="submit" 
                        :disabled="isUpdating"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-lg transition-transform active:scale-[0.98] disabled:opacity-70 flex justify-center"
                    >
                        <svg v-if="isUpdating" class="animate-spin h-5 w-5 mr-2" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        {{ isUpdating ? 'Saving...' : 'Save Changes' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Export Filter Modal -->
    <div v-if="showExportModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden">
             <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-900">Export Options</h3>
                <button @click="showExportModal = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
             <div class="p-6 space-y-4">
                <p class="text-sm text-gray-600 mb-2">Select a date range for your report. Leave empty to export all history.</p>
                
                <div class="space-y-3">
                     <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase">Start Date</label>
                        <input v-model="exportFilters.start_date" type="date" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    </div>
                     <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase">End Date</label>
                        <input v-model="exportFilters.end_date" type="date" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    </div>
                </div>

                <div class="pt-2 flex gap-3">
                     <button @click="setExportPreset('month')" class="flex-1 py-1.5 text-xs font-medium bg-blue-50 text-blue-600 rounded hover:bg-blue-100">
                         This Month
                     </button>
                     <button @click="setExportPreset('year')" class="flex-1 py-1.5 text-xs font-medium bg-blue-50 text-blue-600 rounded hover:bg-blue-100">
                         This Year
                     </button>
                </div>

                <button 
                    @click="confirmExport" 
                    class="w-full mt-2 bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl shadow-lg transition-transform active:scale-[0.98] flex justify-center items-center gap-2"
                >
                    <span>Send to Telegram</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </button>
             </div>
        </div>
    </div>

    <!-- Download Progress Toast -->
    <transition name="slide-up">
      <div v-if="showDownloadToast" class="fixed bottom-20 left-4 right-4 z-50 max-w-md mx-auto">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-4 rounded-2xl shadow-2xl border-2 border-white/30">
          <div class="flex items-center gap-4">
            <div v-if="downloadStatus === 'downloading'" class="flex-shrink-0">
              <div class="w-12 h-12 border-4 border-white/30 border-t-white rounded-full animate-spin"></div>
            </div>
            <div v-else class="flex-shrink-0">
              <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
              </div>
            </div>
            <div class="flex-1">
              <p v-if="downloadStatus === 'downloading'" class="font-bold text-lg">Sending to Telegram...</p>
              <p v-else class="font-bold text-lg">Sent Successfully!</p>
              <p class="text-sm text-white/90 mt-1">
                <span v-if="downloadStatus === 'downloading'">Preparing your CSV file</span>
                <span v-else>Check your Telegram messages</span>
              </p>
            </div>
          </div>
        </div>
      </div>
    </transition>

  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const { apiCall } = useApi();

// Interfaces
interface DashboardStats {
    today: number;
    this_month: number;
    total: number;
}

interface ChartData {
    date: string;
    count: number;
}

interface TopBrand {
    name: string;
    count: number;
    percentage: number;
}

interface DashboardData {
    stats: DashboardStats;
    chart_data: ChartData[];
    top_brands: TopBrand[];
}

// Tab State
const activeTab = ref<'dashboard' | 'history'>('dashboard');

// Dashboard State
const loadingDashboard = ref(true);
const dashboardData = ref<DashboardData>({
    stats: { today: 0, this_month: 0, total: 0 },
    chart_data: [],
    top_brands: []
});

// History State
const entries = ref<any[]>([]);
const provinces = ref<any[]>([]);
const loadingHistory = ref(true);
const searchQuery = ref('');
const isExporting = ref(false);
const showDownloadToast = ref(false);
const downloadStatus = ref<'downloading' | 'complete'>('downloading');
const pagination = ref({ current_page: 1, last_page: 1 });

// Export Modal
const showExportModal = ref(false);
const exportFilters = ref({ start_date: '', end_date: '' });

// Edit State
const editingEntry = ref<any>(null);
const isUpdating = ref(false);
const editForm = ref({
    outlet_name: '',
    province_id: '',
    hanger_id: '',
    notes: ''
});

// Fetch Dashboard Data
const fetchDashboard = async () => {
    loadingDashboard.value = true;
    try {
        const data = await apiCall('/entries/dashboard');
        dashboardData.value = data as any;
    } catch (error) {
        console.error('Failed to fetch dashboard data', error);
    } finally {
        loadingDashboard.value = false;
    }
};

// Debounce search
let searchTimeout: any;
const debounceSearch = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        pagination.value.current_page = 1;
        fetchEntries();
    }, 500);
};

const fetchProvinces = async () => {
    try {
        const data = await apiCall('/provinces');
        provinces.value = data as any[];
    } catch (e) {
        console.error('Failed to load provinces');
    }
};

const fetchEntries = async () => {
    loadingHistory.value = true;
    try {
        const params = new URLSearchParams({
            page: pagination.value.current_page.toString()
        });
        if (searchQuery.value) params.append('search', searchQuery.value);

        const data = await apiCall(`/entries?${params.toString()}`) as any;
        entries.value = data.data;
        pagination.value = {
            current_page: data.current_page,
            last_page: data.last_page
        };
    } catch (error) {
        console.error('Failed to fetch entries', error);
    } finally {
        loadingHistory.value = false;
    }
};

const changePage = (page: number) => {
    pagination.value.current_page = page;
    fetchEntries();
};

const exportCSV = () => {
    showExportModal.value = true;
};

const setExportPreset = (type: 'month' | 'year') => {
    const now = new Date();
    const formatDate = (date: Date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };

    if (type === 'month') {
        const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
        exportFilters.value.start_date = formatDate(firstDay);
        exportFilters.value.end_date = formatDate(now);
    } else if (type === 'year') {
        const firstDay = new Date(now.getFullYear(), 0, 1);
        exportFilters.value.start_date = formatDate(firstDay);
        exportFilters.value.end_date = formatDate(now);
    }
};

const confirmExport = async () => {
    showExportModal.value = false;
    isExporting.value = true;
    showDownloadToast.value = true;
    downloadStatus.value = 'downloading';
    
    try {
        const params = new URLSearchParams();
        if (exportFilters.value.start_date) params.append('start_date', exportFilters.value.start_date);
        if (exportFilters.value.end_date) params.append('end_date', exportFilters.value.end_date);

        const { apiCall } = useApi();
        await apiCall(`/entries/export?${params.toString()}`, { method: 'GET' });
        
        // Show success state
        downloadStatus.value = 'complete';
        
        // Hide toast after showing success
        setTimeout(() => {
            showDownloadToast.value = false;
        }, 3000);
    } catch (error) {
        console.error('Export error', error);
        showDownloadToast.value = false;
        alert('Failed to send export. Please try again.');
    } finally {
        isExporting.value = false;
    }
};

const openEditModal = (entry: any) => {
    editingEntry.value = entry;
    editForm.value = {
        outlet_name: entry.outlet_name,
        province_id: entry.province_id,
        hanger_id: entry.hanger_id,
        notes: entry.notes || ''
    };
};

const updateEntry = async () => {
    isUpdating.value = true;
    try {
        await apiCall(`/entries/${editingEntry.value.id}`, {
            method: 'PUT',
            body: editForm.value
        });
        
        // Update local list
        const index = entries.value.findIndex(e => e.id === editingEntry.value.id);
        if (index !== -1) {
             // Refresh data logic or simple reload
             await fetchEntries(); 
             // Also refresh dashboard if visible
             if(activeTab.value === 'dashboard') fetchDashboard();
        }
        editingEntry.value = null;
    } catch (error) {
        console.error('Update failed', error);
        alert('Failed to update entry');
    } finally {
        isUpdating.value = false;
    }
};

const deleteEntry = async (entry: any) => {
    if (!confirm('Are you sure you want to delete this entry? This cannot be undone.')) return;
    
    try {
        await apiCall(`/entries/${entry.id}`, { method: 'DELETE' });
        entries.value = entries.value.filter(e => e.id !== entry.id);
        // Also refresh dashboard metrics
        fetchDashboard();
    } catch (error) {
        console.error('Delete failed', error);
        alert('Failed to delete entry');
    }
};

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'
    });
};

onMounted(() => {
    fetchProvinces();
    fetchDashboard();
    fetchEntries();
});
</script>
