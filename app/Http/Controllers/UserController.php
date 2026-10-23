<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $users = User::when($search, function($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%")
                            ->orWhere('username', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('role', 'like', "%{$search}%");
            })
            ->latest()
            ->get();

        return view('admin.users.index', compact('users', 'search'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users,username',
        'email' => 'required|email|max:255|unique:users,email',
        'password' => 'required|string|min:6',
        'role' => 'required|in:admin,petugas', // HANYA ADMIN & PETUGAS
    ]);

    User::create([
        'name' => $request->name,
        'username' => $request->username,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
        'has_seen_tour' => false,
    ]);

    return redirect()->route('users.index')->with('success', 'Akun pengguna berhasil dibuat.');
}

public function update(Request $request, User $user)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users,username,'.$user->id,
        'email' => 'required|email|max:255|unique:users,email,'.$user->id,
        'role' => 'required|in:admin,petugas',
        'password' => 'nullable|string|min:6',
    ]);

    $data = $request->except('password');
    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    }

    $user->update($data);
    return redirect()->route('users.index')->with('success', 'Data pengguna diperbarui.');
}

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'Anda tidak bisa menghapus akun sendiri.']);
        }

        ActivityLog::record('user', "Menghapus akun: {$user->name}");
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Akun pengguna dihapus.');
    }
}
