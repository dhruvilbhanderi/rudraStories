@php
    $css=['demonav','footer','prplc','books_store'];
    $nav=['commentcn','navbar'];
@endphp
<x-navbar :nav="$nav" :css="$css" desc="Rudra Stories Orders" key="Rudra Stories Orders" />

<div class="book-page">
    <div class="book-header">
        <h1>My Orders</h1>
        <div class="header-actions">
            <a class="cart-btn" href="/books">Shop More</a>
            <a class="cart-btn alt" href="/books/resale">Resale Market</a>
        </div>
    </div>

    @if (session('book_success'))
        <p class="msg success">{{ session('book_success') }}</p>
    @endif
    @if (session('book_error'))
        <p class="msg error">{{ session('book_error') }}</p>
    @endif

    @if ($orders->isEmpty())
        <p class="empty">No orders placed yet.</p>
    @else
        <div class="cart-table-wrap">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Order No</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Items</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>Rs. {{ number_format((float) $order->total_amount, 2) }}</td>
                            <td>{{ strtoupper($order->payment_status) }} ({{ strtoupper($order->payment_method) }})</td>
                            <td>{{ strtoupper($order->order_status) }}</td>
                            <td>{{ date('d M Y h:i A', strtotime($order->created_at)) }}</td>
                            <td>
                                @php
                                    $items = $orderItems[$order->id] ?? collect();
                                @endphp
                                @if ($items->isEmpty())
                                    <small>No items.</small>
                                @else
                                    <div class="order-item-list">
                                        @foreach ($items as $item)
                                            <div class="order-item-card">
                                                <strong>{{ $item->book_title }}</strong>
                                                <small>Qty: {{ $item->quantity }} | Rs. {{ number_format((float) $item->line_total, 2) }}</small>
                                                <div class="row-actions">
                                                    <a href="/books/read/{{ $item->book_id }}" class="mini-btn">Read PDF</a>
                                                    @if ((int) $item->allow_resale === 1 && ($item->book_type ?? 'physical') !== 'digital')
                                                        <form action="/books/resale/list/{{ $item->id }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="mini-btn buy">Sell Original Copy</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<x-footer />
