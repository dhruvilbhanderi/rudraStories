<?php

namespace App\Http\Controllers;

use App\Models\ProfileModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProfilePicUploadController extends Controller
{
    //
    function show(Request $request){
       
        $request->validate([
            'usor4fg' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if (!session()->has('usnm')) {
            return response('Please login first.', 401);
        }

        $file = $request->file('usor4fg');
        if ($file && $file->isValid()) {
            $baseName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $baseName = preg_replace('/[^a-zA-Z0-9_-]+/', '-', (string) $baseName);
            $baseName = trim((string) $baseName, '-');
            if ($baseName === '') {
                $baseName = 'user';
            }

            $realimg = $baseName . "-" . time() . "." . $file->getClientOriginalExtension();
            $dest = public_path('/userProfile');
            if (!File::exists($dest)) {
                File::makeDirectory($dest, 0755, true);
            }

            $file->move($dest, $realimg);
            ProfileModel::where('UserName', session('usnm'))->update(['images' => $realimg]);

            return 'Successfully Uploaded';
        }

        return response('Upload failed.', 422);
       
    }
   
}
