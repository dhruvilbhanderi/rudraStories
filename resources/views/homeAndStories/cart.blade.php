@php
    $css=['demonav','footer','prplc','books_store'];
    $nav=['commentcn','navbar'];
@endphp
<x-navbar :nav="$nav" :css="$css" desc="Rudra Stories Cart" key="Rudra Stories Cart" />

<div class="book-page">
    <div class="book-header">
        <h1>Your Cart</h1>
        <a class="cart-btn" href="/books">Continue Shopping</a>
    </div>

    @if (session('book_success'))
        <p class="msg success">{{ session('book_success') }}</p>
    @endif
    @if (session('book_error'))
        <p class="msg error">{{ session('book_error') }}</p>
    @endif
    @if ($errors->any())
        <p class="msg error">{{ $errors->first() }}</p>
    @endif

    @if ($cartItems->isEmpty())
        <p class="empty">Cart is empty.</p>
    @else
        <div class="cart-table-wrap">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Book</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cartItems as $item)
                        <tr>
                            <td>{{ $item->title }}</td>
                            <td>Rs. {{ number_format((float) $item->price, 2) }}</td>
                            <td>
                                <form action="/cart/update/{{ $item->cart_id }}" method="POST" class="inline-form">
                                    @csrf
                                    <input type="number" name="quantity" min="1" max="{{ $item->stock }}" value="{{ $item->quantity }}">
                                    <button type="submit">Update</button>
                                </form>
                            </td>
                            <td>Rs. {{ number_format((float) $item->price * (int) $item->quantity, 2) }}</td>
                            <td>
                                <form action="/cart/remove/{{ $item->cart_id }}" method="POST">
                                    @csrf
                                    <button type="submit" class="danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="checkout-box">
            <h2>Checkout</h2>
            <p class="grand">Subtotal: <strong>Rs. {{ number_format((float) $subtotal, 2) }}</strong></p>
            <form id="rzp-checkout-form" method="POST" class="checkout-form">
                @csrf
                <input type="text" name="full_name" placeholder="Full Name" required>
                <input type="text" name="phone" placeholder="Phone Number" required>
                <input type="text" name="address_line" placeholder="Address" required>
                <input type="text" name="city" placeholder="City" required>
                <input type="text" name="state" placeholder="State" required>
                <input type="text" name="pincode" placeholder="Pincode" required>
                <input type="hidden" name="payment_method" value="razorpay">
                <button type="submit" class="buy" id="rzp-pay-btn">Pay With Razorpay</button>
            </form>
            <!-- <p class="muted-note">Razorpay key set karva `.env` ma `RAZORPAY_KEY_ID` ane `RAZORPAY_KEY_SECRET` add karo.</p> -->
        </div>
    @endif
</div>

<x-footer />

@if (! $cartItems->isEmpty())
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        (function () {
            var form = document.getElementById('rzp-checkout-form');
            var payBtn = document.getElementById('rzp-pay-btn');
            if (!form || !payBtn) return;

            form.addEventListener('submit', function (event) {
                event.preventDefault();
                payBtn.disabled = true;
                payBtn.textContent = 'Creating order...';

                var payload = new FormData(form);
                fetch('/checkout/razorpay/order', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: payload
                }).then(function (response) {
                    return response.json().then(function (json) {
                        return { ok: response.ok, json: json };
                    });
                }).then(function (result) {
                    if (!result.ok) {
                        throw new Error(result.json.message || 'Unable to create payment.');
                    }

                    var options = {
                        key: result.json.key,
                        amount: result.json.amount,
                        currency: result.json.currency,
                        name: 'Rudra Stories',
                        description: 'Book Purchase',
                        order_id: result.json.razorpay_order_id,
                        handler: function (paymentResult) {
                            verifyPayment(result.json.internal_order_id, paymentResult);
                        },
                        prefill: {
                            name: result.json.customer_name || '',
                            contact: result.json.customer_phone || ''
                        },
                        theme: {
                            color: '#0f766e'
                        }
                    };

                    var rzp = new Razorpay(options);
                    rzp.on('payment.failed', function () {
                        payBtn.disabled = false;
                        payBtn.textContent = 'Pay With Razorpay';
                        alert('Payment failed. Please try again.');
                    });
                    rzp.open();
                }).catch(function (error) {
                    payBtn.disabled = false;
                    payBtn.textContent = 'Pay With Razorpay';
                    alert(error.message || 'Checkout failed.');
                });
            });

            function verifyPayment(internalOrderId, paymentResult) {
                payBtn.disabled = true;
                payBtn.textContent = 'Verifying payment...';

                var verifyPayload = new FormData();
                verifyPayload.append('internal_order_id', internalOrderId);
                verifyPayload.append('razorpay_payment_id', paymentResult.razorpay_payment_id);
                verifyPayload.append('razorpay_order_id', paymentResult.razorpay_order_id);
                verifyPayload.append('razorpay_signature', paymentResult.razorpay_signature);

                fetch('/checkout/razorpay/verify', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: verifyPayload
                }).then(function (response) {
                    return response.json().then(function (json) {
                        return { ok: response.ok, json: json };
                    });
                }).then(function (result) {
                    if (!result.ok) {
                        throw new Error(result.json.message || 'Payment verification failed.');
                    }
                    window.location.href = '/my-orders';
                }).catch(function (error) {
                    payBtn.disabled = false;
                    payBtn.textContent = 'Pay With Razorpay';
                    alert(error.message || 'Verification failed.');
                });
            }
        })();
    </script>
@endif
