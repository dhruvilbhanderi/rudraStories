@extends('author.app_layout')

@section('title', 'Write New Story')
@section('subtitle', 'Create')
@section('heading', 'Write New Story')

@section('content')
    <div class="ap-card p-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <div style="font-weight: 900; font-size: 16px;">Story details</div>
                <div style="color: rgba(229,231,235,0.65); font-size: 12px;">Fill in the fields and publish.</div>
            </div>
            <a href="{{ route('author.stories.index') }}" class="btn btn-sm btn-outline-light" style="border-radius: 12px;">
                <i class="fa fa-arrow-left"></i> Back
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" style="background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.28); color: #fee2e2; border-radius: 16px;">
                <ul class="mb-0 pl-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('author.stories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Story title</label>
                <input type="text" name="story_heading" class="form-control" value="{{ old('story_heading') }}" required>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Story type</label>
                    <select name="story_type" class="form-control" required>
                        <option value="" disabled {{ old('story_type') ? '' : 'selected' }}>Select a type</option>
                        @foreach ($types as $type)
                            <option value="{{ $type->sno }}" {{ (string) old('story_type') === (string) $type->sno ? 'selected' : '' }}>
                                {{ $type->Story_type }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label>Cover image</label>
                    <input type="file" name="cover_image" class="form-control" required>
                    <small style="color: rgba(229,231,235,0.65);">PNG/JPG up to 2MB.</small>
                </div>
            </div>

            <div class="form-group">
                <label>Short description</label>
                <textarea name="story_desc" class="form-control" rows="3" required>{{ old('story_desc') }}</textarea>
            </div>

            <div class="form-group">
                <label>Main story</label>
                <textarea name="main_story" class="form-control" rows="10" required>{{ old('main_story') }}</textarea>
            </div>

            <div class="d-flex align-items-center justify-content-between mt-4">
                <div style="color: rgba(229,231,235,0.65); font-size: 12px;">
                    Tip: Keep your first paragraph strong.
                </div>
                <button class="btn btn-accent" type="submit">
                    <i class="fa fa-upload"></i> Publish story
                </button>
            </div>
        </form>
    </div>
@endsection

