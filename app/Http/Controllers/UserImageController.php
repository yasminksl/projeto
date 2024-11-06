<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Http\Request;

class UserImageController extends Controller
{
    public function __invoke(Request $request, User $user)
    {
        $request->validate([
            'profile_photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_photo' => 'nullable|boolean',
        ]);

        if ($request->has('remove_photo') && $request->boolean('remove_photo')) {
            $defaultImagePath = 'default.png';

            if ($user->profile_photo_path && Storage::exists($user->profile_photo_path)) {
                Storage::delete($user->profile_photo_path);
            }

            $user->update(['profile_photo_path' => $defaultImagePath]);
        }

        if ($request->hasFile('profile_photo_path')) {
            $file = $request->file('profile_photo_path');
            $filename = time() . '-' . $file->getClientOriginalName();
            $file->storeAs('public/images/profiles', $filename);

            if ($user->profile_photo_path && Storage::exists($user->profile_photo_path)) {
                Storage::delete($user->profile_photo_path);
            }

            $user->update(['profile_photo_path' => $filename]);
        }
    }
}
