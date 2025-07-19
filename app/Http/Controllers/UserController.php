<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(): View
    {
        return view('pages.dashboard.user.index', [
            'users' => User::paginate(10)
        ]);
    }

    public function create()
    {
        return 1;
    }

    public function store(Request $request)
    {
        return 1;
    }

    public function show($id)
    {
        return 1;
    }

    public function destroy($id)
    {
        return 1;
    }

    public function restore($id)
    {
        return 1;
    }
}
