$('#ophl22').click(function (e) {
    e.preventDefault();
    $('.helpque').toggleClass('blkinv');
});

(function initSupportChat() {
    const widget = document.getElementById('support-chat-widget');
    if (!widget) return;

    const wsUrl = widget.dataset.wsUrl || 'ws://127.0.0.1:6001';
    const form = document.getElementById('hlpfr252');
    const chatHistory = document.getElementById('chat-history');
    const nameInput = document.getElementById('chatName');
    const emailInput = document.getElementById('chatEmail');
    const msgInput = document.getElementById('chatMessage');
    const fileInput = document.getElementById('chatFile');
    let chatToken = null;
    let ws = null;
    let savedProfile = { name: '', email: '' };

    const appendMessage = (item) => {
        const row = document.createElement('div');
        row.style.marginBottom = '8px';
        row.style.padding = '6px';
        row.style.borderRadius = '6px';
        row.style.background = item.sender_type === 'admin' ? '#e6f7ff' : '#f0f0f0';

        const top = document.createElement('div');
        top.style.fontSize = '11px';
        top.style.opacity = '0.7';
        top.innerText = (item.name || item.sender_type) + ' | ' + (item.created_at || '');
        row.appendChild(top);

        if (item.message) {
            const text = document.createElement('div');
            text.innerText = item.message;
            row.appendChild(text);
        }

        if (item.file_url) {
            const link = document.createElement('a');
            link.href = item.file_url;
            link.target = '_blank';
            link.innerText = item.file_name || 'Attachment';
            row.appendChild(link);
        }

        chatHistory.appendChild(row);
        chatHistory.scrollTop = chatHistory.scrollHeight;
    };

    const loadSession = () => {
        $.get('/chat/session', function (response) {
            chatToken = response.chat_token;
            if (!savedProfile.name) {
                savedProfile.name = response.name || '';
            }
            if (!savedProfile.email) {
                savedProfile.email = response.email || '';
            }
            if (savedProfile.name && !nameInput.value) nameInput.value = savedProfile.name;
            if (savedProfile.email && !emailInput.value) emailInput.value = savedProfile.email;
            localStorage.setItem('support_chat_profile', JSON.stringify(savedProfile));
            connectWs();
            loadMessages();
        });
    };

    const loadMessages = () => {
        $.get('/chat/messages', function (response) {
            chatHistory.innerHTML = '';
            (response.messages || []).forEach(appendMessage);
            const userMessages = (response.messages || []).filter((item) => item.sender_type === 'user');
            if (userMessages.length) {
                const latest = userMessages[userMessages.length - 1];
                if (latest.name && !nameInput.value) nameInput.value = latest.name;
                if (latest.email && !emailInput.value) emailInput.value = latest.email;
                if (latest.name || latest.email) {
                    savedProfile = {
                        name: latest.name || savedProfile.name,
                        email: latest.email || savedProfile.email
                    };
                    localStorage.setItem('support_chat_profile', JSON.stringify(savedProfile));
                }
            }
        });
    };

    const connectWs = () => {
        if (!chatToken) return;
        try {
            ws = new WebSocket(wsUrl);
            ws.onopen = function () {
                ws.send(JSON.stringify({ action: 'subscribe-chat', chat_token: chatToken }));
            };
            ws.onmessage = function (event) {
                const payload = JSON.parse(event.data || '{}');
                if (payload.event !== 'message.created') return;
                if (payload.chat_token !== chatToken) return;
                appendMessage(payload.message);
            };
        } catch (e) {
            console.log('Support WS not connected');
        }
    };

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const name = (nameInput.value || '').trim();
        const email = (emailInput.value || '').trim();
        const message = (msgInput.value || '').trim();

        if (!name || !email || (!message && !fileInput.files.length)) {
            alert('Name, Email and Message/File are required.');
            return;
        }
        savedProfile = { name: name, email: email };
        localStorage.setItem('support_chat_profile', JSON.stringify(savedProfile));

        const formData = new FormData(form);
        $.ajax({
            type: 'POST',
            url: '/help',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                if (!ws || ws.readyState !== WebSocket.OPEN) {
                    appendMessage(response.data);
                }
                msgInput.value = '';
                fileInput.value = '';
            },
            error: function (xhr) {
                const res = xhr.responseJSON || {};
                alert(res.message || 'Unable to send message.');
            }
        });
    });

    try {
        const cached = JSON.parse(localStorage.getItem('support_chat_profile') || '{}');
        if (cached.name) nameInput.value = cached.name;
        if (cached.email) emailInput.value = cached.email;
        savedProfile = { name: cached.name || '', email: cached.email || '' };
    } catch (e) {}

    loadSession();
})();
