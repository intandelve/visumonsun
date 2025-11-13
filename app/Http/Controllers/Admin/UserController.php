<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('admin.users_index', [
            'users' => $users
        ]);
    }

    public function destroy($id)
    {
        if ($id == Auth::id()) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete yourself!');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index');
    }
}