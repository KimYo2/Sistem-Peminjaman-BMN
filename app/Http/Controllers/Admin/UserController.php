<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\LogsAudit;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    use LogsAudit;

    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('nip', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        $createData = [
            'nip'      => $data['nip'],
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => $data['password'],
            'role'     => $data['role'],
        ];

        if ($request->hasFile('foto')) {
            $createData['foto_path'] = $request->file('foto')
                ->store('users/avatar', 'public');
        }

        $user = User::create($createData);

        $this->logAudit('create', 'user', $user->id, [
            'nip' => $user->nip,
            'role' => $user->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validated();

        $updateData = [
            'nip' => $data['nip'],
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = $data['password'];
        }

        if ($request->hasFile('foto')) {
            if ($user->foto_path) {
                Storage::disk('public')->delete($user->foto_path);
            }
            $updateData['foto_path'] = $request->file('foto')->store('users/avatar', 'public');
        }

        if ($request->boolean('hapus_foto') && $user->foto_path) {
            Storage::disk('public')->delete($user->foto_path);
            $updateData['foto_path'] = null;
        }

        $user->update($updateData);

        $this->logAudit('update', 'user', $user->id, [
            'nip' => $user->nip,
            'role' => $user->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent admin from deactivating themselves
        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'Tidak dapat menonaktifkan akun sendiri.']);
        }

        $user->update(['is_active' => false]);

        $this->logAudit('deactivate', 'user', $user->id, [
            'nip' => $user->nip,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dinonaktifkan.');
    }
}
