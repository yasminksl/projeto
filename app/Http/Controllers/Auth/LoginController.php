<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LoginController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Auth/Login');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validação dos campos com mensagens personalizadas
        $credentials = $request->validate(
            [
                'email' => ['required', 'email'],
                'password' => 'required',
            ],
            [
                'email.required' => 'O campo de e-mail é obrigatório.',
                'email.email' => 'Por favor, insira um endereço de e-mail válido.',
                'password.required' => 'A senha é obrigatória.',
            ]
        );

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors([
                'email' => 'Este e-mail não está cadastrado.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended();
        }

        return back()->withErrors([
            'password' => 'A senha fornecida está incorreta.',
        ])->onlyInput('email');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        Auth::logout();

        return redirect('/login');
    }
}
