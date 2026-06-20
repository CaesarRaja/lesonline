@extends('layouts.app')
@section('title', 'Manajemen User')
@section('content')
<div class="flex h-screen overflow-hidden bg-bg-base">
@include('admin._sidebar')
<main class="flex-1 ml-sidebar-width h-full overflow-y-auto bg-surface-container-lowest p-margin-desktop max-w-container-max mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="font-display-logo text-[24px] font-extrabold text-on-surface">Manajemen User</h2>
        <div class="flex items-center gap-3">
            @if($trashedCount > 0)
            <span class="text-label-sm text-text-muted">{{ $trashedCount }} terhapus</span>
            @endif
            <button onclick="openCreateModal()" class="bg-primary text-on-primary font-label-bold text-label-bold px-4 py-2 rounded-lg hover:bg-primary/90 transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Tambah User
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-success-bg text-success-text font-label-bold text-label-bold px-4 py-3 rounded-xl border border-success-text/20">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="bg-error-container text-on-error-container font-label-bold text-label-bold px-4 py-3 rounded-xl border border-error/20">{{ session('error') }}</div>
    @endif

    <div class="bg-surface border border-outline-variant rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-left">
            <thead><tr class="bg-surface-container-low border-b border-outline-variant">
                <th class="px-6 py-4 font-label-sm text-label-sm text-text-muted uppercase">Nama</th>
                <th class="px-6 py-4 font-label-sm text-label-sm text-text-muted uppercase">Email</th>
                <th class="px-6 py-4 font-label-sm text-label-sm text-text-muted uppercase">Role</th>
                <th class="px-6 py-4 font-label-sm text-label-sm text-text-muted uppercase">Verifikasi</th>
                <th class="px-6 py-4 font-label-sm text-label-sm text-text-muted uppercase">Bergabung</th>
                <th class="px-6 py-4 font-label-sm text-label-sm text-text-muted uppercase">Aksi</th>
            </tr></thead>
            <tbody class="divide-y divide-outline-variant bg-surface">
                @foreach($users as $user)
                <tr class="hover:bg-surface-container-lowest transition-colors">
                    <td class="px-6 py-4 font-body-main text-on-surface font-medium">{{ $user->name }}</td>
                    <td class="px-6 py-4 font-body-main text-text-muted">{{ $user->email }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 rounded-md text-[12px] font-semibold
                            @if($user->role === 'admin') bg-primary-fixed text-primary
                            @elseif($user->role === 'mentor') bg-secondary-fixed text-secondary
                            @else bg-surface-container-high text-on-surface-variant @endif">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 rounded-md text-[12px] font-semibold
                            @if($user->verification_status === 'verified') bg-success-bg text-success-text
                            @elseif($user->verification_status === 'rejected') bg-error-container text-on-error-container
                            @else bg-pending-bg text-pending-text @endif">
                            {{ $user->verification_status ?? 'verified' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-body-main text-body-main text-text-muted">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <button onclick='openEditModal(@json($user))' class="p-1.5 text-primary hover:bg-primary-fixed rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-[18px]">edit</span>
                            </button>
                            @if($user->id !== auth()->id())
                            <button onclick="openDeleteModal({{ $user->id }}, '{{ $user->name }}')" class="p-1.5 text-error hover:bg-error-container rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $users->links() }}</div>
</main>
</div>

{{-- Modal Tambah User --}}
<div id="create-modal" class="fixed inset-0 z-50 hidden bg-black/40 flex items-center justify-center p-4" onclick="if(event.target===this)closeCreateModal()">
    <div class="bg-surface rounded-2xl border border-outline-variant shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto p-6" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline-card text-headline-card text-on-surface">Tambah User Baru</h3>
            <button onclick="closeCreateModal()" class="text-text-muted hover:text-on-surface"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="font-label-sm text-label-sm text-text-muted block mb-1">Nama</label>
                    <input name="name" required class="w-full border border-outline-variant rounded-lg px-3 py-2 font-body-main" placeholder="Nama lengkap">
                </div>
                <div class="col-span-2">
                    <label class="font-label-sm text-label-sm text-text-muted block mb-1">Email</label>
                    <input type="email" name="email" required class="w-full border border-outline-variant rounded-lg px-3 py-2 font-body-main" placeholder="email@example.com">
                </div>
                <div class="col-span-2">
                    <label class="font-label-sm text-label-sm text-text-muted block mb-1">Password</label>
                    <input type="password" name="password" required minlength="8" class="w-full border border-outline-variant rounded-lg px-3 py-2 font-body-main" placeholder="Minimal 8 karakter">
                </div>
                <div>
                    <label class="font-label-sm text-label-sm text-text-muted block mb-1">Role</label>
                    <select name="role" id="create-role" onchange="toggleMentorFields('create')" class="w-full border border-outline-variant rounded-lg px-3 py-2 font-body-main" required>
                        <option value="student">Student</option>
                        <option value="mentor">Mentor</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div>
                    <label class="font-label-sm text-label-sm text-text-muted block mb-1">Verifikasi</label>
                    <select name="verification_status" class="w-full border border-outline-variant rounded-lg px-3 py-2 font-body-main">
                        <option value="pending">Pending</option>
                        <option value="verified">Verified</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
            </div>
            <div id="create-mentor-fields" class="hidden space-y-4 border-t border-outline-variant pt-4">
                <h4 class="font-label-bold text-label-bold text-on-surface">Data Mentor</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="font-label-sm text-label-sm text-text-muted block mb-1">Keahlian</label>
                        <input name="keahlian" class="w-full border border-outline-variant rounded-lg px-3 py-2 font-body-main" placeholder="Contoh: Matematika">
                    </div>
                    <div>
                        <label class="font-label-sm text-label-sm text-text-muted block mb-1">Tarif per Jam (Rp)</label>
                        <input type="number" name="tarif_per_jam" min="0" class="w-full border border-outline-variant rounded-lg px-3 py-2 font-body-main" placeholder="100000">
                    </div>
                    <div class="col-span-2">
                        <label class="font-label-sm text-label-sm text-text-muted block mb-1">Bio</label>
                        <textarea name="bio" rows="2" class="w-full border border-outline-variant rounded-lg px-3 py-2 font-body-main" placeholder="Deskripsi singkat"></textarea>
                    </div>
                    <div class="col-span-2">
                        <label class="font-label-sm text-label-sm text-text-muted block mb-1">Link Meeting</label>
                        <input name="link_meeting" class="w-full border border-outline-variant rounded-lg px-3 py-2 font-body-main" placeholder="https://meet.google.com/...">
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeCreateModal()" class="px-4 py-2 border border-outline-variant rounded-lg font-label-bold text-label-bold text-text-muted hover:bg-surface-variant">Batal</button>
                <button class="px-4 py-2 bg-primary text-on-primary rounded-lg font-label-bold text-label-bold hover:bg-primary/90">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit User --}}
<div id="edit-modal" class="fixed inset-0 z-50 hidden bg-black/40 flex items-center justify-center p-4" onclick="if(event.target===this)closeEditModal()">
    <div class="bg-surface rounded-2xl border border-outline-variant shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto p-6" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline-card text-headline-card text-on-surface">Edit User</h3>
            <button onclick="closeEditModal()" class="text-text-muted hover:text-on-surface"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form method="POST" action="" id="edit-form" class="space-y-4">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="font-label-sm text-label-sm text-text-muted block mb-1">Nama</label>
                    <input name="name" id="edit-name" required class="w-full border border-outline-variant rounded-lg px-3 py-2 font-body-main">
                </div>
                <div class="col-span-2">
                    <label class="font-label-sm text-label-sm text-text-muted block mb-1">Email</label>
                    <input type="email" name="email" id="edit-email" required class="w-full border border-outline-variant rounded-lg px-3 py-2 font-body-main">
                </div>
                <div class="col-span-2">
                    <label class="font-label-sm text-label-sm text-text-muted block mb-1">Password <span class="text-text-muted">(kosongkan jika tidak diubah)</span></label>
                    <input type="password" name="password" minlength="8" class="w-full border border-outline-variant rounded-lg px-3 py-2 font-body-main" placeholder="Minimal 8 karakter">
                </div>
                <div>
                    <label class="font-label-sm text-label-sm text-text-muted block mb-1">Role</label>
                    <select name="role" id="edit-role" onchange="toggleMentorFields('edit')" class="w-full border border-outline-variant rounded-lg px-3 py-2 font-body-main" required>
                        <option value="student">Student</option>
                        <option value="mentor">Mentor</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div>
                    <label class="font-label-sm text-label-sm text-text-muted block mb-1">Verifikasi</label>
                    <select name="verification_status" id="edit-verification" class="w-full border border-outline-variant rounded-lg px-3 py-2 font-body-main">
                        <option value="pending">Pending</option>
                        <option value="verified">Verified</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
            </div>
            <div id="edit-mentor-fields" class="hidden space-y-4 border-t border-outline-variant pt-4">
                <h4 class="font-label-bold text-label-bold text-on-surface">Data Mentor</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="font-label-sm text-label-sm text-text-muted block mb-1">Keahlian</label>
                        <input name="keahlian" id="edit-keahlian" class="w-full border border-outline-variant rounded-lg px-3 py-2 font-body-main">
                    </div>
                    <div>
                        <label class="font-label-sm text-label-sm text-text-muted block mb-1">Tarif per Jam (Rp)</label>
                        <input type="number" name="tarif_per_jam" id="edit-tarif" min="0" class="w-full border border-outline-variant rounded-lg px-3 py-2 font-body-main">
                    </div>
                    <div class="col-span-2">
                        <label class="font-label-sm text-label-sm text-text-muted block mb-1">Bio</label>
                        <textarea name="bio" id="edit-bio" rows="2" class="w-full border border-outline-variant rounded-lg px-3 py-2 font-body-main"></textarea>
                    </div>
                    <div class="col-span-2">
                        <label class="font-label-sm text-label-sm text-text-muted block mb-1">Link Meeting</label>
                        <input name="link_meeting" id="edit-link" class="w-full border border-outline-variant rounded-lg px-3 py-2 font-body-main">
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 border border-outline-variant rounded-lg font-label-bold text-label-bold text-text-muted hover:bg-surface-variant">Batal</button>
                <button class="px-4 py-2 bg-primary text-on-primary rounded-lg font-label-bold text-label-bold hover:bg-primary/90">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Hapus User --}}
<div id="delete-modal" class="fixed inset-0 z-50 hidden bg-black/40 flex items-center justify-center p-4" onclick="if(event.target===this)closeDeleteModal()">
    <div class="bg-surface rounded-2xl border border-outline-variant shadow-xl max-w-sm w-full p-6 text-center" onclick="event.stopPropagation()">
        <div class="w-14 h-14 rounded-full bg-error-container flex items-center justify-center text-error mx-auto mb-4">
            <span class="material-symbols-outlined text-3xl">warning</span>
        </div>
        <h3 class="font-headline-card text-headline-card text-on-surface mb-2">Hapus User</h3>
        <p class="font-body-main text-body-main text-text-muted mb-6">Yakin ingin menghapus <strong id="delete-user-name"></strong>? User akan masuk ke trash.</p>
        <form method="POST" action="" id="delete-form" class="flex justify-center gap-3">
            @csrf @method('DELETE')
            <button type="button" onclick="closeDeleteModal()" class="px-5 py-2 border border-outline-variant rounded-lg font-label-bold text-label-bold text-text-muted hover:bg-surface-variant">Batal</button>
            <button class="px-5 py-2 bg-error text-on-error rounded-lg font-label-bold text-label-bold hover:bg-error/90">Hapus</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
function toggleMentorFields(prefix) {
    const role = document.getElementById(prefix + '-role').value;
    const fields = document.getElementById(prefix + '-mentor-fields');
    fields.classList.toggle('hidden', role !== 'mentor');
}

function openCreateModal() {
    document.getElementById('create-modal').classList.remove('hidden');
    document.getElementById('create-role').value = 'student';
    document.getElementById('create-mentor-fields').classList.add('hidden');
}

function closeCreateModal() {
    document.getElementById('create-modal').classList.add('hidden');
}

function openEditModal(user) {
    document.getElementById('edit-modal').classList.remove('hidden');
    document.getElementById('edit-form').action = '/admin/users/' + user.id;
    document.getElementById('edit-name').value = user.name;
    document.getElementById('edit-email').value = user.email;
    document.getElementById('edit-role').value = user.role;
    document.getElementById('edit-verification').value = user.verification_status || 'pending';

    const hasMentor = user.mentor !== null;
    document.getElementById('edit-keahlian').value = hasMentor ? (user.mentor?.keahlian || '') : '';
    document.getElementById('edit-tarif').value = hasMentor ? (user.mentor?.tarif_per_jam || 0) : 0;
    document.getElementById('edit-bio').value = hasMentor ? (user.mentor?.bio || '') : '';
    document.getElementById('edit-link').value = hasMentor ? (user.mentor?.link_meeting || '') : '';

    toggleMentorFields('edit');
}

function closeEditModal() {
    document.getElementById('edit-modal').classList.add('hidden');
}

function openDeleteModal(id, name) {
    document.getElementById('delete-modal').classList.remove('hidden');
    document.getElementById('delete-user-name').textContent = name;
    document.getElementById('delete-form').action = '/admin/users/' + id;
}

function closeDeleteModal() {
    document.getElementById('delete-modal').classList.add('hidden');
}
</script>
@endpush
@endsection
