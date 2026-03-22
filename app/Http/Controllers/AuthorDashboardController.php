<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthorDashboardController extends Controller
{
    public function index()
    {
        $authorId = Auth::guard('author')->id();

        $stats = DB::table('all_stories')
            ->where('author_id', $authorId)
            ->selectRaw('COUNT(*) as stories_count, COALESCE(SUM(view),0) as total_views, COALESCE(SUM(stry_likes),0) as total_likes')
            ->first();

        $recentStories = DB::table('all_stories as s')
            ->leftJoin('story_type as t', 't.sno', '=', 's.story_type')
            ->where('s.author_id', $authorId)
            ->orderByDesc('s.post_time')
            ->limit(5)
            ->get([
                's.story_id',
                's.story_heading',
                's.story_identy',
                's.post_time',
                's.view',
                's.stry_likes',
                't.Story_type as story_type_name',
            ]);

        return view('author.dashboard', [
            'stats' => $stats,
            'recentStories' => $recentStories,
        ]);
    }
}
