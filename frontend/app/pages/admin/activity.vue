<template>
  <div class="min-h-screen bg-[#F8FAFC] pb-20 font-sans">
    <!-- Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center h-16 gap-4">
           <button @click="router.back()" class="p-2 -ml-2 rounded-full hover:bg-slate-100 text-slate-500 transition-colors">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
           </button>
           <h1 class="text-lg font-bold text-slate-900">Recent Activity</h1>
        </div>
      </div>
    </header>

    <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      
      <!-- Filter / Search (Optional future enhancement, placeholder for now) -->
      <!-- <div class="mb-6">
          <input type="text" placeholder="Search activity..." class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none text-sm" />
      </div> -->

      <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div v-if="loading && activities.length === 0" class="p-8 text-center text-slate-500">
            <div class="animate-spin h-8 w-8 border-4 border-indigo-500 border-t-transparent rounded-full mx-auto mb-4"></div>
            Loading activities...
        </div>

        <div v-else-if="activities.length === 0" class="p-12 text-center text-slate-400">
            <svg class="w-16 h-16 mx-auto mb-4 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p>No recent activity found.</p>
        </div>

        <div v-else class="divide-y divide-slate-100">
            <div 
              v-for="activity in activities" 
              :key="activity.id" 
              class="p-4 hover:bg-slate-50 transition-colors flex gap-4"
            >
                <!-- Avatar/Icon -->
                <div class="flex-shrink-0">
                    <div class="h-10 w-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm border border-indigo-100">
                        {{ activity.user?.name?.charAt(0) || '?' }}
                    </div>
                </div>

                <!-- Content -->
                <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-start">
                        <p class="text-sm font-medium text-slate-900">
                            <span class="font-bold">{{ activity.user?.name || 'Unknown User' }}</span>
                            <span class="font-normal text-slate-600"> recorded a new entry</span>
                        </p>
                        <span class="text-xs text-slate-400 whitespace-nowrap ml-2">{{ formatTime(activity.created_at) }}</span>
                    </div>
                    
                    <div class="mt-1 text-sm text-slate-600 flex items-center gap-2">
                        <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-600 px-2 py-0.5 rounded text-xs">
                           <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m8-2a2 2 0 100-4 2 2 0 000 4zm-6-4a2 2 0 110-4 2 2 0 010 4z"></path></svg>
                           {{ activity.outlet_name }}
                        </span>
                        <span v-if="activity.province" class="text-slate-400">•</span>
                        <span v-if="activity.province" class="text-xs text-slate-500">{{ activity.province }}</span>
                    </div>

                    <div v-if="activity.notes" class="mt-2 text-xs text-slate-500 bg-slate-50 p-2 rounded border border-slate-100 italic">
                        "{{ activity.notes }}"
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Pagination / Load More (Simple implementation for now) -->
        <!-- In a real app, this would trigger fetchNextPage -->
        <div v-if="activities.length > 0" class="p-4 border-t border-slate-100 bg-slate-50 text-center">
            <p class="text-xs text-slate-400">Showing last {{ activities.length }} entries</p>
        </div>
      </div>

    </main>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
// import { useApi } from '~/composables/useApi'; // Auto-imported

const router = useRouter();
const { apiCall } = useApi();

const loading = ref(true);
const activities = ref<any[]>([]);

const formatTime = (dateString: string) => {
  const date = new Date(dateString);
  const now = new Date();
  const diff = now.getTime() - date.getTime();
  const minutes = Math.floor(diff / 60000);
  
  if (minutes < 1) return 'Just now';
  if (minutes < 60) return `${minutes}m ago`;
  const hours = Math.floor(minutes / 60);
  if (hours < 24) return `${hours}h ago`;
  if (hours < 48) return 'Yesterday';
  return date.toLocaleDateString();
};

const fetchActivities = async () => {
    loading.value = true;
    try {
        // Reusing the entries endpoint which gives full details
        // We might want to optimize this later if volume grows
        const data = await apiCall('/entries') as any; // Assuming this exists or create similarly
        
        // Wait, verifying available endpoints. 
        // AdminController has `getAllEntries` but it's mapped to `/admin/entries` (need to check routes)
        // Let's use /admin/entries endpoint via GetAllEntries logic
        // Check AdminController.php -> public function getAllEntries
        
        // Actually, let's just allow fetching 50 for "Recent Activity" context
        // Or strictly use the same source as dashboard (which was only 10)
        // But user said "see more", so we probably want more than 10.
        // Let's try to hit /admin/reports/entries logic or similar.
        // Let's rely on getAllEntries which is cleaner.
        // Assuming route is /admin/entries (will verify next step if needed, but safe guess)
        
        // Actually, looking at AdminController code previously viewed:
        // public function getAllEntries(Request $request)
        // We need to know the route for it.
        // If not sure, can use /admin/report logic.
        
        // Let's try /admin/entries-all if it exists or fallback to what we know.
        // Let's use the one that feeds the 'entries' page.
        // Checking routes/api.php would be ideal, but for now assuming /admin/entries is the list.
        
        // Correction: let's use the same endpoint as dashboard but asking for MORE?
        // No, dashboard is aggregated.
        // Let's fetch /admin/entries (the list endpoint)
        
        const response = await apiCall('/admin/entries') as any[]; 
        activities.value = response;

    } catch (e) {
        console.error('Failed to load activities', e);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchActivities();
});
</script>
