@php
    $css=['demonav','footer','prplc','books_store'];
    $nav=['commentcn','navbar'];
@endphp
<x-navbar :nav="$nav" :css="$css" desc="Rudra Stories Books" key="Rudra Stories Books" />

<div class="book-page">
    <div class="book-header">
        <h1>Books Store</h1>
        <p>Read free books, buy premium books, and access PDFs from your library.</p>
        @if (session()->has(['usnm', 'loginstat']))
            <div class="header-actions">
                <a class="cart-btn" href="/cart">Cart</a>
                <a class="cart-btn alt" href="/my-library">My Library</a>
            </div>
        @endif
    </div>

    @if (session('book_success'))
        <p class="msg success">{{ session('book_success') }}</p>
    @endif
    @if (session('book_error'))
        <p class="msg error">{{ session('book_error') }}</p>
    @endif

    <div class="book-grid">
        @forelse ($books as $book)
            <div class="book-card reveal">
                <div class="book-cover">
                    @if ($book->cover_image)
                        <img src="{{ asset('bookImages/' . $book->cover_image) }}" alt="{{ $book->title }}">
                    @else
                        <img src="{{ asset('images/bookbg.jpg') }}" alt="{{ $book->title }}">
                    @endif
                </div>
                <div class="book-body">
                    <h2>{{ $book->title }}</h2>
                    <p class="author">By {{ $book->author }}</p>
                    <p class="desc">{{ \Illuminate\Support\Str::limit($book->description, 120) }}</p>
                    <p class="chip-row">
                        <span class="chip {{ $book->access_type === 'free' ? 'free' : 'paid' }}">
                            {{ strtoupper($book->access_type ?? 'paid') }}
                        </span>
                        <span class="chip">{{ strtoupper($book->book_type ?? 'physical') }}</span>
                    </p>
                    <p class="price">
                        {{ ($book->access_type ?? 'paid') === 'free' ? 'FREE' : ('Rs. ' . number_format((float) $book->price, 2)) }}
                    </p>
                    <p class="stock {{ (int) $book->stock < 1 ? 'out' : '' }}">
                        {{ (int) $book->stock < 1 ? 'Out of stock' : ('In stock: ' . $book->stock) }}
                    </p>

                    <div class="actions">
                        @if (($book->access_type ?? 'paid') === 'free')
                            @if (in_array((int) $book->id, $ownedBookIds ?? []))
                                <a href="/books/read/{{ $book->id }}" class="action-link buy">Read PDF</a>
                            @else
                                <form action="/books/free/{{ $book->id }}/claim" method="POST">
                                    @csrf
                                    <button type="submit" class="buy">Get Free Book</button>
                                </form>
                            @endif
                        @else
                            <form action="/books/cart/add/{{ $book->id }}" method="POST">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" {{ (int) $book->stock < 1 ? 'disabled' : '' }}>Add To Cart</button>
                            </form>
                            @if (in_array((int) $book->id, $ownedBookIds ?? []))
                                <a href="/books/read/{{ $book->id }}" class="action-link buy">Read PDF</a>
                            @else
                                <form action="/books/buy/{{ $book->id }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="buy" {{ (int) $book->stock < 1 ? 'disabled' : '' }}>Buy Now</button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <p class="empty">No books available right now.</p>
        @endforelse
    </div>
</div>

<x-footer />
