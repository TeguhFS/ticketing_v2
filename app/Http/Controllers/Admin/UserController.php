<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount(['orders', 'tickets'])->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->paginate(15)->withQueryString();

        $stats = [
            'total'         => User::count(),
            'user'          => User::where('role', 'user')->count(),
            'admin'         => User::where('role', 'admin')->count(),
            'field_officer' => User::where('role', 'field_officer')->count(),
            'inactive'      => User::where('is_active', false)->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function show(User $user)
    {
        $user->loadCount(['orders', 'tickets']);
        $user->load([
            'orders'  => fn($q) => $q->latest()->take(5),
            'tickets' => fn($q) => $q->latest()->take(5),
            'fieldOfficer.event',
        ]);

        $totalSpent = $user->orders()
            ->where('status', 'paid')
            ->sum('total');

        return view('admin.users.show', compact('user', 'totalSpent'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:admin,user,field_officer',
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string',
            'gender'   => 'nullable|in:male,female',
            'birth_date'     => 'nullable|date',
            'id_card_number' => 'nullable|string|max:20',
            'avatar'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:1024',
            'is_active'      => 'boolean',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'role'           => $request->role,
            'phone'          => $request->phone,
            'address'        => $request->address,
            'gender'         => $request->gender,
            'birth_date'     => $request->birth_date,
            'id_card_number' => $request->id_card_number,
            'avatar'         => $avatarPath,
            'is_active'      => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role'     => 'required|in:admin,user,field_officer',
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string',
            'gender'   => 'nullable|in:male,female',
            'birth_date'     => 'nullable|date',
            'id_card_number' => 'nullable|string|max:20',
            'avatar'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:1024',
            'is_active'      => 'boolean',
        ]);

        $avatarPath = $user->avatar;
        if ($request->hasFile('avatar')) {
            if ($user->avatar) Storage::disk('public')->delete($user->avatar);
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $data = [
            'name'           => $request->name,
            'email'          => $request->email,
            'role'           => $request->role,
            'phone'          => $request->phone,
            'address'        => $request->address,
            'gender'         => $request->gender,
            'birth_date'     => $request->birth_date,
            'id_card_number' => $request->id_card_number,
            'avatar'         => $avatarPath,
            'is_active'      => $request->boolean('is_active'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri!');
        }

        if ($user->avatar) Storage::disk('public')->delete($user->avatar);
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Tidak dapat menonaktifkan akun sendiri!');
        }

        $user->update(['is_active' => !$user->is_active]);

        return back()->with('success', 'Status user berhasil diperbarui!');
    }
}
