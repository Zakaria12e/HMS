<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index(Request $request){

        return $request->user()->only(['name','email','type','img_path']);

    }

    public function update(Request $request){

        $validatedData = $request->validate([
            'name' => 'required|string',
                'email' => ['required','email',Rule::unique('users')->ignore($request->user()->id)],
        ]);

        $request->user()->update($validatedData);

        return response()->json(['message' => 'Doctor updated successfully']);

    }

    public function uploadImage(Request $request)
    {
        if ($request->hasFile('profile_picture')) {
            $previousPath = $request->user()->getRawOriginal('img_path');

            // Store the image in the storage directory
            $link = Storage::put('/photos', $request->file('profile_picture'));

            // Update the user's img_path with the complete URL
            $imageUrl = asset('storage/' . $link);
            $request->user()->update(['img_path' => $imageUrl]);

            // Delete the previous image
            Storage::delete($previousPath);

            return response()->json(['message' => 'Profile picture uploaded successfully!']);
        }
    }

}
