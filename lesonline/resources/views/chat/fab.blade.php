@auth
@if(!auth()->user()->isAdmin() && !request()->routeIs('mentor.chat'))
<div x-data="chatFab()" x-init="initFab()" class="fixed bottom-6 right-6 z-40">
    {{-- Unread badge --}}
    <template x-if="totalUnread > 0">
        <div class="absolute -top-1 -right-1 w-6 h-6 rounded-full bg-error text-on-primary text-[11px] font-bold flex items-center justify-center shadow-lg z-10" x-text="totalUnread > 99 ? '99+' : totalUnread"></div>
    </template>

    {{-- FAB button --}}
    <button @click="togglePopup()"
        class="w-14 h-14 rounded-full bg-primary text-on-primary shadow-xl hover:bg-primary-container hover:scale-105 transition-all flex items-center justify-center">
        <span class="material-symbols-outlined text-2xl fill-icon" x-show="!open">chat</span>
        <span class="material-symbols-outlined text-2xl" x-show="open" x-cloak>close</span>
    </button>

    {{-- Popup --}}
    <div x-show="open" x-cloak @click.outside="open = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        class="absolute bottom-16 right-0 w-80 md:w-96 bg-surface rounded-2xl shadow-2xl border border-outline-variant overflow-hidden">
        <div class="px-4 py-3 border-b border-outline-variant bg-surface-container-low flex items-center justify-between">
            <h3 class="font-headline-card text-headline-card text-text-main">Pesan</h3>
            @if(auth()->user()->isMentor())
            <a href="{{ route('mentor.chat') }}" class="font-label-sm text-label-sm text-primary hover:underline">Lihat Semua</a>
            @elseif(auth()->user()->isStudent())
            <a href="{{ route('student.chat') }}" class="font-label-sm text-label-sm text-primary hover:underline">Lihat Semua</a>
            @endif
        </div>
        <div class="max-h-80 overflow-y-auto">
            <template x-for="conv in conversations" :key="conv.id">
                <button @click="openChat(conv)"
                    class="w-full text-left px-4 py-3 flex items-center gap-3 hover:bg-surface-variant transition-colors border-b border-outline-variant/20 last:border-0">
                    <div class="relative flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-label-bold text-label-bold">
                            <span x-text="conv.name.charAt(0).toUpperCase()"></span>
                        </div>
                        <div x-show="conv.unread > 0" class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-error text-on-primary text-[10px] font-bold flex items-center justify-center">
                            <span x-text="conv.unread > 9 ? '9+' : conv.unread"></span>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <span class="font-label-bold text-label-bold text-text-main truncate" x-text="conv.name"></span>
                            <span class="font-label-sm text-label-sm text-text-muted flex-shrink-0 ml-2" x-text="formatFabTime(conv.last_time)"></span>
                        </div>
                        <p class="font-body-main text-body-main text-text-muted truncate text-sm" x-text="conv.last_message || 'Belum ada pesan'"></p>
                    </div>
                </button>
            </template>
            <div x-show="conversations.length === 0" class="p-6 text-center">
                <p class="font-body-main text-body-main text-text-muted">Belum ada percakapan.</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function chatFab() {
        return {
            open: false,
            conversations: [],
            totalUnread: 0,
            polling: null,

            initFab() {
                this.fetchConversations();
                this.polling = setInterval(() => this.fetchConversations(), 10000);
            },

            fetchConversations() {
                fetch('{{ route("chat.conversations") }}')
                    .then(r => r.json())
                    .then(async (users) => {
                        const enriched = await Promise.all(users.map(async (u) => {
                            const unread = await this.countUnread(u.id);
                            const lastMsg = await this.lastMessage(u.id);
                            return { ...u, unread, last_message: lastMsg?.isi || null, last_time: lastMsg?.created_at || null };
                        }));
                        enriched.sort((a, b) => new Date(b.last_time || 0) - new Date(a.last_time || 0));
                        this.conversations = enriched;
                        this.totalUnread = enriched.reduce((sum, c) => sum + c.unread, 0);
                    });
            },

            async countUnread(userId) {
                try {
                    const r = await fetch('/chat/' + userId);
                    const msgs = await r.json();
                    return msgs.filter(m => m.sender_id === userId && !m.dibaca).length;
                } catch { return 0; }
            },

            async lastMessage(userId) {
                try {
                    const r = await fetch('/chat/' + userId);
                    const msgs = await r.json();
                    return msgs[msgs.length - 1] || null;
                } catch { return null; }
            },

            openChat(conv) {
                this.open = false;
                if (typeof toggleChat === 'function') {
                    toggleChat(conv.id, conv.name);
                }
            },

            togglePopup() {
                this.open = !this.open;
                if (this.open) this.fetchConversations();
            },

            formatFabTime(dateStr) {
                if (!dateStr) return '';
                const d = new Date(dateStr);
                const now = new Date();
                const diff = now - d;
                if (diff < 86400000) return d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
            }
        }
    }
</script>
@endpush
@endif
@endauth
