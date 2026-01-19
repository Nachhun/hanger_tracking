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
        <h1 class="text-xl font-bold">Manage Users</h1>
        <div class="w-10"></div> <!-- Spacer -->
      </div>
    </div>

    <div class="p-4 max-w-4xl mx-auto space-y-6">
      <!-- Add New Salesman -->
      <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
          <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
          </svg>
          Add New User
        </h2>

        <form @submit.prevent="createUser" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
              <input 
                v-model="newUser.name"
                type="text" 
                placeholder="e.g. John Doe"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              />
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Telegram ID</label>
              <input 
                v-model="newUser.telegram_id"
                type="number" 
                placeholder="e.g. 12345678"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              />
              <p class="text-xs text-gray-500 mt-1">Get this from the user's Telegram profile or bot.</p>
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Role</label>
              <select 
                v-model="newUser.role"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white"
              >
                <option value="salesman">Salesman</option>
                <option value="admin">Admin</option>
              </select>
            </div>
          </div>

          <div class="flex justify-end">
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
              {{ isSubmitting ? 'Adding...' : 'Add Salesman' }}
            </button>
          </div>
          
          <p v-if="error" class="text-red-500 text-sm mt-2">{{ error }}</p>
          <p v-if="success" class="text-green-500 text-sm mt-2">{{ success }}</p>
        </form>
      </div>

      <!-- Administrators List -->
      <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-purple-50">
          <h2 class="text-lg font-bold text-purple-900 flex items-center">
            <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
            Administrators
          </h2>
          <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ adminUsers.length }}</span>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
              <tr>
                <th scope="col" class="px-6 py-3">Name</th>
                <th scope="col" class="px-6 py-3">Telegram ID</th>
                <th scope="col" class="px-6 py-3">Status</th>
                <th scope="col" class="px-6 py-3 text-right">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="adminUsers.length === 0">
                <td colspan="4" class="px-6 py-8 text-center text-gray-400">
                  No administrators found.
                </td>
              </tr>
              <tr 
                v-for="user in adminUsers" 
                :key="user.id" 
                class="bg-white border-b hover:bg-gray-50 transition-colors"
              >
                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                  {{ user.name }}
                </td>
                <td class="px-6 py-4 font-mono text-gray-600">
                  {{ user.telegram_id || 'N/A' }}
                </td>
                <td class="px-6 py-4">
                  <span 
                    class="px-2 py-1 text-xs font-semibold rounded-full"
                    :class="user.is_active ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800'"
                  >
                    {{ user.is_active ? 'Active' : 'Inactive' }}
                  </span>
                </td>
                <td class="px-6 py-4 text-right">
                  <button 
                    @click="router.push(`/admin/users/${user.id}`)"
                    class="font-medium text-blue-600 hover:underline"
                  >
                    View
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Salesmen List -->
      <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-blue-50">
          <h2 class="text-lg font-bold text-blue-900 flex items-center">
            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            Registered Salesmen
          </h2>
          <div class="flex items-center gap-4">
            <div class="relative">
              <input 
                v-model="searchQuery"
                type="text" 
                placeholder="Search salesmen..." 
                class="pl-9 pr-4 py-1.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent w-48 transition-all focus:w-64"
              >
              <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
              </div>
            </div>
            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ salesmanUsers.length }}</span>
          </div>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
              <tr>
                <th scope="col" class="px-6 py-3">Name</th>
                <th scope="col" class="px-6 py-3">Telegram ID</th>
                <th scope="col" class="px-6 py-3">Status</th>
                <th scope="col" class="px-6 py-3 text-right">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="salesmanUsers.length === 0">
                <td colspan="4" class="px-6 py-8 text-center text-gray-400">
                  No salesmen found matching your search.
                </td>
              </tr>
              <tr 
                v-for="user in salesmanUsers" 
                :key="user.id" 
                class="bg-white border-b hover:bg-gray-50 transition-colors"
              >
                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                  {{ user.name }}
                </td>
                <td class="px-6 py-4 font-mono text-gray-600">
                  {{ user.telegram_id || 'N/A' }}
                </td>
                <td class="px-6 py-4">
                  <span 
                    class="px-2 py-1 text-xs font-semibold rounded-full"
                    :class="user.is_active ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800'"
                  >
                    {{ user.is_active ? 'Active' : 'Inactive' }}
                  </span>
                </td>
                <td class="px-6 py-4 text-right">
                  <button 
                    @click="router.push(`/admin/users/${user.id}`)"
                    class="font-medium text-blue-600 hover:underline"
                  >
                    View
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useApi } from '~/composables/useApi';

const router = useRouter();
const { apiCall } = useApi();

const users = ref<any[]>([]);
const isSubmitting = ref(false);
const error = ref<string | null>(null);
const success = ref<string | null>(null);

const newUser = ref({
  name: '',
  telegram_id: '',
  role: 'salesman'
});

const searchQuery = ref('');

// Decouple search: Admin list is always full list
const adminUsers = computed(() => {
  return users.value.filter(user => user.role === 'admin');
});

// Salesman list is filtered by search query
const salesmanUsers = computed(() => {
  let list = users.value.filter(user => user.role === 'salesman');
  
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    list = list.filter(user => 
      user.name.toLowerCase().includes(query) || 
      (user.telegram_id && String(user.telegram_id).includes(query))
    );
  }
  
  return list;
});

const fetchUsers = async () => {
  try {
    const data = await apiCall('/admin/users');
    users.value = data as any[];
  } catch (err) {
    console.error('Failed to fetch users', err);
  }
};

const createUser = async () => {
  isSubmitting.value = true;
  error.value = null;
  success.value = null;

  try {
    await apiCall('/admin/users', {
      method: 'POST',
      body: newUser.value
    });
    
    success.value = 'User added successfully!';
    newUser.value = { name: '', telegram_id: '', role: 'salesman' };
    await fetchUsers(); // Refresh list
    
    setTimeout(() => success.value = null, 3000);
  } catch (err: any) {
    console.error('Failed to create user', err);
    // Show the actual validation error if available
    if (err.data?.errors?.telegram_id) {
      error.value = `Telegram ID error: ${err.data.errors.telegram_id[0]}`;
    } else if (err.data?.message) {
      error.value = err.data.message;
    } else {
      error.value = 'Failed to create user. Please check the Telegram ID is unique and try again.';
    }
  } finally {
    isSubmitting.value = false;
  }
};

onMounted(() => {
  fetchUsers();
});
</script>
