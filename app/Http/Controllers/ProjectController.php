<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectDocuments; // Added this import
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    // Create a new project
    public function create(Request $request)
    {
        $request->validate([
            'projectName' => 'required|string|max:255',
            'clientName' => 'required|string|max:255',
            'projectManager' => 'required|string|max:255',
            'projectCode' => 'required|string|max:50|unique:projects,projectCode'
        ]);

        $project = Project::create($request->all());

        return response()->json([
            'message' => 'Project registered successfully',
            'project' => $project
        ], 201);
    }
     
    

    public function index()
    {
        // Including 'documents' allows the frontend to see the file count/list immediately
        return response()->json(Project::with('documents')->get());
    }

   
    public function getProject($id)
    {
        // .find($id) is better than .where('id', $id)->get() because it returns ONE object, not a list
        $project = Project::with('documents')->find($id);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        return response()->json($project);
    }
    

    public function show($id)
    {
        // 'with(documents)' ensures the table in React isn't empty
        $project = Project::with('documents')->find($id);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        return response()->json($project);
    }

    // Handle File Uploads
     public function uploadDocument(Request $request, $id) 
    {
        $request->validate([
        'uploads' => 'required|file|max:10240',
         ]);

    // FIX: Make sure you have "$project =" before the find command
    $project = Project::find($id);

    if (!$project) {
        return response()->json(['error' => 'Project not found'], 404);
    }

    if ($request->hasFile('uploads')) {
        $file = $request->file('uploads');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('documents', $fileName, 'public');

        // Now $project is defined and we can get the projectCode
        $document = ProjectDocuments::create([
            'projectCode' => $project->id, 
            'file_name'   => $file->getClientOriginalName(),
            'file_path'   => $path,
        ]);

        return response()->json([
            'message' => 'File uploaded successfully',
            'document' => $document
        ], 200);
    }

    return response()->json(['error' => 'No file uploaded'], 400);
    }

    // Delete a project
    public function delete($projectCode)
    {
        Project::where('projectCode', $projectCode)->delete();

        return response()->json([
            'message' => 'Project deleted successfully'
        ]);
    }
}