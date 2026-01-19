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
            <h1 class="text-lg font-bold text-gray-900 tracking-wide">Settings</h1>
            <p class="text-[10px] text-primary-600 uppercase tracking-widest">Admin Configuration</p>
        </div>
        <div class="w-10"></div>
      </div>
    </div>

    <!-- Profile Section -->
    <div class="px-4 mb-8">
      <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
        <h2 class="text-sm font-bold text-gray-900 mb-4 flex items-center uppercase tracking-wider">
          <div class="w-1 h-4 bg-gradient-to-b from-primary-500 to-blue-600 rounded-full mr-3"></div>
          Profile Information
        </h2>
        
        <div class="space-y-4">
          <div>
            <label class="block text-xs font-medium text-gray-700 mb-2 ml-1">Full Name</label>
            <input 
              v-model="profile.name"
              type="text" 
              placeholder="John Doe"
              class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 text-gray-900 placeholder-gray-400 focus:border-primary-400 focus:ring-1 focus:ring-primary-400 transition-all outline-none"
            />
          </div>

          <div>
            <label class="block text-xs font-medium text-gray-700 mb-2 ml-1">Email Address</label>
            <input 
              v-model="profile.email"
              type="email" 
              placeholder="admin@example.com"
              class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 text-gray-900 placeholder-gray-400 focus:border-primary-400 focus:ring-1 focus:ring-primary-400 transition-all outline-none"
            />
          </div>

          <div>
            <label class="block text-xs font-medium text-gray-700 mb-2 ml-1">Telegram ID</label>
            <input 
              v-model="profile.telegramId"
              type="text" 
              disabled
              class="w-full px-4 py-3 rounded-xl bg-gray-100 border border-gray-200 text-gray-500 cursor-not-allowed outline-none"
            />
            <p class="text-[10px] text-gray-500 mt-1 ml-1">This cannot be changed</p>
          </div>

          <button 
            @click="updateProfile"
            class="w-full py-3 rounded-xl bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-400 hover:to-amber-500 text-white font-bold shadow-md transition-all"
          >
            Save Changes
          </button>
        </div>
      </div>
    </div>

    <!-- Password Section -->
    <div class="px-4 mb-8">
      <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
        <h2 class="text-sm font-bold text-gray-900 mb-4 flex items-center uppercase tracking-wider">
          <div class="w-1 h-4 bg-gradient-to-b from-primary-500 to-blue-600 rounded-full mr-3"></div>
          Change Password
        </h2>
        
        <div class="space-y-4">
          <div>
            <label class="block text-xs font-medium text-gray-700 mb-2 ml-1">Current Password</label>
            <input 
              v-model="password.current"
              type="password" 
              placeholder="••••••••"
              class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 text-gray-900 focus:border-primary-400 focus:ring-1 focus:ring-primary-400 transition-all outline-none"
            />
          </div>

          <div>
            <label class="block text-xs font-medium text-gray-700 mb-2 ml-1">New Password</label>
            <input 
              v-model="password.new"
              type="password" 
              placeholder="••••••••"
              class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 text-gray-900 focus:border-primary-400 focus:ring-1 focus:ring-primary-400 transition-all outline-none"
            />
          </div>

          <div>
            <label class="block text-xs font-medium text-gray-700 mb-2 ml-1">Confirm New Password</label>
            <input 
              v-model="password.confirm"
              type="password" 
              placeholder="••••••••"
              class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 text-gray-900 focus:border-primary-400 focus:ring-1 focus:ring-primary-400 transition-all outline-none"
            />
          </div>

          <button 
            @click="changePassword"
            class="w-full py-3 rounded-xl bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-400 hover:to-amber-500 text-white font-bold shadow-md transition-all"
          >
            Update Password
          </button>
        </div>
      </div>
    </div>

    <!-- Notification Settings -->
    <div class="px-4 mb-8">
      <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
        <h2 class="text-sm font-bold text-gray-900 mb-4 flex items-center uppercase tracking-wider">
          <div class="w-1 h-4 bg-gradient-to-b from-primary-500 to-blue-600 rounded-full mr-3"></div>
          Notifications
        </h2>
        
        <div class="space-y-4">
          <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
            <div>
              <p class="text-sm font-semibold text-gray-900">New Entry Alerts</p>
              <p class="text-[10px] text-gray-500">Get notified when salesmen submit entries</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
              <input type="checkbox" v-model="notifications.newEntries" class="sr-only peer">
              <div class="w-11 h-6 bg-gray-200 peer-focus:ring-2 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500"></div>
            </label>
          </div>

          <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
            <div>
              <p class="text-sm font-semibold text-gray-900">Daily Summary</p>
              <p class="text-[10px] text-gray-500">Receive daily performance reports</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
              <input type="checkbox" v-model="notifications.dailySummary" class="sr-only peer">
              <div class="w-11 h-6 bg-gray-200 peer-focus:ring-2 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500"></div>
            </label>
          </div>

          <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
            <div>
              <p class="text-sm font-semibold text-gray-900">Low Activity Alerts</p>
              <p class="text-[10px] text-gray-500">Alert when salesmen are inactive</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
              <input type="checkbox" v-model="notifications.lowActivity" class="sr-only peer">
              <div class="w-11 h-6 bg-gray-200 peer-focus:ring-2 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500"></div>
            </label>
          </div>
        </div>
      </div>
    </div>

    <!-- System Actions -->
    <div class="px-4 pb-4">
      <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
        <h2 class="text-sm font-bold text-gray-900 mb-4 flex items-center uppercase tracking-wider">
          <div class="w-1 h-4 bg-gradient-to-b from-primary-500 to-blue-600 rounded-full mr-3"></div>
          System
        </h2>
        
        <div class="space-y-3">
          <button class="w-full py-3 px-4 bg-gray-50 hover:bg-gray-100 border border-gray-200 text-gray-700 rounded-xl transition-all text-left flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
              </div>
              <span class="font-medium text-sm">About System</span>
            </div>
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
          </button>

          <button @click="logout" class="w-full py-3 px-4 bg-red-50 hover:bg-red-100 border border-red-200 text-red-700 rounded-xl transition-all text-left flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
              </div>
              <span class="font-medium text-sm">Logout</span>
            </div>
            <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const router = useRouter();

// Profile data
const profile = ref({
  name: 'Admin User',
  email: 'admin@example.com',
  telegramId: '6821234179',
});

// Password data
const password = ref({
  current: '',
  new: '',
  confirm: '',
});

// Notification settings
const notifications = ref({
  newEntries: true,
  dailySummary: true,
  lowActivity: false,
});

// Methods
const updateProfile = async () => {
  try {
    // TODO: Implement profile update API call
    alert('Profile updated successfully!');
  } catch (error) {
    console.error('Failed to update profile:', error);
    alert('Failed to update profile');
  }
};

const changePassword = async () => {
  if (password.value.new !== password.value.confirm) {
    alert('New passwords do not match!');
    return;
  }
  
  try {
    // TODO: Implement password change API call
    alert('Password changed successfully!');
    password.value = { current: '', new: '', confirm: '' };
  } catch (error) {
    console.error('Failed to change password:', error);
    alert('Failed to change password');
  }
};

const logout = () => {
  const authToken = useCookie('auth_token');
  const userCookie = useCookie('user');
  
  authToken.value = null;
  userCookie.value = null;
  
  router.push('/');
};

// Load user data on mount
onMounted(async () => {
  try {
    const userCookie = useCookie('user');
    if (userCookie.value) {
      const user = userCookie.value as any;
      profile.value = {
        name: user.name || 'Admin User',
        email: user.email || 'admin@example.com',
        telegramId: user.telegram_id || '6821234179',
      };
    }
  } catch (error) {
    console.error('Failed to load user data:', error);
  }
});
</script>
