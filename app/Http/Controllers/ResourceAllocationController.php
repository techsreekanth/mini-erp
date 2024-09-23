<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Project, ResourceAssignment, Resource};



class ResourceAllocationController extends Controller
{
    public function allocateResource(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'resource_id' => 'required|exists:resources,id',
            'assigned_from' => 'required|date',
            'assigned_to' => 'required|date',
            'allocation_percentage' => 'required|integer|min:1|max:100',
        ]);

        $resource = Resource::find($request->resource_id);

        if (!$resource->availability) {
            return response()->json(['error' => 'Resource not available'], 400);
        }

        $conflict = ResourceAssignment::where('resource_id', $request->resource_id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('assigned_from', [$request->assigned_from, $request->assigned_to])
                    ->orWhereBetween('assigned_to', [$request->assigned_from, $request->assigned_to]);
            })->exists();

        if ($conflict) {
            return response()->json(['error' => 'Resource allocation conflict'], 400);
        }

        ResourceAssignment::create($request->all());

        return response()->json(['success' => 'Resource allocated successfully']);
    }

    public function deallocateResource($id)
    {
        $assignment = ResourceAssignment::findOrFail($id);
        $resource = $assignment->resource;

        $assignment->delete();
        $resource->availability = true;
        $resource->save();

        return response()->json(['success' => 'Resource deallocated successfully']);
    }

    public function showProjectResources($project_id)
    {
        $project = Project::with('resourceAssignments.resource')->findOrFail($project_id);
        return response()->json($project);
    }
}
