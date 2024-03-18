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
                return back()->with("error", "User Not Found.");
            }

            // Checking if the product and the review do exist
            $product = Product::find($productId);
            $review = Reviews::find($reviewId);
            if (!$product || !$review) {
                return back()->with("error", "Product or Review Not Found.");
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
            return back()->with('success', 'Comment submitted successfully.');
        } catch (\Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }
    }

    public function update(Request $request, Comments $comment)
    {
        try {
            if (!$comment) {
                return back()->with("error", "Comment not found.");
            }
            if ($request['content'] !== null) {
                $comment->content = $request['content'];
            }
            $comment->save();
            return back()->with('success', 'Comment edited successfully!');
        } catch (\Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }
    }

    public function delete($comment)
    {
        try {
            $comment = Comments::findOrFail($comment);
            $comment->delete();
            return back()->with('success', 'Comment deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Comment not found.');
        }
    }
}
