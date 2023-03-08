<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminConroller extends Controller
{
    public function createbook(Request $request){
        $request->validate([
            'author' => ['required'],
            'description' => ['requied', 'mix:20'],
            'price' => 'required',

        ]);

    }
}
