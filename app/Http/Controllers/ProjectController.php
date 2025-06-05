<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $projects = Project::orderBy('created_at', 'desc')
                             ->paginate(10);
            
            return Inertia::render('Projects/Index', [
                'projects' => $projects
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar los proyectos: ' . $e->getMessage());
        }
    }

    /**
     * Muestra el formulario para crear un proyecto
     */
    public function create()
    {
        return Inertia::render('Projects/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'codigo_proyecto' => 'required|string|max:255|unique:projects,codigo_proyecto',
                'nombre' => 'required|string|max:255'
            ]);

            Project::create($validated);

            return redirect()->route('projects.index')
                ->with('success', 'Proyecto creado exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear el proyecto: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        try {
            return Inertia::render('Projects/Show', [
                'project' => $project
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar el proyecto: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        try {
            return Inertia::render('Projects/Edit', [
                'project' => $project
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar el proyecto: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        try {
            $validated = $request->validate([
                'codigo_proyecto' => 'required|string|max:255|unique:projects,codigo_proyecto,' . $project->id,
                'nombre' => 'required|string|max:255'
            ]);

            $project->update($validated);

            return redirect()->route('projects.index')
                ->with('success', 'Proyecto actualizado exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar el proyecto: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        try {
            $project->delete();
            return redirect()->route('projects.index')
                ->with('success', 'Proyecto eliminado exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el proyecto: ' . $e->getMessage());
        }
    }
}
