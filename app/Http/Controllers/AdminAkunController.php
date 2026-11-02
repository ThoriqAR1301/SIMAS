<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminAkunController extends Controller
{
    private function roleModel()
    {
        return (new User)->role()->getRelated();
    }

    private function fk(): string
    {
        return (new User)->role()->getForeignKeyName();
    }

    public function index(Request $request)
    {
        $q = User::with('role')->orderBy('name');

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $q->where(fn ($w) => $w->where('name', 'like', "%$cari%")->orWhere('email', 'like', "%$cari%"));
        }
        if ($request->filled('role')) {
            $q->whereHas('role', fn ($r) => $r->where('name', $request->role));
        }

        $users = $q->paginate(15)->withQueryString();
        $roles = $this->roleModel()->all();

        return view('akun.index', compact('users', 'roles'));
    }

    public function create()
    {
        return view('akun.form', ['akun' => null, 'roles' => $this->roleModel()->all(), 'roleId' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|max:150|unique:users,email',
            'role_id'  => ['required', Rule::exists($this->roleModel()->getTable(), 'id')],
            'password' => 'required|string|min:8|max:100',
        ]);

        $user = new User;
        $user->forceFill([
            'name'              => $data['name'],
            'email'             => $data['email'],
            $this->fk()         => $data['role_id'],
            'password'          => Hash::make($data['password']),
            'email_verified_at' => now(),
        ])->save();

        return redirect()->route('admin.akun.index')->with('success', 'Akun ' . $user->name . ' berhasil dibuat.');
    }

    public function edit(User $user)
    {
        return view('akun.form', [
            'akun'   => $user,
            'roles'  => $this->roleModel()->all(),
            'roleId' => $user->{$this->fk()},
        ]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->id)],
            'role_id'  => ['required', Rule::exists($this->roleModel()->getTable(), 'id')],
            'password' => 'nullable|string|min:8|max:100',
        ]);

        $fill = [
            'name'      => $data['name'],
            'email'     => $data['email'],
            $this->fk() => $data['role_id'],
        ];
        if (!empty($data['password'])) {
            $fill['password'] = Hash::make($data['password']);
        }

        $user->forceFill($fill)->save();

        return redirect()->route('admin.akun.index')->with('success', 'Akun berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Kamu tidak bisa menghapus akunmu sendiri.');
        }

        try {
            $user->delete();
        } catch (QueryException $e) {
            return back()->with('error', 'Akun ini masih punya data terkait (pengajuan, dll), jadi tidak bisa dihapus.');
        }

        return back()->with('success', 'Akun berhasil dihapus.');
    }
}