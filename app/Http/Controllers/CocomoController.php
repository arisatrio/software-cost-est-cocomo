<?php

namespace App\Http\Controllers;

use App\Enums\CostDriver;
use App\Enums\ScaleFactor;
use App\Enums\ProjectCategory;
use App\Models\Project;
use App\Models\ProjectFactor;
use App\Services\CocomoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CocomoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::orderBy('created_at', 'desc')->paginate(10);
        return view('cocomo.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $scaleFactors = ScaleFactor::cases();
        $costDrivers = CostDriver::cases();
        $projectCategories = ProjectCategory::cases();

        return view('cocomo.create', compact('scaleFactors', 'costDrivers', 'projectCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, CocomoService $cocomoService)
    {
        // Validate form data
        try {
            $validatedData = $request->validate(
            array_merge(
                [
                    'project_name' => 'nullable|string|max:255', 
                    'kloc' => 'required|numeric|min:0.1',
                    // Function Point fields
                    'external_inputs' => 'required|integer|min:0',
                    'external_outputs' => 'required|integer|min:0',
                    'external_inquiries' => 'required|integer|min:0',
                    'internal_files' => 'required|integer|min:0',
                    'external_files' => 'required|integer|min:0',
                    'complexity_factor' => 'required|string|in:low,medium,high',
                    'programming_language' => 'required|string',
                    'project_category' => 'required|string|in:organic,semi_detached,embedded',
                ],
                // Scale factors validation
                collect(ScaleFactor::cases())->mapWithKeys(fn($factor) => [
                    $factor->value => 'required|string|in:VL,L,N,H,VH'
                ])->toArray(),
                // Cost drivers validation  
                collect(CostDriver::cases())->mapWithKeys(fn($driver) => [
                    $driver->value => 'required|string'
                ])->toArray()
            ));
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            throw $e;
        }

        // Calculate function points and derived values
        $functionPoints = $this->calculateFunctionPoints(
            $validatedData['external_inputs'],
            $validatedData['external_outputs'],
            $validatedData['external_inquiries'],
            $validatedData['internal_files'],
            $validatedData['external_files'],
            $validatedData['complexity_factor']
        );
        
        // Map complexity factor from form values to database values
        $complexityMapping = [
            'low' => 'simple',
            'medium' => 'average', 
            'high' => 'complex'
        ];
        
        $validatedData['function_points'] = $functionPoints;
        $validatedData['total_sloc'] = $this->calculateSLOC($functionPoints, $validatedData['programming_language']);
        $validatedData['kloc'] = $validatedData['total_sloc'] / 1000;
        $validatedData['complexity_factor'] = $complexityMapping[$validatedData['complexity_factor']] ?? 'average';

        $project = DB::transaction(function () use ($validatedData, $cocomoService) {
            
            // 1. Simpan data proyek dasar ke tabel `projects`
            $project = Project::create([
                'name' => $validatedData['project_name'] ?? 'Proyek Tanpa Nama',
                'kloc' => $validatedData['kloc'],
                'programming_language' => $validatedData['programming_language'],
                'project_category' => $validatedData['project_category'],
                'external_inputs' => $validatedData['external_inputs'],
                'external_outputs' => $validatedData['external_outputs'],
                'external_inquiries' => $validatedData['external_inquiries'],
                'internal_files' => $validatedData['internal_files'],
                'external_files' => $validatedData['external_files'],
                'complexity_factor' => $validatedData['complexity_factor'],
                'function_points' => $validatedData['function_points'],
                'total_sloc' => $validatedData['total_sloc'],
            ]);

            // 2. Simpan setiap jawaban faktor ke tabel `project_factors`
            // Perulangan untuk semua faktor skala
            foreach (ScaleFactor::cases() as $factor) {
                ProjectFactor::create([
                    'project_id' => $project->id,
                    'factor_name' => $factor->value,
                    'rating' => $validatedData[$factor->value]
                ]);
            }
            // Perulangan untuk semua penggerak biaya
            foreach (CostDriver::cases() as $driver) {
                ProjectFactor::create([
                    'project_id' => $project->id,
                    'factor_name' => $driver->value,
                    'rating' => $validatedData[$driver->value]
                ]);
            }

            // 3. Panggil service untuk menghitung estimasi
            $cocomoService->calculateEstimates($project, $validatedData);

            return $project;
        });

        // Redirect to results page
        return redirect()->route('cocomo.show', $project->id)
            ->with('success', 'Estimasi COCOMO berhasil dihitung!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $project = Project::with('factors')->findOrFail($id);
        
        // Group factors by type for easier display
        $scaleFactors = [];
        $costDrivers = [];
        
        foreach ($project->factors as $factor) {
            if (in_array($factor->factor_name, array_column(ScaleFactor::cases(), 'value'))) {
                $scaleFactors[$factor->factor_name] = $factor->rating;
            } else {
                $costDrivers[$factor->factor_name] = $factor->rating;
            }
        }
        
        return view('cocomo.results', compact('project', 'scaleFactors', 'costDrivers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $project = Project::findOrFail($id);
        
        // Get existing factors
        $factors = $project->factors()->pluck('rating', 'factor_name');
        
        // Separate scale factors and cost drivers
        $scaleFactors = [];
        $costDrivers = [];
        
        foreach (ScaleFactor::cases() as $factor) {
            $scaleFactors[$factor->value] = $factors[$factor->value] ?? 'N';
        }
        
        foreach (CostDriver::cases() as $driver) {
            $costDrivers[$driver->value] = $factors[$driver->value] ?? 'N';
        }
        
        // Map complexity factor from database values to form values
        $complexityMapping = [
            'simple' => 'low',
            'average' => 'medium',
            'complex' => 'high'
        ];
        
        // Apply complexity mapping for form display
        if ($project->complexity_factor) {
            $project->complexity_factor = $complexityMapping[$project->complexity_factor] ?? $project->complexity_factor;
        }
        
        return view('cocomo.edit', compact('project', 'scaleFactors', 'costDrivers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id, CocomoService $cocomoService)
    {
        $project = Project::findOrFail($id);
        
        // Validate form data - same validation as store method
        try {
            $validatedData = $request->validate(
            array_merge(
                [
                    'name' => 'required|string|max:255',
                    'programming_language' => 'required|string',
                    'project_category' => 'required|string|in:organic,semi_detached,embedded',
                    // Function Point fields
                    'external_inputs' => 'required|integer|min:0',
                    'external_outputs' => 'required|integer|min:0',
                    'external_inquiries' => 'required|integer|min:0',
                    'internal_files' => 'required|integer|min:0',
                    'external_files' => 'required|integer|min:0',
                    'complexity_factor' => 'required|string|in:low,medium,high',
                ],
                // Scale factors validation
                collect(ScaleFactor::cases())->mapWithKeys(fn($factor) => [
                    "scale_factors.{$factor->value}" => 'required|string|in:VL,L,N,H,VH'
                ])->toArray(),
                // Cost drivers validation
                collect(CostDriver::cases())->mapWithKeys(fn($driver) => [
                    "cost_drivers.{$driver->value}" => 'required|string'
                ])->toArray()
            ));
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            throw $e;
        }

        $project = DB::transaction(function () use ($project, $validatedData, $cocomoService) {
            
            // Calculate function points and derived values
            $functionPoints = $this->calculateFunctionPoints(
                $validatedData['external_inputs'],
                $validatedData['external_outputs'],
                $validatedData['external_inquiries'],
                $validatedData['internal_files'],
                $validatedData['external_files'],
                $validatedData['complexity_factor']
            );
            
            // Map complexity factor from form values to database values
            $complexityMapping = [
                'low' => 'simple',
                'medium' => 'average', 
                'high' => 'complex'
            ];
            
            $validatedData['function_points'] = $functionPoints;
            $validatedData['total_sloc'] = $this->calculateSLOC($functionPoints, $validatedData['programming_language']);
            $validatedData['kloc'] = $validatedData['total_sloc'] / 1000;
            $validatedData['complexity_factor'] = $complexityMapping[$validatedData['complexity_factor']] ?? 'average';
            
            // 1. Update project basic data
            $project->update([
                'name' => $validatedData['name'],
                'kloc' => $validatedData['kloc'],
                'programming_language' => $validatedData['programming_language'],
                'project_category' => $validatedData['project_category'],
                'external_inputs' => $validatedData['external_inputs'],
                'external_outputs' => $validatedData['external_outputs'],
                'external_inquiries' => $validatedData['external_inquiries'],
                'internal_files' => $validatedData['internal_files'],
                'external_files' => $validatedData['external_files'],
                'complexity_factor' => $validatedData['complexity_factor'],
                'function_points' => $validatedData['function_points'],
                'total_sloc' => $validatedData['total_sloc'],
            ]);

            // 2. Delete existing factors and create new ones
            $project->factors()->delete();
            
            // Save scale factors
            foreach (ScaleFactor::cases() as $factor) {
                ProjectFactor::create([
                    'project_id' => $project->id,
                    'factor_name' => $factor->value,
                    'rating' => $validatedData['scale_factors'][$factor->value]
                ]);
            }
            
            // Save cost drivers
            foreach (CostDriver::cases() as $driver) {
                ProjectFactor::create([
                    'project_id' => $project->id,
                    'factor_name' => $driver->value,
                    'rating' => $validatedData['cost_drivers'][$driver->value]
                ]);
            }

            // 3. Recalculate estimates
            $cocomoService->calculateEstimates($project, $validatedData);

            return $project;
        });

        return redirect()->route('cocomo.results', $project->id)
            ->with('success', 'Proyek berhasil diperbarui dan estimasi telah dihitung ulang!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $project = Project::findOrFail($id);
        $project->delete();
        
        return redirect()->route('cocomo.index')
            ->with('success', 'Proyek berhasil dihapus!');
    }

    /**
     * Show form for inputting actual project data
     */
    public function actualDataForm(string $id)
    {
        $project = Project::findOrFail($id);
        return view('cocomo.actual-data', compact('project'));
    }

    /**
     * Update actual project data
     */
    public function updateActual(Request $request, string $id)
    {
        $project = Project::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:planning,in_progress,completed,cancelled',
            'actual_start_date' => 'nullable|date',
            'actual_end_date' => 'nullable|date|after_or_equal:actual_start_date',
            'actual_schedule' => 'nullable|numeric|min:0',
            'actual_personnel' => 'nullable|integer|min:0',
            'actual_sloc' => 'nullable|integer|min:0',
            'actual_notes' => 'nullable|string|max:1000'
        ], [
            'actual_end_date.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
            'actual_schedule.min' => 'Durasi aktual harus bernilai positif.',
            'actual_personnel.min' => 'Jumlah tim aktual harus bernilai positif.',
            'actual_sloc.min' => 'SLOC aktual harus bernilai positif.',
            'actual_notes.max' => 'Catatan tidak boleh lebih dari 1000 karakter.'
        ]);
        
        // Auto-calculate effort based on schedule and personnel
        if (isset($validated['actual_schedule']) && isset($validated['actual_personnel'])) {
            $validated['actual_effort'] = $validated['actual_schedule'] * $validated['actual_personnel'];
        }
        
        $project->update($validated);
        
        // Calculate accuracy if project is completed and has actual data
        if ($project->status === 'completed' && $project->actual_effort && $project->actual_schedule) {
            $project->calculateAccuracy();
        }
        
        return redirect()->route('cocomo.show', $project->id)
            ->with('success', 'Data aktual berhasil disimpan!');
    }

    /**
     * Calculate Function Points based on inputs
     */
    private function calculateFunctionPoints($inputs, $outputs, $inquiries, $internal, $external, $complexity)
    {
        $complexityMultiplier = match($complexity) {
            'low' => 0.85,
            'medium' => 1.0,
            'high' => 1.15,
            default => 1.0
        };

        // Simple function point calculation using average weights
        $unadjustedFP = ($inputs * 4) + ($outputs * 5) + ($inquiries * 4) + ($internal * 10) + ($external * 7);
        
        return round($unadjustedFP * $complexityMultiplier, 2);
    }

    /**
     * Calculate SLOC from Function Points based on programming language
     */
    private function calculateSLOC($functionPoints, $language)
    {
        $languageFactors = [
            'java' => 53,
            'csharp' => 54,
            'cpp' => 55,
            'python' => 27,
            'javascript' => 47,
            'php' => 36,
            'ruby' => 25,
            'swift' => 75,
            'kotlin' => 50,
            'go' => 45
        ];

        $factor = $languageFactors[$language] ?? 50; // Default factor
        return round($functionPoints * $factor);
    }
}
