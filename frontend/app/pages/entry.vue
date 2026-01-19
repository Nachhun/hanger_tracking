<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-700 pb-24">
    <!-- Header -->
    <div class="bg-white/10 backdrop-blur-xl border-b border-white/20 sticky top-0 z-50 px-4 py-3 shadow-lg">
      <div class="flex items-center justify-between max-w-lg mx-auto">
        <button @click="router.push('/')" class="p-2.5 text-white hover:bg-white/20 rounded-xl transition-all active:scale-95">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
          </svg>
        </button>
        <div class="text-center">
            <h1 class="text-base font-bold text-white tracking-tight">New Entry</h1>
            <p class="text-[10px] text-white/80 font-medium">{{ currentDate }}</p>
        </div>
        <button @click="router.push('/salesman/history')" class="p-2.5 text-white hover:bg-white/20 rounded-xl transition-all active:scale-95">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </button>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="px-4 py-6 max-w-lg mx-auto">
      <div class="grid grid-cols-2 gap-3">
        <div class="bg-white/95 backdrop-blur-lg rounded-2xl p-4 shadow-xl border border-white/50">
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
              <p class="text-2xl font-b old text-gray-900">{{ todayEntries }}</p>
              <p class="text-xs text-gray-600 font-medium">Today</p>
            </div>
          </div>
        </div>
        <div class="bg-white/95 backdrop-blur-lg rounded-2xl p-4 shadow-xl border border-white/50">
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            </div>
            <div>
              <p class="text-2xl font-bold text-gray-900">{{ totalHangers }}</p>
              <p class="text-xs text-gray-600 font-medium">Total</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Entry Form -->
    <div class="px-4 max-w-lg mx-auto">
      <form @submit.prevent="submitEntry" class="space-y-4">
        
        <!-- Outlet Name -->
        <div class="bg-white/95 backdrop-blur-lg rounded-2xl p-5 shadow-xl border border-white/50">
          <label class="block text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            Outlet Name
            <span class="text-red-500">*</span>
          </label>
          <input 
            v-model="form.outletName"
            type="text" 
            required
            placeholder="Enter outlet name"
            class="w-full px-4 py-3.5 rounded-xl bg-gray-50 border-2 border-gray-200 text-gray-900 text-base placeholder-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all outline-none"
          />
        </div>

        <!-- Province & Brand Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <!-- Province -->
          <div class="bg-white/95 backdrop-blur-lg rounded-2xl p-5 shadow-xl border border-white/50">
            <label class="block text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
              <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
              Province
              <span class="text-red-500">*</span>
            </label>
            <div class="relative">
              <select 
                v-model="form.province"
                required
                class="w-full px-4 py-3.5 rounded-xl bg-gray-50 border-2 border-gray-200 text-gray-900 text-base focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all outline-none appearance-none"
              >
                <option value="">Select</option>
                <option v-for="province in provinces" :key="province.id" :value="province.id">
                  {{ province.name }}
                </option>
              </select>
              <div class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
              </div>
            </div>
          </div>

          <!-- Brand -->
          <div class="bg-white/95 backdrop-blur-lg rounded-2xl p-5 shadow-xl border border-white/50">
            <label class="block text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
              <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
              Brand
              <span class="text-red-500">*</span>
            </label>
            <div class="relative">
              <select 
                v-model="form.brand"
                required
                class="w-full px-4 py-3.5 rounded-xl bg-gray-50 border-2 border-gray-200 text-gray-900 text-base focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all outline-none appearance-none"
              >
                <option value="">Select</option>
                <option v-for="brand in brands" :key="brand.id" :value="brand.id">
                  {{ brand.name }}
                </option>
              </select>
              <div class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
              </div>
            </div>
          </div>
        </div>

        <!-- Hanger ID -->
        <div class="bg-white/95 backdrop-blur-lg rounded-2xl p-5 shadow-xl border border-white/50">
          <label class="block text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path></svg>
            Hanger ID
            <span class="text-red-500">*</span>
          </label>
          <input 
            v-model="form.hangerId"
            type="text" 
            required
            placeholder="e.g. HGR-001"
            class="w-full px-4 py-3.5 rounded-xl bg-gray-50 border-2 border-gray-200 text-gray-900 text-base font-mono placeholder-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all outline-none"
          />
        </div>

        <!-- GPS Location -->
        <div class="bg-white/95 backdrop-blur-lg rounded-2xl p-5 shadow-xl border border-white/50">
          <label class="block text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
            GPS Location
          </label>
          
          <div v-if="gpsLoading" class="flex items-center justify-center gap-3 py-4">
            <div class="w-5 h-5 border-3 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
            <span class="text-sm text-gray-600 font-medium">Detecting location...</span>
          </div>
          
          <div v-else-if="form.latitude && form.longitude" class="space-y-3">
            <div class="flex items-center gap-3 p-3 bg-green-50 border-2 border-green-200 rounded-xl">
              <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-green-800">Location Captured</p>
                <p class="text-xs text-green-700 font-mono truncate">{{ form.latitude.toFixed(6) }}, {{ form.longitude.toFixed(6) }}</p>
              </div>
            </div>
            <button 
              type="button"
              @click="getGPSLocation" 
              class="w-full px-4 py-2.5 bg-white border-2 border-gray-200 text-gray-700 rounded-xl font-semibold text-sm hover:bg-gray-50 active:scale-[0.98] transition-all"
            >
              Refresh Location
            </button>
          </div>
          
          <button 
            v-else
            type="button"
            @click="getGPSLocation" 
            class="w-full px-4 py-3.5 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl font-bold shadow-lg hover:shadow-xl hover:from-green-600 hover:to-green-700 active:scale-[0.98] transition-all flex items-center justify-center gap-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            Get My Location
          </button>
        </div>

        <!-- Notes (Optional) -->
        <div class="bg-white/95 backdrop-blur-lg rounded-2xl p-5 shadow-xl border border-white/50">
          <label class="block text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
            Notes (Optional)
          </label>
          <textarea 
            v-model="form.notes"
            rows="3"
            placeholder="Add any additional notes..."
            class="w-full px-4 py-3.5 rounded-xl bg-gray-50 border-2 border-gray-200 text-gray-900 text-base placeholder-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all outline-none resize-none"
          ></textarea>
        </div>

        <!-- Photo Upload -->
        <div class="bg-white/95 backdrop-blur-lg rounded-2xl p-5 shadow-xl border border-white/50">
          <label class="block text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
            <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            Photo
            <span class="text-red-500">*</span>
          </label>
          
          <!-- Hidden inputs -->
          <input 
            ref="photoInputCamera"
            type="file" 
            accept="image/*"
            capture="environment"
            @change="handlePhoto"
            class="hidden"
          />
          <input 
            ref="photoInputGallery"
            type="file" 
            accept="image/*"
            @change="handlePhoto"
            class="hidden"
          />
          
          <div v-if="photoPreview" class="space-y-3">
            <div class="relative rounded-xl overflow-hidden border-2 border-gray-200">
              <img :src="photoPreview" class="w-full h-48 object-cover" alt="Preview" />
              <button 
                type="button"
                @click="removePhoto"
                class="absolute top-2 right-2 w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow-lg active:scale-90 transition-all"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
              </button>
            </div>
            <div class="grid grid-cols-2 gap-3">
              <button 
                type="button"
                @click="openCamera"
                class="px-4 py-2.5 bg-white border-2 border-gray-200 text-gray-700 rounded-xl font-semibold text-sm hover:bg-gray-50 active:scale-[0.98] transition-all flex items-center justify-center gap-2"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                Retake
              </button>
              <button 
                type="button"
                @click="photoInputGallery?.click()"
                class="px-4 py-2.5 bg-white border-2 border-gray-200 text-gray-700 rounded-xl font-semibold text-sm hover:bg-gray-50 active:scale-[0.98] transition-all flex items-center justify-center gap-2"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Gallery
              </button>
            </div>
          </div>
          
          <div v-else class="grid grid-cols-2 gap-3">
            <button 
              type="button"
              @click="openCamera"
              class="px-4 py-3.5 bg-gradient-to-r from-pink-500 to-pink-600 text-white rounded-xl font-bold shadow-lg hover:shadow-xl hover:from-pink-600 hover:to-pink-700 active:scale-[0.98] transition-all flex flex-col items-center justify-center gap-2"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
              <span class="text-sm">Take Photo *</span>
            </button>
            <button 
              type="button"
              @click="photoInputGallery?.click()"
              class="px-4 py-3.5 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-xl font-bold shadow-lg hover:shadow-xl hover:from-purple-600 hover:to-purple-700 active:scale-[0.98] transition-all flex flex-col items-center justify-center gap-2"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
              <span class="text-sm">From Gallery</span>
            </button>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-4 pb-safe">
          <button 
            type="submit"
            :disabled="isSubmitting"
            class="w-full px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-2xl font-bold text-lg shadow-2xl hover:shadow-blue-500/50 hover:from-blue-700 hover:to-blue-800 disabled:opacity-50 disabled:cursor-not-allowed active:scale-[0.98] transition-all flex items-center justify-center gap-3"
          >
            <svg v-if="isSubmitting" class="animate-spin h-6 w-6" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            {{ isSubmitting ? 'Submitting...' : 'Submit Entry' }}
          </button>
        </div>
      </form>
    </div>

    <!-- Camera Modal -->
    <div v-if="showCamera" class="fixed inset-0 bg-black z-[200] flex flex-col">
      <!-- Camera Header -->
      <div class="bg-black/50 backdrop-blur-sm px-4 py-3 flex items-center justify-between">
        <button @click="closeCamera" class="p-2 text-white hover:bg-white/20 rounded-full transition-all">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <p class="text-white font-semibold">Take Photo</p>
        <div class="w-10"></div>
      </div>

      <!-- Camera Video Stream -->
      <div class="flex-1 relative bg-black flex items-center justify-center">
        <video 
          ref="videoElement"
          autoplay 
          playsinline
          class="w-full h-full object-cover"
        ></video>
        
        <!-- Camera guide overlay -->
        <div class="absolute inset-0 pointer-events-none">
          <div class="absolute inset-8 border-2 border-white/30 rounded-2xl"></div>
        </div>
      </div>

      <!-- Camera Controls -->
      <div class="bg-black/50 backdrop-blur-sm px-4 py-6 flex items-center justify-center gap-8">
        <!-- Flip Camera Button -->
        <button 
          @click="flipCamera"
          class="w-12 h-12 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center transition-all"
        >
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
        </button>

        <!-- Capture Button -->
        <button 
          @click="capturePhoto"
          class="w-20 h-20 bg-white rounded-full border-4 border-blue-500 hover:scale-105 active:scale-95 transition-all shadow-2xl flex items-center justify-center"
        >
          <div class="w-16 h-16 bg-white rounded-full"></div>
        </button>

        <!-- Gallery Button -->
        <button 
          @click="openGalleryFromCamera"
          class="w-12 h-12 bg-white/20 hover:bg-white/30 rounded-lg flex items-center justify-center transition-all overflow-hidden"
        >
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        </button>
      </div>
    </div>

    <!-- Success Toast -->
    <transition name="bounce">
      <div v-if="showSuccessToast" class="fixed bottom-24 left-1/2 transform -translate-x-1/2 z-[100] px-4 w-full max-w-sm">
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-4 rounded-2xl shadow-2xl border-2 border-white/30 backdrop-blur-sm">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0">
              <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
              </svg>
            </div>
            <div class="flex-1">
              <p class="font-bold text-lg">Success!</p>
              <p class="text-sm text-white/90">Entry submitted successfully</p>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup lang="ts">
const router = useRouter();

// Current date
const currentDate = computed(() => {
  const now = new Date();
  return now.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
});

// Stats
const todayEntries = ref(0);
const totalHangers = ref(0);

// Form data
const form = ref({
  outletName: '',
  province: '',
  brand: '',
  hangerId: '',
  latitude: null as number | null,
  longitude: null as number | null,
  notes: '',
  photo: null as File | null,
});

const photoInputCamera = ref<HTMLInputElement | null>(null);
const photoInputGallery = ref<HTMLInputElement | null>(null);
const photoPreview = ref<string | null>(null);
const isSubmitting = ref(false);
const showSuccessToast = ref(false);
const gpsLoading = ref(false);

// Camera refs
const showCamera = ref(false);
const videoElement = ref<HTMLVideoElement | null>(null);
let cameraStream: MediaStream | null = null;
let facingMode: 'user' | 'environment' = 'environment'; // Start with rear camera

const provinces = ref<any[]>([]);
const brands = ref<any[]>([]);

// Fetch data on mount
onMounted(async () => {
  await fetchStats();
  await fetchProvinces();
  await fetchBrands();
  getGPSLocation(); // Auto-detect location on load
});

// Methods
const fetchStats = async () => {
  try {
    const { apiCall } = useApi();
    const data = await apiCall('/entries/stats/today') as any;
    todayEntries.value = data.entries_today;
    totalHangers.value = data.total_hangers;
  } catch (error) {
    console.error('Failed to fetch stats:', error);
  }
};

const fetchProvinces = async () => {
  try {
    const { apiCall } = useApi();
    const data = await apiCall('/provinces');
    provinces.value = data as any[];
  } catch (error) {
    console.error('Failed to fetch provinces:', error);
  }
};

const fetchBrands = async () => {
  try {
    const { apiCall } = useApi();
    const data = await apiCall('/brands/active');
    brands.value = data as any[];
  } catch (error) {
    console.error('Failed to fetch brands:', error);
  }
};

const getGPSLocation = () => {
  if (!navigator.geolocation) {
    alert('Geolocation is not supported by your browser');
    return;
  }

  gpsLoading.value = true;
  navigator.geolocation.getCurrentPosition(
    (position) => {
      form.value.latitude = position.coords.latitude;
      form.value.longitude = position.coords.longitude;
      gpsLoading.value = false;
    },
    (error) => {
      console.error('GPS Error:', error);
      gpsLoading.value = false;
      alert('Unable to get location. Please enable location services.');
    },
    { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
  );
};

const handlePhoto = (event: Event) => {
  const target = event.target as HTMLInputElement;
  const file = target.files?.[0];
  if (file) {
    compressAndStorePhoto(file);
  }
};

// Camera Functions
const openCamera = async () => {
  showCamera.value = true;
  try {
    cameraStream = await navigator.mediaDevices.getUserMedia({
      video: { facingMode: facingMode },
      audio: false
    });
    
    if (videoElement.value) {
      videoElement.value.srcObject = cameraStream;
    }
  } catch (error) {
    console.error('Camera access error:', error);
    alert('Unable to access camera. Please allow camera permission.');
    showCamera.value = false;
  }
};

const closeCamera = () => {
  if (cameraStream) {
    cameraStream.getTracks().forEach(track => track.stop());
    cameraStream = null;
  }
  showCamera.value = false;
};

const flipCamera = async () => {
  // Stop current stream
  if (cameraStream) {
    cameraStream.getTracks().forEach(track => track.stop());
  }
  
  // Toggle facing mode
  facingMode = facingMode === 'environment' ? 'user' : 'environment';
  
  // Restart camera with new facing mode
  try {
    cameraStream = await navigator.mediaDevices.getUserMedia({
      video: { facingMode: facingMode },
      audio: false
    });
    
    if (videoElement.value) {
      videoElement.value.srcObject = cameraStream;
    }
  } catch (error) {
    console.error('Camera flip error:', error);
  }
};

const capturePhoto = () => {
  if (!videoElement.value) return;
  
  const canvas = document.createElement('canvas');
  canvas.width = videoElement.value.videoWidth;
  canvas.height = videoElement.value.videoHeight;
  
  const ctx = canvas.getContext('2d');
  ctx?.drawImage(videoElement.value, 0, 0);
  
  // Convert to blob with compression
  canvas.toBlob(
    (blob) => {
      if (blob) {
        const file = new File([blob], 'camera-photo.jpg', {
          type: 'image/jpeg',
          lastModified: Date.now(),
        });
        
        // Compress the captured photo
        compressAndStorePhoto(file);
        closeCamera();
      }
    },
    'image/jpeg',
    0.8 // 80% quality for camera photos
  );
};

const openGalleryFromCamera = () => {
  closeCamera();
  photoInputGallery.value?.click();
};

const compressAndStorePhoto = (file: File) => {
  const reader = new FileReader();
  reader.onload = (e) => {
    const img = new Image();
    img.onload = () => {
      const canvas = document.createElement('canvas');
      const ctx = canvas.getContext('2d');
      
      let width = img.width;
      let height = img.height;
      const maxWidth = 1200;
      
      if (width > maxWidth) {
        height = (height * maxWidth) / width;
        width = maxWidth;
      }
      
      canvas.width = width;
      canvas.height = height;
      ctx?.drawImage(img, 0, 0, width, height);
      
      canvas.toBlob(
        (blob) => {
          if (blob) {
            const compressedFile = new File([blob], file.name, {
              type: 'image/jpeg',
              lastModified: Date.now(),
            });
            
            form.value.photo = compressedFile;
            photoPreview.value = canvas.toDataURL('image/jpeg', 0.7);
            
            console.log('Compressed size:', (compressedFile.size / 1024).toFixed(2), 'KB');
          }
        },
        'image/jpeg',
        0.7
      );
    };
    img.src = e.target?.result as string;
  };
  reader.readAsDataURL(file);
};

const removePhoto = () => {
  form.value.photo = null;
  photoPreview.value = null;
  if (photoInputCamera.value) {
    photoInputCamera.value.value = '';
  }
  if (photoInputGallery.value) {
    photoInputGallery.value.value = '';
  }
};

const submitEntry = async () => {
  // Validate photo is required
  if (!form.value.photo) {
    alert('Please take a photo before submitting');
    return;
  }
  
  isSubmitting.value = true;
  
  try {
    const { apiCall } = useApi();
    const formData = new FormData();
    formData.append('outlet_name', form.value.outletName);
    formData.append('province_id', form.value.province);
    formData.append('brand_id', form.value.brand);
    formData.append('hanger_id', form.value.hangerId);
    if (form.value.latitude) formData.append('latitude', form.value.latitude.toString());
    if (form.value.longitude) formData.append('longitude', form.value.longitude.toString());
    if (form.value.notes) formData.append('notes', form.value.notes);
    if (form.value.photo) formData.append('photo', form.value.photo);

    await apiCall('/entries', {
      method: 'POST',
      body: formData,
    });

    // Refresh stats
    await fetchStats();
    
    // Reset form
    form.value = {
      outletName: '',
      province: '',
      brand: '',
      hangerId: '',
      latitude: null,
      longitude: null,
      notes: '',
      photo: null,
    };
    removePhoto();
    
    // Show success toast
    showSuccessToast.value = true;
    setTimeout(() => {
      showSuccessToast.value = false;
    }, 3000);
    
    // Auto-detect location for next entry
    setTimeout(() => {
      getGPSLocation();
    }, 500);
  } catch (error) {
    console.error('Submit error:', error);
    alert('Failed to submit entry. Please try again.');
  } finally {
    isSubmitting.value = false;
  }
};
</script>

<style scoped>
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

.bounce-enter-active {
  animation: bounce-in 0.5s;
}

.bounce-leave-active {
  animation: bounce-out 0.5s;
}

@keyframes bounce-in {
  0% {
    transform: translate(-50%, 100px);
    opacity: 0;
  }
  50% {
    transform: translate(-50%, -10px);
  }
  100% {
    transform: translate(-50%, 0);
    opacity: 1;
  }
}

@keyframes bounce-out {
  0% {
    transform: translate(-50%, 0);
    opacity: 1;
  }
  100% {
    transform: translate(-50%, 100px);
    opacity: 0;
  }
}

/* Safe area for iOS notch */
.pb-safe {
  padding-bottom: env(safe-area-inset-bottom);
}
</style>
