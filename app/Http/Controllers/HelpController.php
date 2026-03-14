<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupportChatMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class HelpController extends Controller
{
    public function sessionInfo(Request $request)
    {
        $identity = $this->resolveIdentity($request);
        return response()->json([
            'chat_token' => $this->resolveChatToken($request),
            'ws_url' => env('CHAT_WS_URL', 'ws://127.0.0.1:6001'),
            'name' => $identity['name'],
            'email' => $identity['email'],
            'needs_identity' => empty($identity['name']) || empty($identity['email']),
        ]);
    }

    public function messages(Request $request)
    {
        $chatToken = $this->resolveChatToken($request);
        $messages = SupportChatMessage::where('chat_token', $chatToken)
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'chat_token' => $chatToken,
            'messages' => $messages->map(function ($item) {
                return $this->serializeMessage($item);
            }),
        ]);
    }

    public function show(Request $request)
    {
        $request->validate([
            'nm45226' => ['nullable', 'string', 'max:255'],
            'em45226' => ['nullable', 'email', 'max:255'],
            'msg45226' => ['nullable', 'string', 'max:4000'],
            'chat_file' => ['nullable', 'file', 'max:10240'],
        ]);

        if (!$request->filled('msg45226') && !$request->hasFile('chat_file')) {
            return response()->json([
                'message' => 'Message or file is required.',
            ], 422);
        }

        $chatToken = $this->resolveChatToken($request);
        $identity = $this->resolveIdentity($request);
        $nameInput = strip_tags((string) $request->input('nm45226'));
        $emailInput = strip_tags((string) $request->input('em45226'));
        $name = $nameInput !== '' ? $nameInput : $identity['name'];
        $email = $emailInput !== '' ? $emailInput : $identity['email'];
        $text = strip_tags((string) $request->input('msg45226'));

        if ($name === '' || $email === '') {
            return response()->json([
                'message' => 'Name and email are required once to start chat.',
            ], 422);
        }

        $request->session()->put('chat_name', $name);
        $request->session()->put('chat_email', $email);

        $filePath = null;
        $fileName = null;
        $fileMime = null;
        if ($request->hasFile('chat_file')) {
            if (!File::exists(public_path('supportChat'))) {
                File::makeDirectory(public_path('supportChat'), 0755, true);
            }
            $file = $request->file('chat_file');
            $storedName = time().'_'.Str::random(10).'_'.$file->getClientOriginalName();
            $file->move(public_path('supportChat'), $storedName);
            $filePath = 'supportChat/'.$storedName;
            $fileName = $file->getClientOriginalName();
            $fileMime = $file->getMimeType();
        }

        $message = SupportChatMessage::create([
            'chat_token' => $chatToken,
            'sender_type' => 'user',
            'name' => $name,
            'email' => $email,
            'message' => $text ?: null,
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
            'message' => 'Message sent successfully.',
            'data' => $payload,
        ]);
    }

    private function resolveChatToken(Request $request): string
    {
        $chatToken = (string) $request->session()->get('chat_token', '');
        if ($chatToken === '') {
            $chatToken = 'chat_'.Str::random(20);
            $request->session()->put('chat_token', $chatToken);
        }

        return $chatToken;
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

    private function resolveIdentity(Request $request): array
    {
        $name = (string) $request->session()->get('chat_name', '');
        $email = (string) $request->session()->get('chat_email', '');

        if (($name === '' || $email === '') && $request->session()->has(['usnm', 'loginstat'])) {
            $username = (string) $request->session()->get('usnm');
            $userRow = DB::table('usersignupinfo')->where('UserName', $username)->first();
            if ($name === '' && $username !== '') {
                $name = $username;
            }
            if ($email === '' && !empty($userRow->Email)) {
                $email = (string) $userRow->Email;
            }
        }

        return [
            'name' => $name,
            'email' => $email,
        ];
    }
}
 
