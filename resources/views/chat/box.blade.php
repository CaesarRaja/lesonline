<div id="chat-box" class="fixed bottom-4 right-4 z-50 w-80 bg-surface rounded-2xl shadow-xl border border-outline-variant overflow-hidden hidden">
    <div class="bg-primary text-on-primary px-4 py-3 flex items-center justify-between">
        <span class="font-label-bold text-label-bold">Chat dengan Mentor</span>
        <button onclick="toggleChat()" class="text-on-primary/80 hover:text-on-primary"><span class="material-symbols-outlined text-[20px]">close</span></button>
    </div>
    <div id="chat-messages" class="h-72 overflow-y-auto p-3 space-y-2 bg-surface-container-lowest"></div>
    <form id="chat-form" class="border-t border-outline-variant p-3 flex gap-2 bg-surface" onsubmit="sendChat(event)">
        @csrf
        <input type="hidden" name="receiver_id" id="chat-receiver-id" value="">
        <input type="text" name="isi" id="chat-input" class="flex-1 border border-outline-variant rounded-lg px-3 py-2 font-body-main text-body-main" placeholder="Ketik pesan..." required maxlength="1000">
        <button class="bg-primary text-on-primary px-3 py-2 rounded-lg font-label-bold text-label-bold" type="submit">Kirim</button>
    </form>
</div>

@push('scripts')
<script>
    let chatReceiverId = null;
    let chatReceiverName = '';
    let chatChannel = null;

    function toggleChat(targetUser = null, targetName = '') {
        const box = document.getElementById('chat-box');
        if (box.classList.contains('hidden') && targetUser) {
            chatReceiverId = targetUser;
            chatReceiverName = targetName;
            document.getElementById('chat-receiver-id').value = targetUser;
            document.querySelector('#chat-box .bg-primary span').textContent = 'Chat dengan ' + targetName;
            fetchMessages(targetUser);
            box.classList.remove('hidden');
            listenChat();
        } else if (!box.classList.contains('hidden')) {
            box.classList.add('hidden');
            if (chatChannel) chatChannel.unsubscribe();
        } else {
            box.classList.remove('hidden');
            if (chatReceiverId) {
                fetchMessages(chatReceiverId);
                listenChat();
            }
        }
    }

    function fetchMessages(userId) {
        fetch('/chat/' + userId)
            .then(r => r.json())
            .then(messages => {
                const container = document.getElementById('chat-messages');
                container.innerHTML = '';
                messages.forEach(msg => appendMessage(msg));
                container.scrollTop = container.scrollHeight;
            });
    }

    function sendChat(e) {
        e.preventDefault();
        const input = document.getElementById('chat-input');
        if (!input.value.trim()) return;
        fetch('{{ route("chat.send") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ receiver_id: chatReceiverId, isi: input.value })
        }).then(r => r.json()).then(msg => {
            appendMessage(msg);
            input.value = '';
            document.getElementById('chat-messages').scrollTop = document.getElementById('chat-messages').scrollHeight;
        });
    }

    function appendMessage(msg) {
        const container = document.getElementById('chat-messages');
        const isMe = msg.sender_id === {{ auth()->id() }};
        const div = document.createElement('div');
        div.className = 'flex ' + (isMe ? 'justify-end' : 'justify-start');
        div.innerHTML = '<div class="max-w-[75%] px-3 py-2 rounded-xl ' +
            (isMe ? 'bg-primary text-on-primary' : 'bg-surface-container-high text-on-surface') +
            ' font-body-main text-body-main">' +
            (!isMe ? '<span class="font-label-sm text-label-sm block opacity-70">' + (msg.sender?.name || '') + '</span>' : '') +
            msg.isi +
            '</div>';
        container.appendChild(div);
    }

    function listenChat() {
        if (chatChannel) chatChannel.unsubscribe();
        chatChannel = window.reverbPusher.subscribe('private-chat.{{ auth()->id() }}');
        chatChannel.bind('MessageSent', (e) => {
            if (e.message && (e.message.sender_id === chatReceiverId || e.message.receiver_id === chatReceiverId)) {
                appendMessage(e.message);
                document.getElementById('chat-messages').scrollTop = document.getElementById('chat-messages').scrollHeight;
            }
        });
    }
</script>
@endpush
