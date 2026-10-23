<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\ActivityLog;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $members = Member::with('user')
            ->when($search, function($query) use ($search) {
                return $query->where('nama', 'like', "%{$search}%")
                            ->orWhere('kode_member', 'like', "%{$search}%")
                            ->orWhere('telepon', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->get();  // GANTI dari paginate(10) ke get()

        return view('members.index', compact('members', 'search'));
    }

    public function create()
    {
        return view('members.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nis_nip' => 'nullable|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string',
            'telepon' => 'required|string|max:15',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        // 1. Buat akun login (users) dengan role anggota
        $user = User::create([
            'name' => $request->nama,
            'username' => explode('@', $request->email)[0] . rand(10, 99),
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'anggota',
            'has_seen_tour' => false,
        ]);

        // 2. Buat record anggota (members) ter-link ke user_id
        Member::create([
            'user_id' => $user->id,
            'kode_member' => 'MBR-' . Str::upper(Str::random(6)),
            'nama' => $request->nama,
            'nis_nip' => $request->nis_nip,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'email' => $request->email,
            'tanggal_daftar' => now(),
            'status' => 'aktif',
        ]);

        ActivityLog::record('create', "Mendaftarkan anggota baru: {$request->nama}");

        return redirect()->route('members.index')->with('success', 'Anggota & akun login berhasil dibuat.');
    }

    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nis_nip' => 'nullable|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string',
            'telepon' => 'required|string|max:15',
            'email' => 'required|email|max:255|unique:users,email,'.$member->user_id,
            'password' => 'nullable|string|min:6',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $member->update([
            'nama' => $request->nama,
            'nis_nip' => $request->nis_nip,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'email' => $request->email,
            'status' => $request->status,
        ]);

        if ($member->user) {
            $userData = [
                'name' => $request->nama,
                'email' => $request->email,
            ];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $member->user->update($userData);
        }

        return redirect()->route('members.index')->with('success', 'Data anggota diperbarui.');
    }

    public function destroy(Member $member)
    {
        if ($member->user) {
            $member->user->delete();
        }
        $member->delete();
        return redirect()->route('members.index')->with('success', 'Anggota & akun login dihapus.');
    }
}