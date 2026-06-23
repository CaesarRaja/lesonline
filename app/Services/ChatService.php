<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Collection;

class ChatService
{
    public function getConversations(User $authUser, UserRole $role): Collection
    {
        $userIds = Message::where('sender_id', $authUser->id)
            ->select('receiver_id')
            ->union(
                Message::where('receiver_id', $authUser->id)
                    ->select('sender_id')
            )
            ->pluck('receiver_id')
            ->merge(
                Message::where('receiver_id', $authUser->id)
                    ->pluck('sender_id')
            )
            ->unique()
            ->filter(fn ($id) => (int) $id !== $authUser->id);

        return User::whereIn('id', $userIds)
            ->where('role', $role->value)
            ->select('id', 'name')
            ->get()
            ->map(function ($user) use ($authUser) {
                $lastMsg = Message::where(function ($q) use ($authUser, $user) {
                    $q->where('sender_id', $authUser->id)->where('receiver_id', $user->id);
                })->orWhere(function ($q) use ($authUser, $user) {
                    $q->where('sender_id', $user->id)->where('receiver_id', $authUser->id);
                })->latest()->first();

                $unread = Message::where('sender_id', $user->id)
                    ->where('receiver_id', $authUser->id)
                    ->where('dibaca', false)
                    ->count();

                return (object) [
                    'id' => $user->id,
                    'name' => $user->name,
                    'last_message' => $lastMsg?->isi,
                    'last_time' => $lastMsg?->created_at,
                    'unread' => $unread,
                ];
            })
            ->sortByDesc(fn ($item) => $item->last_time)
            ->values();
    }

    public function markAsRead(int $senderId, int $receiverId): void
    {
        Message::where('sender_id', $senderId)
            ->where('receiver_id', $receiverId)
            ->where('dibaca', false)
            ->update(['dibaca' => true]);
    }
}
