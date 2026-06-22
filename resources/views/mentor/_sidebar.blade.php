<aside class="hidden md:flex bg-surface-container flex-col h-full p-4 fixed left-0 w-sidebar-width border-r border-outline-variant z-20">
    <div class="mb-8 px-4 flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center text-on-primary">
            <span class="material-symbols-outlined" style="font-size: 20px;">menu_book</span>
        </div>
        <div>
            <h1 class="font-display-logo text-display-logo text-primary">BimbelEdu</h1>
            <p class="font-label-sm text-label-sm text-on-surface-variant">Mentor Portal</p>
        </div>
    </div>
    <nav class="flex-1 space-y-1">
        <a class="flex items-center gap-3 px-4 py-3 rounded-lg font-label-bold text-label-bold transition-colors {{ $active === 'dashboard' ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-variant' }}" href="{{ route('mentor.dashboard') }}">
            <span class="material-symbols-outlined {{ $active === 'dashboard' ? 'fill-icon' : '' }}">dashboard</span> Dashboard
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-lg font-label-bold text-label-bold transition-colors {{ $active === 'schedules' ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-variant' }}" href="{{ route('mentor.schedules') }}">
            <span class="material-symbols-outlined {{ $active === 'schedules' ? 'fill-icon' : '' }}">calendar_month</span> Jadwal
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-lg font-label-bold text-label-bold transition-colors {{ $active === 'materials' ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-variant' }}" href="{{ route('mentor.materials') }}">
            <span class="material-symbols-outlined {{ $active === 'materials' ? 'fill-icon' : '' }}">cloud_upload</span> Upload Materi
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-lg font-label-bold text-label-bold transition-colors {{ $active === 'withdrawals' ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-variant' }}" href="{{ route('mentor.withdrawals') }}">
            <span class="material-symbols-outlined {{ $active === 'withdrawals' ? 'fill-icon' : '' }}">account_balance</span> Penarikan Saldo
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-lg font-label-bold text-label-bold transition-colors {{ $active === 'settings' ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-variant' }}" href="{{ route('mentor.settings') }}">
            <span class="material-symbols-outlined {{ $active === 'settings' ? 'fill-icon' : '' }}">badge</span> Detail Mentor
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-lg font-label-bold text-label-bold transition-colors text-on-surface-variant hover:bg-surface-variant" href="{{ route('mentor.export-pdf') }}">
            <span class="material-symbols-outlined">picture_as_pdf</span> Export Laporan
        </a>
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
