<div class="ulstlnnm">
    <style>
        .cmnt-wrap { background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 14px; }
        .cmnt-summary { display: flex; gap: 10px; margin-bottom: 12px; }
        .cmnt-pill { background: #edf6ff; color: #1d3557; border-radius: 999px; padding: 6px 12px; font-weight: 600; }
        .cmnt-table-wrap { overflow-x: auto; }
        .cmnt-table { width: 100%; border-collapse: collapse; }
        .cmnt-table th, .cmnt-table td { border-bottom: 1px solid #eee; padding: 8px; text-align: left; vertical-align: top; }
        .cmnt-type-story { color: #1d3557; font-weight: 700; }
        .cmnt-type-part { color: #ef476f; font-weight: 700; }
        .cmnt-empty { padding: 20px; text-align: center; color: #666; }
    </style>

    <div class="cmnt-wrap">
        <div class="cmnt-summary">
            <div class="cmnt-pill">Story Comments: {{ $storyCommentCount ?? 0 }}</div>
            <div class="cmnt-pill">Part Comments: {{ $partCommentCount ?? 0 }}</div>
            <div class="cmnt-pill">Total: {{ isset($comments) ? $comments->count() : 0 }}</div>
        </div>

        <div class="cmnt-table-wrap">
            @if(isset($comments) && $comments->count())
                <table class="cmnt-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Type</th>
                            <th>Story/Part</th>
                            <th>Comment</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($comments as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->comment_by }}</td>
                                <td>
                                    @if ($item->comment_type === 'story')
                                        <span class="cmnt-type-story">Story</span>
                                    @else
                                        <span class="cmnt-type-part">Story Part</span>
                                    @endif
                                </td>
                                <td>{{ $item->target_title ?? $item->target_identity }}</td>
                                <td>{{ $item->comment }}</td>
                                <td>{{ $item->created_at ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="cmnt-empty">No comments found.</div>
            @endif
        </div>
    </div>
</div>
