<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('admin.auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // Não revela se o e-mail existe (sempre retorna a mesma mensagem).
        Password::sendResetLink($request->only('email'));

        return back()->with('status', 'Se o e-mail existir, enviamos um link para redefinir a senha.');
    }
}
