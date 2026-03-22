<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthorProfileController extends Controller
{
    public function show()
    {
        $author = Auth::guard('author')->user();

        return view('author.profile', [
            'author' => $author,
        ]);
    }

    public function update(Request $request)
    {
        $author = Auth::guard('author')->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('authors', 'email')->ignore($author->id),
            ],
            'profile_pic' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'new_password' => ['nullable', 'string', 'min:6'],
        ]);

        $update = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if ($request->hasFile('profile_pic') && $request->file('profile_pic')->isValid()) {
            $dest = public_path('authorProfile');
            if (!File::exists($dest)) {
                File::makeDirectory($dest, 0755, true);
            }

            $file = $request->file('profile_pic');
            $baseName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safe = preg_replace('/[^a-zA-Z0-9_-]+/', '-', $baseName);
            $safe = trim($safe, '-');
            if ($safe === '') {
                $safe = 'author';
            }

            $filename = $safe . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($dest, $filename);

            $update['profile_pic'] = $filename;
        }

        if (!empty($validated['new_password'])) {
            $update['password'] = Hash::make($validated['new_password']);
        }

        $author->update($update);

        return redirect()->route('author.profile')->with('success', 'Profile updated.');
    }
}

