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

        .cmnt-delete-btn { background: none; border: none; cursor: pointer; font-size: 16px; padding: 0; }
        .cmnt-delete-btn:hover { filter: brightness(0.95); }
        .cmnt-table .cmnt-delete-btn,
        .cmnt-table .cmnt-delete-btn i.fa,
        .cmnt-table .cmnt-delete-btn i.fa.fa-trash { color: #ef4444 !important; }

        .cmnt-del-overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
            z-index: 9999; display: none; align-items: center; justify-content: center;
            opacity: 0; transition: opacity 0.3s ease;
        }
        .cmnt-del-modal {
            background: #ffffff; border-radius: 20px; padding: 32px; width: 90%; max-width: 420px;
            text-align: center; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transform: scale(0.95) translateY(10px); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .cmnt-del-overlay.show { display: flex; opacity: 1; }
        .cmnt-del-overlay.show .cmnt-del-modal { transform: scale(1) translateY(0); }
        .cdm-icon {
            width: 64px; height: 64px; background: #fee2e2; color: #ef4444; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; font-size: 28px; margin: 0 auto 20px;
        }
        .cdm-title { font-size: 22px; font-weight: 700; color: #0f172a; margin-bottom: 8px; font-family: 'Inter', sans-serif; }
        .cdm-desc { font-size: 15px; color: #64748b; margin-bottom: 26px; line-height: 1.5; font-family: 'Inter', sans-serif; }
        .cdm-actions { display: flex; gap: 12px; }
        .cdm-btn {
            flex: 1; padding: 12px; border-radius: 12px; font-size: 15px; font-weight: 600; cursor: pointer; border: none;
            transition: all 0.2s; font-family: 'Inter', sans-serif;
        }
        .cdm-cancel { background: #f1f5f9; color: #475569; }
        .cdm-cancel:hover { background: #e2e8f0; color: #0f172a; }
        .cdm-confirm { background: #ef4444; color: #ffffff; }
        .cdm-confirm:hover { background: #dc2626; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3); }
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
                            <th>Action</th>
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
                                <td>
                                    <form class="cmnt-delete-form" action="/admin/comments/delete/{{ $item->comment_type }}/{{ $item->id }}" method="POST">
                                        @csrf
                                        <button type="button" class="cmnt-delete-btn" aria-label="Delete comment">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="cmnt-empty">No comments found.</div>
            @endif
        </div>
    </div>

    <div class="cmnt-del-overlay" id="cmntDeleteOverlay" aria-hidden="true">
        <div class="cmnt-del-modal" role="dialog" aria-modal="true" aria-labelledby="cmntDeleteTitle">
            <div class="cdm-icon"><i class="fa fa-trash"></i></div>
            <div class="cdm-title" id="cmntDeleteTitle">Delete Comment</div>
            <div class="cdm-desc">Are you sure you want to delete this comment? This action can’t be undone.</div>
            <div class="cdm-actions">
                <button class="cdm-btn cdm-cancel" type="button" id="cmntDeleteCancel">Cancel</button>
                <button class="cdm-btn cdm-confirm" type="button" id="cmntDeleteConfirm">Yes, Delete</button>
            </div>
        </div>
    </div>
</div>
