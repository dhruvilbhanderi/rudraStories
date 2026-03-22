@extends('author.app_layout')

@section('title', 'My Stories')
@section('subtitle', 'Content')
@section('heading', 'My Stories')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div style="color: rgba(229,231,235,0.70); font-weight: 700;">Manage your published stories</div>
        <a href="{{ route('author.stories.create') }}" class="btn btn-accent">
            <i class="fa fa-plus"></i> New story
        </a>
    </div>

    <div class="ap-card">
        <div class="table-responsive">
            <table class="table mb-0 ap-table">
                <thead>
                    <tr>
                        <th style="width: 44%;">Story</th>
                        <th>Type</th>
                        <th class="text-right">Views</th>
                        <th class="text-right">Likes</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stories as $story)
                        <tr>
                            <td>
                                <div style="font-weight: 900;">{{ $story->story_heading }}</div>
                                <div style="color: rgba(229,231,235,0.65); font-size: 12px;">
                                    {{ \Illuminate\Support\Str::limit($story->story_desc, 90) }}
                                </div>
                            </td>
                            <td><span class="ap-badge-pill">{{ $story->story_type_name ?? '—' }}</span></td>
                            <td class="text-right">{{ (int) $story->view }}</td>
                            <td class="text-right">{{ (int) $story->stry_likes }}</td>
                            <td class="text-right">
                                <a href="{{ route('author.stories.edit', $story->story_id) }}" class="btn btn-sm btn-outline-light" style="border-radius: 12px;">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a href="/stories/{{ $story->story_id }}/{{ $story->story_identy }}" target="_blank" rel="noreferrer"
                                    class="btn btn-sm btn-outline-light" style="border-radius: 12px;">
                                    <i class="fa fa-external-link"></i>
                                </a>
                                <form action="{{ route('author.stories.delete', $story->story_id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this story?');">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-danger" style="border-radius: 12px;">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center" style="padding: 44px 16px; color: rgba(229,231,235,0.75);">
                                <div style="font-weight: 900; font-size: 16px;">No stories found</div>
                                <div style="margin-top: 6px;">Create your first story to get started.</div>
                                <div class="mt-3">
                                    <a href="{{ route('author.stories.create') }}" class="btn btn-accent">
                                        <i class="fa fa-pencil"></i> Write a story
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($stories->count() > 0)
        <div class="d-flex align-items-center justify-content-between mt-3">
            <div style="color: rgba(229,231,235,0.65); font-size: 12px;">
                Showing {{ $stories->count() }} on this page
            </div>
            <div class="btn-group" role="group">
                <a href="{{ $stories->previousPageUrl() ?? '#' }}"
                    class="btn btn-sm btn-outline-light {{ $stories->previousPageUrl() ? '' : 'disabled' }}"
                    style="border-radius: 12px;">
                    <i class="fa fa-chevron-left"></i> Prev
                </a>
                <a href="{{ $stories->nextPageUrl() ?? '#' }}"
                    class="btn btn-sm btn-outline-light {{ $stories->nextPageUrl() ? '' : 'disabled' }}"
                    style="border-radius: 12px;">
                    Next <i class="fa fa-chevron-right"></i>
                </a>
            </div>
        </div>
    @endif
@endsection

