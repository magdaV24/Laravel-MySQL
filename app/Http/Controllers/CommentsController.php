<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Models\Product;
use App\Models\Reviews;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function store(Request $request, $productId, $reviewId)
    {
        try {
            $user = auth()->user();
            if (!$user) {
                return redirect()->back()->with("error", "User Not Found.");
            }

            // Checking if the product and the review do exist
            $product = Product::find($productId);
            $review = Reviews::find($reviewId);
            if (!$product || !$review) {
                return redirect()->back()->with("error", "Product or Review Not Found.");
            }

            $request->validate([
                "content" => ['nullable', 'string', 'max:500'],
            ]);

            Comments::create([
                'content' => $request->content,
                'user_id' => $user->id,
                'product_id' => $productId,
                'review_id' => $reviewId,
            ]);
            return redirect()->back()->with('success', 'Comment submitted successfully.');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }

    public function update(Request $request, Comments $comment)
    {
        if (!$comment) {
            return redirect()->back()->with("error", "Comment not found.");
        }
        if ($request['content'] !== null) {
            $comment->content = $request['content'];
        }
        $comment->save();
        return redirect()->back()->with('success', 'Comment edited successfully!');
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
