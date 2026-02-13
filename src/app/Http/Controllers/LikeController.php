<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;


class LikeController extends Controller
{
    public function toggle(Item $item)
{
    $user = auth()->user();

    $liked = $user->likedItems()->where('item_id', $item->id)->exists();

    if ($liked) {
        $user->likedItems()->detach($item->id);
    } else {
        $user->likedItems()->attach($item->id);
    }

    return response()->json([
        'liked' => !$liked,
        'count' => $item->likes()->count(),
    ]);
}

}
