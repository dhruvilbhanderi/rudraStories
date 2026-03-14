<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupportChatMessage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
class MsgController extends Controller
{
    public function show()
    {
        $sessions = SupportChatMessage::select('chat_token')
            ->selectRaw('MAX(id) as last_id')
            ->groupBy('chat_token')
            ->orderByDesc('last_id')
            ->limit(50)
            ->get()
            ->map(function ($item) {
                $lastMessage = SupportChatMessage::where('id', $item->last_id)->first();
                return [
                    'chat_token' => $item->chat_token,
                    'last_message' => $lastMessage ? $this->serializeMessage($lastMessage) : null,
                ];
            });

        return view('admin.messages')->with([
            'sessions' => $sessions,
            'wsUrl' => env('CHAT_WS_URL', 'ws://127.0.0.1:6001'),
        ]);
    }

    public function sessions()
    {
        $sessions = SupportChatMessage::select('chat_token')
            ->selectRaw('MAX(id) as last_id')
            ->groupBy('chat_token')
            ->orderByDesc('last_id')
            ->limit(100)
            ->get()
            ->map(function ($item) {
                $lastMessage = SupportChatMessage::where('id', $item->last_id)->first();
                return [
                    'chat_token' => $item->chat_token,
                    'last_message' => $lastMessage ? $this->serializeMessage($lastMessage) : null,
                ];
            });

        return response()->json(['sessions' => $sessions]);
    }

    public function messages($chatToken)
    {
        $messages = SupportChatMessage::where('chat_token', $chatToken)
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'messages' => $messages->map(function ($item) {
                return $this->serializeMessage($item);
            }),
        ]);
    }

    public function send(Request $request, $chatToken)
    {
        $request->validate([
            'message' => ['nullable', 'string', 'max:4000'],
            'chat_file' => ['nullable', 'file', 'max:10240'],
        ]);

        if (!$request->filled('message') && !$request->hasFile('chat_file')) {
            return response()->json(['message' => 'Message or file is required.'], 422);
        }

        $filePath = null;
        $fileName = null;
        $fileMime = null;
        if ($request->hasFile('chat_file')) {
            if (!File::exists(public_path('supportChat'))) {
                File::makeDirectory(public_path('supportChat'), 0755, true);
            }
            $file = $request->file('chat_file');
            $storedName = time().'_admin_'.$file->getClientOriginalName();
            $file->move(public_path('supportChat'), $storedName);
            $filePath = 'supportChat/'.$storedName;
            $fileName = $file->getClientOriginalName();
            $fileMime = $file->getMimeType();
        }

        $message = SupportChatMessage::create([
            'chat_token' => $chatToken,
            'sender_type' => 'admin',
            'name' => 'Admin',
            'email' => null,
            'message' => $request->filled('message') ? strip_tags((string) $request->message) : null,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_mime' => $fileMime,
        ]);

        $payload = $this->serializeMessage($message);
        $this->broadcast([
            'event' => 'message.created',
            'scope' => 'chat',
            'chat_token' => $chatToken,
            'message' => $payload,
        ]);

        return response()->json([
            'status' => 'ok',
            'data' => $payload,
        ]);
    }

    private function serializeMessage(SupportChatMessage $item): array
    {
        return [
            'id' => $item->id,
            'chat_token' => $item->chat_token,
            'sender_type' => $item->sender_type,
            'name' => $item->name,
            'email' => $item->email,
            'message' => $item->message,
            'file_url' => $item->file_path ? asset($item->file_path) : null,
            'file_name' => $item->file_name,
            'file_mime' => $item->file_mime,
            'created_at' => optional($item->created_at)->toDateTimeString(),
        ];
    }

    private function broadcast(array $payload): void
    {
        $endpoint = rtrim(env('CHAT_WS_HTTP_URL', 'http://127.0.0.1:6002'), '/').'/broadcast';
        $secret = env('CHAT_WS_SECRET');
        if (empty($secret)) {
            return;
        }

        try {
            Http::timeout(1.5)->withHeaders([
                'X-Chat-Secret' => $secret,
            ])->post($endpoint, $payload);
        } catch (\Throwable $e) {
            // Relay is optional.
        }
    }
}
