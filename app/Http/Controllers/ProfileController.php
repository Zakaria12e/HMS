<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Actions\Fortify\UpdateUserPassword;

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

            $link = Storage::put('/photos', $request->file('profile_picture'));
            $imageUrl = asset('storage/' . $link);
            $request->user()->update(['img_path' => $imageUrl]);

            Storage::delete($previousPath);

            return response()->json(['message' => 'Profile picture uploaded successfully!']);
        }
    }




    public function changePassword(Request $request, UpdateUserPassword $updater)
    {
        $updater->update(
            auth()->user(),
            [
                'current_password' => $request->currentPassword,
                'password' => $request->password,
                'password_confirmation' => $request->passwordConfirmation,
            ]
        );

        return response()->json(['message' => 'Password changed successfully!']);
    }

}
