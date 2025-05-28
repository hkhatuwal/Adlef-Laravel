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
            <a href="{{route('admin.dashboard')}}">
                <img src="{{asset('assets/images/logo.svg')}}" class="h-12 mx-auto" alt="Logo">
            </a>
        </div>

        <!-- Navigation Section -->
        <div class="flex-1 overflow-y-auto px-4 py-5">
            <!-- Admin Info -->
            <div class="mb-6 pb-6 border-b border-slate-200 dark:border-slate-700">
                <div class="flex items-center px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-700/50">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-red-500 flex items-center justify-center text-white">
                            <i class="material-symbols-outlined">{{ auth()->user()->hasRole('admin') ? 'admin_panel_settings' : 'badge' }}</i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-slate-900 dark:text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ ucfirst(auth()->user()->roles->first()->name) }}</p>
                    </div>
                </div>
            </div>

            <!-- Main Navigation -->
            <nav class="space-y-6">
                <!-- Overview Section -->
                <div>
                    <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Overview</p>
                    <a href="{{route('admin.dashboard')}}"
                       class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.dashboard') ? 'text-slate-900 bg-slate-200 dark:text-white dark:bg-slate-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-slate-700/50' }} group transition-colors">
                        <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 mr-3 transition-transform group-hover:scale-110">
                            <i class="material-symbols-outlined text-[20px]">dashboard</i>
                        </span>
                        Dashboard
                    </a>
                </div>

                @role('admin')
                <!-- Content Management -->
                <div>
                    <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Content Management</p>
                    <div class="space-y-1">
                        <a href="{{route('admin.posts.create')}}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.posts.create') ? 'text-slate-900 bg-slate-200 dark:text-white dark:bg-slate-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-slate-700/50' }} group transition-colors">
                            <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-50 dark:bg-slate-700 text-emerald-600 dark:text-emerald-400 mr-3 transition-transform group-hover:scale-110">
                                <i class="material-symbols-outlined text-[20px]">post_add</i>
                            </span>
                            Create Post
                        </a>
                        <a href="{{route('admin.posts.index')}}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.posts.index') ? 'text-slate-900 bg-slate-200 dark:text-white dark:bg-slate-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-slate-700/50' }} group transition-colors">
                            <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-purple-50 dark:bg-slate-700 text-purple-600 dark:text-purple-400 mr-3 transition-transform group-hover:scale-110">
                                <i class="material-symbols-outlined text-[20px]">article</i>
                            </span>
                            All Posts
                        </a>
                    </div>
                </div>
                @endrole

                <!-- User & Client Management -->
                <div>
                    <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">User Management</p>
                    <div class="space-y-1">
                        @role('admin')
                        <a href="{{ route('admin.staff.index') }}"
                           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.staff.*') ? 'text-slate-900 bg-slate-200 dark:text-white dark:bg-slate-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-slate-700/50' }} group transition-colors">
                            <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-teal-50 dark:bg-slate-700 text-teal-600 dark:text-teal-400 mr-3 transition-transform group-hover:scale-110">
                                <i class="material-symbols-outlined text-[20px]">badge</i>
                            </span>
                            Staff Members
                        </a>
                        @endrole

                        @can('view users')
                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.users.*') ? 'text-slate-900 bg-slate-200 dark:text-white dark:bg-slate-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-slate-700/50' }} group transition-colors">
                            <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 dark:bg-slate-700 text-blue-600 dark:text-blue-400 mr-3 transition-transform group-hover:scale-110">
                                <i class="material-symbols-outlined text-[20px]">group</i>
                            </span>
                            Users
                        </a>
                        @endcan


                        @role('admin')
                        <a href="{{route('admin.currencies.index')}}"
                           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.currencies.*') ? 'text-slate-900 bg-slate-200 dark:text-white dark:bg-slate-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-slate-700/50' }} group transition-colors">
                            <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-amber-50 dark:bg-slate-700 text-amber-600 dark:text-amber-400 mr-3 transition-transform group-hover:scale-110">
                                <i class="material-symbols-outlined text-[20px]">currency_exchange</i>
                            </span>
                            Currencies
                        </a>
                        @endrole

                        @can('view users')
                            <a href="{{ route('admin.help-center.index') }}"
                               class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.help-center.*') ? 'text-slate-900 bg-slate-200 dark:text-white dark:bg-slate-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-slate-700/50' }} group transition-colors">
                            <span
                                class="flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 dark:bg-slate-700 text-blue-600 dark:text-blue-400 mr-3 transition-transform group-hover:scale-110">
                                <i class="material-symbols-outlined text-[20px]">help</i>
                            </span>
                                Help Center
                            </a>
                        @endcan
                    </div>
                </div>

                <!-- Asset Management -->
                <div>
                    <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Asset Management</p>
                    <div class="space-y-1">
                        @role('admin')
                        <a href="{{ route('admin.deposit-accounts.index') }}"
                           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.deposit-accounts.*') ? 'text-slate-900 bg-slate-200 dark:text-white dark:bg-slate-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-slate-700/50' }} group transition-colors">
                            <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-cyan-50 dark:bg-slate-700 text-cyan-600 dark:text-cyan-400 mr-3 transition-transform group-hover:scale-110">
                                <i class="material-symbols-outlined text-[20px]">account_balance</i>
                            </span>
                            Deposit Accounts
                        </a>
                        @endrole

                        @can('view asset transfers')
                        <a href="{{ route('admin.transfers.index') }}"
                           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.transfers.*') ? 'text-slate-900 bg-slate-200 dark:text-white dark:bg-slate-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-slate-700/50' }} group transition-colors">
                            <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-green-50 dark:bg-slate-700 text-green-600 dark:text-green-400 mr-3 transition-transform group-hover:scale-110">
                                <i class="material-symbols-outlined text-[20px]">swap_horiz</i>
                            </span>
                            Asset Transfers
                        </a>
                        @endcan

                        @can('view otc trades')
                        <a href="{{ route('admin.otc.index') }}"
                           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.otc.*') ? 'text-slate-900 bg-slate-200 dark:text-white dark:bg-slate-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-slate-700/50' }} group transition-colors">
                            <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-orange-50 dark:bg-slate-700 text-orange-600 dark:text-orange-400 mr-3 transition-transform group-hover:scale-110">
                                <i class="material-symbols-outlined text-[20px]">currency_exchange</i>
                            </span>
                            OTC Trades
                        </a>
                        @endcan

                        @can('view users')
                        <a href="{{ route('admin.activities.index') }}"
                           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.activities.*') ? 'text-slate-900 bg-slate-200 dark:text-white dark:bg-slate-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-slate-700/50' }} group transition-colors">
                            <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 mr-3 transition-transform group-hover:scale-110">
                                <i class="material-symbols-outlined text-[20px]">history</i>
                            </span>
                            User Activities
                        </a>
                        @endcan
                    </div>
                </div>

                @role('admin')
                <!-- System -->
                <div>
                    <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">System</p>
                    <div class="space-y-1">
                        <a href="{{ route('admin.settings.index') }}"
                           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.settings.*') ? 'text-slate-900 bg-slate-200 dark:text-white dark:bg-slate-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-slate-700/50' }} group transition-colors">
                            <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 mr-3 transition-transform group-hover:scale-110">
                                <i class="material-symbols-outlined text-[20px]">settings</i>
                            </span>
                            Settings
                        </a>
                    </div>
                </div>
                @endrole
            </nav>
        </div>

        <!-- Bottom Section -->
        <div class="p-4 mt-auto border-t border-slate-200 dark:border-slate-700">
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





