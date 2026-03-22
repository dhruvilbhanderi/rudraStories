<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class AdminCommentsController extends Controller
{
    public function index()
    {
        $storyComments = DB::table('comment_section as c')
            ->leftJoin('all_stories as s', 's.story_identy', '=', 'c.stry_iden')
            ->select(
                'c.id',
                'c.comment_by',
                'c.comment',
                'c.created_at',
                'c.stry_iden as target_identity',
                DB::raw("'story' as comment_type"),
                's.story_heading as target_title'
            )
            ->get();

        $partComments = DB::table('stry_part_comments as c')
            ->leftJoin('story_parts as p', 'p.story_identy', '=', 'c.part_stry_identy')
            ->select(
                'c.id',
                'c.comment_by',
                'c.comment',
                'c.created_at',
                'c.part_stry_identy as target_identity',
                DB::raw("'story_part' as comment_type"),
                'p.story_heading as target_title'
            )
            ->get();

        $comments = $storyComments
            ->concat($partComments)
            ->sortByDesc(function ($item) {
                return $item->created_at ?? '1970-01-01 00:00:00';
            })
            ->values();

        return view('admin.comments')->with([
            'comments' => $comments,
            'storyCommentCount' => $storyComments->count(),
            'partCommentCount' => $partComments->count(),
        ]);
    }

    public function destroy($type, $id)
    {
        if ($type === 'story') {
            DB::table('comment_section')->where('id', $id)->delete();
        } elseif ($type === 'story_part') {
            DB::table('stry_part_comments')->where('id', $id)->delete();
        }

        return redirect()->back()->with('success', 'Comment deleted successfully.');
    }
}

