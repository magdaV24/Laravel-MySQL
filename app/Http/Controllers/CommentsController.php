<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function store(Request $request, $productId, $reviewId)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->back()->with("error", "User Not Found.");
        }

        $request->validate([
            "content" => ['nullable', 'string', 'max:500'],
        ]);

        Comments::create([
            'content' => $request->content,
            'user_id' => $user->id,
            'product_id' => $productId,
            'review_id'=> $reviewId,
        ]);
        return redirect()->back()->with('success', 'Comment submitted successfully.');
    }

    public function update(Request $request, Comments $comment){
        if(! $comment){
            return redirect()->back()->with("error","Comment not found.");
        }
        if( $request['content'] !== null){
            $comment->content = $request['content'];
        }
        $comment->save();
        return redirect()->back()->with('success','Comment edited successfully!');
    }

    public function delete($comment)
    {
        try {
            $comment = Comments::findOrFail($comment);
            $comment->delete();
            return redirect()->back()->with('success', 'Comment deleted successfully!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Comment not found.');
        }
    }
}
