<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use App\Support\Audit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::withCount('posts')->orderBy('name')->get();

        return view('admin.users.index', [
            'users' => $users,
            'roles' => Role::cases(),
        ]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'password' => $data['password'], // cast 'hashed'
        ]);

        Audit::log('user.create', 'user', $user->id);

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuário criado.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'user' => $user,
            'roles' => Role::cases(),
        ]);
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        // Salvaguarda: o admin não pode rebaixar o próprio papel (evita lockout).
        if ($user->is($request->user()) && $data['role'] !== Role::ADMIN->value) {
            return back()->with('error', 'Você não pode remover o seu próprio acesso de administrador.');
        }

        $user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
        ]);

        if (! empty($data['password'])) {
            $user->password = $data['password'];
        }

        $user->save();

        Audit::log('user.update', 'user', $user->id);

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuário atualizado.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'Você não pode excluir a própria conta.');
        }

        if ($user->posts()->exists()) {
            return back()->with('error', 'Este usuário possui notícias; reatribua ou exclua antes.');
        }

        $user->delete();

        Audit::log('user.delete', 'user', $user->id);

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuário excluído.');
    }
}
