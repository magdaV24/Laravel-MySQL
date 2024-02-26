<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Models\OrderedProducts;
use App\Models\Reviews;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewsController extends Controller
{
    public function store(Request $request, $productId)
    {
        $user = Auth::user();
        if ($user->exists == false) {
            return redirect()->back()->with("error", "User Not Found.");
        }
        $check = Reviews::where("user_id", $user->id)->where("product_id", $productId)->exists();
        if ($check) {
            return redirect()->back()->with("error", "You already reviewed this product.");
        }
        $request->validate([
            "content" => ['nullable', 'string', 'max:500'],
            'grade' => ['required', 'numeric'],
        ]);

        Reviews::create([
            'content' => $request->content,
            'user_id' => $user->id,
            'product_id' => $productId,
            'grade' => $request->grade,
        ]);
        return redirect()->back()->with('success', 'Review added successfully.');
    }

    public function getComments($reviewId)
    {
        $comments = Comments::where("review_id", $reviewId)->get();
        foreach ($comments as $comment) {
            $user = User::find($comment->user_id);
            if ($user->exists == false) {
                return redirect()->back()->with("error", "User not found");
            }
            $comment->avatar = $user->avatar;
            $comment->name = $user->name;
        }
        return $comments;
    }

    public function isVerified($reviewId)
    {
        $review = Reviews::find($reviewId);
        $verified = OrderedProducts::where("user_id", $review->user_id)->where("product_id", $review->product_id)->exists();
        return $verified;
    }

    public function update(Request $request, Reviews $review)
    {
        if (!$review) {
            return redirect()->back()->with("error", "Review not found.");
        }
        if ($request['content'] !== null) {
            $review->content = $request['content'];
        }
        if ($request['grade'] !== null) {
            $review->grade = $request['grade'];
        }
        $review->save();
        return redirect()->back()->with('success', 'Review edited successfully!');
    }

    public function delete($review)
    {
        try {
            $review = Reviews::findOrFail($review);
            $review->delete();
            return redirect()->back()->with('success', 'Review deleted successfully!');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Error while looking for you review: ' . $ex->getMessage());
        }
    }
}
