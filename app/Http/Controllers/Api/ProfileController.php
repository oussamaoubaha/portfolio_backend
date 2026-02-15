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
        $data = $request->validated();

        if ($request->hasFile('hero_image')) {
            // Delete old image if exists
            if ($profile->hero_image && file_exists(public_path($profile->hero_image))) {
                @unlink(public_path($profile->hero_image));
            }

            $file = $request->file('hero_image');
            $filename = 'profile_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profiles'), $filename);
            $data['hero_image'] = '/uploads/profiles/' . $filename;
        }

        $profile->update($data);
        return response()->json($profile);
    }
}
