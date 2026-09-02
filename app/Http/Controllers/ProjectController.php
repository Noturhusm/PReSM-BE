<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectDocuments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    public function create(Request $request)
    {
        $validated = $request->validate([
            'projectName' => 'required|string|max:255',
            'clientName' => 'required|string|max:255',
            'projectManager' => 'required|string|max:255',
            'projectCode' => 'required|string|max:50|unique:projects,projectCode',
            'projectDetail' => 'required|string',
        ]);

        $project = Project::create($validated);

        return response()->json([
            'message' => 'Project registered successfully',
            'project' => $project,
        ], 201);
    }

    public function index()
    {
        return response()->json(Project::with('documents')->get());
    }

    public function show($id)
    {
        $project = Project::with('documents')->find($id);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        return response()->json($project);
    }

    public function update(Request $request, $id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        $validated = $request->validate([
            'projectCode' => [
                'required',
                'string',
                'max:50',
                Rule::unique('projects', 'projectCode')->ignore($project->id),
            ],
            'projectStartDate' => 'nullable|date',
            'projectEndDate' => 'nullable|date',
            'projectType' => 'nullable|string|max:255',
            'projectCategory' => 'nullable|string|max:255',
            'projectCost' => 'nullable|string|max:255',
            'projectDetail' => 'nullable|string',
            'projectStatus' => 'nullable|string|max:255',
            'projectTeamMembers' => 'nullable|string',
            'projectManager' => 'nullable|string|max:255',
            'projectName' => 'sometimes|string|max:255',
            'clientName' => 'sometimes|string|max:255',
        ]);

        $project->fill($validated);

        if (array_key_exists('projectDetail', $validated) && $validated['projectDetail'] === null) {
            $project->projectDetail = '';
        }

        $project->save();

        return response()->json([
            'message' => 'Project updated successfully',
            'project' => $project->fresh('documents'),
        ]);
    }

    public function uploadDocument(Request $request, $id)
    {
        $request->validate([
            'uploads' => 'required|file|max:10240',
        ]);

        $project = Project::find($id);

        if (!$project) {
            return response()->json(['error' => 'Project not found'], 404);
        }

        if ($request->hasFile('uploads')) {
            $file = $request->file('uploads');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('documents', $fileName, 'public');

            $document = ProjectDocuments::create([
                'projectCode' => $project->projectCode,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
            ]);

            return response()->json([
                'message' => 'File uploaded successfully',
                'document' => $document,
            ], 200);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }

    public function deleteDocument($documentId)
    {
        $document = ProjectDocuments::find($documentId);

        if (!$document) {
            return response()->json(['message' => 'Document not found'], 404);
        }

        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return response()->json([
            'message' => 'Document deleted successfully',
        ]);
    }

    public function delete($projectCode)
    {
        Project::where('projectCode', $projectCode)->delete();

        return response()->json([
            'message' => 'Project deleted successfully',
        ]);
    }
}
