<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Services\CocomoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CocomoValidationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test to see actual COCOMO calculation values for validation
     */
    public function test_display_actual_cocomo_calculation_values()
    {
        // Arrange
        $cocomoService = new CocomoService();
        $project = Project::create([
            'name' => 'Validation Test Project',
            'kloc' => 10
        ]);

        // All factors set to Nominal (N)
        $inputData = [
            'project_name' => 'Validation Test Project',
            'kloc' => 10,
            // Scale Factors - All Nominal
            'PREC' => 'N', 'FLEX' => 'N', 'RESL' => 'N', 'TEAM' => 'N', 'PMAT' => 'N',
            // Cost Drivers - All Nominal  
            'RELY' => 'N', 'DATA' => 'N', 'CPLX' => 'N', 'RUSE' => 'N', 'DOCU' => 'N',
            'TIME' => 'N', 'STOR' => 'N', 'PVOL' => 'N', 'ACAP' => 'N', 'PCAP' => 'N',
            'AEXP' => 'N', 'LTEX' => 'N', 'PCON' => 'N', 'TOOL' => 'N', 'SITE' => 'N', 'SCED' => 'N'
        ];

        // Act
        $result = $cocomoService->calculateEstimates($project, $inputData);

        // Display actual values for manual verification
        echo "\n=== COCOMO Calculation Results (10 KLOC, All Nominal) ===\n";
        echo "Effort: " . round($result->calculated_effort, 2) . " person-months\n";
        echo "Schedule: " . round($result->calculated_schedule, 2) . " months\n";
        echo "Personnel: " . round($result->calculated_personnel, 2) . " people\n";
        echo "\n=== Manual Calculation Verification ===\n";
        echo "Scale Factors: PREC=3.72, FLEX=3.04, RESL=3.29, TEAM=2.82, PMAT=4.68\n";
        echo "Sum of SF = 17.55\n";
        echo "E = 0.91 + (0.01 × 17.55) = 1.0855\n";
        echo "EAF = 1.00 (all nominal)\n";
        echo "Expected Effort = 2.94 × (10)^1.0855 × 1.00 = " . round(2.94 * pow(10, 1.0855) * 1.00, 2) . "\n";
        echo "Expected Schedule = 3.67 × (" . round(2.94 * pow(10, 1.0855) * 1.00, 2) . ")^0.28 = " . round(3.67 * pow(2.94 * pow(10, 1.0855) * 1.00, 0.28), 2) . "\n";
        echo "Expected Personnel = Effort / Schedule = " . round((2.94 * pow(10, 1.0855) * 1.00) / (3.67 * pow(2.94 * pow(10, 1.0855) * 1.00, 0.28)), 2) . "\n";

        // Assert (just check they are reasonable values)
        $this->assertGreaterThan(0, $result->calculated_effort);
        $this->assertGreaterThan(0, $result->calculated_schedule);
        $this->assertGreaterThan(0, $result->calculated_personnel);
    }

    /**
     * Test different project scenarios to validate ranges
     */
    public function test_cocomo_calculation_scenarios()
    {
        $cocomoService = new CocomoService();
        
        $scenarios = [
            [
                'name' => 'Small Simple Project',
                'kloc' => 2,
                'scale_factors' => ['VH', 'VH', 'VH', 'VH', 'VH'], // All Very High (favorable)
                'cost_drivers' => 'all_favorable'
            ],
            [
                'name' => 'Medium Standard Project', 
                'kloc' => 20,
                'scale_factors' => ['N', 'N', 'N', 'N', 'N'], // All Nominal
                'cost_drivers' => 'all_nominal'
            ],
            [
                'name' => 'Large Complex Project',
                'kloc' => 200,
                'scale_factors' => ['VL', 'VL', 'VL', 'VL', 'VL'], // All Very Low (unfavorable)
                'cost_drivers' => 'all_challenging'
            ]
        ];

        echo "\n=== COCOMO Calculation Scenarios ===\n";
        
        foreach ($scenarios as $scenario) {
            $project = Project::create([
                'name' => $scenario['name'],
                'kloc' => $scenario['kloc']
            ]);

            // Prepare input data based on scenario
            $inputData = [
                'project_name' => $scenario['name'],
                'kloc' => $scenario['kloc'],
                'PREC' => $scenario['scale_factors'][0],
                'FLEX' => $scenario['scale_factors'][1], 
                'RESL' => $scenario['scale_factors'][2],
                'TEAM' => $scenario['scale_factors'][3],
                'PMAT' => $scenario['scale_factors'][4],
            ];

            // Set cost drivers based on scenario type
            $driverValue = match($scenario['cost_drivers']) {
                'all_favorable' => 'VH', // Best case
                'all_nominal' => 'N',    // Standard case
                'all_challenging' => 'VL', // Worst case (where applicable)
                default => 'N'
            };

            foreach (['RELY', 'DATA', 'CPLX', 'RUSE', 'DOCU', 'TIME', 'STOR', 'PVOL', 
                     'ACAP', 'PCAP', 'AEXP', 'LTEX', 'PCON', 'TOOL', 'SITE', 'SCED'] as $driver) {
                $inputData[$driver] = $driverValue === 'VL' && in_array($driver, ['DATA', 'TIME', 'STOR']) ? 'N' : $driverValue;
            }

            $result = $cocomoService->calculateEstimates($project, $inputData);

            echo sprintf(
                "%-25s | %3d KLOC | %6.1f PM | %5.1f months | %4.1f people\n",
                $scenario['name'],
                $scenario['kloc'],
                $result->calculated_effort,
                $result->calculated_schedule, 
                $result->calculated_personnel
            );

            // Basic sanity checks
            $this->assertGreaterThan(0, $result->calculated_effort, $scenario['name'] . ' should have positive effort');
            $this->assertGreaterThan(0, $result->calculated_schedule, $scenario['name'] . ' should have positive schedule');
            $this->assertGreaterThan(0, $result->calculated_personnel, $scenario['name'] . ' should have positive personnel');
        }
    }
}