<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Requests\Api\ReviewRequest;

class ReviewController extends Controller
{
    public function index()
    {
        return Review::where('is_published', true)->orderBy('created_at', 'desc')->get();
    }
    
    public function indexAdmin()
    {
        return Review::orderBy('created_at', 'desc')->get();
    }

    public function store(ReviewRequest $request)
    {
        // Public reviews are inactive/unpublished by default
        $data = $request->validated();
        $data['is_active'] = false;
        $data['is_published'] = false;
        
        $review = Review::create($data);
        return response()->json($review, 201);
    }

    public function update(ReviewRequest $request, Review $review)
    {
        $review->update($request->validated());
        return response()->json($review);
    }

    public function publish(Review $review)
    {
        $review->update([
            'is_published' => !$review->is_published,
            'is_active' => !$review->is_published // Usually active when published
        ]);
        return response()->json($review);
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return response()->json(['message' => 'Review deleted successfully']);
    }
}
