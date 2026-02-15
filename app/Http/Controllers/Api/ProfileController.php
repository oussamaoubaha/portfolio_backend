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
        try {
            $profile = Profile::firstOrCreate(['id' => 1]);
            $data = $request->validated();

            if ($request->hasFile('hero_image')) {
                // Ensure upload directory exists
                $uploadPath = public_path('uploads/profiles');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                // Delete old image if exists
                if ($profile->hero_image && file_exists(public_path($profile->hero_image))) {
                    @unlink(public_path($profile->hero_image));
                }

                $file = $request->file('hero_image');
                $filename = 'profile_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $filename);
                $data['hero_image'] = '/uploads/profiles/' . $filename;
            }

            $profile->update($data);
            return response()->json($profile);
        } catch (\Exception $e) {
            \Log::error('Profile update failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'error' => 'Profile update failed',
                'message' => $e->getMessage(),
                'details' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }
}
