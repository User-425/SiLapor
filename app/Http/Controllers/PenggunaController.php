<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PenggunaController extends Controller
{
    /**
     * Display a listing of users.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Pengguna::query();

        // Show trashed users if requested
        if ($request->has('show_deleted') && $request->show_deleted == 'true') {
            $query->onlyTrashed();
            
            if ($request->ajax()) {
                $deletedUsers = $query->orderBy('deleted_at', 'desc')->get();
                return response()->json([
                    'success' => true,
                    'users' => $deletedUsers
                ]);
            }
        }

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_lengkap', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('nama_pengguna', 'LIKE', "%{$searchTerm}%");
            });
        }

        $users = $query->orderBy('created_at', 'asc')->paginate(10);

        return view('pages.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.users.create');
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:pengguna',
            'nama_pengguna' => 'required|string|max:50|unique:pengguna',
            'kata_sandi' => 'required|string|min:8|confirmed',
            'peran' => 'required|string|in:admin,mahasiswa,dosen,tendik,sarpras,teknisi',
            'nomor_telepon' => 'nullable|string|max:15',
        ]);

        Pengguna::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'nama_pengguna' => $request->nama_pengguna,
            'kata_sandi' => Hash::make($request->kata_sandi),
            'peran' => $request->peran,
            'nomor_telepon' => $request->nomor_telepon,
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dibuat!');
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  \App\Models\Pengguna  $pengguna
     * @return \Illuminate\Http\Response
     */
    public function edit(Pengguna $pengguna)
    {
        return view('pages.users.edit', compact('pengguna'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pengguna  $pengguna
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pengguna $pengguna)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'email' => ['required', 'string', 'email', 'max:100', Rule::unique('pengguna')->ignore($pengguna->id_pengguna, 'id_pengguna')],
            'nama_pengguna' => ['required', 'string', 'max:50', Rule::unique('pengguna')->ignore($pengguna->id_pengguna, 'id_pengguna')],
            'kata_sandi' => 'nullable|string|min:8|confirmed',
            'peran' => 'required|string|in:admin,mahasiswa,dosen,tendik,sarpras,teknisi',
            'nomor_telepon' => 'nullable|string|max:15',
        ]);

        $userData = [
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'nama_pengguna' => $request->nama_pengguna,
            'peran' => $request->peran,
            'nomor_telepon' => $request->nomor_telepon,
        ];

        // Only update password if provided
        if (!empty($request->kata_sandi)) {
            $userData['kata_sandi'] = Hash::make($request->kata_sandi);
        }

        $pengguna->update($userData);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui!');
    }

    /**
     * Remove the specified user from storage (soft delete).
     *
     * @param  \App\Models\Pengguna  $pengguna
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pengguna $pengguna)
    {
        $pengguna->delete();
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus!');
    }

    /**
     * Restore the specified user from soft delete.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $pengguna = Pengguna::withTrashed()->findOrFail($id);
        $pengguna->restore();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengguna berhasil dipulihkan!'
            ]);
        }
        
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dipulihkan!');
    }

    /**
     * Permanently delete the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function forceDelete($id)
    {
        $pengguna = Pengguna::withTrashed()->findOrFail($id);
        
        // Delete profile photo if exists
        if ($pengguna->img_url && Storage::disk('public')->exists($pengguna->img_url)) {
            Storage::disk('public')->delete($pengguna->img_url);
        }
        
        $pengguna->forceDelete();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengguna berhasil dihapus permanen!'
            ]);
        }
        
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus permanen!');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('pages.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $authUser = Auth::user();
        $user = Pengguna::where('id_pengguna', $authUser->id_pengguna)->firstOrFail();

        // Validasi dasar
        $rules = [
            'nama_lengkap' => 'required|string|max:100',
            'email' => ['required', 'string', 'email', 'max:100', Rule::unique('pengguna')->ignore($user->id_pengguna, 'id_pengguna')],
            'nama_pengguna' => ['required', 'string', 'max:50', Rule::unique('pengguna')->ignore($user->id_pengguna, 'id_pengguna')],
            'kata_sandi' => 'nullable|string|min:8|confirmed',
            'nomor_telepon' => 'nullable|string|max:15',
            'img_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Jika ingin ganti password, wajib isi kata_sandi_lama dan harus benar
        if ($request->filled('kata_sandi')) {
            $rules['kata_sandi_lama'] = [
                'required',
                function ($attribute, $value, $fail) use ($user) {
                    if (!\Hash::check($value, $user->kata_sandi)) {
                        $fail('Kata sandi lama salah.');
                    }
                }
            ];
        }

        $validated = $request->validate($rules);

        $data = [
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'nama_pengguna' => $request->nama_pengguna,
            'nomor_telepon' => $request->nomor_telepon,
        ];

        if ($request->filled('kata_sandi')) {
            $data['kata_sandi'] = Hash::make($request->kata_sandi);
        }

        if ($request->hasFile('img_url')) {
            // Delete old photo if exists
            if ($user->img_url && Storage::disk('public')->exists($user->img_url)) {
                Storage::disk('public')->delete($user->img_url);
            }
            $data['img_url'] = $request->file('img_url')->store('profile_photos', 'public');
        }

        $user->update($data);

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui!');
    }
}
