
$('#da2022').click(function (e) {
    e.preventDefault();

    $.ajax({
        type: "get",
        url: "/dash",
        beforeSend: function(){
            $('#ch10ngcon').append("<div class='loader'></div>");
        },
        complete: function(){
            $('#ch10ngcon').remove("<div class='loader'></div>");
        },
        success: function (response) {  
            // alert(response);
            $('#ch10ngcon').empty();
            $('#ch10ngcon').append(response);

        },
        error: function () {
            alert('devn');
        }
    });
}); 



$('#da2025').click(function (e) {
    e.preventDefault();

    $.ajax({
        type: "get",
        url: "/admin/books",
        beforeSend: function(){
            $('#ch10ngcon').append("<div class='loader'></div>");
        },
        complete: function(){
            $('#ch10ngcon').remove("<div class='loader'></div>");
        },
        success: function (response) {  
            // alert(response);
            $('#ch10ngcon').empty();
            $('#ch10ngcon').append(response);

        },
        error: function () {
            alert('devn');
        }
    });
});

$('#da_orders').click(function (e) {
    e.preventDefault();

    $.ajax({
        type: "get",
        url: "/admin/orders",
        beforeSend: function(){
            $('#ch10ngcon').append("<div class='loader'></div>");
        },
        complete: function(){
            $('#ch10ngcon').remove("<div class='loader'></div>");
        },
        success: function (response) {  
            $('#ch10ngcon').empty();
            $('#ch10ngcon').append(response);
        },
        error: function () {
            alert('Error loading orders');
        }
    });
});

$('#da2026').click(function (e) {
    e.preventDefault();

    $.ajax({
        type: "get",
        url: "/ussr",
        beforeSend: function(){
            $('#ch10ngcon').append("<div class='loader'></div>");
        },
        complete: function(){
            $('#ch10ngcon').remove("<div class='loader'></div>");
        },
        success: function (response) {  
            // alert(response);
            $('#ch10ngcon').empty();
            $('#ch10ngcon').append(response);

        },
        error: function () {
            alert('devn');
        }
    });
});
$('#da2027').click(function (e) {
    e.preventDefault();

    $.ajax({
        type: "get",
        url: "/msg",
       
        success: function (response) {  
            // alert(response);
            $('#ch10ngcon').empty();
            $('#ch10ngcon').append(response);
            initAdminChat();

        },
        error: function () {
            alert('devn');
        }
    });
});

$('#da2028').click(function (e) {
    e.preventDefault();

    $.ajax({
        type: "get",
        url: "/cmntget",
        beforeSend: function(){
            $('#ch10ngcon').append("<div class='loader'></div>");
        },
        complete: function(){
            $('#ch10ngcon').remove("<div class='loader'></div>");
        },
        success: function (response) {  
            // alert(response);
            $('#ch10ngcon').empty();
            $('#ch10ngcon').append(response);

        },
        error: function () {
            alert('devn');
        }
    });
});


    $('#da2030').click(function (e) {
    e.preventDefault();

    $.ajax({
        type: "get",
        url: "/thgt",
        beforeSend: function(){
            $('#ch10ngcon').append("<div class='loader'></div>");
        },
        complete: function(){
            $('#ch10ngcon').remove("<div class='loader'></div>");
        },
        success: function (response) {  
            // alert(response);
            $('#ch10ngcon').empty();
            $('#ch10ngcon').append(response);

            $('#hlpfr253').submit(function (e) {
                e.preventDefault();
                if($('#n12001').val().length==0){
                    $('#n12001').css('border','2px solid red');
                }
                else{
                    
                    $('#n12001').css('border-color','#1d3557');
                    $('#s55214').prop('disabled',true);
                    $('#s55214').val('Posting...');
                    $('#s55214').css('background-color','#8F9AA5');
                    const formData=new FormData(this);
                    $.ajax({
                        type: "post",
                        url: "/thgt",
                        data: formData,
                         cache: false,
                        contentType: false,
                        processData: false,
                        success: function (response) {  
                            if(response){
                                $('#s55214').prop('disabled',false);
                                $('#s55214').val('POST');
                                $('#s55214').css('background-color','#1d3557');
                                $('#erm').text('Posted Successfully !');
                                setTimeout(() => {
                                $('#erm').text(' ');
                                    
                                }, 2000);
                            }
                            
                
                        },
                        error: function () {
                            alert('devn');
                        }
                    });
                }
                
            })
        },
        error: function () {
            alert('devn');
        }
    });
}); 

function initAdminChat() {
    const root = document.getElementById('admin-chat-root');
    if (!root || root.dataset.initialized === '1') {
        return;
    }
    root.dataset.initialized = '1';

    const wsUrl = root.dataset.wsUrl || 'ws://127.0.0.1:6001';
    const sessionsContainer = document.getElementById('admin-chat-sessions');
    const header = document.getElementById('admin-chat-header');
    const body = document.getElementById('admin-chat-body');
    const form = document.getElementById('admin-chat-form');
    const textInput = document.getElementById('admin-chat-text');
    const fileInput = document.getElementById('admin-chat-file');
    let activeToken = null;
    let ws = null;

    const renderMessage = (message) => {
        const bubble = document.createElement('div');
        bubble.className = 'admin-chat-bubble ' + (message.sender_type === 'admin' ? 'admin' : 'user');

        const meta = document.createElement('div');
        meta.style.fontSize = '12px';
        meta.style.opacity = '0.8';
        meta.innerText = (message.name || message.sender_type) + ' | ' + (message.created_at || '');
        bubble.appendChild(meta);

        if (message.message) {
            const text = document.createElement('div');
            text.innerText = message.message;
            bubble.appendChild(text);
        }

        if (message.file_url) {
            const link = document.createElement('a');
            link.href = message.file_url;
            link.target = '_blank';
            link.className = 'admin-chat-file';
            link.innerText = message.file_name || 'Attachment';
            bubble.appendChild(link);
        }

        body.appendChild(bubble);
        body.scrollTop = body.scrollHeight;
    };

    const loadMessages = (chatToken) => {
        if (!chatToken) return;
        $.get('/admin/chat/sessions/' + encodeURIComponent(chatToken) + '/messages', function (response) {
            body.innerHTML = '';
            (response.messages || []).forEach(renderMessage);
        });
    };

    const connectWs = () => {
        try {
            ws = new WebSocket(wsUrl);
            ws.onopen = function () {
                ws.send(JSON.stringify({ action: 'subscribe-admin' }));
            };
            ws.onmessage = function (event) {
                const payload = JSON.parse(event.data || '{}');
                if (payload.event !== 'message.created') return;
                if (!activeToken || payload.chat_token !== activeToken) return;
                renderMessage(payload.message);
            };
        } catch (e) {
            console.log('WS not connected');
        }
    };

    sessionsContainer.addEventListener('click', function (event) {
        const item = event.target.closest('.admin-chat-session-item');
        if (!item) return;
        sessionsContainer.querySelectorAll('.admin-chat-session-item').forEach((node) => node.classList.remove('active'));
        item.classList.add('active');
        activeToken = item.dataset.chatToken;
        header.innerText = 'Conversation: ' + activeToken;
        loadMessages(activeToken);
    });

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        if (!activeToken) {
            alert('Select a conversation first.');
            return;
        }
        if (!textInput.value.trim() && !fileInput.files.length) {
            return;
        }
        const formData = new FormData();
        formData.append('_token', form.querySelector('input[name="_token"]').value);
        formData.append('message', textInput.value.trim());
        if (fileInput.files.length) {
            formData.append('chat_file', fileInput.files[0]);
        }

        $.ajax({
            type: 'POST',
            url: '/admin/chat/sessions/' + encodeURIComponent(activeToken) + '/messages',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function () {
                textInput.value = '';
                fileInput.value = '';
                if (!ws || ws.readyState !== WebSocket.OPEN) {
                    loadMessages(activeToken);
                }
            },
        });
    });

    connectWs();
}




