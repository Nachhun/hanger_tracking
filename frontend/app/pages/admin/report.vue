<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 pb-20">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between sticky top-0 z-20 shadow-sm">
        <div class="flex items-center gap-3">
            <button @click="router.push('/admin/dashboard')" class="p-2 hover:bg-gray-100 rounded-full transition-colors text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7m8 14l-7-7 7-7"></path></svg>
            </button>
            <h1 class="text-xl font-bold text-gray-800">Sales Reports</h1>
        </div>
        <div class="flex gap-2">
            <button 
                @click="exportReport" 
                :disabled="isExporting"
                class="flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors shadow-sm disabled:opacity-50"
            >
                <svg v-if="!isExporting" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                <svg v-else class="animate-spin h-5 w-5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                Export CSV
            </button>
        </div>
    </div>

    <div class="p-6 max-w-7xl mx-auto space-y-6">
        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
            <h2 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Filters</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Date Range</label>
                    <div class="flex items-center gap-2">
                        <input v-model="filters.start_date" type="date" class="w-full p-2 border rounded-lg text-sm">
                        <span class="text-gray-400">-</span>
                        <input v-model="filters.end_date" type="date" class="w-full p-2 border rounded-lg text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Salesman</label>
                    <select v-model="filters.user_id" class="w-full p-2 border rounded-lg text-sm bg-white">
                        <option value="">All Salesmen</option>
                        <option v-for="user in salesmen" :key="user.id" :value="user.id">{{ user.name }}</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Province</label>
                    <select v-model="filters.province_id" class="w-full p-2 border rounded-lg text-sm bg-white">
                        <option value="">All Provinces</option>
                        <option v-for="prov in provinces" :key="prov.id" :value="prov.id">{{ prov.name }}</option>
                    </select>
                </div>
                
                 <div class="flex items-end">
                    <button @click="fetchReport" class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition-colors shadow-sm">
                        Apply Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 text-center">
                <p class="text-xs font-bold text-gray-500 uppercase">Total Entries</p>
                <p class="text-3xl font-bold text-blue-600 mt-2">{{ stats.total_entries }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 text-center">
                <p class="text-xs font-bold text-gray-500 uppercase">Distinct Outlets</p>
                <p class="text-3xl font-bold text-indigo-600 mt-2">{{ stats.total_outlets }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 text-center">
                <p class="text-xs font-bold text-gray-500 uppercase">Hangers Sold</p>
                <p class="text-3xl font-bold text-green-600 mt-2">{{ stats.total_hangers }}</p>
            </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
             <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-xs text-gray-500 uppercase">
                            <th class="p-4 font-bold">Date</th>
                            <th class="p-4 font-bold">Salesman</th>
                            <th class="p-4 font-bold">Outlet</th>
                            <th class="p-4 font-bold">Province</th>
                            <th class="p-4 font-bold">Hanger ID</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        <tr v-if="loading" class="text-center">
                            <td colspan="5" class="p-8"><div class="animate-spin h-8 w-8 border-4 border-blue-500 border-t-transparent rounded-full mx-auto"></div></td>
                        </tr>
                        <tr v-else-if="entries.length === 0" class="text-center">
                             <td colspan="5" class="p-8 text-gray-500">No data matches your filters.</td>
                        </tr>
                        <tr v-for="entry in entries" :key="entry.id" class="hover:bg-gray-50 transition-colors">
                            <td class="p-4 text-gray-600">{{ new Date(entry.created_at).toLocaleDateString() }}</td>
                            <td class="p-4 font-medium text-gray-900">{{ entry.salesman }}</td>
                            <td class="p-4 text-gray-900">{{ entry.outlet_name }}</td>
                             <td class="p-4 text-gray-600">{{ entry.province }}</td>
                            <td class="p-4 font-mono text-blue-600 bg-blue-50/50 rounded inline-block my-2 mx-4 text-xs">{{ entry.hanger_id }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div v-if="pagination.last_page > 1" class="p-4 border-t border-gray-200 flex justify-center gap-2">
                 <button 
                    @click="changePage(pagination.current_page - 1)"
                    :disabled="pagination.current_page === 1"
                    class="px-3 py-1 border rounded hover:bg-gray-50 disabled:opacity-50 text-sm"
                 >
                    Prev
                 </button>
                 <span class="text-sm self-center text-gray-600">Page {{ pagination.current_page }} of {{ pagination.last_page }}</span>
                 <button 
                    @click="changePage(pagination.current_page + 1)"
                    :disabled="pagination.current_page === pagination.last_page"
                    class="px-3 py-1 border rounded hover:bg-gray-50 disabled:opacity-50 text-sm"
                 >
                    Next
                 </button>
            </div>
        </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const router = useRouter();
const { apiCall } = useApi();

const salesmen = ref<any[]>([]);
const provinces = ref<any[]>([]);
const entries = ref<any[]>([]);
const stats = ref({ total_entries: 0, total_outlets: 0, total_hangers: 0 });
const loading = ref(false);
const isExporting = ref(false);
const pagination = ref({ current_page: 1, last_page: 1 });

const filters = ref({
    start_date: '',
    end_date: '',
    user_id: '',
    province_id: ''
});

// Load initial data for dropdowns
onMounted(async () => {
    try {
        const [usersData, provincesData] = await Promise.all([
            apiCall('/admin/users'),
            apiCall('/provinces')
        ]);
        salesmen.value = (usersData as any[]).filter((u: any) => u.role === 'salesman');
        provinces.value = provincesData as any[];
        
        // Default to current month (Local time)
        const now = new Date();
        const formatDate = (date: Date) => {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        };

        const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
        filters.value.start_date = formatDate(firstDay);
        filters.value.end_date = formatDate(now);

        fetchReport();
    } catch (e) {
        console.error('Init error', e);
    }
});

const fetchReport = async () => {
    loading.value = true;
    try {
        const params = new URLSearchParams();
        params.append('page', pagination.value.current_page.toString());
        
        if (filters.value.start_date) params.append('start_date', filters.value.start_date);
        if (filters.value.end_date) params.append('end_date', filters.value.end_date);
        if (filters.value.user_id) params.append('user_id', String(filters.value.user_id));
        if (filters.value.province_id) params.append('province_id', String(filters.value.province_id));

        const data = await apiCall(`/admin/report?${params.toString()}`) as any;
        stats.value = data.stats;
        entries.value = data.entries.data;
        pagination.value = {
            current_page: data.entries.current_page,
            last_page: data.entries.last_page
        };
    } catch (error) {
        console.error('Report error', error);
    } finally {
        loading.value = false;
    }
};

const changePage = (page: number) => {
    pagination.value.current_page = page;
    fetchReport();
};

const exportReport = async () => {
    isExporting.value = true;
    try {
        const token = useCookie('auth_token').value;
        const config = useRuntimeConfig();
        const params = new URLSearchParams(filters.value as any).toString();
        
        const response = await fetch(`${config.public.apiBase}/admin/report/export?${params}`, {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });
        
        if (!response.ok) throw new Error('Export failed');
        
        const blob = await response.blob();
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `report-${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
    } catch (error) {
        console.error('Export error', error);
        alert('Failed to export report');
    } finally {
        isExporting.value = false;
    }
};
</script>
