<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<div id="admin-chat-root" data-ws-url="{{ $wsUrl }}">
    <style>
        #admin-chat-root { font-family: 'Inter', sans-serif; height: calc(100vh - 120px); }
        .admin-chat-layout { 
            display: grid; grid-template-columns: 350px 1fr; gap: 0; 
            height: 100%; max-height: 800px;
            background: #ffffff; border-radius: 16px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.05); overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        
        /* Sidebar Sessions */
        .admin-chat-sidebar {
            background: #ffffff; border-right: 1px solid #e2e8f0;
            display: flex; flex-direction: column; width: 100%;
        }
        .admin-chat-sidebar-header {
            padding: 20px; border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
        }
        .admin-chat-sidebar-header h3 {
            margin: 0; font-size: 1.2rem; color: #0f172a; font-weight: 600;
        }
        .admin-chat-sessions {
            flex: 1; overflow-y: auto;
        }
        .admin-chat-session-item {
            padding: 16px 20px; border-bottom: 1px solid #f1f5f9; cursor: pointer;
            transition: all 0.2s; display: flex; align-items: center; gap: 12px;
        }
        .admin-chat-session-item:hover { background: #f8fafc; }
        .admin-chat-session-item.active { background: #eff6ff; border-left: 4px solid #3b82f6; }
        .session-avatar {
            width: 44px; height: 44px; border-radius: 50%; background: linear-gradient(135deg, #60a5fa, #3b82f6);
            display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 600; font-size: 1.1rem;
            flex-shrink: 0;
        }
        .session-info { flex: 1; overflow: hidden; }
        .session-name { font-weight: 600; color: #1e293b; font-size: 0.95rem; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .session-preview { color: #64748b; font-size: 0.85rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        /* Thread Area */
        .admin-chat-thread {
            display: flex; flex-direction: column; background: #f8fafc; height: 100%;
        }
        .admin-chat-thread-header {
            padding: 16px 24px; border-bottom: 1px solid #e2e8f0; background: #ffffff;
            display: flex; align-items: center; gap: 12px; height: 73px; font-weight: 600; color: #0f172a;
        }
        .admin-chat-thread-header .session-avatar { width: 40px; height: 40px; font-size: 1rem; }
        .admin-chat-thread-body {
            flex: 1; overflow-y: auto; padding: 24px; display: flex; flex-direction: column; gap: 12px;
        }
        
        /* Chat Bubbles */
        .admin-chat-bubble-container { display: flex; max-width: 80%; }
        .admin-chat-bubble-container.user { align-self: flex-start; }
        .admin-chat-bubble-container.admin { align-self: flex-end; flex-direction: row-reverse; }
        
        .admin-chat-bubble {
            padding: 12px 16px; border-radius: 16px;
            position: relative; line-height: 1.5; font-size: 0.95rem;
            word-wrap: break-word; box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .admin-chat-bubble-container.user .admin-chat-bubble {
            background: #ffffff; color: #1e293b; border: 1px solid #e2e8f0;
            border-bottom-left-radius: 4px;
        }
        .admin-chat-bubble-container.admin .admin-chat-bubble {
            background: #3b82f6; color: #ffffff;
            border-bottom-right-radius: 4px;
        }
        
        .admin-chat-meta { font-size: 0.75rem; margin-top: 6px; text-align: right; opacity: 0.8; }
        .admin-chat-bubble-container.user .admin-chat-meta { color: #94a3b8; }
        .admin-chat-bubble-container.admin .admin-chat-meta { color: #bfdbfe; }
        
        .admin-chat-file { 
            display: inline-flex; align-items: center; gap: 8px; margin-top: 8px; 
            padding: 8px 12px; background: rgba(0,0,0,0.05); border-radius: 8px; text-decoration: none;
            font-size: 0.85rem; font-weight: 500;
        }
        .admin-chat-bubble-container.user .admin-chat-file { color: #3b82f6; background: #eff6ff; }
        .admin-chat-bubble-container.admin .admin-chat-file { color: #ffffff; background: rgba(255,255,255,0.2); }
        .admin-chat-image-preview { max-width: 100%; border-radius: 8px; margin-top: 8px; display: block; }

        /* Chat Form */
        .admin-chat-form {
            padding: 16px 24px; border-top: 1px solid #e2e8f0; background: #ffffff;
            display: flex; gap: 12px; align-items: center; justify-content: center;
        }
        #admin-chat-file { display: none; }
        .file-upload-btn {
            background: #f1f5f9; color: #64748b; width: 44px; height: 44px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; cursor: pointer;
            transition: all 0.2s; border: none; font-size: 1.2rem;
        }
        .file-upload-btn:hover { background: #e2e8f0; color: #3b82f6; }
        
        #admin-chat-text {
            flex: 1; padding: 12px 16px; border-radius: 24px; border: 1px solid #e2e8f0;
            background: #f8fafc; font-size: 0.95rem; outline: none; transition: border-color 0.2s;
        }
        #admin-chat-text:focus { border-color: #3b82f6; background: #ffffff; }
        
        .admin-chat-form button[type="submit"] {
            background: #3b82f6; color: #ffffff; width: 44px; height: 44px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; cursor: pointer;
            border: none; transition: all 0.2s; font-size: 1.1rem;
        }
        .admin-chat-form button[type="submit"]:hover { background: #2563eb; transform: scale(1.05); }

        @media (max-width: 900px) { .admin-chat-layout { grid-template-columns: 1fr; } }
    </style>

    <div class="admin-chat-layout">
        <!-- Sidebar -->
        <div class="admin-chat-sidebar">
            <div class="admin-chat-sidebar-header">
                <h3>Support Messages</h3>
            </div>
            <div class="admin-chat-sessions" id="admin-chat-sessions">
                @foreach ($sessions as $session)
                    <div class="admin-chat-session-item" data-chat-token="{{ $session['chat_token'] }}">
                        <div class="session-avatar">
                            {{ substr($session['last_message']['name'] ?? 'U', 0, 1) }}
                        </div>
                        <div class="session-info">
                            <div class="session-name">{{ $session['last_message']['name'] ?? $session['chat_token'] }}</div>
                            <div class="session-preview">{{ $session['last_message']['message'] ?? ($session['last_message']['file_name'] ?? 'Photo/Attachment') }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Thread -->
        <div class="admin-chat-thread">
            <div class="admin-chat-thread-header" id="admin-chat-header">
                Select a conversation
            </div>
            <div class="admin-chat-thread-body" id="admin-chat-body"></div>
            <form class="admin-chat-form" id="admin-chat-form">
                @csrf
                <label for="admin-chat-file" class="file-upload-btn" title="Attach File"><i class="fa fa-paperclip"></i></label>
                <input type="file" id="admin-chat-file" />
                <input type="text" id="admin-chat-text" placeholder="Type a message..." autocomplete="off"/>
                <button type="submit" title="Send"><i class="fa fa-paper-plane"></i></button>
            </form>
        </div>
    </div>
</div>
