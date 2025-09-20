<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Services\CocomoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CocomoCalculationTest extends TestCase
{
    use RefreshDatabase;

    private CocomoService $cocomoService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cocomoService = new CocomoService();
    }

    /**
     * Test Case 1: All Nominal Values
     * This test validates the basic COCOMO calculation with all factors set to nominal
     */
    public function test_cocomo_calculation_with_all_nominal_values()
    {
        // Arrange: Create a project with 10 KLOC
        $project = Project::create([
            'name' => 'Test Project - All Nominal',
            'kloc' => 10
        ]);

        // All factors set to Nominal (N)
        $inputData = [
            'project_name' => 'Test Project - All Nominal',
            'kloc' => 10,
            // Scale Factors - All Nominal
            'PREC' => 'N', // 3.72
            'FLEX' => 'N', // 3.04
            'RESL' => 'N', // 3.29
            'TEAM' => 'N', // 2.82
            'PMAT' => 'N', // 4.68
            // Cost Drivers - All Nominal
            'RELY' => 'N', 'DATA' => 'N', 'CPLX' => 'N', 'RUSE' => 'N', 'DOCU' => 'N',
            'TIME' => 'N', 'STOR' => 'N', 'PVOL' => 'N', 'ACAP' => 'N', 'PCAP' => 'N',
            'AEXP' => 'N', 'LTEX' => 'N', 'PCON' => 'N', 'TOOL' => 'N', 'SITE' => 'N', 'SCED' => 'N'
        ];

        // Manual Calculation:
        // SF = 3.72 + 3.04 + 3.29 + 2.82 + 4.68 = 17.55
        // E = 0.91 + (0.01 × 17.55) = 1.0855
        // EAF = 1.00 (all nominal)
        // Effort = 2.94 × (10)^1.0855 × 1.00 = 2.94 × 12.17 = 35.78 person-months
        // Schedule = 3.67 × (35.78)^0.28 = 3.67 × 2.38 = 8.73 months
        // Personnel = 35.78 / 8.73 = 4.10 people

        // Act: Calculate estimates
        $result = $this->cocomoService->calculateEstimates($project, $inputData);

        // Assert: Verify results (with tolerance for floating point precision)
        $this->assertEqualsWithDelta(35.78, $result->calculated_effort, 0.1, 'Effort calculation mismatch');
        $this->assertEqualsWithDelta(8.73, $result->calculated_schedule, 0.1, 'Schedule calculation mismatch');
        $this->assertEqualsWithDelta(4.10, $result->calculated_personnel, 0.1, 'Personnel calculation mismatch');
    }

    /**
     * Test Case 2: Small Project with High Capability Team
     * This test validates calculation with favorable conditions
     */
    public function test_cocomo_calculation_small_project_high_capability()
    {
        // Arrange: Create a small project with 5 KLOC
        $project = Project::create([
            'name' => 'Small High-Capability Project',
            'kloc' => 5
        ]);

        $inputData = [
            'project_name' => 'Small High-Capability Project',
            'kloc' => 5,
            // Scale Factors - Favorable conditions
            'PREC' => 'H',  // 2.48 (high precedentedness)
            'FLEX' => 'H',  // 2.03 (high flexibility)
            'RESL' => 'H',  // 2.19 (high resolution)
            'TEAM' => 'VH', // 0.94 (very high team cohesion)
            'PMAT' => 'H',  // 3.12 (high process maturity)
            // Cost Drivers - High capability team
            'RELY' => 'N',  'DATA' => 'N',  'CPLX' => 'L',  'RUSE' => 'N',  'DOCU' => 'N',
            'TIME' => 'N',  'STOR' => 'N',  'PVOL' => 'N',  
            'ACAP' => 'VH', // 0.71 (very high analyst capability)
            'PCAP' => 'VH', // 0.81 (very high programmer capability)
            'AEXP' => 'H',  // 0.88 (high application experience)
            'LTEX' => 'H',  // 0.91 (high language/tool experience)
            'PCON' => 'H',  // 0.88 (high personnel continuity)
            'TOOL' => 'H',  // 0.90 (high tool usage)
            'SITE' => 'N',  'SCED' => 'N'
        ];

        // Manual Calculation:
        // SF = 2.48 + 2.03 + 2.19 + 0.94 + 3.12 = 10.76
        // E = 0.91 + (0.01 × 10.76) = 1.0176
        // EAF = 1.0 × 1.0 × 0.87 × 1.0 × 1.0 × 1.0 × 1.0 × 1.0 × 0.71 × 0.81 × 0.88 × 0.91 × 0.88 × 0.90 × 1.0 × 1.0
        // EAF ≈ 0.356
        // Effort = 2.94 × (5)^1.0176 × 0.356 = 2.94 × 5.09 × 0.356 = 5.33 person-months
        // Schedule = 3.67 × (5.33)^0.28 = 3.67 × 1.58 = 5.80 months
        // Personnel = 5.33 / 5.80 = 0.92 people

        // Act: Calculate estimates
        $result = $this->cocomoService->calculateEstimates($project, $inputData);

        // Assert: Should require less effort due to high capabilities
        $this->assertTrue($result->calculated_effort < 20, 'High-capability team should require less effort');
        $this->assertTrue($result->calculated_personnel < 2, 'Small project with high capability should require few people');
        $this->assertGreaterThan(0, $result->calculated_effort, 'Effort should be positive');
        $this->assertGreaterThan(0, $result->calculated_schedule, 'Schedule should be positive');
        $this->assertGreaterThan(0, $result->calculated_personnel, 'Personnel should be positive');
    }

    /**
     * Test Case 3: Large Complex Project
     * This test validates calculation with challenging conditions
     */
    public function test_cocomo_calculation_large_complex_project()
    {
        // Arrange: Create a large complex project with 100 KLOC
        $project = Project::create([
            'name' => 'Large Complex Project',
            'kloc' => 100
        ]);

        $inputData = [
            'project_name' => 'Large Complex Project',
            'kloc' => 100,
            // Scale Factors - Challenging conditions
            'PREC' => 'VL', // 6.20 (very low precedentedness)
            'FLEX' => 'L',  // 4.05 (low flexibility)
            'RESL' => 'L',  // 4.38 (low resolution)
            'TEAM' => 'L',  // 3.76 (low team cohesion)
            'PMAT' => 'L',  // 6.24 (low process maturity)
            // Cost Drivers - Complex and demanding
            'RELY' => 'VH', // 1.26 (very high reliability required)
            'DATA' => 'VH', // 1.28 (very high database size)
            'CPLX' => 'VH', // 1.34 (very high complexity)
            'RUSE' => 'H',  // 1.09 (high reusability)
            'DOCU' => 'H',  // 1.09 (high documentation)
            'TIME' => 'H',  // 1.11 (high time constraints)
            'STOR' => 'H',  // 1.05 (high storage constraints)
            'PVOL' => 'H',  // 1.29 (high platform volatility)
            'ACAP' => 'L',  // 1.19 (low analyst capability)
            'PCAP' => 'L',  // 1.14 (low programmer capability)
            'AEXP' => 'L',  // 1.10 (low application experience)
            'LTEX' => 'L',  // 1.09 (low language/tool experience)
            'PCON' => 'L',  // 1.12 (low personnel continuity)
            'TOOL' => 'L',  // 1.09 (low tool usage)
            'SITE' => 'L',  // 1.09 (low multisite development)
            'SCED' => 'H'   // 1.00 (high schedule pressure)
        ];

        // This should result in high effort due to:
        // - Large size (100 KLOC)
        // - Unfavorable scale factors (high SF sum)
        // - Multiple challenging cost drivers (EAF > 1)

        // Act: Calculate estimates
        $result = $this->cocomoService->calculateEstimates($project, $inputData);

        // Assert: Should require significant effort due to complexity
        $this->assertGreaterThan(1000, $result->calculated_effort, 'Large complex project should require significant effort');
        $this->assertGreaterThan(20, $result->calculated_schedule, 'Large project should take considerable time');
        $this->assertGreaterThan(10, $result->calculated_personnel, 'Large project should require many people');
    }

    /**
     * Test Case 4: Edge Case - Very Small Project
     * This test validates the minimum viable project calculation
     */
    public function test_cocomo_calculation_very_small_project()
    {
        // Arrange: Create a very small project with 0.5 KLOC
        $project = Project::create([
            'name' => 'Very Small Project',
            'kloc' => 0.5
        ]);

        $inputData = [
            'project_name' => 'Very Small Project',
            'kloc' => 0.5,
            // All nominal values
            'PREC' => 'N', 'FLEX' => 'N', 'RESL' => 'N', 'TEAM' => 'N', 'PMAT' => 'N',
            'RELY' => 'N', 'DATA' => 'N', 'CPLX' => 'N', 'RUSE' => 'N', 'DOCU' => 'N',
            'TIME' => 'N', 'STOR' => 'N', 'PVOL' => 'N', 'ACAP' => 'N', 'PCAP' => 'N',
            'AEXP' => 'N', 'LTEX' => 'N', 'PCON' => 'N', 'TOOL' => 'N', 'SITE' => 'N', 'SCED' => 'N'
        ];

        // Act: Calculate estimates
        $result = $this->cocomoService->calculateEstimates($project, $inputData);

        // Assert: Should be reasonable for a very small project
        $this->assertLessThan(10, $result->calculated_effort, 'Very small project should require minimal effort');
        $this->assertLessThan(10, $result->calculated_schedule, 'Very small project should be quick');
        $this->assertLessThan(5, $result->calculated_personnel, 'Very small project should require few people');
        $this->assertGreaterThan(0, $result->calculated_effort, 'Effort should be positive');
    }
}