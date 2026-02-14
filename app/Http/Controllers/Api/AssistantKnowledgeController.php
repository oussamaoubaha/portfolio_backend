<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AssistantKnowledge;
use Illuminate\Http\Request;

class AssistantKnowledgeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return AssistantKnowledge::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);

        $knowledge = AssistantKnowledge::create($validated);

        return response()->json($knowledge, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return AssistantKnowledge::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);

        $knowledge = AssistantKnowledge::findOrFail($id);
        $knowledge->update($validated);

        return response()->json($knowledge);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $knowledge = AssistantKnowledge::findOrFail($id);
        $knowledge->delete();

        return response()->json(null, 204);
    }
}
