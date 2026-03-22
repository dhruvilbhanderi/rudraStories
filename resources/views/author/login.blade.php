@extends('author.auth_layout')

@section('title', 'Author Login')

@section('content')
    <div class="auth-title">Sign in</div>
    <div class="auth-subtitle">Access your author dashboard and manage stories.</div>

    @if ($errors->any())
        <div class="alert alert-danger" style="background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.28); color: #fee2e2; border-radius: 14px;">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="/author/login" method="POST">
        @csrf
        <div class="form-group">
            <label>Email address</label>
            <input type="email" name="email" class="form-control" required value="{{ old('email') }}" autocomplete="email">
        </div>

        <div class="form-group field-wrap">
            <label>Password</label>
            <input type="password" name="password" id="password" class="form-control" required autocomplete="current-password">
            <i class="fa fa-eye toggle-password" id="togglePassword" title="Show/Hide"></i>
        </div>

        <button type="submit" class="btn btn-accent btn-block">Log in</button>

        <div class="help-links text-center mt-3" style="font-size: 13px;">
            <a href="/author/signup">Create an account</a>
            <span style="opacity:0.6;"> • </span>
            <a href="/">Back home</a>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        (function () {
            var toggle = document.getElementById('togglePassword');
            var password = document.getElementById('password');
            if (!toggle || !password) return;
            toggle.addEventListener('click', function () {
                var type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                toggle.classList.toggle('fa-eye-slash');
            });
        })();
    </script>
@endpush
