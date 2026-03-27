<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AuthorStoryController extends Controller
{
    public function index()
    {
        $authorId = Auth::guard('author')->id();

        $stories = DB::table('all_stories as s')
            ->leftJoin('story_type as t', 't.sno', '=', 's.story_type')
            ->where('s.author_id', $authorId)
            ->orderByDesc('s.post_time')
            ->select([
                's.story_id',
                's.story_heading',
                's.story_desc',
                's.story_identy',
                's.images',
                's.stry_likes',
                's.view',
                's.post_time',
                't.Story_type as story_type_name',
            ])
            ->simplePaginate(10);

        return view('author.stories_index', compact('stories'));
    }

    public function create()
    {
        $types = DB::table('story_type')
            ->orderBy('Story_type')
            ->get(['sno', 'Story_type']);

        return view('author.stories_create', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'story_heading' => ['required', 'string', 'max:255'],
            'story_type' => ['required', 'integer'],
            'story_desc' => ['required', 'string'],
            'main_story' => ['required', 'string'],
            'cover_image' => ['required', 'file', 'mimes:png,jpeg,jpg', 'max:2048'],
        ]);

        if (!DB::table('story_type')->where('sno', $validated['story_type'])->exists()) {
            return back()->withInput()->with('error', 'Invalid story type selected.');
        }

        $author = Auth::guard('author')->user();

        $coverFilename = $this->storeCoverImage($request);
        if ($coverFilename === null) {
            return back()->withInput()->with('error', 'Cover image upload failed.');
        }

        $storyIdenty = $this->generateStoryIdenty($validated['story_heading']);

        DB::table('all_stories')->insert([
            'story_heading' => $validated['story_heading'],
            'author_id' => $author->id,
            'story_type' => $validated['story_type'],
            'story_desc' => $validated['story_desc'],
            'written_by' => $author->name,
            'main_story' => $validated['main_story'],
            'stry_likes' => 0,
            'images' => $coverFilename,
            'view' => 0,
            'story_identy' => $storyIdenty,
            'post_time' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('author.stories.index')->with('success', 'Story published successfully.');
    }

    public function edit($storyId)
    {
        $authorId = Auth::guard('author')->id();

        $story = DB::table('all_stories')
            ->where('story_id', (int) $storyId)
            ->where('author_id', $authorId)
            ->first();

        if (!$story) {
            return redirect()->route('author.stories.index')->with('error', 'Story not found.');
        }

        $types = DB::table('story_type')
            ->orderBy('Story_type')
            ->get(['sno', 'Story_type']);

        return view('author.stories_edit', compact('story', 'types'));
    }

    public function update(Request $request, $storyId)
    {
        $authorId = Auth::guard('author')->id();

        $story = DB::table('all_stories')
            ->where('story_id', (int) $storyId)
            ->where('author_id', $authorId)
            ->first();

        if (!$story) {
            return redirect()->route('author.stories.index')->with('error', 'Story not found.');
        }

        $validated = $request->validate([
            'story_heading' => ['required', 'string', 'max:255'],
            'story_type' => ['required', 'integer'],
            'story_desc' => ['required', 'string'],
            'main_story' => ['required', 'string'],
            'cover_image' => ['nullable', 'file', 'mimes:png,jpeg,jpg', 'max:2048'],
        ]);

        if (!DB::table('story_type')->where('sno', $validated['story_type'])->exists()) {
            return back()->withInput()->with('error', 'Invalid story type selected.');
        }

        $update = [
            'story_heading' => $validated['story_heading'],
            'story_type' => $validated['story_type'],
            'story_desc' => $validated['story_desc'],
            'main_story' => $validated['main_story'],
            'updated_at' => now(),
        ];

        if ($request->hasFile('cover_image')) {
            $coverFilename = $this->storeCoverImage($request);
            if ($coverFilename === null) {
                return back()->withInput()->with('error', 'Cover image upload failed.');
            }
            $update['images'] = $coverFilename;
        }

        DB::table('all_stories')
            ->where('story_id', (int) $storyId)
            ->where('author_id', $authorId)
            ->update($update);

        return redirect()->route('author.stories.index')->with('success', 'Story updated successfully.');
    }

    public function destroy($storyId)
    {
        $authorId = Auth::guard('author')->id();

        $deleted = DB::table('all_stories')
            ->where('story_id', (int) $storyId)
            ->where('author_id', $authorId)
            ->delete();

        if (!$deleted) {
            return redirect()->route('author.stories.index')->with('error', 'Story not found.');
        }

        return redirect()->route('author.stories.index')->with('success', 'Story deleted successfully.');
    }

    private function storeCoverImage(Request $request): ?string
    {
        if (!$request->hasFile('cover_image')) {
            return null;
        }

        $file = $request->file('cover_image');
        if (!$file->isValid()) {
            return null;
        }

        $imgbbKey = (string) (env('IMGBB_KEY') ?: '12970868fe9200f5331c2d9579d429ea');
        try {
            $response = Http::attach(
                'image',
                file_get_contents($file->getRealPath()),
                $file->getClientOriginalName()
            )->post('https://api.imgbb.com/1/upload', [
                'key' => $imgbbKey,
            ]);

            if ($response->successful()) {
                $url = $response->json('data.url');
                if (is_string($url) && $url !== '') {
                    return $url;
                }
            }
        } catch (\Throwable $e) {
            // fall back to local storage
        }

        $dest = public_path('storyImages');
        if (!File::exists($dest)) {
            File::makeDirectory($dest, 0755, true);
        }

        $original = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safe = Str::slug($original);
        if ($safe === '') {
            $safe = 'story';
        }

        $filename = $safe . '-' . time() . '.' . $file->getClientOriginalExtension();
        $file->move($dest, $filename);

        return $filename;
    }

    private function generateStoryIdenty(string $seed): string
    {
        // Keep compatible with existing app URLs: /stories/{id}/{hash}
        for ($i = 0; $i < 5; $i++) {
            $hash = strtoupper(substr(Crypt::encryptString($seed . '|' . Str::random(8)), 10, 40));
            if (!DB::table('all_stories')->where('story_identy', $hash)->exists()) {
                return $hash;
            }
        }

        return strtoupper(Str::uuid()->toString());
    }
}
