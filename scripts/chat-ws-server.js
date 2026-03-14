const http = require('http');
const WebSocket = require('ws');

const wsPort = Number(process.env.CHAT_WS_PORT || 6001);
const httpPort = Number(process.env.CHAT_WS_HTTP_PORT || 6002);
const secret = process.env.CHAT_WS_SECRET || '';

const wsServer = new WebSocket.Server({ port: wsPort });
const clients = new Map();

function safeSend(client, payload) {
    if (client.readyState === WebSocket.OPEN) {
        client.send(JSON.stringify(payload));
    }
}

wsServer.on('connection', (socket) => {
    clients.set(socket, { role: 'guest', chatToken: null });

    socket.on('message', (raw) => {
        try {
            const data = JSON.parse(String(raw || '{}'));
            if (data.action === 'subscribe-chat') {
                clients.set(socket, { role: 'chat', chatToken: data.chat_token || null });
            }
            if (data.action === 'subscribe-admin') {
                clients.set(socket, { role: 'admin', chatToken: null });
            }
        } catch (e) {
            // Ignore malformed messages.
        }
    });

    socket.on('close', () => clients.delete(socket));
    socket.on('error', () => clients.delete(socket));
});

const relayServer = http.createServer((req, res) => {
    if (req.method !== 'POST' || req.url !== '/broadcast') {
        res.statusCode = 404;
        res.end('Not found');
        return;
    }

    if (!secret || req.headers['x-chat-secret'] !== secret) {
        res.statusCode = 401;
        res.end('Unauthorized');
        return;
    }

    let body = '';
    req.on('data', (chunk) => {
        body += chunk.toString();
    });
    req.on('end', () => {
        try {
            const payload = JSON.parse(body || '{}');
            clients.forEach((meta, client) => {
                if (meta.role === 'admin') {
                    safeSend(client, payload);
                    return;
                }
                if (meta.role === 'chat' && meta.chatToken && meta.chatToken === payload.chat_token) {
                    safeSend(client, payload);
                }
            });
            res.statusCode = 200;
            res.setHeader('Content-Type', 'application/json');
            res.end(JSON.stringify({ ok: true }));
        } catch (e) {
            res.statusCode = 400;
            res.end('Invalid JSON');
        }
    });
});

relayServer.listen(httpPort, () => {
    // eslint-disable-next-line no-console
    console.log(`Chat relay HTTP listening on :${httpPort}`);
});

// eslint-disable-next-line no-console
console.log(`Chat WS listening on :${wsPort}`);
