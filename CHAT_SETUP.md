# Local WebSocket Chat Setup

## 1) Environment
Add these in `.env`:

```env
CHAT_WS_URL=ws://127.0.0.1:6001
CHAT_WS_HTTP_URL=http://127.0.0.1:6002
CHAT_WS_SECRET=change_this_local_secret
```

## 2) Database
Run migration:

```powershell
php artisan migrate
```

This creates `support_chat_messages`.

## 3) Start servers
Run Laravel:

```powershell
php artisan serve
```

Run WebSocket relay in another terminal:

```powershell
npm run chat:ws
```

## 4) Use chat
- User side: footer `Support Chat` widget
- Admin side: `Admin -> Messages`

Both support text + file message (up to 10 MB).
