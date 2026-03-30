<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class adminUsers extends Controller
{
    //
    function show (){
        
       $us= DB::table('usersignupinfo')->select('S_No','UserName',
        'Email','UserMobile','status','uidenkk')->paginate(10);
            return view('admin.users')->with(['usrd'=>$us]);
        
    }

    public function updateStatus(Request $request, $sNo)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(['active', 'inactive'])],
        ]);

        $updated = DB::table('usersignupinfo')
            ->where('S_No', $sNo)
            ->update([
                'status' => $validated['status'],
                'updated_at' => now(),
            ]);

        if (! $updated) {
            return response()->json([
                'message' => 'User not found.',
            ], 404);
        }

        return response()->json([
            'ok' => true,
            's_no' => (int) $sNo,
            'status' => $validated['status'],
        ]);
    }
}
 
