<div class="us10pr">
    <div class="cstpn">
        <div class="csnm">
            <h2>Books Management</h2>
        </div>
    </div>

    @if (session('admin_book_success'))
        <p style="color:#166534; font-weight:700;">{{ session('admin_book_success') }}</p>
    @endif
    @if ($errors->any())
        <p style="color:#991b1b; font-weight:700;">{{ $errors->first() }}</p>
    @endif

    <div style="margin:10px 0 22px 0; padding:14px; border:1px solid #ddd; border-radius:8px;">
        <h3>Add New Book</h3>
        <form action="/admin/books" method="POST" enctype="multipart/form-data" style="display:grid; gap:8px; grid-template-columns:repeat(2, minmax(180px, 1fr));">
            @csrf
            <input type="text" name="title" placeholder="Book Title" required>
            <input type="text" name="author" placeholder="Author" required>
            <input type="number" step="0.01" name="price" placeholder="Price" required>
            <input type="number" name="stock" placeholder="Stock" required>
            <select name="access_type" required>
                <option value="paid">Paid</option>
                <option value="free">Free</option>
            </select>
            <select name="book_type" required>
                <option value="physical">Physical</option>
                <option value="digital">Digital</option>
                <option value="both">Both</option>
            </select>
            <textarea name="description" placeholder="Description" style="grid-column:1/-1;"></textarea>
            <input type="file" name="cover_image" accept=".jpg,.jpeg,.png">
            <input type="file" name="pdf_file" accept=".pdf">
            <label style="display:flex; align-items:center; gap:6px;">
                <input type="checkbox" name="allow_resale" checked> Allow resale
            </label>
            <input type="number" step="0.01" name="resale_price" placeholder="Resale Price (Optional)">
            <label style="display:flex; align-items:center; gap:6px;">
                <input type="checkbox" name="is_active" checked> Active
            </label>
            <button type="submit" style="grid-column:1/-1; max-width:180px;">Add Book</button>
        </form>
    </div>

    <div class="ulstlnnm">
        <h3>Books List</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($books as $book)
                    <tr>
                        <td>{{ $book->id }}</td>
                        <td>{{ $book->title }}</td>
                        <td>{{ $book->author }}</td>
                        <td>{{ number_format((float) $book->price, 2) }}</td>
                        <td>{{ $book->stock }}</td>
                        <td>{{ strtoupper($book->access_type ?? 'paid') }} / {{ strtoupper($book->book_type ?? 'physical') }}</td>
                        <td>{{ $book->is_active ? 'Active' : 'Inactive' }}</td>
                        <td>
                            <form action="/admin/books/update/{{ $book->id }}" method="POST" enctype="multipart/form-data" style="display:grid; gap:5px; margin-bottom:8px;">
                                @csrf
                                <input type="text" name="title" value="{{ $book->title }}" required>
                                <input type="text" name="author" value="{{ $book->author }}" required>
                                <input type="number" step="0.01" name="price" value="{{ $book->price }}" required>
                                <input type="number" name="stock" value="{{ $book->stock }}" required>
                                <select name="access_type" required>
                                    <option value="paid" {{ ($book->access_type ?? 'paid') === 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="free" {{ ($book->access_type ?? 'paid') === 'free' ? 'selected' : '' }}>Free</option>
                                </select>
                                <select name="book_type" required>
                                    <option value="physical" {{ ($book->book_type ?? 'physical') === 'physical' ? 'selected' : '' }}>Physical</option>
                                    <option value="digital" {{ ($book->book_type ?? 'physical') === 'digital' ? 'selected' : '' }}>Digital</option>
                                    <option value="both" {{ ($book->book_type ?? 'physical') === 'both' ? 'selected' : '' }}>Both</option>
                                </select>
                                <input type="file" name="cover_image" accept=".jpg,.jpeg,.png">
                                <input type="file" name="pdf_file" accept=".pdf">
                                <input type="number" step="0.01" name="resale_price" value="{{ $book->resale_price }}">
                                <textarea name="description">{{ $book->description }}</textarea>
                                <label style="display:flex; align-items:center; gap:6px;">
                                    <input type="checkbox" name="allow_resale" {{ (int) ($book->allow_resale ?? 1) === 1 ? 'checked' : '' }}> Allow resale
                                </label>
                                <label style="display:flex; align-items:center; gap:6px;">
                                    <input type="checkbox" name="is_active" {{ $book->is_active ? 'checked' : '' }}> Active
                                </label>
                                <button type="submit">Update</button>
                            </form>
                            <form action="/admin/books/delete/{{ $book->id }}" method="POST">
                                @csrf
                                <button type="submit" style="background:#dc2626; color:#fff;">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">No books available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="ulstlnnm" style="margin-top:24px;">
        <h3>Orders & Payment Management</h3>
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
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->username }}</td>
                        <td>{{ number_format((float) $order->total_amount, 2) }}</td>
                        <td>{{ strtoupper($order->payment_status) }} ({{ strtoupper($order->payment_method) }})</td>
                        <td>{{ strtoupper($order->order_status) }}</td>
                        <td>
                            <form action="/admin/orders/update/{{ $order->id }}" method="POST" style="display:flex; gap:6px; flex-wrap:wrap;">
                                @csrf
                                <select name="order_status" required>
                                    <option value="processing" {{ $order->order_status === 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="shipped" {{ $order->order_status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="delivered" {{ $order->order_status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                <select name="payment_status" required>
                                    <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                                    <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                                </select>
                                <button type="submit">Save</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No orders yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
