@extends('author.app_layout')

@section('title', 'Author Dashboard')
@section('subtitle', 'Overview')
@section('heading', 'Dashboard')

@section('content')
    <div class="row">
        <div class="col-lg-4 mb-3">
            <div class="ap-card p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div style="color: rgba(229,231,235,0.70); font-weight: 700; font-size: 12px; letter-spacing: 0.3px; text-transform: uppercase;">
                            Stories
                        </div>
                        <div style="font-size: 34px; font-weight: 900; margin-top: 6px;">
                            {{ (int) ($stats->stories_count ?? 0) }}
                        </div>
                    </div>
                    <div class="ap-badge-pill"><i class="fa fa-book"></i> Published</div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-3">
            <div class="ap-card p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div style="color: rgba(229,231,235,0.70); font-weight: 700; font-size: 12px; letter-spacing: 0.3px; text-transform: uppercase;">
                            Total Views
                        </div>
                        <div style="font-size: 34px; font-weight: 900; margin-top: 6px;">
                            {{ (int) ($stats->total_views ?? 0) }}
                        </div>
                    </div>
                    <div class="ap-badge-pill"><i class="fa fa-eye"></i> Readers</div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-3">
            <div class="ap-card p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div style="color: rgba(229,231,235,0.70); font-weight: 700; font-size: 12px; letter-spacing: 0.3px; text-transform: uppercase;">
                            Total Likes
                        </div>
                        <div style="font-size: 34px; font-weight: 900; margin-top: 6px;">
                            {{ (int) ($stats->total_likes ?? 0) }}
                        </div>
                    </div>
                    <div class="ap-badge-pill"><i class="fa fa-heart"></i> Love</div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex align-items-center justify-content-between mb-3 mt-2">
        <div style="font-weight: 900; font-size: 16px;">Recent stories</div>
        <a href="{{ route('author.stories.create') }}" class="btn btn-accent">
            <i class="fa fa-plus"></i> Write new story
        </a>
    </div>

    <div class="ap-card">
        <div class="p-3" style="border-bottom: 1px solid rgba(255,255,255,0.10);">
            <div style="color: rgba(229,231,235,0.70); font-weight: 700; font-size: 12px; letter-spacing: 0.3px; text-transform: uppercase;">
                Latest activity
            </div>
        </div>

        <div class="table-responsive">
            <table class="table mb-0 ap-table">
                <thead>
                    <tr>
                        <th>Story</th>
                        <th>Type</th>
                        <th class="text-right">Views</th>
                        <th class="text-right">Likes</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentStories as $story)
                        <tr>
                            <td style="font-weight: 800;">{{ $story->story_heading }}</td>
                            <td><span class="ap-badge-pill">{{ $story->story_type_name ?? '—' }}</span></td>
                            <td class="text-right">{{ (int) $story->view }}</td>
                            <td class="text-right">{{ (int) $story->stry_likes }}</td>
                            <td class="text-right">
                                <a href="{{ route('author.stories.edit', $story->story_id) }}" class="btn btn-sm btn-outline-light" style="border-radius: 12px;">
                                    <i class="fa fa-pencil"></i> Edit
                                </a>
                                <a href="/stories/{{ $story->story_id }}/{{ $story->story_identy }}" target="_blank" rel="noreferrer"
                                    class="btn btn-sm btn-outline-light" style="border-radius: 12px;">
                                    <i class="fa fa-external-link"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center" style="padding: 40px 16px; color: rgba(229,231,235,0.75);">
                                <div style="font-weight: 900; font-size: 16px;">No stories yet</div>
                                <div style="margin-top: 6px;">Publish your first story to see it here.</div>
                                <div class="mt-3">
                                    <a href="{{ route('author.stories.create') }}" class="btn btn-accent">
                                        <i class="fa fa-pencil"></i> Start writing
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
