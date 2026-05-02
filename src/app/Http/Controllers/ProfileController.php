<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Purchase;

class ProfileController extends Controller
{
    // プロフィール編集ページ表示
    public function editProfile()
    {
        $user = Auth::user(); 
        return view('profile_edit', compact('user'));
    }

    // プロフィール更新
    public function updateProfile(ProfileRequest $request)
    {
        $user = Auth::user();
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatar', 'public');
            $user->avatar_path = $path;
        }
        $user->name = $request->name;
        $user->postal_code = $request->postal_code;
        $user->address = $request->address;
        $user->building_name = $request->building_name;
        $user->profile_completed = true;
        $user->save();
        return redirect('/?tab=mylist')->with('success', 'プロフィールを更新しました！');
    }

    // マイページ表示
    public function showProfile(Request $request)
    {
        $user = Auth::user();
        $tab = $request->query('page', 'sell');
        if ($tab === 'sell') {
            $sellItems = $user->sellingItems()
                ->with('images')
                ->latest()
                ->get();
            $buyItems = collect();
        }elseif ($tab === 'buy') {
            $buyItems = Purchase::where('buyer_id', $user->id)
                ->with('item.images')
                ->get();
            $sellItems = collect();
        }else {
            $tab = 'sell';
            $sellItems = Item::where('seller_id', $user->id)
                ->with('images')
                ->get();
            $buyItems = collect();
        }
        return view('profile', compact('user', 'tab', 'sellItems', 'buyItems'));
    }
}