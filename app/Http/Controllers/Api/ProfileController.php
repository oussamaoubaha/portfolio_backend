<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Requests\Api\ProfileUpdateRequest;

class ProfileController extends Controller
{
    public function index()
    {
        return Profile::first();
    }

    public function update(ProfileUpdateRequest $request)
    {
        $profile = Profile::firstOrCreate(['id' => 1]);
        $profile->update($request->validated());
        return response()->json($profile);
    }
}
