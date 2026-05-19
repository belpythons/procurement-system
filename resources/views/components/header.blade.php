<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700 shadow-sm">
  <div class="px-3 py-3 lg:px-5 lg:pl-3">
    <div class="flex items-center justify-between">
      
      <!-- Left Side (Logo & Hamburger) -->
      <div class="flex items-center justify-start rtl:justify-end">
        <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
            <span class="sr-only">Open sidebar</span>
            <i class="ph ph-list text-2xl"></i>
         </button>
        <a href="#" class="flex ms-2 md:me-24 items-center gap-2">
          <!-- Ganti src dengan logo perusahaan yang sesuai, atau biarkan menggunakan icon -->
          <div class="w-8 h-8 bg-blue-700 rounded-lg flex items-center justify-center text-white">
              <i class="ph-bold ph-shield-check text-xl"></i>
          </div>
          <span class="self-center text-xl font-bold sm:text-2xl whitespace-nowrap dark:text-white text-blue-900">
            PTW <span class="font-light text-gray-500">System</span>
          </span>
        </a>
      </div>

      <!-- Right Side (User Profile & Title) -->
      <div class="flex items-center gap-4">
          
        <!-- Page Title Dynamic (Optional visual enhancement) -->
        <div class="hidden md:block text-sm font-medium text-gray-500 mr-4 border-r pr-4 border-gray-300">
            @yield('title', 'Dashboard')
        </div>

        <div class="flex items-center ms-3">
          <div>
            <button type="button" class="flex text-sm bg-gray-100 rounded-full focus:ring-4 focus:ring-blue-300 dark:focus:ring-gray-600 p-1 pl-2 pr-4 items-center gap-2" aria-expanded="false" data-dropdown-toggle="dropdown-user">
              <span class="sr-only">Open user menu</span>
              @if(auth()->user()->foto)
                  <img class="w-8 h-8 rounded-full object-cover" src="{{ asset('uploads/' . auth()->user()->foto) }}" alt="user photo">
              @else
                  <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-xs">
                      {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                  </div>
              @endif
              <div class="text-left hidden sm:block">
                  <p class="text-sm font-semibold text-gray-800 leading-tight">{{ auth()->user()->name }}</p>
                  <p class="text-xs text-gray-500 leading-tight capitalize">{{ auth()->user()->role }}</p>
              </div>
              <i class="ph ph-caret-down text-gray-500"></i>
            </button>
          </div>

          <!-- Dropdown menu -->
          <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow-lg dark:bg-gray-700 dark:divide-gray-600 border border-gray-100" id="dropdown-user">
            <div class="px-4 py-3 bg-gray-50 rounded-t">
              <p class="text-sm font-semibold text-gray-900 dark:text-white" role="none">
                {{ auth()->user()->name }}
              </p>
              <p class="text-sm font-medium text-gray-500 truncate dark:text-gray-300" role="none">
                {{ auth()->user()->email }}
              </p>
            </div>
            <ul class="py-1" role="none">
              @if(auth()->user()->isAdmin())
              <li>
                <a href="{{ route('admin.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">
                    <i class="ph ph-user-circle mr-2"></i> Profil Saya
                </a>
              </li>
              @endif
              <li>
                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">
                        <i class="ph ph-sign-out mr-2"></i> Sign out
                    </button>
                </form>
              </li>
            </ul>
          </div>
        </div>
      </div>

    </div>
  </div>
</nav>
