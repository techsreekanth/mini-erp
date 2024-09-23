<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\UsersDataTable;
use App\Models\User;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UsersDataTable $dataTable)
    {
        return $dataTable->render('users.index');
    }

    public function show($id)
    {
        return view('users.show', ['user' => User::findOrFail($id)]);
    }

    public function edit($id)
    {
        return view('users.edit', ['user' => User::findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());

        return redirect()->route('users.index');
    }

    public function destroy($id)
    {
        User::destroy($id);
        return redirect()->route('users.index');
    }


    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
        ]);

        User::create($request->all());

        return redirect()->route('users.index');
    }
}
