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
        return Review::where('is_active', true)->get();
    }
    
    public function indexAdmin()
    {
        return Review::all();
    }

    public function store(ReviewRequest $request)
    {
        // Public reviews are inactive by default
        $data = $request->validated();
        if (!isset($data['is_active'])) {
            $data['is_active'] = false;
        }
        
        $review = Review::create($data);
        return response()->json($review, 201);
    }

    public function update(ReviewRequest $request, Review $review)
    {
        $review->update($request->validated());
        return response()->json($review);
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return response()->noContent();
    }
}
