<div class="us10pr">
    <div class="cstpn">
        <div class="csnm">
            <h2>Orders Management</h2>
        </div>
    </div>

    @if (session('admin_book_success'))
        <p style="color:#16a34a; font-weight:600; padding:10px; background:#dcfce7; border-radius:8px; margin-bottom:15px;">{{ session('admin_book_success') }}</p>
    @endif
    @if ($errors->any())
        <p style="color:#dc2626; font-weight:600; padding:10px; background:#fee2e2; border-radius:8px; margin-bottom:15px;">{{ $errors->first() }}</p>
    @endif

    <div class="ulstlnnm" style="margin-top:24px;">
        <h3>All Orders</h3>
        <table>
            <thead>
                <tr>
                    <th>Order No</th>
                    <th>User</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Order Status</th>
                    <th>Update</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td style="font-weight:600;">{{ $order->order_number }}</td>
                        <td>{{ $order->username }}</td>
                        <td style="color:#0ea5e9; font-weight:600;">₹{{ number_format((float) $order->total_amount, 2) }}</td>
                        <td>
                            <span style="display:inline-block; padding:3px 8px; border-radius:12px; font-size:0.75rem; font-weight:600; 
                                {{ $order->payment_status === 'paid' ? 'background:#dcfce7; color:#16a34a;' : 'background:#fef3c7; color:#d97706;' }}">
                                {{ strtoupper($order->payment_status) }}
                            </span>
                            <br>
                            <small style="color:#64748b;">{{ strtoupper($order->payment_method) }}</small>
                        </td>
                        <td>
                            <span style="display:inline-block; padding:3px 8px; border-radius:12px; font-size:0.75rem; font-weight:600; 
                                {{ $order->order_status === 'delivered' ? 'background:#dcfce7; color:#16a34a;' : ($order->order_status === 'processing' ? 'background:#dbeafe; color:#2563eb;' : 'background:#fee2e2; color:#dc2626;') }}">
                                {{ strtoupper($order->order_status) }}
                            </span>
                        </td>
                        <td>
                            <form action="/admin/orders/update/{{ $order->id }}" method="POST" style="display:flex; gap:6px; flex-wrap:wrap; align-items:center;">
                                @csrf
                                <select name="order_status" required style="padding:0.4rem; font-size:0.85rem;">
                                    <option value="processing" {{ $order->order_status === 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="shipped" {{ $order->order_status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="delivered" {{ $order->order_status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                <select name="payment_status" required style="padding:0.4rem; font-size:0.85rem;">
                                    <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                                    <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                                </select>
                                <button type="submit" style="padding:0.4rem 0.8rem; font-size:0.85rem; background-color:#4f46e5; color:#fff; border-radius:6px; border:none; cursor:pointer;">Update</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:2rem; color:#64748b;">No orders yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
