<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Http\Requests\CommentRequest;


class CommentController extends Controller
{
    public function storeComments(CommentRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        $item->comments()->create([
            'user_id' => auth()->id(),
            'body' => $request->body,
        ]);

        return back()->with('message', 'コメントを送信しました！');
    }
}
