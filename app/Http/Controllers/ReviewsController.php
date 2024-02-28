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
        try {
            $user = Auth::user();
            if ($user->exists == false) {
                return back()->with("error", "User Not Found.");
            }
            $check = Reviews::where("user_id", $user->id)->where("product_id", $productId)->exists();
            if ($check) {
                return back()->with("error", "You already reviewed this product.");
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
            return back()->with('success', 'Review added successfully.');
        } catch (\Exception $e) {
            return back()->with('error','Error while trying to add te review: '.$e->getMessage());
        }
    }

    public function fetchReviewComments($reviewId){
        try {
            return $this->getComments($reviewId);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    private function getComments($reviewId)
    {
        try {
            $comments = Comments::where("review_id", $reviewId)->get();
            foreach ($comments as $comment) {
                $user = User::find($comment->user_id);
                if ($user->exists == false) {
                    return back()->with("error", "User not found");
                }
                $comment->avatar = $user->avatar;
                $comment->name = $user->name;
            }
            return $comments;
        } catch (\Exception $e) {
            return back()->with("error", $e->getMessage());
        }
    }
public function checkIsVerified($reviewId)
{
    try {
        return $this->isVerified($reviewId);
    } catch (\Exception $e) {
        return back()->with("error", $e->getMessage());
    }
}
    private function isVerified($reviewId)
    {
        try {
            $review = Reviews::find($reviewId);
            $verified = OrderedProducts::where("user_id", $review->user_id)->where("product_id", $review->product_id)->exists();
            return $verified;
        } catch (\Exception $e) {
            return back()->with("error", $e->getMessage());
        }
    }

    public function update(Request $request, Reviews $review)
    {
        try {
            if (!$review) {
                return back()->with("error", "Review not found.");
            }
            $review->content = $request->input('content', $review->content);
            $review->grade = $request->input('grade', $review->grade);

            $review->save();

            return back()->with('success', 'Review edited successfully!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function delete($review)
    {
        try {
            $review = Reviews::findOrFail($review);
            $review->delete();
            return back()->with('success', 'Review deleted successfully!');
        } catch (\Exception $ex) {
            return back()->with('error', 'Error while looking for you review: ' . $ex->getMessage());
        }
    }
}
