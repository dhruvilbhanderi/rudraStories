@extends('author.app_layout')

@section('title', 'Profile')
@section('subtitle', 'Account')
@section('heading', 'Profile')

@section('content')
    <div class="ap-card p-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 12px;">
            <div>
                <div style="font-weight: 900; font-size: 16px;">Update your profile</div>
                <div style="color: rgba(229,231,235,0.65); font-size: 12px;">Keep your details up to date.</div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger mt-3" style="background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.28); color: #fee2e2; border-radius: 16px;">
                <ul class="mb-0 pl-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('author.profile.update') }}" method="POST" enctype="multipart/form-data" class="mt-3">
            @csrf

            <div class="d-flex align-items-center" style="gap: 14px; flex-wrap: wrap;">
                @if (!empty($author->profile_pic))
                    <img src="/authorProfile/{{ $author->profile_pic }}" alt="Avatar" style="width: 86px; height: 86px; border-radius: 22px; object-fit: cover; border: 1px solid rgba(255,255,255,0.14);">
                @else
                    <div style="width: 86px; height: 86px; border-radius: 22px; background: rgba(255,255,255,0.10); border: 1px solid rgba(255,255,255,0.14); display:grid; place-items:center; font-weight: 900; font-size: 26px;">
                        {{ strtoupper(substr($author->name ?? 'A', 0, 1)) }}
                    </div>
                @endif

                <div style="min-width: 260px;">
                    <div style="font-weight: 900; font-size: 16px;">{{ $author->name }}</div>
                    <div style="color: rgba(229,231,235,0.65); font-size: 12px;">{{ $author->email }}</div>
                    <div class="mt-2">
                        <label style="margin:0; color: rgba(229,231,235,0.65); font-size: 12px; font-weight: 700;">Profile photo</label>
                        <input type="file" name="profile_pic" class="form-control mt-1">
                    </div>
                </div>
            </div>

            <hr style="border-color: rgba(255,255,255,0.10); margin: 18px 0;">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $author->name) }}" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $author->email) }}" required>
                </div>
            </div>

            <div class="form-group">
                <label>New password (optional)</label>
                <input type="password" name="new_password" class="form-control" value="" autocomplete="new-password">
                <small style="color: rgba(229,231,235,0.65);">Leave blank to keep current password.</small>
            </div>

            <div class="d-flex align-items-center justify-content-end mt-4">
                <button class="btn btn-accent" type="submit">
                    <i class="fa fa-save"></i> Save changes
                </button>
            </div>
        </form>
    </div>
@endsection

