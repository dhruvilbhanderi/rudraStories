@extends('author.auth_layout')

@section('title', 'Author Signup')

@section('content')
    <div class="auth-title">Create your author account</div>
    <div class="auth-subtitle">Start publishing stories on Rudra Stories.</div>

    @if ($errors->any())
        <div class="alert alert-danger" style="background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.28); color: #fee2e2; border-radius: 14px;">
            <ul class="mb-0 pl-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="/author/signup" method="POST">
        @csrf
        <div class="form-group">
            <label>Full name</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name') }}" autocomplete="name">
        </div>
        <div class="form-group">
            <label>Email address</label>
            <input type="email" name="email" class="form-control" required value="{{ old('email') }}" autocomplete="email">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required autocomplete="new-password">
            <small style="color: rgba(229,231,235,0.65);">Minimum 6 characters.</small>
        </div>

        <button type="submit" class="btn btn-accent btn-block">Sign up</button>

        <div class="help-links text-center mt-3" style="font-size: 13px;">
            <a href="/author/login">Already have an account? Log in</a>
        </div>
    </form>
@endsection
