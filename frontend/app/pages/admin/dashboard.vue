<template>
  <div class="min-h-screen bg-[#F8FAFC] font-sans pb-20">
    <!-- Top Navigation / Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <!-- Left: Logo/Title -->
          <div class="flex items-center gap-4">
             <button @click="router.push('/')" class="p-2 rounded-full hover:bg-slate-100 text-slate-500 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
             </button>
             <div>
                <h1 class="text-xl font-bold text-slate-900">Admin Dashboard</h1>
                <p class="text-xs text-slate-500 font-medium">{{ currentDate }}</p>
             </div>
          </div>

          <!-- Right: Profile/Actions -->
          <div class="flex items-center gap-3">
            <div class="hidden md:flex flex-col items-end mr-2">
                <span class="text-sm font-semibold text-slate-800">Administrator</span>
                <span class="text-[10px] text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full font-medium border border-emerald-100">Online</span>
            </div>
            <div class="h-10 w-10 rounded-full bg-slate-900 flex items-center justify-center text-white font-bold shadow-sm ring-2 ring-white">
                A
            </div>
          </div>
        </div>
      </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
      
      <!-- Hero Stats Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- Card: Warehouse Stock (New) -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200 relative overflow-hidden group hover:border-slate-300 transition-colors">
            <div class="absolute right-0 top-0 h-24 w-24 bg-indigo-50 rounded-bl-full -mr-4 -mt-4"></div>
            <div class="relative">
                <div class="flex justify-between items-start">
                    <p class="text-slate-500 text-sm font-medium uppercase tracking-wider">Warehouse Stock</p>
                    <button @click="openStockModal" class="text-slate-400 hover:text-indigo-600 transition-colors" title="Edit Total Stock">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </button>
                </div>
                <div class="flex items-baseline gap-2 mt-2">
                    <h3 class="text-3xl font-bold text-slate-900">{{ warehouseStats.total_stock - warehouseStats.total_distributed }}</h3>
                    <span class="text-xs text-slate-500 font-medium">/ {{ warehouseStats.total_stock }}</span>
                </div>
            </div>
             <div class="mt-4 flex items-center justify-between text-xs">
                 <span class="font-medium text-slate-500"> Distributed: <b class="text-slate-800">{{ warehouseStats.total_distributed }}</b></span>
                 <div class="flex items-center text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-md border border-indigo-100">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <span>In Stock</span>
                </div>
            </div>
        </div>

        <!-- Card 1: Today's Hangers -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200 relative overflow-hidden group hover:border-slate-300 transition-colors">
            <div class="absolute right-0 top-0 h-24 w-24 bg-blue-50 rounded-bl-full -mr-4 -mt-4"></div>
            <div class="relative">
                <p class="text-slate-500 text-sm font-medium uppercase tracking-wider">Hangers Today</p>
                <div class="flex items-baseline gap-2 mt-2">
                    <h3 class="text-3xl font-bold text-slate-900">{{ stats.hangersToday }}</h3>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs font-semibold text-blue-700 bg-blue-50 w-fit px-2.5 py-1 rounded-md border border-blue-100">
                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                <span>Live Count</span>
            </div>
        </div>

        <!-- Card 2: Active Salesmen -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200 relative overflow-hidden group hover:border-slate-300 transition-colors">
            <div class="absolute right-0 top-0 h-24 w-24 bg-emerald-50 rounded-bl-full -mr-4 -mt-4"></div>
            <div class="relative">
                <p class="text-slate-500 text-sm font-medium uppercase tracking-wider">Active Team</p>
                 <div class="flex items-baseline gap-2 mt-2">
                    <h3 class="text-3xl font-bold text-slate-900">{{ stats.activeSalesmen }}</h3>
                    <span class="text-slate-400 text-lg">/ {{ stats.totalSalesmen }}</span>
                </div>
            </div>
             <div class="mt-4 flex items-center text-xs font-semibold text-emerald-700 bg-emerald-50 w-fit px-2.5 py-1 rounded-md border border-emerald-100">
                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span>Active Now</span>
            </div>
        </div>

        <!-- Card 3: Weekly Volume -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200 relative overflow-hidden group hover:border-slate-300 transition-colors">
            <div class="absolute right-0 top-0 h-24 w-24 bg-purple-50 rounded-bl-full -mr-4 -mt-4"></div>
            <div class="relative">
                <p class="text-slate-500 text-sm font-medium uppercase tracking-wider">Weekly Volume</p>
                 <div class="flex items-baseline gap-2 mt-2">
                    <h3 class="text-3xl font-bold text-slate-900">{{ stats.weeklyEntries }}</h3>
                </div>
            </div>
             <div class="mt-4 flex items-center text-xs font-semibold text-purple-700 bg-purple-50 w-fit px-2.5 py-1 rounded-md border border-purple-100">
                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                <span>Total Entries</span>
            </div>
        </div>

        <!-- Card 4: Top Performer Name -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200 relative overflow-hidden group hover:border-slate-300 transition-colors">
             <div class="absolute right-0 top-0 h-24 w-24 bg-amber-50 rounded-bl-full -mr-4 -mt-4"></div>
             <div class="relative">
                <p class="text-slate-500 text-sm font-medium uppercase tracking-wider">Star Performer</p>
                 <div class="flex items-baseline gap-2 mt-2">
                    <h3 class="text-xl font-bold text-slate-900 truncate" :title="stats.topPerformer">{{ stats.topPerformer }}</h3>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs font-semibold text-amber-700 bg-amber-50 w-fit px-2.5 py-1 rounded-md border border-amber-100">
                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                <span>Leading</span>
            </div>
        </div>

        <!-- Card: Remaining Stock -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200 relative overflow-hidden group hover:border-slate-300 transition-colors">
            <div class="absolute right-0 top-0 h-24 w-24 bg-cyan-50 rounded-bl-full -mr-4 -mt-4"></div>
            <div class="relative">
                <p class="text-slate-500 text-sm font-medium uppercase tracking-wider">Remaining Stock</p>
                <div class="flex items-baseline gap-2 mt-2">
                    <h3 class="text-3xl font-bold text-slate-900">{{ stockSummary?.balance || 0 }}</h3>
                </div>
            </div>
            <div class="mt-4 flex items-center justify-between text-xs">
                 <span class="font-medium text-slate-500"> Assigned: <b class="text-slate-800">{{ stockSummary?.total_assigned || 0 }}</b></span>
                 <span class="font-medium text-slate-500"> Used: <b class="text-slate-800">{{ stockSummary?.total_used || 0 }}</b></span>
            </div>
        </div>
      </div>

      <!-- Main Content Split -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column: Chart & Quick Actions (2/3 width) -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Weekly Chart -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Performance Overview</h2>
                        <p class="text-sm text-slate-500">Weekly entry volume trends</p>
                    </div>
                    <div class="flex gap-2">
                        <span class="h-3 w-3 rounded-full bg-indigo-600"></span>
                        <span class="text-xs text-slate-500">Daily Entries</span>
                    </div>
                </div>
                
                <!-- Chart Area -->
                <div class="h-80 w-full relative">
                     <svg viewBox="0 0 700 300" class="w-full h-full" preserveAspectRatio="none">
                        <!-- Grid Lines -->
                        <line v-for="i in 5" :key="i" x1="0" :y1="i*50" x2="700" :y2="i*50" stroke="#f1f5f9" stroke-width="1" />
                        
                        <!-- Area (Solid light fill) -->
                        <path :d="getAreaPath()" fill="#e0e7ff" opacity="0.5" />
                        <!-- Line -->
                        <path :d="getLinePath()" fill="none" stroke="#4f46e5" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                        <!-- Points -->
                        <circle v-for="(point, index) in chartPoints" :key="index" 
                            :cx="point.x" :cy="point.y" r="5" fill="white" stroke="#4f46e5" stroke-width="2" 
                            class="hover:r-6 transition-all cursor-pointer">
                            <title>{{ point.value }} entries</title>
                        </circle>
                    </svg>
                    
                    <!-- Labels -->
                    <div class="flex justify-between mt-2 px-2">
                         <span v-for="day in chartData" :key="day.label" class="text-xs text-slate-400 font-medium">{{ day.label }}</span>
                    </div>
                </div>
            </div>

            <!-- Province Distribution Chart -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Province Distribution</h2>
                        <p class="text-sm text-slate-500">Entries by location</p>
                    </div>
                </div>
                
                <div class="h-64 w-full flex justify-between gap-2 px-2">
                    <div v-if="provinceStats.length === 0" class="w-full h-full flex items-center justify-center text-slate-400">
                        No location data available
                    </div>
                    <div v-for="(province, index) in provinceStats" :key="index" class="relative flex-1 flex flex-col items-center justify-end group">
                         <!-- Tooltip -->
                         <div class="absolute -top-10 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-800 text-white text-xs py-1 px-2 rounded pointer-events-none whitespace-nowrap z-10">
                            {{ province.name }}: {{ province.count }}
                         </div>
                         
                         <!-- Bar -->
                         <div class="w-full max-w-[40px] bg-rose-500 rounded-t-lg hover:bg-rose-600 transition-all cursor-pointer relative shadow-sm"
                              :style="{ height: `${(province.count / maxProvinceCount) * 85}%` }">
                         </div>
                         
                         <!-- Label -->
                         <p class="text-[10px] text-slate-500 font-medium mt-2 text-center w-full truncate px-1" :title="province.name">
                             {{ province.name }}
                         </p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Grid -->
            <div>
                 <h2 class="text-lg font-bold text-slate-900 mb-4 px-1">Quick Management</h2>
                 <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                     <button @click="router.push('/entry')" class="flex flex-col items-center justify-center p-6 bg-white rounded-xl border border-slate-200 shadow-sm hover:border-blue-300 hover:shadow-md transition-all group">
                         <div class="h-10 w-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center mb-3">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                         </div>
                         <span class="text-sm font-semibold text-slate-700 group-hover:text-slate-900">New Entry</span>
                     </button>
                     <button @click="router.push('/admin/salesmen')" class="flex flex-col items-center justify-center p-6 bg-white rounded-xl border border-slate-200 shadow-sm hover:border-indigo-300 hover:shadow-md transition-all group">
                         <div class="h-10 w-10 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center mb-3">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                         </div>
                         <span class="text-sm font-semibold text-slate-700 group-hover:text-slate-900">Users</span>
                     </button>
                      <button @click="router.push('/admin/entries')" class="flex flex-col items-center justify-center p-6 bg-white rounded-xl border border-slate-200 shadow-sm hover:border-amber-300 hover:shadow-md transition-all group">
                         <div class="h-10 w-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center mb-3">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                         </div>
                         <span class="text-sm font-semibold text-slate-700 group-hover:text-slate-900">Entries</span>
                     </button>
                      <button @click="router.push('/admin/brands')" class="flex flex-col items-center justify-center p-6 bg-white rounded-xl border border-slate-200 shadow-sm hover:border-indigo-300 hover:shadow-md transition-all group">
                         <div class="h-10 w-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center mb-3">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                         </div>
                         <span class="text-sm font-semibold text-slate-700 group-hover:text-slate-900">Brands</span>
                     </button>
                      <button @click="router.push('/admin/provinces')" class="flex flex-col items-center justify-center p-6 bg-white rounded-xl border border-slate-200 shadow-sm hover:border-rose-300 hover:shadow-md transition-all group">
                         <div class="h-10 w-10 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center mb-3">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                         </div>
                         <span class="text-sm font-semibold text-slate-700 group-hover:text-slate-900">Provinces</span>
                     </button>
                      <button @click="router.push('/admin/report')" class="flex flex-col items-center justify-center p-6 bg-white rounded-xl border border-slate-200 shadow-sm hover:border-teal-300 hover:shadow-md transition-all group">
                         <div class="h-10 w-10 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center mb-3">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                         </div>
                         <span class="text-sm font-semibold text-slate-700 group-hover:text-slate-900">Reports</span>
                     </button>
                      <button @click="openAssignModal" class="flex flex-col items-center justify-center p-6 bg-white rounded-xl border border-slate-200 shadow-sm hover:border-emerald-300 hover:shadow-md transition-all group">
                          <div class="h-10 w-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3">
                              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                          </div>
                          <span class="text-sm font-semibold text-slate-700 group-hover:text-slate-900">Give Hangers</span>
                      </button>
                 </div>
            </div>

        </div>

        <!-- Right Column: Leaderboards & Activity (1/3 width) -->
        <div class="lg:col-span-1 space-y-8">
            
            <!-- Top Performers -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="font-bold text-slate-900 mb-6 flex items-center gap-2">
                    <span class="text-xl">🏆</span> Top Performers <span class="text-xs font-normal text-slate-400 ml-auto">(This Month)</span>
                </h3>
                <div class="space-y-4">
                     <div v-if="topSalesmen.length === 0" class="text-center py-4 text-slate-400 text-sm">No data available</div>
                     <div v-for="(salesman, index) in topSalesmen" :key="'top-'+index" class="flex items-center gap-3">
                         <div class="h-8 w-8 rounded-full flex items-center justify-center font-bold text-sm border" 
                              :class="index === 0 ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : 
                                      index === 1 ? 'bg-slate-100 text-slate-600 border-slate-200' : 
                                      index === 2 ? 'bg-orange-50 text-orange-700 border-orange-200' : 'bg-white text-slate-500 border-slate-100'">
                             {{ index + 1 }}
                         </div>
                         <div class="flex-1 min-w-0">
                             <p class="text-sm font-semibold text-slate-900 truncate">{{ salesman.name }}</p>
                             <div class="w-full bg-slate-100 rounded-full h-1.5 mt-1 overflow-hidden">
                                 <div class="h-full bg-emerald-500 rounded-full" :style="{ width: salesman.percentage + '%' }"></div>
                             </div>
                         </div>
                         <span class="text-sm font-bold text-slate-700">{{ salesman.count }}</span>
                     </div>
                </div>
            </div>

             <!-- Low Performers -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="font-bold text-slate-900 mb-6 flex items-center gap-2">
                    <span class="text-xl">⚠️</span> Needs Support <span class="text-xs font-normal text-slate-400 ml-auto">(This Month)</span>
                </h3>
                 <div class="space-y-4">
                     <div v-if="lowSalesmen.length === 0" class="text-center py-4 text-slate-400 text-sm">No data available</div>
                     <div v-for="(salesman, index) in lowSalesmen" :key="'low-'+index" class="flex items-center gap-3">
                         <div class="h-8 w-8 rounded-full bg-red-50 text-red-600 border border-red-100 flex items-center justify-center font-bold text-sm">!</div>
                         <div class="flex-1 min-w-0">
                             <p class="text-sm font-semibold text-slate-900 truncate">{{ salesman.name }}</p>
                             <div class="w-full bg-slate-100 rounded-full h-1.5 mt-1 overflow-hidden">
                                 <div class="h-full bg-red-500 rounded-full" :style="{ width: (salesman.percentage || 5) + '%' }"></div>
                             </div>
                         </div>
                         <span class="text-sm font-bold text-red-600">{{ salesman.count }}</span>
                     </div>
                </div>
            </div>

             <!-- Recent Feed -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-bold text-slate-900">Recent Activity</h3>
                    <button @click="router.push('/admin/activity')" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 hover:underline">View All</button>
                </div>
                <div class="space-y-6 relative">
                    <!-- Connector Line -->
                    <div class="absolute left-2.5 top-0 bottom-0 w-0.5 bg-slate-100"></div>

                     <div v-for="activity in recentActivity.slice(0, 5)" :key="activity.id" class="relative pl-8">
                         <div class="absolute left-0 top-1.5 h-5 w-5 rounded-full border-2 border-white bg-indigo-500 z-10"></div>
                         <div>
                             <p class="text-sm font-medium text-slate-900">
                                 <span class="font-bold">{{ activity.salesman }}</span> sold 1 hanger
                             </p>
                             <p class="text-xs text-slate-500 mt-0.5">{{ activity.customer }} • {{ activity.location }}</p>
                             <p class="text-[10px] text-slate-400 mt-1">{{ activity.time }}</p>
                         </div>
                     </div>
                </div>
            </div>

        </div>

      </div>

    </main>

    <!-- Assign Hangers Modal -->
    <div v-if="showAssignModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
      <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6 space-y-4">
        <h3 class="text-lg font-bold text-gray-900">Give Hangers</h3>
        
        <form @submit.prevent="submitAssignment" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Select Salesman</label>
            <select 
              v-model="assignForm.user_id" 
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white"
            >
              <option value="" disabled>Select a user...</option>
              <option value="all" class="font-bold text-emerald-700">📣 All Active Salesmen</option>
              <option disabled>──────────────</option>
              <option v-for="user in salesmenList" :key="user.id" :value="user.id">
                {{ user.name }}
              </option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
            <input 
              v-model="assignForm.quantity" 
              type="number" 
              min="1" 
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
            >
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Note (Optional)</label>
            <textarea 
              v-model="assignForm.note" 
              rows="2"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
              placeholder="e.g. Restock for campaign"
            ></textarea>
          </div>

          <div class="flex justify-end gap-3 pt-2">
             <button 
              type="button" 
              @click="closeAssignModal"
              class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200"
            >
              Cancel
            </button>
            <button 
              type="submit" 
              :disabled="isAssigning"
              class="px-4 py-2 text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 disabled:opacity-50 flex items-center"
            >
              <span v-if="isAssigning" class="mr-2">
                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
              </span>
              Confirm Details
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Edit Warehouse Stock Modal -->
    <div v-if="showStockModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
      <div class="bg-white rounded-xl shadow-xl max-w-sm w-full p-6 space-y-4">
        <h3 class="text-lg font-bold text-gray-900">Update Warehouse Stock</h3>
        <p class="text-sm text-gray-500">Set the total initial number of hangers in your warehouse.</p>
        
        <form @submit.prevent="submitStockUpdate" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Total Stock</label>
            <input 
              v-model="stockForm.value" 
              type="number" 
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
            >
          </div>

          <div class="flex justify-end gap-3 pt-2">
             <button 
              type="button" 
              @click="closeStockModal"
              class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200"
            >
              Cancel
            </button>
            <button 
              type="submit" 
              :disabled="isUpdatingStock"
              class="px-4 py-2 text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 disabled:opacity-50"
            >
              {{ isUpdatingStock ? 'Saving...' : 'Save' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
// Ensure direct imports if auto-import fails
// import { useApi } from '~/composables/useApi'; // usually auto-imported in Nuxt

const router = useRouter();
const { apiCall } = useApi();

// Current date
const currentDate = computed(() => {
  const now = new Date();
  return now.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
});

// Statistics
const stats = ref({
  hangersToday: 0,
  activeSalesmen: 0,
  totalSalesmen: 0,
  weeklyEntries: 0,
  topPerformer: 'Loading...',
});

const stockSummary = ref({
  total_assigned: 0,
  total_used: 0,
  balance: 0

});

const warehouseStats = ref({
    total_stock: 0,
    total_distributed: 0
});

// Chart data (last 7 days)

// Chart data (last 7 days)
const chartData = ref([
  { label: 'Mon', value: 45 },
  { label: 'Tue', value: 62 },
  { label: 'Wed', value: 38 },
  { label: 'Thu', value: 75 },
  { label: 'Fri', value: 52 },
  { label: 'Sat', value: 68 },
  { label: 'Sun', value: 41 },
]);

// Top Salesmen data
const topSalesmen = ref<any[]>([]);

// Low Salesmen data
const lowSalesmen = ref<any[]>([]);

// Province stats
const provinceStats = ref<any[]>([]);

// Max province count for scaling
const maxProvinceCount = computed(() => {
    if (provinceStats.value.length === 0) return 1;
    return Math.max(...provinceStats.value.map(p => p.count));
});

// Recent activity
const recentActivity = ref<any[]>([]);

const maxChartValue = computed(() => {
  const max = Math.max(...chartData.value.map(d => d.value));
  return max > 0 ? max : 10;
});

// Generate chart points for SVG
const chartPoints = computed(() => {
  const width = 700;
  const height = 300; // Updated to match new viewbox
  const max = maxChartValue.value;
  
  return chartData.value.map((day, index) => {
    const x = (index / (chartData.value.length - 1)) * width;
    // Keep 20px padding from top to avoid cropping
    const y = height - ((day.value / max) * (height - 20)); 
    return { x, y, value: day.value };
  });
});

// Generate smooth line path
const getLinePath = () => {
  if (chartPoints.value.length === 0) return '';
  
  const firstPoint = chartPoints.value[0];
  if (!firstPoint) return '';
  
  let path = `M ${firstPoint.x} ${firstPoint.y}`;
  
  for (let i = 0; i < chartPoints.value.length - 1; i++) {
    const current = chartPoints.value[i];
    const next = chartPoints.value[i + 1];
    if (!current || !next) continue;
    
    // Control points for smooth bezier (simplified)
    const controlX = (current.x + next.x) / 2;
    path += ` Q ${controlX} ${current.y}, ${next.x} ${next.y}`;
  }
  
  return path;
};

// Generate area fill path
const getAreaPath = () => {
  if (chartPoints.value.length === 0) return '';
  
  const height = 300;
  const firstPoint = chartPoints.value[0];
  if (!firstPoint) return '';
  
  let path = `M ${firstPoint.x} ${height}`;
  path += ` L ${firstPoint.x} ${firstPoint.y}`;
  
  // Reuse same logic as line
  for (let i = 0; i < chartPoints.value.length - 1; i++) {
    const current = chartPoints.value[i];
    const next = chartPoints.value[i + 1];
    if (!current || !next) continue;
    
    const controlX = (current.x + next.x) / 2;
    path += ` Q ${controlX} ${current.y}, ${next.x} ${next.y}`;
  }
  
  const lastPoint = chartPoints.value[chartPoints.value.length - 1];
  if (lastPoint) {
    path += ` L ${lastPoint.x} ${height} Z`;
  }
  
  return path;
};

const formatTime = (dateString: string) => {
  const date = new Date(dateString);
  const now = new Date();
  const diff = now.getTime() - date.getTime();
  const minutes = Math.floor(diff / 60000);
  
  if (minutes < 1) return 'Just now';
  if (minutes < 60) return `${minutes} mins ago`;
  const hours = Math.floor(minutes / 60);
  if (hours < 24) return `${hours} hour${hours > 1 ? 's' : ''} ago`;
  return date.toLocaleDateString();
};

const fetchAnalytics = async () => {
  console.log('🔄 Fetching analytics data from backend...');
  try {
    console.log('📡 Making API call to /admin/analytics');
    const data = await apiCall('/admin/analytics') as any;
    
    console.log('✅ Analytics data received:', data);
    
    // Update stats
    stats.value = {
      hangersToday: data.hangers_today,
      activeSalesmen: data.active_salesmen,
      totalSalesmen: data.total_salesmen,
      weeklyEntries: data.weekly_entries,
      topPerformer: data.top_performer?.user?.name || 'N/A',
    };

    // Update province stats
    provinceStats.value = data.province_stats || [];

    // Update stock summary
    if (data.stock_summary) {
        stockSummary.value = {
            total_assigned: parseInt(data.stock_summary.total_assigned) || 0,
            total_used: parseInt(data.stock_summary.total_used) || 0,
            balance: (parseInt(data.stock_summary.total_assigned) || 0) - (parseInt(data.stock_summary.total_used) || 0)
        };
    }

    // Update warehouse stats
    if (data.warehouse) {
        warehouseStats.value = {
            total_stock: parseInt(data.warehouse.total_stock) || 0,
            total_distributed: parseInt(data.warehouse.total_distributed) || 0
        };
    }

    // Update chart data

    chartData.value = data.daily_chart.map((day: any) => ({
      label: new Date(day.date).toLocaleDateString('en-US', { weekday: 'short' }),
      value: day.count,
    }));

    // Update recent activity
    recentActivity.value = data.recent_activity.map((entry: any) => ({
      id: entry.id,
      salesman: entry.user.name,
      customer: entry.outlet_name || 'N/A',
      quantity: 1, 
      location: entry.province || entry.location || 'N/A',
      time: formatTime(entry.created_at),
    }));
    
    // Update top salesmen
    topSalesmen.value = data.top_salesmen || [];

    // Update low salesmen
    lowSalesmen.value = data.low_salesmen || [];
    
    console.log('✨ Dashboard data updated successfully');
  } catch (error: any) {
    console.error('❌ Failed to fetch analytics:', error);
    // Silent fail or simple console log to preserve UI aesthetics
  }
};

// Hanger Assignment Logic
const showAssignModal = ref(false);
const isAssigning = ref(false);
const salesmenList = ref<any[]>([]);
const assignForm = ref({
  user_id: '',
  quantity: 50,
  note: ''
});

const openAssignModal = async () => {
  showAssignModal.value = true;
  assignForm.value = { user_id: '', quantity: 50, note: '' };
  
  if (salesmenList.value.length === 0) {
    try {
      const users = await apiCall('/admin/users') as any[];
      salesmenList.value = users.filter((u: any) => u.role === 'salesman' && u.is_active);
    } catch (e) {
      console.error('Failed to load salesmen', e);
    }
  }
};

const closeAssignModal = () => {
    showAssignModal.value = false;
};

const submitAssignment = async () => {
  if (!assignForm.value.user_id) {
      alert('Please select a salesman or "All Salesmen"');
      return;
  }
  
  isAssigning.value = true;
  try {
    if (assignForm.value.user_id === 'all') {
        const result = await apiCall('/admin/salesmen/assign-to-all', {
            method: 'POST',
            body: {
                quantity: assignForm.value.quantity,
                note: assignForm.value.note
            }
        }) as any;
        alert(`Successfully assigned hangers to ${result.count} salesmen!`);
    } else {
        await apiCall(`/admin/salesmen/${assignForm.value.user_id}/assign-hangers`, {
          method: 'POST',
          body: {
            quantity: assignForm.value.quantity,
            note: assignForm.value.note
          }
        });
        alert('Hangers assigned successfully!');
    }
    closeAssignModal();
    fetchAnalytics(); // Refresh stats
  } catch (err) {
    console.error('Failed to assign hangers', err);
    alert('Failed to assign hangers.');
  } finally {
    isAssigning.value = false;
  }

};


// Stock Update Logic
const showStockModal = ref(false);
const isUpdatingStock = ref(false);
const stockForm = ref({ value: 0 });

const openStockModal = () => {
    stockForm.value.value = warehouseStats.value.total_stock;
    showStockModal.value = true;
};

const closeStockModal = () => {
    showStockModal.value = false;
};

const submitStockUpdate = async () => {
    isUpdatingStock.value = true;
    try {
        await apiCall('/admin/settings', {
            method: 'POST',
            body: {
                key: 'global_hanger_stock',
                value: stockForm.value.value
            }
        });
        
        // Refresh data
        await fetchAnalytics();
        closeStockModal();
    } catch (e) {
        console.error('Failed to update stock', e);
        alert('Failed to update stock');
    } finally {
        isUpdatingStock.value = false;
    }
};



// Fetch analytics on mount
onMounted(async () => {
  await fetchAnalytics();
});
</script>
