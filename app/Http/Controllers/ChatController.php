<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use App\Services\ChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct(
        private ChatService $chatService,
    ) {}

    public function index(User $user): JsonResponse
    {
        $auth = auth()->user();
        $messages = Message::where(function ($q) use ($auth, $user) {
            $q->where('sender_id', $auth->id)->where('receiver_id', $user->id);
        })->orWhere(function ($q) use ($auth, $user) {
            $q->where('sender_id', $user->id)->where('receiver_id', $auth->id);
        })->orderBy('created_at')->get();

        $this->chatService->markAsRead($user->id, $auth->id);

        return response()->json($messages->load('sender:id,name'));
    }

    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'isi' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $validated['receiver_id'],
            'isi' => $validated['isi'],
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message->load('sender:id,name'));
    }

    public function conversations(): JsonResponse
    {
        $auth = auth()->user();
        $userIds = Message::where('sender_id', $auth->id)
            ->orWhere('receiver_id', $auth->id)
            ->pluck('sender_id', 'receiver_id')
            ->flatten()->unique()->filter(fn ($id) => (int) $id !== $auth->id);

        $users = User::whereIn('id', $userIds)->select('id', 'name')->get();

        return response()->json($users);
    }
}
