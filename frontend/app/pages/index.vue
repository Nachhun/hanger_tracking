<template>
  <div class="min-h-screen relative flex items-center justify-center p-4 overflow-hidden">
      <!-- Animated Background -->
      <div class="fixed inset-0 bg-gradient-to-br from-blue-50 via-white to-orange-50 -z-10">
         <div class="absolute top-0 right-0 w-[50%] h-[50%] bg-primary-600/5 rounded-full blur-[120px] animate-float"></div>
         <div class="absolute bottom-0 left-0 w-[40%] h-[40%] bg-orange-600/5 rounded-full blur-[100px] animate-float" style="animation-delay: -4s;"></div>
      </div>

      <transition name="fade" mode="out-in">
        <!-- Loading State -->
        <div v-if="loading" key="loading" class="relative z-10 bg-white rounded-2xl shadow-xl border border-gray-100 p-8 w-full max-w-sm flex flex-col items-center justify-center min-h-[400px]">
           <div class="relative w-16 h-16 mb-8">
             <div class="absolute inset-0 border-4 border-gray-100 rounded-full"></div>
             <div class="absolute inset-0 border-4 border-primary-500 border-t-transparent rounded-full animate-spin"></div>
           </div>
           <h2 class="text-xl font-bold text-gray-900 mb-2 tracking-wide">Authenticating</h2>
           <p class="text-gray-500 text-sm animate-pulse">Securing connection...</p>
        </div>
        
        <!-- Error / Manual Login State -->
        <div v-else-if="error" key="error" class="relative z-10 bg-white rounded-2xl shadow-xl border border-gray-100 p-8 w-full max-w-sm">
          <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-red-50 text-red-500 mb-4 border border-red-100 shadow-sm">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
              </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-900 mb-2">Access Denied</h2>
            <div class="p-3 bg-red-50 border border-red-100 rounded-xl text-red-600 text-xs font-mono break-all leading-relaxed">
               {{ error }}
            </div>
          </div>
          
          <div class="space-y-4">
              <div class="text-xs font-medium text-gray-500 uppercase tracking-wider text-center">- Manual Override -</div>
              <div>
                  <input 
                      v-model="manualDevId" 
                      type="text" 
                      placeholder="Enter Telegram ID"
                      class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 text-gray-900 focus:border-primary-400 focus:ring-1 focus:ring-primary-400 transition-all outline-none"
                      @keyup.enter="handleManualLogin"
                  />
              </div>
              <button 
                @click="handleManualLogin"
                class="w-full py-4 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white font-bold shadow-lg shadow-blue-500/30 transition-all duration-300 transform hover:-translate-y-0.5 border border-blue-400/20 disabled:opacity-50 disabled:cursor-not-allowed font-medium"
                :disabled="!manualDevId"
              >
                Login Manually
              </button>
               <p class="text-[10px] text-center text-gray-400">Restricted to authorized personnel only.</p>
          </div>

          <div class="mt-6 pt-6 border-t border-gray-100">
             <button 
              @click="retry" 
              class="w-full py-2 text-sm text-gray-500 hover:text-primary-600 transition-colors flex items-center justify-center gap-2"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.051M20 20v-5h-.051M9 17h6.2c3 0 5.6-2.2 5.6-5s-2.6-5-5.6-5H10m-7 0a6 6 0 016-6 6 6 0 016 6v3"></path></svg>
              Retry Connection
            </button>
          </div>
        </div>

        <!-- Non-Telegram View -->
        <div v-else key="no-telegram" class="relative z-10 bg-white rounded-2xl shadow-xl border border-gray-100 p-10 w-full max-w-sm text-center">
          <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-6 border border-blue-100 shadow-sm">
            <svg class="w-10 h-10 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.48-.94-2.4-1.54-1.06-.7 1.1-.8 1.1-.8.27 0 .38 0 .58-.08s.16-.36.08-.57l-4.14 5.25c-.24-.26-.85-2.68-.85-2.68L4.85 13c-.9-.4-1.6-.4-2-.25-.43.16-.43.43-.2.6l2 .63 11 3.5c.5.16 1.1.16 1.5.05.4-.1.6-.3.6-.6s-.15-.6-.5-.73l-10.8-3.3-1.4-.4z"/>
            </svg>
          </div>
          <h1 class="text-2xl font-bold text-gray-900 mb-2">Telegram Only</h1>
          <p class="text-gray-500 mb-8 leading-relaxed">This exclusive tool is designed to run within the Telegram environment.</p>
          
          <!-- Fallback Manual Login -->
          <div class="space-y-4 pt-6 border-t border-gray-100">
              <div class="text-xs font-medium text-gray-500 uppercase tracking-wider text-center">- Developer Access -</div>
              <input 
                  v-model="manualDevId" 
                  type="text" 
                  placeholder="Enter Telegram ID"
                  class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 text-gray-900 focus:border-primary-400 focus:ring-1 focus:ring-primary-400 transition-all outline-none"
                  @keyup.enter="handleManualLogin"
              />
              <button 
                @click="handleManualLogin"
                class="w-full py-4 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white font-bold shadow-lg shadow-blue-500/30 transition-all duration-300 transform hover:-translate-y-0.5 border border-blue-400/20 disabled:opacity-50 disabled:cursor-not-allowed font-medium"
                :disabled="!manualDevId"
              >
                Login Manually
              </button>
          </div>
        </div>
      </transition>
  </div>
</template>


<script setup lang="ts">
import { useTelegram } from '~/composables/useTelegram';

const router = useRouter();
const route = useRoute();
const { initData, ready, expand } = useTelegram();
const loading = ref(true);
const error = ref<string | null>(null);
const manualDevId = ref('');

const handleManualLogin = async () => {
    console.log('Manual login triggered. ID:', manualDevId.value);
    if (!manualDevId.value) {
        console.warn('Manual ID is empty');
        return;
    }
    loading.value = true;
    error.value = null; // Clear previous error
    await loginManual(manualDevId.value);
    loading.value = false;
};

const retry = () => {
  window.location.reload();
};

const handleLoginSuccess = (userData: any) => {
  console.log('Handling success with:', userData);
  
  if (userData?.token) {
    // Cookie Setting
    const authToken = useCookie('auth_token', {
      maxAge: 60 * 60 * 24 * 7,
      sameSite: 'lax',
    });
    authToken.value = userData.token;

    const userCookie = useCookie('user', {
      maxAge: 60 * 60 * 24 * 7,
      sameSite: 'lax',
    });
    userCookie.value = userData.user;

    // Fallback: Manually set document cookie to ensure it sticks
    if (import.meta.client) {
        document.cookie = `auth_token=${userData.token}; path=/; max-age=${60 * 60 * 24 * 7}; SameSite=Lax`;
        document.cookie = `user=${encodeURIComponent(JSON.stringify(userData.user))}; path=/; max-age=${60 * 60 * 24 * 7}; SameSite=Lax`;
    }

    // Alert user as requested
    alert(`Login Successful! Welcome, ${userData.user.name}`);

    // Use router to navigate
    const target = userData.user.role === 'admin' ? '/admin/dashboard' : '/entry';
    console.log('Redirecting to:', target);
    
    setTimeout(() => {
        router.push(target).catch(err => {
             console.error('Router Push Error:', err);
             window.location.href = target;
        });
    }, 100);
  }
};

const loginManual = async (telegramId: string) => {
  try {
    const data = await $fetch('/api/auth/login-by-id', {
      method: 'POST',
      body: { telegram_id: telegramId },
    });
    
    console.log('Manual login response:', data);
    handleLoginSuccess(data);
  } catch (err: any) {
    error.value = err.data?.error || 'Login failed: ' + err.message;
    console.error(err);
  }
};

onMounted(async () => {
  console.log('Mounting App...');
  
  // Check if already authenticated (Fallback if middleware didn't redirect)
  const existingToken = useCookie('auth_token');
  const existingUser = useCookie('user');
  if (existingToken.value && existingUser.value) {
     const user = existingUser.value as any;
     console.log('Found existing session, directing to:', user.role);
     router.push(user.role === 'admin' ? '/admin/dashboard' : '/entry');
     return;
  }

  console.log('Window Telegram exists:', !!(window as any).Telegram);
  console.log('WebApp exists:', !!(window as any).Telegram?.WebApp);
  console.log('Raw initData:', (window as any).Telegram?.WebApp?.initData);
  console.log('initDataUnsafe:', (window as any).Telegram?.WebApp?.initDataUnsafe);

  ready();
  expand();

  // Check for Dev ID in URL
  const devId = route.query.dev_id as string;
  if (devId) {
    console.log('Dev ID found, attempting login...');
    await loginManual(devId);
    loading.value = false;
    return;
  }

  if (!initData.value || !initData.value.includes('hash=')) {
    // Not in Telegram, or invalid data (e.g. just "query_id")
    console.warn('Invalid initData:', initData.value);
    error.value = "Invalid Telegram Data. Please use the Manual Login below.";
    loading.value = false;
    return;
  }

    // Telegram Auto Login
    try {
        const data = await $fetch('/api/auth/telegram', {
            method: 'POST',
            body: { initData: initData.value },
        });

        handleLoginSuccess(data);
    } catch (err: any) {
        console.error('Auto-login failed:', err);
        // If 403 (e.g. expired or integrity fail), show error + manual login
        error.value = 'Auto-login failed (likely Bot Token mismatch). Please use Manual Login below.';
    } finally {
        loading.value = false;
    }
});
</script>
