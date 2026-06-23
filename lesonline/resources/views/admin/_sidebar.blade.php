{{-- Desktop sidebar --}}
<aside class="hidden md:flex flex-col h-full w-sidebar-width bg-surface-container border-r border-outline-variant p-4 flex-shrink-0 z-20">
    <div class="mb-8 px-2 flex items-center gap-3">
        <div class="w-10 h-10 rounded-lg bg-primary-container flex items-center justify-center text-on-primary">
            <span class="material-symbols-outlined fill-icon">admin_panel_settings</span>
        </div>
        <div><h1 class="font-display-logo text-display-logo text-primary">BimbelEdu</h1><p class="font-label-sm text-label-sm text-on-surface-variant">Admin Portal</p></div>
    </div>
    <nav class="flex-1 space-y-1">
        <a class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('admin.dashboard') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-variant' }} rounded-lg font-label-bold text-label-bold transition-colors" href="{{ route('admin.dashboard') }}"><span class="material-symbols-outlined">dashboard</span> Dashboard</a>
        <a class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('admin.users') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-variant' }} rounded-lg font-label-bold text-label-bold transition-colors" href="{{ route('admin.users') }}"><span class="material-symbols-outlined">group</span> Manajemen User</a>
        <a class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('admin.verifications') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-variant' }} rounded-lg font-label-bold text-label-bold transition-colors" href="{{ route('admin.verifications') }}"><span class="material-symbols-outlined">verified_user</span> Verifikasi Mentor</a>
        <a class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('admin.fees') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-variant' }} rounded-lg font-label-bold text-label-bold transition-colors" href="{{ route('admin.fees') }}"><span class="material-symbols-outlined">payments</span> Komisi Platform</a>
        <a class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('admin.withdrawals') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-variant' }} rounded-lg font-label-bold text-label-bold transition-colors" href="{{ route('admin.withdrawals') }}"><span class="material-symbols-outlined">account_balance</span> Penarikan Dana</a>
        <a class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('admin.reviews') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-variant' }} rounded-lg font-label-bold text-label-bold transition-colors" href="{{ route('admin.reviews') }}"><span class="material-symbols-outlined">rate_review</span> Moderasi Ulasan</a>
    </nav>
    <div class="mt-auto pt-4 border-t border-outline-variant flex flex-col-reverse" x-data="{ open: false }" @click.outside="open = false">
        <button @click="open = ! open" class="flex items-center gap-3 px-2 py-2 w-full rounded-lg hover:bg-surface-variant transition-colors text-left">
            <div class="w-8 h-8 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-label-bold flex-shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <span class="font-label-bold text-label-bold text-text-main truncate flex-1">{{ auth()->user()->name }}</span>
        </button>
        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="mb-2 bg-surface-container-lowest rounded-lg border border-outline-variant shadow-xl overflow-hidden" style="display: none;" @click="open = false">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:bg-surface-variant font-label-bold text-label-bold transition-colors w-full text-left">
                    <span class="material-symbols-outlined">logout</span> Keluar
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- Mobile drawer overlay --}}
<div x-show="sidebarOpen" x-cloak x-transition:enter="transition-opacity ease-linear duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="sidebarOpen = false" class="fixed inset-0 z-30 bg-black/40 md:hidden"></div>

{{-- Mobile drawer --}}
<aside class="fixed md:hidden left-0 top-0 h-full w-sidebar-width bg-surface-container border-r border-outline-variant flex flex-col p-4 z-40 transition-transform duration-300 ease-in-out -translate-x-full" :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">
    <div class="mb-8 px-2 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-primary-container flex items-center justify-center text-on-primary">
                <span class="material-symbols-outlined fill-icon">admin_panel_settings</span>
            </div>
            <div><h1 class="font-display-logo text-display-logo text-primary">BimbelEdu</h1><p class="font-label-sm text-label-sm text-on-surface-variant">Admin Portal</p></div>
        </div>
        <button @click="sidebarOpen = false" class="p-1 text-on-surface-variant hover:text-text-main rounded-lg">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>
    <nav class="flex-1 space-y-1">
        <a @click="sidebarOpen = false" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('admin.dashboard') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-variant' }} rounded-lg font-label-bold text-label-bold transition-colors" href="{{ route('admin.dashboard') }}"><span class="material-symbols-outlined">dashboard</span> Dashboard</a>
        <a @click="sidebarOpen = false" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('admin.users') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-variant' }} rounded-lg font-label-bold text-label-bold transition-colors" href="{{ route('admin.users') }}"><span class="material-symbols-outlined">group</span> Manajemen User</a>
        <a @click="sidebarOpen = false" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('admin.verifications') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-variant' }} rounded-lg font-label-bold text-label-bold transition-colors" href="{{ route('admin.verifications') }}"><span class="material-symbols-outlined">verified_user</span> Verifikasi Mentor</a>
        <a @click="sidebarOpen = false" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('admin.fees') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-variant' }} rounded-lg font-label-bold text-label-bold transition-colors" href="{{ route('admin.fees') }}"><span class="material-symbols-outlined">payments</span> Komisi Platform</a>
        <a @click="sidebarOpen = false" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('admin.withdrawals') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-variant' }} rounded-lg font-label-bold text-label-bold transition-colors" href="{{ route('admin.withdrawals') }}"><span class="material-symbols-outlined">account_balance</span> Penarikan Dana</a>
        <a @click="sidebarOpen = false" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('admin.reviews') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-variant' }} rounded-lg font-label-bold text-label-bold transition-colors" href="{{ route('admin.reviews') }}"><span class="material-symbols-outlined">rate_review</span> Moderasi Ulasan</a>
    </nav>
    <div class="mt-auto pt-4 border-t border-outline-variant">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="flex items-center gap-3 px-3 py-2 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-bold text-label-bold transition-colors w-full text-left" type="submit">
                <span class="material-symbols-outlined">logout</span> Keluar
            </button>
        </form>
    </div>
</aside>
