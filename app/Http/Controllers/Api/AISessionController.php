<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AISession;
use Illuminate\Http\Request;

class AISessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            AISession::with(['messages' => function($q) {
                $q->orderBy('created_at', 'asc');
            }])
            ->latest()
            ->get()
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(AISession::with('messages')->findOrFail($id));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $session = AISession::findOrFail($id);
        $session->delete();

        return response()->json(['message' => 'Session supprimée']);
    }
}
