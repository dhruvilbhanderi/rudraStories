# Books + Razorpay + Library + Resale

## DB Changes (phpMyAdmin)
Run `BOOKSTORE_DB_UPDATE.sql` in phpMyAdmin with `rudra_stories` selected.

It creates:
- `books_store`
- `cart_items`
- `book_orders`
- `book_order_items`
- `user_book_access`
- `book_resale_listings`

## User URLs
- `/books` -> books listing
- `/cart` -> cart page
- `/my-orders` -> order history
- `/my-library` -> purchased/free PDF library
- `/books/read/{bookId}` -> protected inline PDF read
- `/books/resale` -> resale marketplace

## Admin URLs
- `/admin` -> admin login
- `/dashboard` -> admin panel
- admin books module is loaded from `/admin/books` (inside dashboard Books menu)

## Features Added
- Book listing with free/paid + digital/physical type
- Add to cart
- Razorpay checkout (`/checkout/razorpay/order`, `/checkout/razorpay/verify`)
- Cart quantity update/remove
- Free book claim into library
- PDF read only after free-claim/purchase
- Original book resale listing + buyer purchase flow
- Admin add/update/delete books with `pdf_file`, `access_type`, `book_type`, resale controls
- Admin order status and payment status update
