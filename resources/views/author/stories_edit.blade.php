@extends('author.app_layout')

@section('title', 'Edit Story')
@section('subtitle', 'Update')
@section('heading', 'Edit Story')

@section('content')
    <div class="ap-card p-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <div style="font-weight: 900; font-size: 16px;">Update your story</div>
                <div style="color: rgba(229,231,235,0.65); font-size: 12px;">
                    Story ID: <span class="ap-badge-pill">{{ $story->story_identy }}</span>
                </div>
            </div>
            <div class="d-flex align-items-center" style="gap: 8px;">
                <a href="/stories/{{ $story->story_id }}/{{ $story->story_identy }}" target="_blank" rel="noreferrer"
                    class="btn btn-sm btn-outline-light" style="border-radius: 12px;">
                    <i class="fa fa-external-link"></i> View
                </a>
                <a href="{{ route('author.stories.index') }}" class="btn btn-sm btn-outline-light" style="border-radius: 12px;">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
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

        <form action="{{ route('author.stories.update', $story->story_id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Story title</label>
                <input type="text" name="story_heading" class="form-control" value="{{ old('story_heading', $story->story_heading) }}" required>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Story type</label>
                    <select name="story_type" class="form-control" required>
                        @foreach ($types as $type)
                            <option value="{{ $type->sno }}"
                                {{ (string) old('story_type', $story->story_type) === (string) $type->sno ? 'selected' : '' }}>
                                {{ $type->Story_type }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label>Replace cover image (optional)</label>
                    <input type="file" name="cover_image" class="form-control">
                    <small style="color: rgba(229,231,235,0.65);">PNG/JPG up to 2MB.</small>
                </div>
            </div>

            @if (!empty($story->images))
                <div class="form-group">
                    <label>Current cover</label>
                    <div style="max-width: 360px;">
                        <img src="/storyImages/{{ $story->images }}" alt="Cover" style="width:100%; border-radius: 16px; border: 1px solid rgba(255,255,255,0.12);">
                    </div>
                </div>
            @endif

            <div class="form-group">
                <label>Short description</label>
                <textarea name="story_desc" class="form-control" rows="3" required>{{ old('story_desc', $story->story_desc) }}</textarea>
            </div>

            <div class="form-group">
                <label>Main story</label>
                <textarea name="main_story" class="form-control" rows="10" required>{{ old('main_story', $story->main_story) }}</textarea>
            </div>

            <div class="d-flex align-items-center justify-content-between mt-4">
                <button type="button" class="btn btn-outline-danger" style="border-radius: 14px;"
                    onclick="if(confirm('Delete this story?')) { document.getElementById('author-delete-story-form').submit(); }">
                    <i class="fa fa-trash"></i> Delete
                </button>

                <button class="btn btn-accent" type="submit">
                    <i class="fa fa-save"></i> Save changes
                </button>
            </div>
        </form>

        <form id="author-delete-story-form" action="{{ route('author.stories.delete', $story->story_id) }}" method="POST" style="display:none;">
            @csrf
        </form>
    </div>
@endsection
