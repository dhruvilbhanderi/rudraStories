<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;

class BookStoreController extends Controller
{
    public function books()
    {
        $books = DB::table('books_store')
            ->where('is_active', 1)
            ->orderBy('id', 'desc')
            ->get();

        $ownedBookIds = [];
        if (session()->has(['usnm', 'loginstat', 'usiden'])) {
            $ownedBookIds = DB::table('user_book_access')
                ->where('user_identity', session('usiden'))
                ->pluck('book_id')
                ->map(function ($id) {
                    return (int) $id;
                })
                ->all();
        }

        return view('homeAndStories.books')->with([
            'books' => $books,
            'cartCount' => $this->cartCount(),
            'ownedBookIds' => $ownedBookIds,
        ]);
    }

    public function addToCart(Request $request, $bookId)
    {
        if (! session()->has(['usnm', 'loginstat', 'usiden'])) {
            return redirect('/Log_In')->with('notmatch', 'Please login first.');
        }

        $qty = (int) $request->input('quantity', 1);
        if ($qty < 1) {
            $qty = 1;
        }

        $book = DB::table('books_store')->where('id', $bookId)->where('is_active', 1)->first();
        if (! $book) {
            return redirect()->back()->with('book_error', 'Book not found.');
        }

        if ((string) ($book->access_type ?? 'paid') === 'free') {
            return redirect('/books')->with('book_error', 'Free books can be claimed directly.');
        }

        if ((int) $book->stock < 1) {
            return redirect()->back()->with('book_error', 'Book is out of stock.');
        }

        $userIdentity = session('usiden');
        $existing = DB::table('cart_items')
            ->where('user_identity', $userIdentity)
            ->where('book_id', $bookId)
            ->first();

        if ($existing) {
            $newQty = min((int) $book->stock, ((int) $existing->quantity + $qty));
            DB::table('cart_items')->where('id', $existing->id)->update([
                'quantity' => $newQty,
                'updated_at' => now(),
            ]);
        } else {
            DB::table('cart_items')->insert([
                'user_identity' => $userIdentity,
                'book_id' => $bookId,
                'quantity' => min((int) $book->stock, $qty),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->back()->with('book_success', 'Book added to cart.');
    }

    public function buyNow(Request $request, $bookId)
    {
        if (! session()->has(['usnm', 'loginstat', 'usiden'])) {
            return redirect('/Log_In')->with('notmatch', 'Please login first.');
        }

        $book = DB::table('books_store')->where('id', $bookId)->where('is_active', 1)->first();
        if (! $book || (int) $book->stock < 1) {
            return redirect('/books')->with('book_error', 'Book is not available right now.');
        }

        if ((string) ($book->access_type ?? 'paid') === 'free') {
            return redirect('/books')->with('book_error', 'Free books can be claimed directly.');
        }

        $userIdentity = session('usiden');
        $existing = DB::table('cart_items')
            ->where('user_identity', $userIdentity)
            ->where('book_id', $bookId)
            ->first();

        if ($existing) {
            $newQty = min((int) $book->stock, ((int) $existing->quantity + 1));
            DB::table('cart_items')->where('id', $existing->id)->update([
                'quantity' => $newQty,
                'updated_at' => now(),
            ]);
        } else {
            DB::table('cart_items')->insert([
                'user_identity' => $userIdentity,
                'book_id' => $bookId,
                'quantity' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect('/cart');
    }

    public function cart()
    {
        if (! session()->has(['usnm', 'loginstat', 'usiden'])) {
            return redirect('/Log_In')->with('notmatch', 'Please login first.');
        }

        $userIdentity = session('usiden');
        $cartItems = DB::table('cart_items')
            ->join('books_store', 'books_store.id', '=', 'cart_items.book_id')
            ->where('cart_items.user_identity', $userIdentity)
            ->select(
                'cart_items.id as cart_id',
                'cart_items.quantity',
                'books_store.id as book_id',
                'books_store.title',
                'books_store.author',
                'books_store.price',
                'books_store.stock',
                'books_store.cover_image',
                'books_store.book_type',
                'books_store.access_type'
            )
            ->get();

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += ((float) $item->price * (int) $item->quantity);
        }

        return view('homeAndStories.cart')->with([
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
        ]);
    }

    public function updateCart(Request $request, $cartId)
    {
        if (! session()->has(['usnm', 'loginstat', 'usiden'])) {
            return redirect('/Log_In');
        }

        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $userIdentity = session('usiden');
        $cartItem = DB::table('cart_items')
            ->where('id', $cartId)
            ->where('user_identity', $userIdentity)
            ->first();

        if (! $cartItem) {
            return redirect('/cart')->with('book_error', 'Cart item not found.');
        }

        $book = DB::table('books_store')->where('id', $cartItem->book_id)->first();
        if (! $book) {
            return redirect('/cart')->with('book_error', 'Book not found.');
        }

        $qty = min((int) $book->stock, (int) $request->quantity);
        DB::table('cart_items')->where('id', $cartId)->update([
            'quantity' => $qty,
            'updated_at' => now(),
        ]);

        return redirect('/cart')->with('book_success', 'Cart updated.');
    }

    public function removeFromCart($cartId)
    {
        if (! session()->has(['usnm', 'loginstat', 'usiden'])) {
            return redirect('/Log_In');
        }

        DB::table('cart_items')
            ->where('id', $cartId)
            ->where('user_identity', session('usiden'))
            ->delete();

        return redirect('/cart')->with('book_success', 'Item removed from cart.');
    }

    public function createRazorpayOrder(Request $request)
    {
        if (! session()->has(['usnm', 'loginstat', 'usiden'])) {
            return response()->json([
                'message' => 'Please login first.',
            ], 401);
        }

        $request->validate([
            'full_name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:20'],
            'address_line' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:120'],
            'state' => ['required', 'string', 'max:120'],
            'pincode' => ['required', 'string', 'max:12'],
        ]);

        $userIdentity = session('usiden');
        $username = session('usnm');
        $cartItems = DB::table('cart_items')
            ->join('books_store', 'books_store.id', '=', 'cart_items.book_id')
            ->where('cart_items.user_identity', $userIdentity)
            ->select(
                'cart_items.id as cart_id',
                'cart_items.book_id',
                'cart_items.quantity',
                'books_store.title',
                'books_store.price',
                'books_store.stock',
                'books_store.book_type',
                'books_store.access_type',
                'books_store.pdf_file'
            )
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'message' => 'Your cart is empty.',
            ], 422);
        }

        $total = 0;
        foreach ($cartItems as $item) {
            if ((int) $item->stock < (int) $item->quantity) {
                return response()->json([
                    'message' => 'Stock changed for ' . $item->title . '. Please update cart.',
                ], 422);
            }

            if ((string) $item->access_type === 'free') {
                continue;
            }

            $total += ((float) $item->price * (int) $item->quantity);
        }

        if ($total <= 0) {
            return response()->json([
                'message' => 'Only free books are in cart. Claim them directly from Books page.',
            ], 422);
        }

        $razorpayKey = (string) config('services.razorpay.key_id');
        $razorpaySecret = (string) config('services.razorpay.key_secret');
        if ($razorpayKey === '' || $razorpaySecret === '') {
            return response()->json([
                'message' => 'Razorpay is not configured. Set RAZORPAY_KEY_ID and RAZORPAY_KEY_SECRET in .env',
            ], 500);
        }

        DB::beginTransaction();
        try {
            $orderNumber = 'ORD' . date('YmdHis') . rand(100, 999);

            $orderId = DB::table('book_orders')->insertGetId([
                'order_number' => $orderNumber,
                'user_identity' => $userIdentity,
                'username' => $username,
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'address_line' => $request->address_line,
                'city' => $request->city,
                'state' => $request->state,
                'pincode' => $request->pincode,
                'payment_method' => 'razorpay',
                'payment_status' => 'pending',
                'payment_reference' => null,
                'order_status' => 'processing',
                'subtotal_amount' => $total,
                'total_amount' => $total,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($cartItems as $item) {
                if ((string) $item->access_type === 'free') {
                    continue;
                }

                DB::table('book_order_items')->insert([
                    'order_id' => $orderId,
                    'book_id' => $item->book_id,
                    'book_title' => $item->title,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->price,
                    'line_total' => ((float) $item->price * (int) $item->quantity),
                    'book_type_snapshot' => $item->book_type ?? 'physical',
                    'access_type_snapshot' => $item->access_type ?? 'paid',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $razorpayOrder = Http::withBasicAuth($razorpayKey, $razorpaySecret)
                ->asForm()
                ->post('https://api.razorpay.com/v1/orders', [
                    'amount' => (int) round($total * 100),
                    'currency' => 'INR',
                    'receipt' => $orderNumber,
                    'notes[user_identity]' => (string) $userIdentity,
                ]);

            if (! $razorpayOrder->ok() || ! isset($razorpayOrder['id'])) {
                throw new \RuntimeException('Unable to create Razorpay order');
            }

            DB::table('book_orders')->where('id', $orderId)->update([
                'payment_reference' => (string) $razorpayOrder['id'],
                'updated_at' => now(),
            ]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Checkout failed. Please try again.',
            ], 500);
        }

        return response()->json([
            'message' => 'Razorpay order created.',
            'key' => $razorpayKey,
            'amount' => (int) round($total * 100),
            'currency' => 'INR',
            'razorpay_order_id' => (string) $razorpayOrder['id'],
            'internal_order_id' => (int) $orderId,
            'customer_name' => $request->full_name,
            'customer_phone' => $request->phone,
        ]);
    }

    public function verifyRazorpayPayment(Request $request)
    {
        if (! session()->has(['usnm', 'loginstat', 'usiden'])) {
            return response()->json([
                'message' => 'Please login first.',
            ], 401);
        }

        $request->validate([
            'internal_order_id' => ['required', 'integer'],
            'razorpay_payment_id' => ['required', 'string', 'max:100'],
            'razorpay_order_id' => ['required', 'string', 'max:100'],
            'razorpay_signature' => ['required', 'string', 'max:255'],
        ]);

        $order = DB::table('book_orders')
            ->where('id', (int) $request->internal_order_id)
            ->where('user_identity', session('usiden'))
            ->first();

        if (! $order) {
            return response()->json([
                'message' => 'Order not found.',
            ], 404);
        }

        $secret = (string) config('services.razorpay.key_secret');
        $payload = $request->razorpay_order_id . '|' . $request->razorpay_payment_id;
        $generatedSignature = hash_hmac('sha256', $payload, $secret);
        $signatureIsValid = hash_equals($generatedSignature, (string) $request->razorpay_signature);

        if (! $signatureIsValid) {
            DB::table('book_orders')->where('id', $order->id)->update([
                'payment_status' => 'failed',
                'updated_at' => now(),
            ]);

            return response()->json([
                'message' => 'Payment signature mismatch.',
            ], 422);
        }

        DB::beginTransaction();
        try {
            DB::table('book_orders')->where('id', $order->id)->update([
                'payment_status' => 'paid',
                'payment_reference' => $request->razorpay_payment_id,
                'updated_at' => now(),
            ]);

            $orderItems = DB::table('book_order_items')
                ->where('order_id', $order->id)
                ->get();

            foreach ($orderItems as $item) {
                $book = DB::table('books_store')->where('id', $item->book_id)->first();
                if ($book && ($book->book_type ?? 'physical') !== 'digital') {
                    DB::table('books_store')
                        ->where('id', $item->book_id)
                        ->update([
                            'stock' => DB::raw('stock - ' . (int) $item->quantity),
                            'updated_at' => now(),
                        ]);
                }

                if ($book && $book->pdf_file) {
                    DB::table('user_book_access')->updateOrInsert(
                        [
                            'user_identity' => session('usiden'),
                            'book_id' => $item->book_id,
                        ],
                        [
                            'username' => session('usnm'),
                            'source' => 'purchase',
                            'order_id' => $order->id,
                            'is_active' => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            }

            DB::table('cart_items')->where('user_identity', session('usiden'))->delete();
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Payment verification failed.',
            ], 500);
        }

        return response()->json([
            'message' => 'Payment successful and order confirmed.',
        ]);
    }

    public function myOrders()
    {
        if (! session()->has(['usnm', 'loginstat', 'usiden'])) {
            return redirect('/Log_In');
        }

        $orders = DB::table('book_orders')
            ->where('user_identity', session('usiden'))
            ->orderBy('id', 'desc')
            ->get();

        $orderIds = $orders->pluck('id')->all();
        $orderItems = collect();
        if (! empty($orderIds)) {
            $orderItems = DB::table('book_order_items')
                ->join('books_store', 'books_store.id', '=', 'book_order_items.book_id')
                ->whereIn('book_order_items.order_id', $orderIds)
                ->select(
                    'book_order_items.*',
                    'books_store.allow_resale',
                    'books_store.book_type'
                )
                ->get()
                ->groupBy('order_id');
        }

        return view('homeAndStories.my_orders')->with([
            'orders' => $orders,
            'orderItems' => $orderItems,
        ]);
    }

    public function claimFreeBook($bookId)
    {
        if (! session()->has(['usnm', 'loginstat', 'usiden'])) {
            return redirect('/Log_In')->with('notmatch', 'Please login first.');
        }

        $book = DB::table('books_store')
            ->where('id', $bookId)
            ->where('is_active', 1)
            ->first();

        if (! $book || (string) $book->access_type !== 'free') {
            return redirect('/books')->with('book_error', 'This book is not free.');
        }

        if (! $book->pdf_file) {
            return redirect('/books')->with('book_error', 'Free claim is available only for digital PDF books.');
        }

        DB::table('user_book_access')->updateOrInsert(
            [
                'user_identity' => session('usiden'),
                'book_id' => $book->id,
            ],
            [
                'username' => session('usnm'),
                'source' => 'free',
                'order_id' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        return redirect('/my-library')->with('book_success', 'Book added to your library.');
    }

    public function myLibrary()
    {
        if (! session()->has(['usnm', 'loginstat', 'usiden'])) {
            return redirect('/Log_In')->with('notmatch', 'Please login first.');
        }

        $libraryBooks = DB::table('user_book_access')
            ->join('books_store', 'books_store.id', '=', 'user_book_access.book_id')
            ->where('user_book_access.user_identity', session('usiden'))
            ->where('user_book_access.is_active', 1)
            ->select(
                'books_store.id',
                'books_store.title',
                'books_store.author',
                'books_store.description',
                'books_store.cover_image',
                'books_store.access_type',
                'books_store.book_type',
                'books_store.pdf_file',
                'user_book_access.source',
                'user_book_access.created_at as granted_at'
            )
            ->orderBy('user_book_access.id', 'desc')
            ->get();

        return view('homeAndStories.my_library')->with([
            'libraryBooks' => $libraryBooks,
        ]);
    }

    public function readBook($bookId)
    {
        if (! session()->has(['usnm', 'loginstat', 'usiden'])) {
            return redirect('/Log_In')->with('notmatch', 'Please login first.');
        }

        $book = DB::table('books_store')->where('id', $bookId)->where('is_active', 1)->first();
        if (! $book || ! $book->pdf_file) {
            return redirect('/my-library')->with('book_error', 'PDF is not available for this book.');
        }

        $hasAccess = DB::table('user_book_access')
            ->where('user_identity', session('usiden'))
            ->where('book_id', $book->id)
            ->where('is_active', 1)
            ->exists();

        if (! $hasAccess) {
            return redirect('/books')->with('book_error', 'Please purchase or claim this book first.');
        }

        $path = public_path('bookPdfs/' . $book->pdf_file);
        if (! file_exists($path)) {
            return redirect('/my-library')->with('book_error', 'PDF file not found on server.');
        }

        return Response::file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
        ]);
    }

    public function resaleMarket()
    {
        if (! session()->has(['usnm', 'loginstat', 'usiden'])) {
            return redirect('/Log_In')->with('notmatch', 'Please login first.');
        }

        $listings = DB::table('book_resale_listings')
            ->join('books_store', 'books_store.id', '=', 'book_resale_listings.book_id')
            ->where('book_resale_listings.status', 'active')
            ->where('book_resale_listings.seller_identity', '!=', session('usiden'))
            ->select(
                'book_resale_listings.*',
                'books_store.title',
                'books_store.author',
                'books_store.cover_image',
                'books_store.book_type'
            )
            ->orderBy('book_resale_listings.id', 'desc')
            ->get();

        $myListings = DB::table('book_resale_listings')
            ->join('books_store', 'books_store.id', '=', 'book_resale_listings.book_id')
            ->where('book_resale_listings.seller_identity', session('usiden'))
            ->select(
                'book_resale_listings.*',
                'books_store.title',
                'books_store.author',
                'books_store.cover_image',
                'books_store.book_type'
            )
            ->orderBy('book_resale_listings.id', 'desc')
            ->get();

        return view('homeAndStories.resale_market')->with([
            'listings' => $listings,
            'myListings' => $myListings,
        ]);
    }

    public function createResaleListing($orderItemId)
    {
        if (! session()->has(['usnm', 'loginstat', 'usiden'])) {
            return redirect('/Log_In')->with('notmatch', 'Please login first.');
        }

        $orderItem = DB::table('book_order_items')
            ->join('book_orders', 'book_orders.id', '=', 'book_order_items.order_id')
            ->join('books_store', 'books_store.id', '=', 'book_order_items.book_id')
            ->where('book_order_items.id', $orderItemId)
            ->where('book_orders.user_identity', session('usiden'))
            ->where('book_orders.payment_status', 'paid')
            ->select(
                'book_order_items.*',
                'book_orders.user_identity',
                'books_store.allow_resale',
                'books_store.book_type',
                'books_store.resale_price'
            )
            ->first();

        if (! $orderItem) {
            return redirect('/my-orders')->with('book_error', 'Order item not found.');
        }

        if ((int) $orderItem->allow_resale !== 1) {
            return redirect('/my-orders')->with('book_error', 'Resale is not allowed for this book.');
        }

        if ((string) $orderItem->book_type === 'digital') {
            return redirect('/my-orders')->with('book_error', 'Digital-only books cannot be resold.');
        }

        $existing = DB::table('book_resale_listings')
            ->where('seller_identity', session('usiden'))
            ->where('source_order_item_id', $orderItemId)
            ->where('status', 'active')
            ->exists();

        if ($existing) {
            return redirect('/my-orders')->with('book_error', 'This copy is already listed.');
        }

        $resalePrice = (float) ($orderItem->resale_price ?: ($orderItem->unit_price * 0.6));

        DB::table('book_resale_listings')->insert([
            'book_id' => $orderItem->book_id,
            'source_order_item_id' => $orderItemId,
            'seller_identity' => session('usiden'),
            'seller_username' => session('usnm'),
            'price' => $resalePrice,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/books/resale')->with('book_success', 'Resale listing created.');
    }

    public function buyResale($listingId)
    {
        return redirect('/books/resale')->with('book_error', 'Direct resale buy is disabled. Please pay via Razorpay checkout.');
    }

    public function createResaleRazorpayOrder($listingId)
    {
        if (! session()->has(['usnm', 'loginstat', 'usiden'])) {
            return response()->json([
                'message' => 'Please login first.',
            ], 401);
        }

        $listing = DB::table('book_resale_listings')
            ->where('id', $listingId)
            ->where('status', 'active')
            ->first();

        if (! $listing) {
            return response()->json([
                'message' => 'Listing not available.',
            ], 404);
        }

        if ((string) $listing->seller_identity === (string) session('usiden')) {
            return response()->json([
                'message' => 'You cannot buy your own listing.',
            ], 422);
        }

        $razorpayKey = (string) config('services.razorpay.key_id');
        $razorpaySecret = (string) config('services.razorpay.key_secret');
        if ($razorpayKey === '' || $razorpaySecret === '') {
            return response()->json([
                'message' => 'Razorpay is not configured. Set RAZORPAY_KEY_ID and RAZORPAY_KEY_SECRET in .env',
            ], 500);
        }

        DB::beginTransaction();
        try {
            $orderNumber = 'RSL' . date('YmdHis') . rand(100, 999);

            $orderId = DB::table('book_orders')->insertGetId([
                'order_number' => $orderNumber,
                'user_identity' => session('usiden'),
                'username' => session('usnm'),
                'full_name' => session('usnm'),
                'phone' => '-',
                'address_line' => 'Resale marketplace purchase',
                'city' => '-',
                'state' => '-',
                'pincode' => '-',
                'payment_method' => 'resale_razorpay',
                'payment_status' => 'pending',
                'payment_reference' => null,
                'order_status' => 'processing',
                'subtotal_amount' => $listing->price,
                'total_amount' => $listing->price,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $book = DB::table('books_store')->where('id', $listing->book_id)->first();

            DB::table('book_order_items')->insert([
                'order_id' => $orderId,
                'book_id' => $listing->book_id,
                'book_title' => $book ? $book->title : 'Book',
                'quantity' => 1,
                'unit_price' => $listing->price,
                'line_total' => $listing->price,
                'book_type_snapshot' => $book ? $book->book_type : 'physical',
                'access_type_snapshot' => $book ? $book->access_type : 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $razorpayOrder = Http::withBasicAuth($razorpayKey, $razorpaySecret)
                ->asForm()
                ->post('https://api.razorpay.com/v1/orders', [
                    'amount' => (int) round(((float) $listing->price) * 100),
                    'currency' => 'INR',
                    'receipt' => $orderNumber,
                    'notes[listng_id]' => (string) $listingId,
                    'notes[user_identity]' => (string) session('usiden'),
                ]);

            if (! $razorpayOrder->ok() || ! isset($razorpayOrder['id'])) {
                throw new \RuntimeException('Unable to create Razorpay order for resale');
            }

            DB::table('book_orders')->where('id', $orderId)->update([
                'payment_reference' => (string) $razorpayOrder['id'],
                'updated_at' => now(),
            ]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Unable to create resale payment order.',
            ], 500);
        }

        return response()->json([
            'message' => 'Resale Razorpay order created.',
            'key' => $razorpayKey,
            'amount' => (int) round(((float) $listing->price) * 100),
            'currency' => 'INR',
            'razorpay_order_id' => (string) $razorpayOrder['id'],
            'internal_order_id' => (int) $orderId,
            'listing_id' => (int) $listingId,
            'customer_name' => session('usnm'),
            'customer_phone' => '',
        ]);
    }

    public function verifyResaleRazorpayPayment(Request $request)
    {
        if (! session()->has(['usnm', 'loginstat', 'usiden'])) {
            return response()->json([
                'message' => 'Please login first.',
            ], 401);
        }

        $request->validate([
            'internal_order_id' => ['required', 'integer'],
            'listing_id' => ['required', 'integer'],
            'razorpay_payment_id' => ['required', 'string', 'max:100'],
            'razorpay_order_id' => ['required', 'string', 'max:100'],
            'razorpay_signature' => ['required', 'string', 'max:255'],
        ]);

        $order = DB::table('book_orders')
            ->where('id', (int) $request->internal_order_id)
            ->where('user_identity', session('usiden'))
            ->where('payment_method', 'resale_razorpay')
            ->first();

        if (! $order) {
            return response()->json([
                'message' => 'Resale order not found.',
            ], 404);
        }

        if ((string) $order->payment_status === 'paid') {
            return response()->json([
                'message' => 'Payment already verified.',
            ]);
        }

        $secret = (string) config('services.razorpay.key_secret');
        $payload = $request->razorpay_order_id . '|' . $request->razorpay_payment_id;
        $generatedSignature = hash_hmac('sha256', $payload, $secret);
        $signatureIsValid = hash_equals($generatedSignature, (string) $request->razorpay_signature);

        if (! $signatureIsValid) {
            DB::table('book_orders')->where('id', $order->id)->update([
                'payment_status' => 'failed',
                'updated_at' => now(),
            ]);

            return response()->json([
                'message' => 'Payment signature mismatch.',
            ], 422);
        }

        DB::beginTransaction();
        try {
            $listing = DB::table('book_resale_listings')
                ->where('id', (int) $request->listing_id)
                ->lockForUpdate()
                ->first();

            if (! $listing || $listing->status !== 'active') {
                throw new \RuntimeException('Listing already sold.');
            }

            if ((string) $listing->seller_identity === (string) session('usiden')) {
                throw new \RuntimeException('You cannot buy your own listing.');
            }

            $orderItem = DB::table('book_order_items')
                ->where('order_id', $order->id)
                ->first();

            if (! $orderItem || (int) $orderItem->book_id !== (int) $listing->book_id) {
                throw new \RuntimeException('Resale order item mismatch.');
            }

            DB::table('book_orders')->where('id', $order->id)->update([
                'payment_status' => 'paid',
                'payment_reference' => $request->razorpay_payment_id,
                'updated_at' => now(),
            ]);

            DB::table('book_resale_listings')->where('id', $listing->id)->update([
                'status' => 'sold',
                'buyer_identity' => session('usiden'),
                'sold_at' => now(),
                'updated_at' => now(),
            ]);

            $book = DB::table('books_store')->where('id', $listing->book_id)->first();
            if ($book && $book->pdf_file) {
                DB::table('user_book_access')->updateOrInsert(
                    [
                        'user_identity' => session('usiden'),
                        'book_id' => $book->id,
                    ],
                    [
                        'username' => session('usnm'),
                        'source' => 'resale',
                        'order_id' => $order->id,
                        'is_active' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            DB::table('book_orders')->where('id', $order->id)->update([
                'payment_status' => 'failed',
                'updated_at' => now(),
            ]);

            return response()->json([
                'message' => 'Resale payment verification failed: ' . $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'message' => 'Resale payment successful and order confirmed.',
        ]);
    }

    private function cartCount()
    {
        if (! session()->has(['usnm', 'loginstat', 'usiden'])) {
            return 0;
        }

        return DB::table('cart_items')->where('user_identity', session('usiden'))->sum('quantity');
    }
}
