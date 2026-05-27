<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user     = auth()->user();
        $officers = $user->fieldOfficer()->with('event')->get();
        return view('officer.profile', compact('user', 'officers'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'     => 'required|string|max:255',
            'phone'    => 'nullable|string|max:20',
            'avatar'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:1024',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $avatarPath = $user->avatar;
        if ($request->hasFile('avatar')) {
            if ($user->avatar) Storage::disk('public')->delete($user->avatar);
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $data = [
            'name'   => $request->name,
            'phone'  => $request->phone,
            'avatar' => $avatarPath,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
