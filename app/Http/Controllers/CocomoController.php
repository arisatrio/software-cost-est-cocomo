<?php

namespace App\Http\Controllers;

use App\Enums\CostDriver;
use App\Enums\ScaleFactor;
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
        //
        $scaleFactors = ScaleFactor::cases();
        $costDrivers = CostDriver::cases();

        return view('cocomo.create', compact('scaleFactors', 'costDrivers'));
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
                    'programming_language' => 'required|string|in:java,csharp,cpp,python,javascript,php,ruby,swift,kotlin,go',
                    'external_inputs' => 'required|integer|min:0',
                    'external_outputs' => 'required|integer|min:0', 
                    'external_inquiries' => 'required|integer|min:0',
                    'internal_files' => 'required|integer|min:0',
                    'external_files' => 'required|integer|min:0',
                    'complexity_factor' => 'required|string|in:simple,average,complex',
                    'function_points' => 'required|numeric|min:0',
                    'total_sloc' => 'required|integer|min:0',
                    // Questionnaire fields
                    'q_inputs' => 'required|string|in:low,medium,high',
                    'q_outputs' => 'required|string|in:low,medium,high',
                    'q_inquiries' => 'required|string|in:low,medium,high',
                    'q_internal_files' => 'required|string|in:low,medium,high',
                    'q_external_files' => 'required|string|in:low,medium,high'
                ],
                // Validasi untuk semua faktor skala
                array_reduce(ScaleFactor::cases(), fn($carry, $factor) => array_merge($carry, [
                    $factor->value => 'required|in:VL,L,N,H,VH'
                ]), []),
                // Validasi untuk semua penggerak biaya dengan opsi yang sesuai
                array_reduce(CostDriver::cases(), fn($carry, $driver) => array_merge($carry, [
                    $driver->value => 'required|in:' . implode(',', array_map(fn($rating) => $rating->value, $driver->options()))
                ]), [])
            ),
            [
                // Custom error messages in Indonesian
                'project_name.max' => 'Nama proyek tidak boleh lebih dari 255 karakter.',
                'programming_language.required' => 'Silakan pilih bahasa pemrograman.',
                'programming_language.in' => 'Bahasa pemrograman yang dipilih tidak valid.',
                'q_inputs.required' => 'Silakan pilih tingkat kompleksitas untuk Input Data & Transaksi.',
                'q_inputs.in' => 'Pilihan untuk Input Data & Transaksi tidak valid.',
                'q_outputs.required' => 'Silakan pilih tingkat kompleksitas untuk Laporan & Output.',
                'q_outputs.in' => 'Pilihan untuk Laporan & Output tidak valid.',
                'q_inquiries.required' => 'Silakan pilih tingkat kompleksitas untuk Pencarian & Query.',
                'q_inquiries.in' => 'Pilihan untuk Pencarian & Query tidak valid.',
                'q_internal_files.required' => 'Silakan pilih tingkat kompleksitas untuk Penyimpanan Data.',
                'q_internal_files.in' => 'Pilihan untuk Penyimpanan Data tidak valid.',
                'q_external_files.required' => 'Silakan pilih tingkat kompleksitas untuk Integrasi Eksternal.',
                'q_external_files.in' => 'Pilihan untuk Integrasi Eksternal tidak valid.',
                'complexity_factor.required' => 'Silakan pilih tingkat kompleksitas keseluruhan aplikasi.',
                'complexity_factor.in' => 'Pilihan untuk tingkat kompleksitas tidak valid.'
            ]
        );

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log validation errors for debugging
            Log::error('Validation failed', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            throw $e;
        }

        $project = DB::transaction(function () use ($validatedData, $cocomoService) {
            
            // 1. Simpan data proyek dasar ke tabel `projects`
            $project = Project::create([
                'name' => $validatedData['project_name'] ?? 'Proyek Tanpa Nama',
                'kloc' => $validatedData['kloc'],
                'programming_language' => $validatedData['programming_language'],
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
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
}
