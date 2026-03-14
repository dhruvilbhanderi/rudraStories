<div id="admin-chat-root" data-ws-url="{{ $wsUrl }}">
    <style>
        .admin-chat-layout { display: grid; grid-template-columns: 320px 1fr; gap: 16px; }
        .admin-chat-sessions, .admin-chat-thread { background: #fff; border: 1px solid #ddd; border-radius: 8px; }
        .admin-chat-sessions { max-height: 70vh; overflow-y: auto; }
        .admin-chat-session-item { border-bottom: 1px solid #eee; padding: 10px 12px; cursor: pointer; }
        .admin-chat-session-item.active { background: #edf6ff; }
        .admin-chat-thread { display: flex; flex-direction: column; height: 70vh; }
        .admin-chat-thread-header { border-bottom: 1px solid #eee; padding: 10px 12px; font-weight: 600; }
        .admin-chat-thread-body { flex: 1; overflow-y: auto; padding: 12px; background: #f7f8fb; }
        .admin-chat-bubble { margin-bottom: 10px; max-width: 75%; padding: 8px 10px; border-radius: 8px; background: #fff; }
        .admin-chat-bubble.admin { margin-left: auto; background: #d5f3ff; }
        .admin-chat-file { display: inline-block; margin-top: 6px; }
        .admin-chat-form { border-top: 1px solid #eee; padding: 10px; display: flex; gap: 8px; align-items: center; }
        .admin-chat-form input[type="text"] { flex: 1; border: 1px solid #ccc; border-radius: 6px; padding: 8px; }
        .admin-chat-form button { border: 0; background: #1d3557; color: #fff; padding: 8px 14px; border-radius: 6px; }
        @media (max-width: 900px) { .admin-chat-layout { grid-template-columns: 1fr; } }
    </style>

    <div class="admin-chat-layout">
        <div class="admin-chat-sessions" id="admin-chat-sessions">
            @foreach ($sessions as $session)
                <div class="admin-chat-session-item" data-chat-token="{{ $session['chat_token'] }}">
                    <div><strong>{{ $session['chat_token'] }}</strong></div>
                    <div>{{ $session['last_message']['message'] ?? ($session['last_message']['file_name'] ?? 'No text') }}</div>
                </div>
            @endforeach
        </div>

        <div class="admin-chat-thread">
            <div class="admin-chat-thread-header" id="admin-chat-header">Select a conversation</div>
            <div class="admin-chat-thread-body" id="admin-chat-body"></div>
            <form class="admin-chat-form" id="admin-chat-form">
                @csrf
                <input type="text" id="admin-chat-text" placeholder="Type reply..." />
                <input type="file" id="admin-chat-file" />
                <button type="submit">Send</button>
            </form>
        </div>
    </div>
</div>
