<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();
        $user->loadCount(['orders', 'tickets']);

        $totalSpent = $user->orders()
            ->where('status', 'paid')
            ->sum('total');

        return view('profile.edit', compact('user', 'totalSpent'));
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // 1. Ambil data yang sudah lolos validasi request
        $validated = $request->validated();

        // 2. Buat array penampung data yang akan diupdate secara spesifik
        $updateData = [];

        // Cek input dari tab Profil Utama / Hidden inputs
        if ($request->has('name')) $updateData['name'] = $validated['name'];
        if ($request->has('email')) $updateData['email'] = $validated['email'];
        if ($request->has('phone')) $updateData['phone'] = $validated['phone'];
        if ($request->has('address')) $updateData['address'] = $validated['address'];
        if ($request->has('gender')) $updateData['gender'] = $validated['gender'];
        if ($request->has('birth_date')) $updateData['birth_date'] = $validated['birth_date'];

        // Cek input dari tab NIK
        if ($request->has('id_card_number')) {
            $updateData['id_card_number'] = $validated['id_card_number'];
        }

        // Handle upload foto KTP jika ada berkas baru
        if ($request->hasFile('id_card_image')) {
            if ($user->id_card_image) {
                Storage::disk('public')->delete($user->id_card_image);
            }
            $updateData['id_card_image'] = $request->file('id_card_image')->store('id_cards', 'public');
        }

        // 3. Gunakan fill() hanya untuk data yang terfilter di atas
        $user->fill($updateData);

        // 4. Reset verifikasi jika email diganti
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // 5. Simpan langsung ke database
        $user->save();

        return Redirect::route('user.profile.edit')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,webp|max:1024',
        ]);

        $user = $request->user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password'         => 'required|string|min:8|confirmed',
        ], [
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.confirmed'                => 'Konfirmasi password tidak cocok.',
            'password.min'                      => 'Password minimal 8 karakter.',
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success_password', 'Password berhasil diperbarui!');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
