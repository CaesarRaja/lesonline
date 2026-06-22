@extends('layouts.app')

@section('title', 'Pesan')

@section('content')
<div class="flex h-screen overflow-hidden bg-bg-base" x-data="chatApp()" x-init="init()">
    {{-- Student sidebar is inline, not a partial --}}
    <aside class="fixed left-0 h-full w-sidebar-width bg-surface-container border-r border-outline-variant flex flex-col p-4 z-40 transition-transform duration-300 ease-in-out -translate-x-full md:translate-x-0" :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">
        <div class="mb-8 px-2 flex flex-col gap-1">
            <span class="font-display-logo text-display-logo text-primary">BimbelEdu</span>
            <span class="font-label-sm text-label-sm text-on-surface-variant">Student Portal</span>
        </div>
        <nav class="flex-1 flex flex-col gap-2">
            <a class="flex items-center gap-3 px-3 py-2 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-bold text-label-bold transition-colors" href="{{ route('student.dashboard') }}">
                <span class="material-symbols-outlined">dashboard</span>
                Dashboard
            </a>
            <a class="flex items-center gap-3 px-3 py-2 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-bold text-label-bold transition-colors" href="{{ route('student.materials') }}">
                <span class="material-symbols-outlined">folder</span>
                Materi Saya
            </a>
            <a class="flex items-center gap-3 px-3 py-2 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-bold text-label-bold transition-colors" href="{{ route('student.payments') }}">
                <span class="material-symbols-outlined">receipt_long</span>
                Pembayaran
            </a>
            <a class="flex items-center gap-3 px-3 py-2 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-bold text-label-bold transition-colors" href="{{ route('mentors.index') }}">
                <span class="material-symbols-outlined">search</span>
                Cari Mentor
            </a>
        </nav>
        <div class="mt-auto border-t border-outline-variant pt-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="flex items-center gap-3 px-3 py-2 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-bold text-label-bold transition-colors w-full text-left" type="submit">
                    <span class="material-symbols-outlined">logout</span>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 ml-0 md:ml-sidebar-width h-full overflow-hidden flex flex-col">
        <div class="flex flex-1 overflow-hidden relative">
            {{-- Conversations List --}}
            <div class="w-full md:w-80 lg:w-96 flex-shrink-0 bg-surface-container border-r border-outline-variant flex flex-col overflow-hidden absolute md:relative inset-0 z-10 transition-transform duration-300 ease-in-out md:transform-none"
                 :class="selectedUser ? '-translate-x-full md:translate-x-0' : 'translate-x-0'">
                <div class="px-4 pt-4 pb-2 border-b border-outline-variant bg-surface-container-lowest">
                    <div class="flex items-center gap-4 mb-3">
                        <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 -ml-2 text-on-surface-variant hover:text-text-main hover:bg-surface-variant rounded-lg transition-colors">
                            <span class="material-symbols-outlined">menu</span>
                        </button>
                        <h1 class="font-display-logo text-xl font-extrabold text-text-main">Pesan</h1>
                    </div>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-muted text-[18px]">search</span>
                        <input type="text" x-model="searchQuery" placeholder="Cari percakapan..."
                            class="w-full pl-10 pr-3 py-2 bg-surface rounded-xl border border-outline-variant font-body-main text-body-main outline-none focus:ring-2 focus:ring-primary transition-shadow placeholder:text-text-muted">
                    </div>
                </div>
                <div class="flex-1 overflow-y-auto">
                    <template x-for="mentor in filteredMentors" :key="mentor.id">
                        <button @click="selectMentor(mentor)"
                            class="w-full text-left px-4 py-3 flex items-center gap-3 hover:bg-surface-variant transition-colors border-b border-outline-variant/20"
                            :class="selectedUser?.id === mentor.id ? 'bg-surface-variant' : ''">
                            <div class="relative flex-shrink-0">
                                <div class="w-12 h-12 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-headline-card text-headline-card">
                                    <span x-text="mentor.name.charAt(0).toUpperCase()"></span>
                                </div>
                                <div x-show="mentor.unread > 0" class="absolute -top-0.5 -right-0.5 min-w-[20px] h-5 px-1 rounded-full bg-error text-on-primary text-[11px] font-bold flex items-center justify-center shadow-sm">
                                    <span x-text="mentor.unread > 9 ? '9+' : mentor.unread"></span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-0.5">
                                    <span class="font-label-bold text-label-bold text-text-main truncate" x-text="mentor.name"></span>
                                    <span class="font-label-sm text-label-sm text-text-muted flex-shrink-0 ml-2" x-text="formatTime(mentor.last_time)"></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <p class="font-body-main text-body-main text-text-muted truncate text-sm flex-1 min-w-0" x-text="mentor.last_message || 'Belum ada pesan'"></p>
                                    <template x-if="mentor.unread > 0">
                                        <span class="w-2 h-2 rounded-full bg-error flex-shrink-0 ml-2"></span>
                                    </template>
                                </div>
                            </div>
                        </button>
                    </template>
                    <div x-show="filteredMentors.length === 0" class="p-8 text-center">
                        <span class="material-symbols-outlined text-4xl text-text-muted mb-2" x-text="searchQuery ? 'search_off' : 'chat'"></span>
                        <p class="font-body-main text-body-main text-text-muted" x-text="searchQuery ? 'Tidak ada hasil untuk &quot;' + searchQuery + '&quot;' : 'Belum ada percakapan.'"></p>
                    </div>
                </div>
            </div>

            {{-- Chat Area --}}
            <div class="flex-1 flex flex-col overflow-hidden bg-surface-container-lowest flex-1"
                 :class="!selectedUser ? 'hidden md:flex' : 'flex'">
                {{-- Chat Header --}}
                <template x-if="selectedUser">
                    <div class="px-4 py-3 border-b border-outline-variant bg-surface flex items-center gap-3 flex-shrink-0 shadow-sm">
                        <button @click="selectedUser = null" class="md:hidden p-1 -ml-1 text-on-surface-variant hover:text-text-main rounded-lg transition-colors">
                            <span class="material-symbols-outlined">arrow_back</span>
                        </button>
                        <div class="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-headline-card text-headline-card flex-shrink-0">
                            <span x-text="selectedUser.name.charAt(0).toUpperCase()"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h2 class="font-label-bold text-label-bold text-text-main truncate" x-text="selectedUser.name"></h2>
                            <p class="font-label-sm text-label-sm text-text-muted truncate">Mentor</p>
                        </div>
                    </div>
                </template>
                <template x-if="!selectedUser">
                    <div class="hidden md:flex flex-1 items-center justify-center">
                        <div class="text-center p-8">
                            <div class="w-20 h-20 rounded-full bg-surface-container flex items-center justify-center text-text-muted mx-auto mb-5">
                                <span class="material-symbols-outlined text-4xl">chat_bubble</span>
                            </div>
                            <h3 class="font-headline-card text-headline-card text-xl text-text-main mb-2">Pilih Percakapan</h3>
                            <p class="font-body-main text-body-main text-text-muted max-w-xs mx-auto">Pilih mentor dari daftar untuk mulai chatting</p>
                        </div>
                    </div>
                </template>

                {{-- Messages --}}
                <div x-show="selectedUser" class="flex-1 overflow-y-auto px-4 py-4 space-y-1 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48Y2lyY2xlIGN4PSIyMCIgY3k9IjIwIiByPSIxIiBmaWxsPSIjZTJlMmUyIiBmaWxsLW9wYWNpdHk9IjAuNCIvPjwvc3ZnPg==")]" x-ref="messages">
                    <template x-for="(msg, index) in messages" :key="msg.id">
                        <div>
                            {{-- Date separator --}}
                            <div x-show="showDateSeparator(index)" class="flex justify-center my-3">
                                <span class="px-3 py-1 bg-surface-container-lowest/80 backdrop-blur-sm rounded-full text-label-sm font-label-sm text-text-muted shadow-sm border border-outline-variant/30" x-text="getDateLabel(msg.created_at)"></span>
                            </div>

                            {{-- Message bubble --}}
                            <div class="flex px-1" :class="msg.sender_id === {{ auth()->id() }} ? 'justify-end' : 'justify-start'">
                                <div class="max-w-[85%] md:max-w-[65%] px-3.5 py-2 shadow-sm"
                                    :class="msg.sender_id === {{ auth()->id() }}
                                        ? 'bg-primary text-on-primary rounded-t-2xl rounded-l-2xl rounded-br-sm'
                                        : 'bg-surface text-text-main border border-outline-variant/20 rounded-t-2xl rounded-r-2xl rounded-bl-sm'">
                                    <div x-text="msg.isi" class="whitespace-pre-wrap break-words text-body-main"></div>
                                    <div class="flex items-center justify-end gap-1 mt-0.5"
                                        :class="msg.sender_id === {{ auth()->id() }} ? 'text-on-primary/60' : 'text-text-muted'">
                                        <span class="text-[10px] leading-none" x-text="formatTime(msg.created_at)"></span>
                                        <template x-if="msg.sender_id === {{ auth()->id() }}">
                                            <span class="material-symbols-outlined text-[14px]">done_all</span>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Input --}}
                <div x-show="selectedUser" class="border-t border-outline-variant bg-surface px-3 md:px-4 py-3 flex-shrink-0">
                    <form @submit.prevent="sendMessage" class="flex gap-2 md:gap-3 items-end">
                        <input x-model="newMessage" type="text" placeholder="Ketik pesan..." maxlength="1000"
                            class="flex-1 border border-outline-variant rounded-xl px-4 py-2.5 font-body-main text-body-main bg-surface-container-lowest outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-shadow"
                            :disabled="sending"
                            @keydown.enter.prevent="sendMessage">
                        <button type="submit" :disabled="!newMessage.trim() || sending"
                            class="w-11 h-11 rounded-full bg-primary text-on-primary flex items-center justify-center hover:bg-primary/90 transition-colors disabled:opacity-40 disabled:cursor-not-allowed flex-shrink-0 shadow-sm">
                            <span class="material-symbols-outlined text-[22px] fill-icon">send</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

@push('scripts')
<script>
    function chatApp() {
        return {
            mentors: @json($mentors),
            selectedUser: null,
            messages: [],
            newMessage: '',
            sending: false,
            channel: null,
            searchQuery: '',
            sidebarOpen: false,

            get filteredMentors() {
                if (!this.searchQuery.trim()) return this.mentors;
                const q = this.searchQuery.toLowerCase();
                return this.mentors.filter(m => m.name.toLowerCase().includes(q));
            },

            init() {
                this.listen();
            },

            selectMentor(mentor) {
                this.selectedUser = mentor;
                this.fetchMessages(mentor.id);
                this.markAsRead(mentor.id);
            },

            fetchMessages(userId) {
                fetch('/chat/' + userId)
                    .then(r => r.json())
                    .then(messages => {
                        this.messages = messages;
                        this.$nextTick(() => this.scrollToBottom());
                        if (this.channel) this.channel.unsubscribe();
                        this.listen();
                    });
            },

            markAsRead(userId) {
                const mentor = this.mentors.find(m => m.id === userId);
                if (mentor) mentor.unread = 0;
            },

            sendMessage() {
                if (!this.newMessage.trim() || this.sending || !this.selectedUser) return;
                this.sending = true;
                fetch('{{ route("chat.send") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ receiver_id: this.selectedUser.id, isi: this.newMessage })
                })
                .then(r => r.json())
                .then(msg => {
                    this.messages.push(msg);
                    this.newMessage = '';
                    this.sending = false;
                    this.$nextTick(() => this.scrollToBottom());
                    this.updateLastMessage(msg);
                })
                .catch(() => { this.sending = false; });
            },

            updateLastMessage(msg) {
                const mentor = this.mentors.find(m => m.id === msg.receiver_id);
                if (mentor) {
                    mentor.last_message = msg.isi;
                    mentor.last_time = msg.created_at;
                    this.mentors.sort((a, b) => new Date(b.last_time) - new Date(a.last_time));
                }
            },

            listen() {
                if (this.channel) this.channel.unsubscribe();
                this.channel = window.reverbPusher.subscribe('private-chat.{{ auth()->id() }}');
                this.channel.bind('MessageSent', (e) => {
                    if (!e.message) return;
                    if (this.selectedUser && (e.message.sender_id === this.selectedUser.id || e.message.receiver_id === this.selectedUser.id)) {
                        const exists = this.messages.some(m => m.id === e.message.id);
                        if (!exists) {
                            this.messages.push(e.message);
                            this.$nextTick(() => this.scrollToBottom());
                        }
                    }
                    if (e.message.sender_id !== {{ auth()->id() }}) {
                        this.updateConversationList(e.message);
                    }
                });
            },

            updateConversationList(msg) {
                const existing = this.mentors.find(m => m.id === msg.sender_id);
                if (existing) {
                    existing.last_message = msg.isi;
                    existing.last_time = msg.created_at;
                    if (this.selectedUser?.id !== msg.sender_id) existing.unread++;
                    this.mentors.sort((a, b) => new Date(b.last_time) - new Date(a.last_time));
                }
            },

            scrollToBottom() {
                const el = this.$refs.messages;
                if (el) setTimeout(() => { el.scrollTop = el.scrollHeight; }, 50);
            },

            showDateSeparator(index) {
                if (index === 0) return true;
                const curr = new Date(this.messages[index].created_at).toDateString();
                const prev = new Date(this.messages[index - 1].created_at).toDateString();
                return curr !== prev;
            },

            getDateLabel(dateStr) {
                if (!dateStr) return '';
                const d = new Date(dateStr);
                const now = new Date();
                const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
                const yesterday = new Date(today);
                yesterday.setDate(yesterday.getDate() - 1);
                const msgDate = new Date(d.getFullYear(), d.getMonth(), d.getDate());

                if (msgDate.getTime() === today.getTime()) return 'Hari ini';
                if (msgDate.getTime() === yesterday.getTime()) return 'Kemarin';
                return d.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
            },

            formatTime(dateStr) {
                if (!dateStr) return '';
                const d = new Date(dateStr);
                const now = new Date();
                const diff = now - d;
                const oneDay = 86400000;
                if (diff < oneDay) return d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                if (diff < 7 * oneDay) return d.toLocaleDateString('id-ID', { weekday: 'short' });
                return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
            }
        }
    }
</script>
@endpush
@endsection
