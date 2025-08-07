<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    public function index(): View
    {
        return view('pages.dashboard.user.index', [
            'users' => User::paginate(10),
            'usersDeleted' => User::onlyTrashed()->paginate(10)
        ]);
    }

    public function create(): View
    {
        return view('pages.dashboard.user.create');
    }

    public function store(Request $request)
    {
        return 1;
    }

    public function show(User $user)
    {
        return 1;
    }

    public function edit(User $user)
    {
        return 1;
    }

    public function update(Request $request, String $id)
    {
        return 1;
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();
        return Redirect::back();
    }

    public function restore(String $id): RedirectResponse
    {
        User::onlyTrashed()->findOrFail($id)->restore();
        return Redirect::back();
    }

    public function fetch($id)
    {
        $user = User::withTrashed()->findOrFail($id, ['name', 'surname', 'email', 'phone', 'image']);

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        return response()->json($user);
    }
}
