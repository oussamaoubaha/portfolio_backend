<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AssistantSetting;
use Illuminate\Http\Request;

class AssistantSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(AssistantSetting::all()->pluck('value', 'key'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $key)
    {
        $request->validate([
            'value' => 'required|string',
        ]);

        $setting = AssistantSetting::updateOrCreate(
            ['key' => $key],
            ['value' => $request->value]
        );

        return response()->json([
            'message' => "Réglage '{$key}' mis à jour avec succès.",
            'setting' => $setting
        ]);
    }
}
