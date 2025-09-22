<?php

namespace App\Services;

use App\Models\Project;
use App\Enums\ScaleFactor;
use App\Enums\CostDriver;

class CocomoService
{
    // Koefisien utama COCOMO
    private $effortCoefficients = [
        'A' => 2.94
    ];

    private $scheduleCoefficients = [
        'C' => 3.67,
        'D' => 0.28
    ];

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
        // 1. Hitung Eksponen (E)
        $sumOfSF = 0;
        foreach (ScaleFactor::cases() as $factor) {
            $sumOfSF += $this->scaleFactorValues[$factor->value][$data[$factor->value]];
        }
        $E = 0.91 + (0.01 * $sumOfSF);

        // 2. Hitung EAF (Effort Adjustment Factor)
        $EAF = 1;
        foreach (CostDriver::cases() as $driver) {
            $EAF *= $this->costDriverValues[$driver->value][$data[$driver->value]];
        }

        // 3. Hitung Effort, Schedule, dan Personnel
        $effort = $this->effortCoefficients['A'] * pow($project->kloc, $E) * $EAF;
        $schedule = $this->scheduleCoefficients['C'] * pow($effort, $this->scheduleCoefficients['D']);
        $personnel = $effort / $schedule;

        // Simpan hasil ke database
        $project->calculated_effort = $effort;
        $project->calculated_schedule = $schedule;
        $project->calculated_personnel = $personnel;
        $project->save();

        return $project;
    }
}