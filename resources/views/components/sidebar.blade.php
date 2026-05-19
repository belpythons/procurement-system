<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-[#1e293b] border-r border-gray-700 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700 sidebar-transition" aria-label="Sidebar">
   <div class="h-full px-3 pb-4 overflow-y-auto bg-[#1e293b] dark:bg-gray-800">
      
      <!-- User Info Badge in Sidebar (Mobile fallback) -->
      <div class="mb-5 p-3 rounded-lg bg-[#0f172a] sm:hidden flex items-center gap-3">
          <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-sm">
              {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
          </div>
          <div>
              <p class="text-sm font-semibold text-white">{{ auth()->user()->name }}</p>
              <p class="text-xs text-gray-400 capitalize">{{ auth()->user()->role }}</p>
          </div>
      </div>

      <!-- Menu Section -->
      <ul class="space-y-2 font-medium">
         
         {{-- ========================================== --}}
         {{-- MENU ADMIN --}}
         {{-- ========================================== --}}
         @if(auth()->user()->isAdmin())
            <li>
               <a href="{{ route('admin.dashboard') }}" class="flex items-center p-2 text-gray-300 rounded-lg hover:bg-blue-600 hover:text-white group {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : '' }}">
                  <i class="ph ph-squares-four text-xl transition duration-75 group-hover:text-white {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-gray-400' }}"></i>
                  <span class="ms-3">Dashboard</span>
               </a>
            </li>
            <li>
               <a href="{{ route('admin.users.index') }}" class="flex items-center p-2 text-gray-300 rounded-lg hover:bg-blue-600 hover:text-white group {{ request()->routeIs('admin.users.*') ? 'bg-blue-600 text-white' : '' }}">
                  <i class="ph ph-users text-xl transition duration-75 group-hover:text-white {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-gray-400' }}"></i>
                  <span class="ms-3">Manajemen User</span>
               </a>
            </li>
            <li>
               <a href="{{ route('admin.monitoring') }}" class="flex items-center p-2 text-gray-300 rounded-lg hover:bg-blue-600 hover:text-white group {{ request()->routeIs('admin.monitoring') ? 'bg-blue-600 text-white' : '' }}">
                  <i class="ph ph-activity text-xl transition duration-75 group-hover:text-white {{ request()->routeIs('admin.monitoring') ? 'text-white' : 'text-gray-400' }}"></i>
                  <span class="ms-3">Monitoring Permit</span>
                  @php
                      $activePermits = \App\Models\Permit::whereIn('status', ['Pending','Disetujui'])->count();
                  @endphp
                  @if($activePermits > 0)
                      <span class="inline-flex items-center justify-center w-3 h-3 p-3 ms-3 text-sm font-medium text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">{{ $activePermits }}</span>
                  @endif
               </a>
            </li>
            <li>
               <a href="{{ route('admin.riwayat') }}" class="flex items-center p-2 text-gray-300 rounded-lg hover:bg-blue-600 hover:text-white group {{ request()->routeIs('admin.riwayat') ? 'bg-blue-600 text-white' : '' }}">
                  <i class="ph ph-clock-counter-clockwise text-xl transition duration-75 group-hover:text-white {{ request()->routeIs('admin.riwayat') ? 'text-white' : 'text-gray-400' }}"></i>
                  <span class="ms-3">Riwayat Permit</span>
               </a>
            </li>
            <li>
               <a href="{{ route('admin.laporan') }}" class="flex items-center p-2 text-gray-300 rounded-lg hover:bg-blue-600 hover:text-white group {{ request()->routeIs('admin.laporan') ? 'bg-blue-600 text-white' : '' }}">
                  <i class="ph ph-file-text text-xl transition duration-75 group-hover:text-white {{ request()->routeIs('admin.laporan') ? 'text-white' : 'text-gray-400' }}"></i>
                  <span class="ms-3">Laporan Permit</span>
               </a>
            </li>
         @endif

         {{-- ========================================== --}}
         {{-- MENU PEKERJA --}}
         {{-- ========================================== --}}
         @if(auth()->user()->isPekerja())
            <li>
               <a href="{{ route('pekerja.dashboard') }}" class="flex items-center p-2 text-gray-300 rounded-lg hover:bg-blue-600 hover:text-white group {{ request()->routeIs('pekerja.dashboard') ? 'bg-blue-600 text-white' : '' }}">
                  <i class="ph ph-squares-four text-xl transition duration-75 group-hover:text-white {{ request()->routeIs('pekerja.dashboard') ? 'text-white' : 'text-gray-400' }}"></i>
                  <span class="ms-3">Dashboard</span>
               </a>
            </li>
            <li>
               <a href="{{ route('pekerja.permit.create') }}" class="flex items-center p-2 text-gray-300 rounded-lg hover:bg-blue-600 hover:text-white group {{ request()->routeIs('pekerja.permit.create') ? 'bg-blue-600 text-white' : '' }}">
                  <i class="ph ph-plus-circle text-xl transition duration-75 group-hover:text-white {{ request()->routeIs('pekerja.permit.create') ? 'text-white' : 'text-gray-400' }}"></i>
                  <span class="ms-3">Buat Permit</span>
               </a>
            </li>
            <li>
               <a href="{{ route('pekerja.riwayat') }}" class="flex items-center p-2 text-gray-300 rounded-lg hover:bg-blue-600 hover:text-white group {{ request()->routeIs('pekerja.riwayat') ? 'bg-blue-600 text-white' : '' }}">
                  <i class="ph ph-clock-counter-clockwise text-xl transition duration-75 group-hover:text-white {{ request()->routeIs('pekerja.riwayat') ? 'text-white' : 'text-gray-400' }}"></i>
                  <span class="ms-3">Riwayat Permit</span>
               </a>
            </li>
            <li>
               <a href="{{ route('pekerja.laporan') }}" class="flex items-center p-2 text-gray-300 rounded-lg hover:bg-blue-600 hover:text-white group {{ request()->routeIs('pekerja.laporan') ? 'bg-blue-600 text-white' : '' }}">
                  <i class="ph ph-file-text text-xl transition duration-75 group-hover:text-white {{ request()->routeIs('pekerja.laporan') ? 'text-white' : 'text-gray-400' }}"></i>
                  <span class="ms-3">Laporan Permit</span>
               </a>
            </li>
         @endif

         {{-- ========================================== --}}
         {{-- MENU SUPERVISOR --}}
         {{-- ========================================== --}}
         @if(auth()->user()->isSupervisor())
            <li>
               <a href="{{ route('supervisor.dashboard') }}" class="flex items-center p-2 text-gray-300 rounded-lg hover:bg-blue-600 hover:text-white group {{ request()->routeIs('supervisor.dashboard') ? 'bg-blue-600 text-white' : '' }}">
                  <i class="ph ph-squares-four text-xl transition duration-75 group-hover:text-white {{ request()->routeIs('supervisor.dashboard') ? 'text-white' : 'text-gray-400' }}"></i>
                  <span class="ms-3">Dashboard</span>
               </a>
            </li>
            <li>
               <a href="{{ route('supervisor.monitoring') }}" class="flex items-center p-2 text-gray-300 rounded-lg hover:bg-blue-600 hover:text-white group {{ request()->routeIs('supervisor.monitoring') ? 'bg-blue-600 text-white' : '' }}">
                  <i class="ph ph-list-checks text-xl transition duration-75 group-hover:text-white {{ request()->routeIs('supervisor.monitoring') ? 'text-white' : 'text-gray-400' }}"></i>
                  <span class="ms-3 flex-1 whitespace-nowrap">Review Permit</span>
                  @php
                      $pendingCount = auth()->user()->supervisedPermits()->where('status', 'Pending')->count();
                  @endphp
                  @if($pendingCount > 0)
                      <span class="inline-flex items-center justify-center w-3 h-3 p-3 ms-3 text-sm font-medium text-white bg-red-500 rounded-full">{{ $pendingCount }}</span>
                  @endif
               </a>
            </li>
            <li>
               <a href="{{ route('supervisor.riwayat') }}" class="flex items-center p-2 text-gray-300 rounded-lg hover:bg-blue-600 hover:text-white group {{ request()->routeIs('supervisor.riwayat') ? 'bg-blue-600 text-white' : '' }}">
                  <i class="ph ph-clock-counter-clockwise text-xl transition duration-75 group-hover:text-white {{ request()->routeIs('supervisor.riwayat') ? 'text-white' : 'text-gray-400' }}"></i>
                  <span class="ms-3">Riwayat Permit</span>
               </a>
            </li>
            <li>
               <a href="{{ route('supervisor.laporan') }}" class="flex items-center p-2 text-gray-300 rounded-lg hover:bg-blue-600 hover:text-white group {{ request()->routeIs('supervisor.laporan') ? 'bg-blue-600 text-white' : '' }}">
                  <i class="ph ph-file-text text-xl transition duration-75 group-hover:text-white {{ request()->routeIs('supervisor.laporan') ? 'text-white' : 'text-gray-400' }}"></i>
                  <span class="ms-3">Laporan Permit</span>
               </a>
            </li>
         @endif

         {{-- ========================================== --}}
         {{-- MENU SAFETY OFFICER --}}
         {{-- ========================================== --}}
         @if(auth()->user()->isSafetyOfficer())
            <li>
               <a href="{{ route('safety_officer.dashboard') }}" class="flex items-center p-2 text-gray-300 rounded-lg hover:bg-blue-600 hover:text-white group {{ request()->routeIs('safety_officer.dashboard') ? 'bg-blue-600 text-white' : '' }}">
                  <i class="ph ph-squares-four text-xl transition duration-75 group-hover:text-white {{ request()->routeIs('safety_officer.dashboard') ? 'text-white' : 'text-gray-400' }}"></i>
                  <span class="ms-3">Dashboard</span>
               </a>
            </li>
            <li>
               <a href="{{ route('safety_officer.monitoring') }}" class="flex items-center p-2 text-gray-300 rounded-lg hover:bg-blue-600 hover:text-white group {{ request()->routeIs('safety_officer.monitoring') ? 'bg-blue-600 text-white' : '' }}">
                  <i class="ph ph-shield-check text-xl transition duration-75 group-hover:text-white {{ request()->routeIs('safety_officer.monitoring') ? 'text-white' : 'text-gray-400' }}"></i>
                  <span class="ms-3 flex-1 whitespace-nowrap">Evaluasi Permit</span>
                  @php
                      // Muncul badge jika ada permit pending/disetujui yang belum ada evaluasinya
                      $evalCount = auth()->user()->reviewedPermits()
                                        ->whereIn('status', ['Pending', 'Disetujui'])
                                        ->whereNull('evaluasi_risiko')
                                        ->count();
                  @endphp
                  @if($evalCount > 0)
                      <span class="inline-flex items-center justify-center w-3 h-3 p-3 ms-3 text-sm font-medium text-white bg-orange-500 rounded-full">{{ $evalCount }}</span>
                  @endif
               </a>
            </li>
            <li>
               <a href="{{ route('safety_officer.riwayat') }}" class="flex items-center p-2 text-gray-300 rounded-lg hover:bg-blue-600 hover:text-white group {{ request()->routeIs('safety_officer.riwayat') ? 'bg-blue-600 text-white' : '' }}">
                  <i class="ph ph-clock-counter-clockwise text-xl transition duration-75 group-hover:text-white {{ request()->routeIs('safety_officer.riwayat') ? 'text-white' : 'text-gray-400' }}"></i>
                  <span class="ms-3">Riwayat Permit</span>
               </a>
            </li>
            <li>
               <a href="{{ route('safety_officer.laporan') }}" class="flex items-center p-2 text-gray-300 rounded-lg hover:bg-blue-600 hover:text-white group {{ request()->routeIs('safety_officer.laporan') ? 'bg-blue-600 text-white' : '' }}">
                  <i class="ph ph-file-text text-xl transition duration-75 group-hover:text-white {{ request()->routeIs('safety_officer.laporan') ? 'text-white' : 'text-gray-400' }}"></i>
                  <span class="ms-3">Laporan Permit</span>
               </a>
            </li>
         @endif

      </ul>
   </div>
</aside>
