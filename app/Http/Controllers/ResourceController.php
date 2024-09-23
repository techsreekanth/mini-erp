<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Resource};


class ResourceController extends Controller
{
    public function index()
    {
        $resources = Resource::all();
        return view('resources.index',['resources' => $resources]);
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
}
