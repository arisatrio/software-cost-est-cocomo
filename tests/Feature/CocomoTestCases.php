<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Services\CocomoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CocomoTestCases extends TestCase
{
    use RefreshDatabase;

    private CocomoService $cocomoService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cocomoService = new CocomoService();
    }

    /**
     * TEST CASE 1: Standard Project - All Nominal Values
     * Expected Results: 35.8 PM, 9.99 months, 3.58 people
     */
    public function test_case_1_standard_project_all_nominal()
    {
        $project = Project::create(['name' => 'Standard Project', 'kloc' => 10]);

        $inputData = [
            'project_name' => 'Standard Project',
            'kloc' => 10,
            // Scale Factors - All Nominal
            'PREC' => 'N', 'FLEX' => 'N', 'RESL' => 'N', 'TEAM' => 'N', 'PMAT' => 'N',
            // Cost Drivers - All Nominal
            'RELY' => 'N', 'DATA' => 'N', 'CPLX' => 'N', 'RUSE' => 'N', 'DOCU' => 'N',
            'TIME' => 'N', 'STOR' => 'N', 'PVOL' => 'N', 'ACAP' => 'N', 'PCAP' => 'N',
            'AEXP' => 'N', 'LTEX' => 'N', 'PCON' => 'N', 'TOOL' => 'N', 'SITE' => 'N', 'SCED' => 'N'
        ];

        $result = $this->cocomoService->calculateEstimates($project, $inputData);

        // Verify against actual calculated values
        $this->assertEqualsWithDelta(35.8, $result->calculated_effort, 0.1);
        $this->assertEqualsWithDelta(9.99, $result->calculated_schedule, 0.1);
        $this->assertEqualsWithDelta(3.58, $result->calculated_personnel, 0.1);

        echo "\nTEST CASE 1 - Standard Project (10 KLOC, All Nominal):\n";
        echo sprintf("Effort: %.1f person-months\n", $result->calculated_effort);
        echo sprintf("Schedule: %.1f months\n", $result->calculated_schedule);
        echo sprintf("Personnel: %.1f people\n", $result->calculated_personnel);
    }

    /**
     * TEST CASE 2: High-Capability Team on Simple Project
     * Favorable conditions should reduce effort
     */
    public function test_case_2_high_capability_simple_project()
    {
        $project = Project::create(['name' => 'Simple Project', 'kloc' => 5]);

        $inputData = [
            'project_name' => 'Simple Project',
            'kloc' => 5,
            // Scale Factors - Favorable (High experience, good team)
            'PREC' => 'VH', // 1.24 - Very familiar with this type
            'FLEX' => 'H',  // 2.03 - High flexibility
            'RESL' => 'H',  // 2.19 - Well understood requirements
            'TEAM' => 'VH', // 0.94 - Excellent team cohesion
            'PMAT' => 'H',  // 3.12 - Mature processes
            // Cost Drivers - High capability team
            'RELY' => 'L',  // 0.92 - Lower reliability needed
            'DATA' => 'N',  // 1.00 - Normal database
            'CPLX' => 'L',  // 0.87 - Low complexity
            'RUSE' => 'N',  // 1.00 - Normal reuse
            'DOCU' => 'N',  // 1.00 - Normal documentation
            'TIME' => 'N',  // 1.00 - Normal time constraints
            'STOR' => 'N',  // 1.00 - Normal storage
            'PVOL' => 'L',  // 0.87 - Stable platform
            'ACAP' => 'VH', // 0.71 - Very high analyst capability
            'PCAP' => 'VH', // 0.81 - Very high programmer capability
            'AEXP' => 'VH', // 0.81 - Very high application experience
            'LTEX' => 'H',  // 0.91 - High language/tool experience
            'PCON' => 'VH', // 0.81 - Very high personnel continuity
            'TOOL' => 'VH', // 0.78 - Very high tool usage
            'SITE' => 'N',  // 1.00 - Normal site
            'SCED' => 'N'   // 1.00 - Normal schedule
        ];

        $result = $this->cocomoService->calculateEstimates($project, $inputData);

        // Should be much less effort than standard case
        $this->assertLessThan(15, $result->calculated_effort, 'High-capability team should reduce effort significantly');
        $this->assertLessThan(8, $result->calculated_schedule, 'Should complete faster');
        $this->assertGreaterThan(0.5, $result->calculated_personnel, 'Should need at least some people');

        echo "\nTEST CASE 2 - High-Capability Simple Project (5 KLOC):\n";
        echo sprintf("Effort: %.1f person-months\n", $result->calculated_effort);
        echo sprintf("Schedule: %.1f months\n", $result->calculated_schedule);
        echo sprintf("Personnel: %.1f people\n", $result->calculated_personnel);
    }

    /**
     * TEST CASE 3: Large Complex Enterprise Project
     * Challenging conditions should increase effort significantly
     */
    public function test_case_3_large_complex_enterprise_project()
    {
        $project = Project::create(['name' => 'Enterprise System', 'kloc' => 50]);

        $inputData = [
            'project_name' => 'Enterprise System',
            'kloc' => 50,
            // Scale Factors - Challenging conditions
            'PREC' => 'VL', // 6.20 - New type of system
            'FLEX' => 'L',  // 4.05 - Low flexibility due to regulations
            'RESL' => 'L',  // 4.38 - Unclear requirements
            'TEAM' => 'L',  // 3.76 - Distributed team
            'PMAT' => 'L',  // 6.24 - Immature processes
            // Cost Drivers - High demands and constraints
            'RELY' => 'VH', // 1.26 - Mission-critical reliability
            'DATA' => 'VH', // 1.28 - Large database
            'CPLX' => 'VH', // 1.34 - Very high complexity
            'RUSE' => 'H',  // 1.09 - High reusability requirements
            'DOCU' => 'VH', // 1.20 - Extensive documentation needed
            'TIME' => 'H',  // 1.11 - Performance critical
            'STOR' => 'H',  // 1.05 - Memory constraints
            'PVOL' => 'H',  // 1.29 - Changing platform
            'ACAP' => 'L',  // 1.19 - Limited analyst capability
            'PCAP' => 'L',  // 1.14 - Limited programmer capability
            'AEXP' => 'VL', // 1.22 - New application domain
            'LTEX' => 'L',  // 1.09 - Limited tool experience
            'PCON' => 'VL', // 1.25 - High turnover
            'TOOL' => 'L',  // 1.09 - Limited tools
            'SITE' => 'L',  // 1.09 - Multi-site development
            'SCED' => 'H'   // 1.00 - Accelerated schedule
        ];

        $result = $this->cocomoService->calculateEstimates($project, $inputData);

        // Should require significant effort due to size and complexity
        $this->assertGreaterThan(300, $result->calculated_effort, 'Large complex project should require substantial effort');
        $this->assertGreaterThan(15, $result->calculated_schedule, 'Should take considerable time');
        $this->assertGreaterThan(8, $result->calculated_personnel, 'Should require substantial team');

        echo "\nTEST CASE 3 - Large Complex Enterprise Project (50 KLOC):\n";
        echo sprintf("Effort: %.1f person-months\n", $result->calculated_effort);
        echo sprintf("Schedule: %.1f months\n", $result->calculated_schedule);
        echo sprintf("Personnel: %.1f people\n", $result->calculated_personnel);
    }

    /**
     * TEST CASE 4: Small Prototype/Research Project
     * Very small project with some unknowns
     */
    public function test_case_4_small_prototype_project()
    {
        $project = Project::create(['name' => 'Research Prototype', 'kloc' => 1]);

        $inputData = [
            'project_name' => 'Research Prototype',
            'kloc' => 1,
            // Scale Factors - Mixed (research nature)
            'PREC' => 'L',  // 4.96 - Some unknowns in research
            'FLEX' => 'VH', // 1.01 - Very flexible approach
            'RESL' => 'L',  // 4.38 - Research uncertainties
            'TEAM' => 'H',  // 1.88 - Good small team
            'PMAT' => 'N',  // 4.68 - Standard processes
            // Cost Drivers - Prototype characteristics
            'RELY' => 'L',  // 0.92 - Lower reliability for prototype
            'DATA' => 'L',  // 0.90 - Small dataset
            'CPLX' => 'H',  // 1.17 - Research complexity
            'RUSE' => 'L',  // 0.91 - Limited reuse
            'DOCU' => 'L',  // 0.91 - Minimal documentation
            'TIME' => 'N',  // 1.00 - Normal timing
            'STOR' => 'N',  // 1.00 - Normal storage
            'PVOL' => 'N',  // 1.00 - Stable platform
            'ACAP' => 'H',  // 0.85 - Good analyst (researcher)
            'PCAP' => 'H',  // 0.90 - Good programmer
            'AEXP' => 'L',  // 1.10 - New research area
            'LTEX' => 'N',  // 1.00 - Standard tools
            'PCON' => 'H',  // 0.88 - Stable small team
            'TOOL' => 'H',  // 0.90 - Good development tools
            'SITE' => 'N',  // 1.00 - Single site
            'SCED' => 'N'   // 1.00 - Normal schedule
        ];

        $result = $this->cocomoService->calculateEstimates($project, $inputData);

        // Very small project should have minimal requirements
        $this->assertLessThan(5, $result->calculated_effort, 'Small prototype should require minimal effort');
        $this->assertLessThan(6, $result->calculated_schedule, 'Should be quick to develop');
        $this->assertLessThan(2, $result->calculated_personnel, 'Should need very few people');

        echo "\nTEST CASE 4 - Small Research Prototype (1 KLOC):\n";
        echo sprintf("Effort: %.1f person-months\n", $result->calculated_effort);
        echo sprintf("Schedule: %.1f months\n", $result->calculated_schedule);
        echo sprintf("Personnel: %.1f people\n", $result->calculated_personnel);
    }

    /**
     * TEST CASE 5: Maintenance Project 
     * Using existing system with good team
     */
    public function test_case_5_maintenance_project()
    {
        $project = Project::create(['name' => 'System Maintenance', 'kloc' => 15]);

        $inputData = [
            'project_name' => 'System Maintenance',
            'kloc' => 15,
            // Scale Factors - Maintenance advantages
            'PREC' => 'VH', // 1.24 - Very familiar with existing system
            'FLEX' => 'N',  // 3.04 - Normal flexibility
            'RESL' => 'VH', // 1.10 - Well understood system
            'TEAM' => 'VH', // 0.94 - Experienced maintenance team
            'PMAT' => 'H',  // 3.12 - Established processes
            // Cost Drivers - Maintenance characteristics
            'RELY' => 'H',  // 1.10 - Must maintain reliability
            'DATA' => 'N',  // 1.00 - Existing database
            'CPLX' => 'N',  // 1.00 - Known complexity
            'RUSE' => 'H',  // 1.09 - Reusing existing components
            'DOCU' => 'H',  // 1.09 - Good existing documentation
            'TIME' => 'N',  // 1.00 - Normal performance
            'STOR' => 'N',  // 1.00 - Known storage needs
            'PVOL' => 'L',  // 0.87 - Stable existing platform
            'ACAP' => 'H',  // 0.85 - Experienced analysts
            'PCAP' => 'H',  // 0.90 - Experienced programmers
            'AEXP' => 'VH', // 0.81 - Very experienced with this app
            'LTEX' => 'VH', // 0.84 - Very experienced with tools
            'PCON' => 'VH', // 0.81 - Stable maintenance team
            'TOOL' => 'H',  // 0.90 - Good maintenance tools
            'SITE' => 'N',  // 1.00 - Single site
            'SCED' => 'N'   // 1.00 - Normal schedule
        ];

        $result = $this->cocomoService->calculateEstimates($project, $inputData);

        // Maintenance should be more efficient than new development
        $this->assertLessThan(40, $result->calculated_effort, 'Maintenance should be more efficient');
        $this->assertGreaterThan(5, $result->calculated_effort, 'Still requires significant effort');

        echo "\nTEST CASE 5 - System Maintenance (15 KLOC):\n";
        echo sprintf("Effort: %.1f person-months\n", $result->calculated_effort);
        echo sprintf("Schedule: %.1f months\n", $result->calculated_schedule);
        echo sprintf("Personnel: %.1f people\n", $result->calculated_personnel);
    }
}