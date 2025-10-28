<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;

class ProfileController extends Controller
{
    //プロフィール編集 ページ表示
    public function editProfile(){
        return view ('profile_edit');
    }

    //プロフィール編集 バリデーション確認
    public function updateProfile(ProfileRequest $request){
        $form = $request -> all();
        return redirect('profile_edit');

    }
}
