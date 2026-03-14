@php
    $css = ['demonav', 'support_chat'];
    $nav = ['commentcn', 'navbar'];
@endphp
<x-navbar :nav="$nav" :css="$css" desc="Support Chat - Rudra Stories" key="support chat rudra stories help" />

<main class="support-page" id="support-chat-page" data-ws-url="{{ env('CHAT_WS_URL', 'ws://127.0.0.1:6001') }}">
    <section class="support-shell">
        <div class="support-left">
            <h1>Support Chat</h1>
            <p>Message our team in real-time. You can send text and files.</p>
            <div class="support-tip">
                <h4>Quick Notes</h4>
                <p>Name/email is required only first time. After that it auto-fills.</p>
                <p>File size limit: 10 MB</p>
            </div>
        </div>
        <div class="support-right">
            <div class="identity-card" id="identity-card">
                <div class="identity-title">Set Your Profile</div>
                <div class="identity-row">
                    <input type="text" id="chatName" placeholder="Your name">
                    <input type="email" id="chatEmail" placeholder="Your email">
                    <button type="button" id="saveIdentity">Save</button>
                </div>
                <small id="identityHint"></small>
            </div>

            <div class="chat-card">
                <div class="chat-header">
                    <h3>Conversation</h3>
                    <span id="chatStatus">Connecting...</span>
                </div>
                <div class="chat-history" id="chat-history"></div>
                <form id="chat-form" class="chat-form" enctype="multipart/form-data">
                    @csrf
                    <textarea name="msg45226" id="chatMessage" rows="3" placeholder="Write your message..."></textarea>
                    <div class="chat-actions">
                        <input type="file" name="chat_file" id="chatFile">
                        <button type="submit" id="sendBtn">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>

<script src="{{ asset('js/support_chat.js') }}"></script>
</body>
</html>
