@php
    $css=['demonav','footer','prplc','books_store'];
    $nav=['commentcn','navbar'];
@endphp
<x-navbar :nav="$nav" :css="$css" desc="Rudra Stories Library" key="Rudra Stories Library" />

<div class="book-page">
    <div class="book-header">
        <h1>My Library</h1>
        <a class="cart-btn" href="/books">Browse Books</a>
    </div>

    @if (session('book_success'))
        <p class="msg success">{{ session('book_success') }}</p>
    @endif
    @if (session('book_error'))
        <p class="msg error">{{ session('book_error') }}</p>
    @endif

    @if ($libraryBooks->isEmpty())
        <p class="empty">Library is empty. Claim free or purchase books from store.</p>
    @else
        <div class="book-grid">
            @foreach ($libraryBooks as $book)
                <div class="book-card reveal">
                    <div class="book-cover">
                        @if ($book->cover_image)
                            @php
                                $coverImage = (string) $book->cover_image;
                                $coverSrc = \Illuminate\Support\Str::startsWith($coverImage, ['http://', 'https://'])
                                    ? preg_replace('/^http:\\/\\//i', 'https://', $coverImage)
                                    : asset('bookImages/' . $coverImage);
                            @endphp
                            <img src="{{ $coverSrc }}" alt="{{ $book->title }}">
                        @else
                            <img src="{{ asset('images/bookbg.jpg') }}" alt="{{ $book->title }}">
                        @endif
                    </div>
                    <div class="book-body">
                        <h2>{{ $book->title }}</h2>
                        <p class="author">By {{ $book->author }}</p>
                        <p class="chip-row">
                            <span class="chip">{{ strtoupper($book->source) }}</span>
                            <span class="chip">{{ strtoupper($book->book_type ?? 'digital') }}</span>
                        </p>
                        <a href="/books/read/{{ $book->id }}" class="action-link buy">Open PDF</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<x-footer />
