<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\DataTables\ProjectsDataTable;
use App\Models\{Project, User};



class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ProjectsDataTable $dataTable)
    {
        return $dataTable->render('projects.index');
    }

    public function show($id)
    {
        return view('projects.show', ['project' => Project::findOrFail($id)]);
    }

    public function edit($id)
    {
        $users = User::all();
        return view('projects.edit', ['project' => Project::findOrFail($id), 'users' => $users]);
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        $project->update($request->all());
        $project->users()->sync($request->users);


        return redirect()->route('projects.index');
    }

    public function destroy($id)
    {
        Project::destroy($id);
        return redirect()->route('projects.index');
    }


    public function create()
    {
        $users = User::all();
        return view('projects.create', ['users' => $users]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $project = Project::create($request->all());

        $project->users()->sync($request->users);

        return redirect()->route('projects.index');
    }
}
