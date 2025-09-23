<?php

namespace App\Services;

use App\Models\Project;
use App\Enums\ScaleFactor;
use App\Enums\CostDriver;
use App\Enums\ProjectCategory;

class CocomoService
{
    // Nilai numerik untuk Faktor Skala
    private $scaleFactorValues = [
        'PREC' => ['VL' => 6.20, 'L' => 4.96, 'N' => 3.72, 'H' => 2.48, 'VH' => 1.24],
        'FLEX' => ['VL' => 5.07, 'L' => 4.05, 'N' => 3.04, 'H' => 2.03, 'VH' => 1.01],
        'RESL' => ['VL' => 5.48, 'L' => 4.38, 'N' => 3.29, 'H' => 2.19, 'VH' => 1.10],
        'TEAM' => ['VL' => 4.70, 'L' => 3.76, 'N' => 2.82, 'H' => 1.88, 'VH' => 0.94],
        'PMAT' => ['VL' => 7.80, 'L' => 6.24, 'N' => 4.68, 'H' => 3.12, 'VH' => 1.56],
    ];

    // Nilai numerik untuk Penggerak Biaya
    private $costDriverValues = [
        'RELY' => ['VL' => 0.82, 'L' => 0.92, 'N' => 1.00, 'H' => 1.10, 'VH' => 1.26],
        'DATA' => ['L' => 0.90, 'N' => 1.00, 'H' => 1.14, 'VH' => 1.28],
        'CPLX' => ['VL' => 0.73, 'L' => 0.87, 'N' => 1.00, 'H' => 1.17, 'VH' => 1.34, 'XH' => 1.74],
        'RUSE' => ['L' => 0.91, 'N' => 1.00, 'H' => 1.09, 'VH' => 1.19, 'XH' => 1.29],
        'DOCU' => ['VL' => 0.81, 'L' => 0.91, 'N' => 1.00, 'H' => 1.09, 'VH' => 1.20],
        'TIME' => ['N' => 1.00, 'H' => 1.11, 'VH' => 1.29, 'XH' => 1.63],
        'STOR' => ['N' => 1.00, 'H' => 1.05, 'VH' => 1.17, 'XH' => 1.46],
        'PVOL' => ['L' => 0.87, 'N' => 1.00, 'H' => 1.29, 'VH' => 1.48],
        'ACAP' => ['VL' => 1.42, 'L' => 1.19, 'N' => 1.00, 'H' => 0.85, 'VH' => 0.71],
        'PCAP' => ['VL' => 1.29, 'L' => 1.14, 'N' => 1.00, 'H' => 0.90, 'VH' => 0.81],
        'AEXP' => ['VL' => 1.22, 'L' => 1.10, 'N' => 1.00, 'H' => 0.88, 'VH' => 0.81],
        'LTEX' => ['VL' => 1.20, 'L' => 1.09, 'N' => 1.00, 'H' => 0.91, 'VH' => 0.84],
        'PCON' => ['VL' => 1.25, 'L' => 1.12, 'N' => 1.00, 'H' => 0.88, 'VH' => 0.81],
        'TOOL' => ['VL' => 1.17, 'L' => 1.09, 'N' => 1.00, 'H' => 0.90, 'VH' => 0.78, 'XH' => 0.80],
        'SITE' => ['VL' => 1.22, 'L' => 1.09, 'N' => 1.00, 'H' => 0.93, 'VH' => 0.86],
        'SCED' => ['VL' => 1.43, 'L' => 1.14, 'N' => 1.00, 'H' => 1.00, 'VH' => 1.00],
    ];

    public function calculateEstimates(Project $project, array $data)
    {
        // 1. Dapatkan kategori proyek dan koefisiennya
        $category = ProjectCategory::from($project->project_category->value ?? 'organic');
        $coefficients = $category->coefficients();

        // 2. Hitung Eksponen (E) dengan Scale Factors
        $sumOfSF = 0;
        foreach (ScaleFactor::cases() as $factor) {
            // Handle both data structures: direct values and nested scale_factors array
            $ratingValue = null;
            if (isset($data['scale_factors'][$factor->value])) {
                // From edit form: scale_factors[PREC] = 'N'
                $ratingValue = $data['scale_factors'][$factor->value];
            } elseif (isset($data[$factor->value])) {
                // From create form: PREC = 'N'
                $ratingValue = $data[$factor->value];
            } else {
                // Get from database if not in data
                $factorRecord = $project->factors()->where('factor_name', $factor->value)->first();
                $ratingValue = $factorRecord ? $factorRecord->rating : 'N';
            }
            
            if (isset($this->scaleFactorValues[$factor->value][$ratingValue])) {
                $sumOfSF += $this->scaleFactorValues[$factor->value][$ratingValue];
            }
        }
        $E = $coefficients['B'] + (0.01 * $sumOfSF);

        // 3. Hitung EAF (Effort Adjustment Factor)
        $EAF = 1;
        foreach (CostDriver::cases() as $driver) {
            // Handle both data structures: direct values and nested cost_drivers array
            $ratingValue = null;
            if (isset($data['cost_drivers'][$driver->value])) {
                // From edit form: cost_drivers[RELY] = 'N'
                $ratingValue = $data['cost_drivers'][$driver->value];
            } elseif (isset($data[$driver->value])) {
                // From create form: RELY = 'N'
                $ratingValue = $data[$driver->value];
            } else {
                // Get from database if not in data
                $factorRecord = $project->factors()->where('factor_name', $driver->value)->first();
                $ratingValue = $factorRecord ? $factorRecord->rating : 'N';
            }
            
            if (isset($this->costDriverValues[$driver->value][$ratingValue])) {
                $EAF *= $this->costDriverValues[$driver->value][$ratingValue];
            }
        }

        // 4. Hitung Effort, Schedule, dan Personnel menggunakan koefisien kategori proyek
        $effort = $coefficients['A'] * pow($project->kloc, $E) * $EAF;
        $schedule = $coefficients['C'] * pow($effort, $coefficients['D']);
        $personnel = $effort / $schedule;

        // Simpan hasil ke database
        $project->calculated_effort = $effort;
        $project->calculated_schedule = $schedule;
        $project->calculated_personnel = $personnel;
        $project->save();

        return $project;
    }
}