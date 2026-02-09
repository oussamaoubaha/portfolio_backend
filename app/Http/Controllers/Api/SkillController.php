<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;
use App\Http\Requests\Api\SkillRequest;

class SkillController extends Controller
{
    public function index()
    {
        return Skill::all();
    }

    public function store(SkillRequest $request)
    {
        $skill = Skill::create($request->validated());
        return response()->json($skill, 201);
    }

    public function update(SkillRequest $request, Skill $skill)
    {
        $skill->update($request->validated());
        return response()->json($skill);
    }

    public function destroy(Skill $skill)
    {
        $skill->delete();
        return response()->noContent();
    }
}
