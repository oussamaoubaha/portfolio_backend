<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Http\Request;
use App\Http\Requests\Api\ExperienceRequest;

class ExperienceController extends Controller
{
    public function index()
    {
        return Experience::all();
    }

    public function store(ExperienceRequest $request)
    {
        $experience = Experience::create($request->validated());
        return response()->json($experience, 201);
    }

    public function update(ExperienceRequest $request, Experience $experience)
    {
        $experience->update($request->validated());
        return response()->json($experience);
    }

    public function destroy(Experience $experience)
    {
        $experience->delete();
        return response()->noContent();
    }
}
