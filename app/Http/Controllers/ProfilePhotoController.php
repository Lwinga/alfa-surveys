<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilePhotoController extends Controller
{
    private function deleteExistingPhoto(?string $path): void
    {
        if ($path === null) return;
        Storage::delete($path);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validateWithBag('updatePhoto', [
            'photo' => ['required', 'file', 'image', 'dimensions:width=256,height=256'],
        ]);

        $user = $request->user();
        $this->deleteExistingPhoto($user->photo_path);
        $user->photo_path = $request->file('photo')->store('profile-photos');
        $user->save();

        return back();
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();
        $this->deleteExistingPhoto($user->photo_path);
        $user->photo_path = null;
        $user->save();

        return back();
    }
}
