<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\DataTables\ProjectsDataTable;
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

        // Check if resource is available
        if (!$resource->availability) {
            return response()->json(['error' => 'Resource not available'], 400);
        }

        // Check if the resource is already allocated during the specified period
        $conflict = ResourceAssignment::where('resource_id', $request->resource_id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('assigned_from', [$request->assigned_from, $request->assigned_to])
                    ->orWhereBetween('assigned_to', [$request->assigned_from, $request->assigned_to]);
            })->exists();

        if ($conflict) {
            return response()->json(['error' => 'Resource allocation conflict'], 400);
        }

        // Create a new resource assignment
        ResourceAssignment::create($request->all());

        return response()->json(['success' => 'Resource allocated successfully']);
    }

    public function deallocateResource($id)
    {
        $assignment = ResourceAssignment::findOrFail($id);
        $resource = $assignment->resource;

        // Deallocate resource and update its availability
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
