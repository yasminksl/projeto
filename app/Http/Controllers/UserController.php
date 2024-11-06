<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        return Inertia::render('Users/Index', [
            'users' => UserResource::collection(User::query()
                ->when(FacadesRequest::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->paginate(10)
                ->withQueryString()),
            'filters' => FacadesRequest::only(['search']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Users/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => 'required|min:7',
            'profile_photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'O nome é obrigatório.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'O e-mail deve ser um endereço válido.',
            'email.unique' => 'O e-mail informado já está em uso. Por favor, escolha outro.',
            'password.required' => 'A senha é obrigatória.',
            'password.min' => 'A senha deve ter pelo menos 7 caracteres.',
            'profile_photo_path.image' => 'O arquivo deve ser uma imagem.',
            'profile_photo_path.mimes' => 'A imagem deve ser do tipo: jpeg, png, jpg ou gif.',
            'profile_photo_path.max' => 'A imagem não pode ter mais de 2MB.',
        ]);

        if ($request->hasFile('profile_photo_path')) {
            $file = $request->file('profile_photo_path');
            $filename = time() . '-' . $file->getClientOriginalName();
            $file->storeAs('public/images/profiles', $filename);
            $profile_photo_path = $filename;
        } else {
            $profile_photo_path = 'default.png';
        }

        $attributes = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'profile_photo_path' => $profile_photo_path
        ];

        $user = User::create($attributes);

        return redirect('/users/' . $user->id);
    }

    public function show(User $user)
    {
        return Inertia::render('Users/Show', [
            'user' => UserResource::make($user),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $attributes = $request->validate([
            'name' => 'required',
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'password' => 'nullable|min:7',
        ]);

        if ($attributes['password']) {
            $attributes['password'] = bcrypt($attributes['password']);
        } else {
            unset($attributes['password']);
        }

        $user->update($attributes);
    }

    public function destroy(User $user)
    {
        if ($user->profile_photo_path && $user->profile_photo_path !== 'storage/images/profiles/default.png') {
            $path = str_replace('storage/', 'public/', $user->profile_photo_path);

            Storage::delete($path);
        }

        $user->delete();

        return redirect('/users');
    }
}
