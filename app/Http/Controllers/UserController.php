<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Mail\WelcomeWithPasswordMail;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(): View
    {
        return view('pages.dashboard.user.index', [
            'users' => User::paginate(10),
            'usersDeleted' => User::onlyTrashed()->paginate(10, pageName: 'pageDeleted'),
            'roles' => Role::where('name', '!=', 'Super Admin')->get(['name', 'id']),
        ]);
    }

    public function create(): View
    {
        return view('pages.dashboard.user.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $password = bin2hex(random_bytes(16));
        $validated = $request->validated();
        $validated['password'] = Hash::make($password);
        $validated['active'] = true;
        $user = User::create($validated)->assignRole('Cliente');

        // Mail::to($user)->send(new WelcomeWithPasswordMail($user, $password));
        Mail::to('maximo4735@gmail.com')->send(new WelcomeWithPasswordMail($user, $password));

        return redirect()->back()->with('success', 'Usuario creado correctamente. Verifique su correo para activar su cuenta.');
    }

    public function show(User $user): View
    {
        return view('pages.dashboard.user.show', [
            'user' => $user
        ]);
    }

    public function edit(User $user): View
    {
        return view('pages.dashboard.user.edit', [
            'user' => $user
        ]);
    }

    public function update(StoreUserRequest $request, String $id): RedirectResponse
    {
        User::findOrFail($id)->update($request->validated());

        return redirect()->back();
    }

    public function destroy(User $user): RedirectResponse
    {
        if (auth()->user()->id === $user->id) {
            return redirect()->back()->with('error', 'No puede eliminar su cuenta.');
        }

        $user->delete();
        return Redirect::back();
    }

    public function restore(String $id): RedirectResponse
    {
        User::onlyTrashed()->findOrFail($id)->restore();
        return Redirect::back();
    }

    public function fetch(String $id): JsonResponse
    {
        $user = User::withTrashed()->findOrFail($id, ['name', 'surname', 'email', 'phone', 'image', 'dni']);

        return response()->json($user);
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'roles' => 'array',
            'roles.*' => 'required|string|exists:roles,id',
        ]);

        $roles = Role::whereIn('id', $request->roles)->get();
        $user->syncRoles($roles);

        return redirect()->back();
    }

    public function fetchRoles(String $id): JsonResponse
    {
        $user = User::withTrashed()->findOrFail($id);

        return response()->json(['roles' => $user->roles()->pluck('id')]);
    }
}
