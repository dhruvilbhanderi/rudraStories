<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\writeNewStory;
use tidy;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;

class wrtieNewStory extends Controller
{
    //
    function show(Request $rq){

      
        // echo $rq->stypm;
        $valid= $rq->validate([
                'stehad'=>['required ','string'],
                'stypm'=>['required','string'],
                'stdesck'=>['required','string'],
                'wrbynm'=>['required','string'],
                'mnstcom'=>['required','string'],
                'stpcim' => 'required|mimes:png,jpeg,jpg|max:500',
                
            ]);
            
          $img=  htmlspecialchars(trim($rq->file('stpcim')));
        $nw= new writeNewStory;
        $sthd= htmlspecialchars(trim($rq->stehad));
        $stypm= htmlspecialchars(trim($rq->stypm));
        $stdesck= htmlspecialchars(trim($rq->stdesck));
        $wrbynm= htmlspecialchars(trim($rq->wrbynm));
        $mnstcom= htmlspecialchars(trim($rq->mnstcom));

        if ($rq->file('stpcim')->isValid()) {
            $file = $rq->file('stpcim');

            // Prefer persistent storage via ImgBB (Render has ephemeral filesystem / multiple instances)
            $imgbbKey = (string) (env('IMGBB_KEY') ?: '12970868fe9200f5331c2d9579d429ea');
            $stryimg = null;
            try {
                $response = Http::attach(
                    'image',
                    file_get_contents($file->getRealPath()),
                    $file->getClientOriginalName()
                )->post('https://api.imgbb.com/1/upload', [
                    'key' => $imgbbKey,
                ]);

                if ($response->successful()) {
                    $stryimg = $response->json('data.url');
                }
            } catch (\Throwable $e) {
                $stryimg = null;
            }

            // Fallback to local public folder
            if ($stryimg === null) {
                $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $realimg = $fileName."-".time().".".$file->getClientOriginalExtension();
                $dest=public_path('/storyImages');
                if (!File::exists($dest)) {
                    File::makeDirectory($dest, 0755, true);
                }
                $file->move($dest,$realimg);
                $stryimg= $realimg;
            }
        }       

        $nw->story_heading=$sthd;
        $nw->story_type=$stypm;
        $nw->story_desc=$stdesck;
        $nw->written_by=$wrbynm;
        $nw->main_story=$mnstcom;
        $nw->stry_likes='0';
        $nw->images=$stryimg;
        $nw->view='0';
        $hash= substr(Crypt::encryptString($sthd),10,74);
        $nw->story_identy=strtoupper($hash);
        if ($nw->save()) {
            
            return  true;
        }else{
            
            return  false;
        }
    }

    
}
