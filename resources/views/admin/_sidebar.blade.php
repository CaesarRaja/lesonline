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
        <a class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('admin.disputes') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-variant' }} rounded-lg font-label-bold text-label-bold transition-colors" href="{{ route('admin.disputes') }}"><span class="material-symbols-outlined">gavel</span> Sengketa</a>
        <a class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('admin.reviews') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-variant' }} rounded-lg font-label-bold text-label-bold transition-colors" href="{{ route('admin.reviews') }}"><span class="material-symbols-outlined">rate_review</span> Moderasi Ulasan</a>
        <a class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('admin.broadcasts') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-variant' }} rounded-lg font-label-bold text-label-bold transition-colors" href="{{ route('admin.broadcasts') }}"><span class="material-symbols-outlined">campaign</span> Broadcast</a>
        <a class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('admin.test-midtrans') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-variant' }} rounded-lg font-label-bold text-label-bold transition-colors" href="{{ route('admin.test-midtrans') }}"><span class="material-symbols-outlined">api</span> Test Midtrans</a>
    </nav>
    <div class="mt-auto pt-4 border-t border-outline-variant">
        <form method="POST" action="{{ route('logout') }}">@csrf
            <button class="flex items-center gap-3 px-3 py-2 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-sm text-label-sm w-full text-left"><span class="material-symbols-outlined text-[18px]">logout</span> Keluar</button>
        </form>
    </div>
</aside>
