(function () {
    const page = document.getElementById('support-chat-page');
    if (!page) return;

    const wsUrl = page.dataset.wsUrl || 'ws://127.0.0.1:6001';
    const nameInput = document.getElementById('chatName');
    const emailInput = document.getElementById('chatEmail');
    const saveIdentityBtn = document.getElementById('saveIdentity');
    const identityCard = document.getElementById('identity-card');
    const identityHint = document.getElementById('identityHint');
    const chatStatus = document.getElementById('chatStatus');
    const chatHistory = document.getElementById('chat-history');
    const chatForm = document.getElementById('chat-form');
    const messageInput = document.getElementById('chatMessage');
    const fileInput = document.getElementById('chatFile');
    const sendBtn = document.getElementById('sendBtn');

    let chatToken = '';
    let ws = null;
    let profile = { name: '', email: '' };

    function escapeHtml(value) {
        return (value || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function loadLocalProfile() {
        try {
            const raw = localStorage.getItem('support_chat_profile');
            if (raw) {
                const parsed = JSON.parse(raw);
                profile.name = parsed.name || '';
                profile.email = parsed.email || '';
            }
        } catch (err) {
            profile = { name: '', email: '' };
        }
    }

    function saveLocalProfile() {
        localStorage.setItem('support_chat_profile', JSON.stringify(profile));
    }

    function applyProfileUI() {
        nameInput.value = profile.name || '';
        emailInput.value = profile.email || '';
        if (profile.name && profile.email) {
            identityHint.innerText = 'Identity saved. You will not need to enter it again.';
            identityCard.style.opacity = '0.85';
        } else {
            identityHint.innerText = 'Fill once and continue chatting.';
            identityCard.style.opacity = '1';
        }
    }

    function renderMessage(item) {
        const row = document.createElement('div');
        row.className = 'chat-row ' + (item.sender_type === 'admin' ? 'admin' : 'user');

        let html = '<div class="chat-bubble">';
        html += '<div class="chat-meta">' + escapeHtml(item.name || item.sender_type) + ' | ' + escapeHtml(item.created_at || '') + '</div>';
        if (item.message) {
            html += '<div>' + escapeHtml(item.message) + '</div>';
        }
        if (item.file_url) {
            html += '<div><a href="' + encodeURI(item.file_url) + '" target="_blank">' + escapeHtml(item.file_name || 'Attachment') + '</a></div>';
        }
        html += '</div>';
        row.innerHTML = html;
        chatHistory.appendChild(row);
        chatHistory.scrollTop = chatHistory.scrollHeight;
    }

    function loadMessages() {
        $.get('/chat/messages', function (response) {
            chatHistory.innerHTML = '';
            (response.messages || []).forEach(renderMessage);
        });
    }

    function connectWs() {
        try {
            ws = new WebSocket(wsUrl);
            ws.onopen = function () {
                chatStatus.innerText = 'Live connected';
                ws.send(JSON.stringify({ action: 'subscribe-chat', chat_token: chatToken }));
            };
            ws.onmessage = function (event) {
                const payload = JSON.parse(event.data || '{}');
                if (payload.event !== 'message.created') return;
                if (payload.chat_token !== chatToken) return;
                renderMessage(payload.message);
            };
            ws.onclose = function () {
                chatStatus.innerText = 'Disconnected';
            };
        } catch (e) {
            chatStatus.innerText = 'Realtime unavailable';
        }
    }

    function initSession() {
        $.get('/chat/session', function (response) {
            chatToken = response.chat_token || '';
            if (!profile.name && response.name) profile.name = response.name;
            if (!profile.email && response.email) profile.email = response.email;
            saveLocalProfile();
            applyProfileUI();
            loadMessages();
            connectWs();
        });
    }

    saveIdentityBtn.addEventListener('click', function () {
        const name = (nameInput.value || '').trim();
        const email = (emailInput.value || '').trim();
        if (!name || !email) {
            identityHint.innerText = 'Name and email both required.';
            return;
        }
        profile.name = name;
        profile.email = email;
        saveLocalProfile();
        applyProfileUI();
    });

    chatForm.addEventListener('submit', function (event) {
        event.preventDefault();
        const text = (messageInput.value || '').trim();
        if (!profile.name || !profile.email) {
            identityHint.innerText = 'Save your name and email first.';
            return;
        }
        if (!text && !fileInput.files.length) {
            return;
        }

        sendBtn.disabled = true;
        const fd = new FormData(chatForm);
        fd.append('nm45226', profile.name);
        fd.append('em45226', profile.email);

        $.ajax({
            type: 'POST',
            url: '/help',
            data: fd,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                if (!ws || ws.readyState !== WebSocket.OPEN) {
                    renderMessage(response.data);
                }
                messageInput.value = '';
                fileInput.value = '';
            },
            error: function (xhr) {
                const res = xhr.responseJSON || {};
                alert(res.message || 'Unable to send message.');
            },
            complete: function () {
                sendBtn.disabled = false;
            }
        });
    });

    loadLocalProfile();
    applyProfileUI();
    initSession();
})();
