<button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar"
        type="button"
        class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-slate-500 rounded-lg sm:hidden hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-slate-200">
    <span class="sr-only">Open sidebar</span>
    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <path clip-rule="evenodd" fill-rule="evenodd"
              d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
    </svg>
</button>

<aside id="default-sidebar"
       class="fixed top-0 left-0 z-40 w-72 h-screen transition-transform -translate-x-full sm:translate-x-0"
       aria-label="Sidebar">
    <div class="h-full bg-white dark:bg-slate-800 flex flex-col shadow-xl">
        <!-- Logo Section -->
        <div class="p-5 border-b border-slate-200 dark:border-slate-700">
            <img src="{{asset('assets/images/logo.svg')}}" class="h-12 mx-auto" alt="Logo">
        </div>

        <!-- Navigation Section -->
        <div class="flex-1 overflow-y-auto px-4 py-5">
            <!-- Admin Info -->
            <div class="mb-6 pb-6 border-b border-slate-200 dark:border-slate-700">
                <div class="flex items-center px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-700/50">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-red-500 flex items-center justify-center text-white">
                            <i class="material-symbols-outlined">admin_panel_settings</i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-slate-900 dark:text-white">Administrator</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Super Admin</p>
                    </div>
                </div>
            </div>

            <!-- Main Navigation -->
            <nav class="space-y-1">
                <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Main Menu</p>
                
                <!-- Dashboard -->
                <a href="#" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg text-slate-900 dark:text-white hover:bg-slate-100 dark:hover:bg-slate-700/50 group transition-colors">
                    <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 mr-3 transition-transform group-hover:scale-110">
                        <i class="material-symbols-outlined text-[20px]">dashboard</i>
                    </span>
                    Dashboard
                </a>

                <!-- Posts Section -->
                <div class="mt-4">
                    <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Content Management</p>
                    
                    <a href="{{route('admin.posts.create')}}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg text-slate-900 dark:text-white hover:bg-slate-100 dark:hover:bg-slate-700/50 group transition-colors">
                        <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-50 dark:bg-slate-700 text-emerald-600 dark:text-emerald-400 mr-3 transition-transform group-hover:scale-110">
                            <i class="material-symbols-outlined text-[20px]">post_add</i>
                        </span>
                        Create Post
                    </a>

                    <a href="{{route('admin.posts.index')}}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg text-slate-900 dark:text-white hover:bg-slate-100 dark:hover:bg-slate-700/50 group transition-colors">
                        <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-purple-50 dark:bg-slate-700 text-purple-600 dark:text-purple-400 mr-3 transition-transform group-hover:scale-110">
                            <i class="material-symbols-outlined text-[20px]">article</i>
                        </span>
                        All Posts
                    </a>
                </div>

                <!-- Client Management Section -->
                <div class="mt-4">
                    <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Client Management</p>
                    
                    <a href="{{route('admin.currencies.index')}}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg text-slate-900 dark:text-white hover:bg-slate-100 dark:hover:bg-slate-700/50 group transition-colors">
                        <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-amber-50 dark:bg-slate-700 text-amber-600 dark:text-amber-400 mr-3 transition-transform group-hover:scale-110">
                            <i class="material-symbols-outlined text-[20px]">currency_exchange</i>
                        </span>
                        Currencies
                    </a>
                </div>

                <!-- User Management -->
                <div class="space-y-1">
                    <h3 class="px-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        User Management
                    </h3>
                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.users.*') ? 'text-slate-900 bg-slate-200 dark:text-white dark:bg-slate-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-slate-700' }}">
                        <i class="material-symbols-outlined mr-3 flex-shrink-0 h-6 w-6">group</i>
                        <span class="truncate">Users</span>
                    </a>
                </div>

                <!-- Asset Management -->
                <div class="space-y-1 mt-4">
                    <h3 class="px-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        Asset Management
                    </h3>
                    <a href="{{ route('admin.transfers.index') }}" 
                       class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.transfers.*') ? 'text-slate-900 bg-slate-200 dark:text-white dark:bg-slate-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-slate-700' }}">
                        <i class="material-symbols-outlined mr-3 flex-shrink-0 h-6 w-6">swap_horiz</i>
                        <span class="truncate">Asset Transfers</span>
                    </a>
                </div>
            </nav>
        </div>

        <!-- Bottom Section -->
        <div class="p-4 border-t border-slate-200 dark:border-slate-700">
            <form action="{{route('admin.logout')}}" method="post" id="logout-form">
                @csrf
                <button type="button" onclick="logout()" 
                    class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                    <i class="material-symbols-outlined mr-2">logout</i>
                    Sign Out
                </button>
            </form>
        </div>
    </div>
</aside>

<script>
function logout() {
    if (confirm("Are you sure you want to sign out?")) {
        document.getElementById('logout-form').submit();
    }
}
</script>





