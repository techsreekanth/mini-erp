<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Resource};
use App\DataTables\ResourcesDataTable;


class ResourceController extends Controller
{
    public function index(ResourcesDataTable $dataTable)
    {
        return $dataTable->render('projects.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required',
        ]);

        Resource::create($request->all());
        return redirect()->route('resources.index');
    }

    public function show($id)
    {
        return view('resources.show', ['resource' => Resource::findOrFail($id)]);
    }

    public function edit($id)
    {
        return view('resources.edit', ['resource' => Resource::findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        $resource = Resource::findOrFail($id);
        $resource->update($request->all());

        return redirect()->route('resources.index');
    }

    public function destroy($id)
    {
        Resource::destroy($id);
        return redirect()->route('resources.index');
    }

    public function create()
    {
        return view('resources.create');
    }
}
