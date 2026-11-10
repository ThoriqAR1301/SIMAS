<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Services\LogAktivitasService;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminAkunController extends Controller
{
    public function __construct(
        private readonly LogAktivitasService $logService
    ) {}

    public function index(Request $request): View
    {
        $users = User::with('role')
            ->when($request->filled('cari'), function ($q) use ($request) {
                $q->search($request->cari);
            })
            ->when($request->filled('role'), function ($q) use ($request) {
                $q->role($request->role);
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $roles = Role::orderBy('display_name')->get();

        return view('akun.index', compact('users', 'roles'));
    }

    public function create(): View
    {
        return view('akun.form', [
            'akun' => null,
            'roles' => Role::orderBy('display_name')->get(),
            'roleId' => null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'nip' => ['nullable', 'string', 'max:255'],
            'jabatan' => ['nullable', 'string', 'max:255'],
            'role_id' => ['required', 'integer', 'exists:roles,id'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = DB::transaction(function () use ($validated) {
            $user = new User;
            $user->forceFill([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'nip' => $validated['nip'] ?? null,
                'jabatan' => $validated['jabatan'] ?? null,
                'role_id' => $validated['role_id'],
                'password' => Hash::make($validated['password']),
                'email_verified_at' => now(),
            ])->save();

            return $user;
        });

        $this->logService->catat('create_akun', $user, null, $user->toArray());

        return redirect()
            ->route('admin.akun.index')
            ->with('success', "Akun {$user->name} berhasil dibuat");
    }

    public function edit(User $user): View
    {
        return view('akun.form', [
            'akun' => $user,
            'roles' => Role::orderBy('display_name')->get(),
            'roleId' => $user->role_id,
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', "unique:users,email,{$user->id}"],
            'nip' => ['nullable', 'string', 'max:255'],
            'jabatan' => ['nullable', 'string', 'max:255'],
            'role_id' => ['required', 'integer', 'exists:roles,id'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $before = $user->toArray();

        DB::transaction(function () use ($validated, $user) {
            $fill = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'nip' => $validated['nip'] ?? null,
                'jabatan' => $validated['jabatan'] ?? null,
                'role_id' => $validated['role_id'],
            ];

            if (! empty($validated['password'])) {
                $fill['password'] = Hash::make($validated['password']);
            }

            $user->forceFill($fill)->save();
        });

        $this->logService->catat('update_akun', $user, $before, $user->fresh()->toArray());

        return redirect()
            ->route('admin.akun.index')
            ->with('success', 'Akun berhasil diperbarui');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Kamu tidak bisa menghapus akunmu sendiri');
        }

        try {
            DB::transaction(function () use ($user) {
                $this->logService->catat('delete_akun', $user, $user->toArray(), null);
                $user->delete();
            });
        } catch (QueryException) {
            return back()->with('error', 'Akun ini masih punya data terkait (pengajuan, dll), jadi tidak bisa dihapus');
        }

        return back()->with('success', 'Akun berhasil dihapus');
    }
}
