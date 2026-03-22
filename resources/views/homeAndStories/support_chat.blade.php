@php
    $css = ['demonav', 'support_chat'];
    $nav = ['commentcn', 'navbar'];
@endphp
<x-navbar :nav="$nav" :css="$css" desc="Support Chat - Rudra Stories" key="support chat rudra stories help" />

<main class="support-page" id="support-chat-page" data-ws-url="{{ env('CHAT_WS_URL', 'ws://127.0.0.1:6001') }}">
    <section class="support-shell">
        <div class="support-left">
            <div class="support-left-header">
                <h1>Support Chat</h1>
                <p>Chat with our team directly. Share images and details here.</p>
            </div>
            
            <div class="identity-card" id="identity-card">
                <div class="identity-title">Your Profile</div>
                <div class="identity-row">
                    <input type="text" id="chatName" placeholder="Your name">
                    <input type="email" id="chatEmail" placeholder="Your email">
                    <button type="button" id="saveIdentity">Save Identity</button>
                </div>
                <small id="identityHint"></small>
            </div>
        </div>
        
        <div class="support-right">
            <div class="chat-card">
                <div class="chat-header">
                    <h3>Support Team</h3>
                    <span id="chatStatus">Connecting...</span>
                </div>
                <div class="chat-history" id="chat-history"></div>
                <form id="chat-form" class="chat-form" enctype="multipart/form-data">
                    @csrf
                    <label for="chatFile" class="file-upload-btn" title="Attach Image"><i class="fa fa-paperclip"></i></label>
                    <input type="file" name="chat_file" id="chatFile" accept="image/*,.pdf">
                    <textarea name="msg45226" id="chatMessage" rows="1" placeholder="Type a message..."></textarea>
                    <div class="chat-actions">
                        <button type="submit" id="sendBtn" title="Send"><i class="fa fa-paper-plane"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>

<script src="{{ asset('js/support_chat.js') }}"></script>
</body>
</html>
