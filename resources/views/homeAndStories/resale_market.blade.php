@php
    $css=['demonav','footer','prplc','books_store'];
    $nav=['commentcn','navbar'];
@endphp
<x-navbar :nav="$nav" :css="$css" desc="Rudra Stories Resale Market" key="Rudra Stories Resale Market" />

<div class="book-page">
    <div class="book-header">
        <h1>Resale Market</h1>
        <a class="cart-btn" href="/my-orders">My Orders</a>
    </div>

    @if (session('book_success'))
        <p class="msg success">{{ session('book_success') }}</p>
    @endif
    @if (session('book_error'))
        <p class="msg error">{{ session('book_error') }}</p>
    @endif

    <div class="cart-table-wrap" style="margin-bottom:16px;">
        <h3 style="margin:8px 0 12px 0;">My Resale Listings</h3>
        @if (($myListings ?? collect())->isEmpty())
            <p class="empty" style="margin:0;">You have not listed any book for resale yet.</p>
        @else
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Book</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Buyer</th>
                        <th>Listed On</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($myListings as $mine)
                        <tr>
                            <td>{{ $mine->title }}</td>
                            <td>Rs. {{ number_format((float) $mine->price, 2) }}</td>
                            <td>{{ strtoupper($mine->status) }}</td>
                            <td>{{ $mine->buyer_identity ?: '-' }}</td>
                            <td>{{ date('d M Y h:i A', strtotime($mine->created_at)) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <h3 style="margin-bottom:10px;">Available To Buy</h3>
    @if ($listings->isEmpty())
        <p class="empty">No resale listings available right now.</p>
    @else
        <div class="book-grid">
            @foreach ($listings as $listing)
                <div class="book-card reveal">
                    <div class="book-cover">
                        @if ($listing->cover_image)
                            <img src="{{ asset('bookImages/' . $listing->cover_image) }}" alt="{{ $listing->title }}">
                        @else
                            <img src="{{ asset('images/bookbg.jpg') }}" alt="{{ $listing->title }}">
                        @endif
                    </div>
                    <div class="book-body">
                        <h2>{{ $listing->title }}</h2>
                        <p class="author">By {{ $listing->author }}</p>
                        <p class="chip-row">
                            <span class="chip">SECOND HAND</span>
                            <span class="chip">{{ strtoupper($listing->book_type ?? 'physical') }}</span>
                        </p>
                        <p class="desc">Seller: {{ $listing->seller_username }}</p>
                        <p class="price">Rs. {{ number_format((float) $listing->price, 2) }}</p>
                        <button
                            type="button"
                            class="buy resale-buy-btn"
                            data-listing-id="{{ $listing->id }}"
                            data-price="{{ (float) $listing->price }}">
                            Buy With Payment
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    (function () {
        const csrfToken = '{{ csrf_token() }}';
        const buyButtons = document.querySelectorAll('.resale-buy-btn');
        if (!buyButtons.length) return;

        buyButtons.forEach(function (button) {
            button.addEventListener('click', async function () {
                const listingId = button.getAttribute('data-listing-id');
                if (!listingId) return;

                button.disabled = true;
                const oldText = button.textContent;
                button.textContent = 'Creating payment...';

                try {
                    const orderResp = await fetch('/books/resale/razorpay/order/' + encodeURIComponent(listingId), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                    });

                    const orderJson = await orderResp.json();
                    if (!orderResp.ok) {
                        throw new Error(orderJson.message || 'Unable to create resale payment.');
                    }

                    const options = {
                        key: orderJson.key,
                        amount: orderJson.amount,
                        currency: orderJson.currency || 'INR',
                        name: 'Rudra Stories',
                        description: 'Resale Book Purchase',
                        order_id: orderJson.razorpay_order_id,
                        prefill: {
                            name: orderJson.customer_name || '',
                            contact: orderJson.customer_phone || '',
                        },
                        handler: async function (paymentResult) {
                            button.textContent = 'Verifying payment...';

                            const verifyPayload = new FormData();
                            verifyPayload.append('internal_order_id', orderJson.internal_order_id);
                            verifyPayload.append('listing_id', orderJson.listing_id);
                            verifyPayload.append('razorpay_payment_id', paymentResult.razorpay_payment_id);
                            verifyPayload.append('razorpay_order_id', paymentResult.razorpay_order_id);
                            verifyPayload.append('razorpay_signature', paymentResult.razorpay_signature);

                            const verifyResp = await fetch('/books/resale/razorpay/verify', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json',
                                },
                                body: verifyPayload,
                            });
                            const verifyJson = await verifyResp.json();
                            if (!verifyResp.ok) {
                                throw new Error(verifyJson.message || 'Payment verification failed.');
                            }

                            window.location.href = '/my-orders';
                        },
                    };

                    const rzp = new Razorpay(options);
                    rzp.on('payment.failed', function () {
                        alert('Payment failed or cancelled.');
                        button.disabled = false;
                        button.textContent = oldText;
                    });
                    rzp.open();
                } catch (error) {
                    alert(error.message || 'Resale payment failed.');
                    button.disabled = false;
                    button.textContent = oldText;
                }
            });
        });
    })();
</script>

<x-footer />
