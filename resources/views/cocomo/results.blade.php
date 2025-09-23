
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Software Cost Estimation Result using COCOMO') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Project Summary Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Project Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700">Nama Project</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $project->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700">Bahasa Pemrograman</label>
                            <p class="mt-1 text-sm text-gray-900">{{ ucfirst($project->programming_language ?? 'N/A') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700">Function Points</label>
                            <p class="mt-1 text-sm text-gray-900">{{ number_format($project->function_points ?? 0, 1) }} FP</p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700">Total SLOC</label>
                            <p class="mt-1 text-sm text-gray-900">{{ number_format($project->total_sloc ?? 0) }} SLOC</p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700">Ukuran Project (KLOC)</label>
                            <p class="mt-1 text-sm text-gray-900">{{ number_format($project->kloc, 2) }} KLOC</p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700">Kompleksitas</label>
                            <p class="mt-1 text-sm text-gray-900">{{ ucfirst($project->complexity_factor ?? 'N/A') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700">Tanggal Estimasi</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $project->created_at->format('d F Y, H:i') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700">ID Project</label>
                            <p class="mt-1 text-sm text-gray-900">#{{ $project->id }}</p>
                        </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Status</label>
                                <p class="mt-1 text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $project->status ?? 'planning')) }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Tanggal Mulai Aktual</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $project->actual_start_date ? $project->actual_start_date->format('d F Y') : '-' }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Tanggal Selesai Aktual</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $project->actual_end_date ? $project->actual_end_date->format('d F Y') : '-' }}
                                </p>
                            </div>
                    </div>
                </div>
            </div>

            <!-- Project Category Information -->
            @if($project->project_category)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Project Category</h3>
                        
                        <!-- Category Badge and Description -->
                        <div class="mb-4">
                            <div class="flex items-center mb-3">
                                <span class="inline-flex px-4 py-2 text-sm font-semibold rounded-full {{ $project->project_category->colorClass() }}">
                                    {{ $project->project_category->label() }}
                                </span>
                            </div>
                            <p class="text-gray-700">{{ $project->project_category->description() }}</p>
                        </div>

                        <!-- Category Characteristics -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                            @foreach($project->project_category->characteristics() as $characteristic)
                                <div class="flex items-start p-3 bg-gray-50 rounded-lg">
                                    <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm text-gray-700">{{ $characteristic }}</span>
                                </div>
                            @endforeach
                        </div>

                        <!-- COCOMO II Coefficients -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                </svg>
                                Koefisien COCOMO II
                            </h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @php $coefficients = $project->project_category->coefficients(); @endphp
                                <div class="text-center">
                                    <div class="text-lg font-bold text-blue-600">{{ $coefficients['A'] }}</div>
                                    <div class="text-sm text-gray-600">Koefisien A</div>
                                    <div class="text-xs text-gray-500">Effort coefficient</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-lg font-bold text-green-600">{{ $coefficients['B'] }}</div>
                                    <div class="text-sm text-gray-600">Eksponen B</div>
                                    <div class="text-xs text-gray-500">Effort exponent</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-lg font-bold text-purple-600">{{ $coefficients['C'] }}</div>
                                    <div class="text-sm text-gray-600">Koefisien C</div>
                                    <div class="text-xs text-gray-500">Schedule coefficient</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-lg font-bold text-orange-600">{{ $coefficients['D'] }}</div>
                                    <div class="text-sm text-gray-600">Eksponen D</div>
                                    <div class="text-xs text-gray-500">Schedule exponent</div>
                                </div>
                            </div>
                            
                            <!-- Formula Display -->
                            {{-- <div class="mt-4 p-3 bg-white border border-gray-200 rounded-lg">
                                <div class="text-sm text-gray-700">
                                    <div class="mb-2">
                                        <strong>Formula yang digunakan:</strong>
                                    </div>
                                    <div class="font-mono text-xs bg-gray-100 p-2 rounded">
                                        <div>Effort = A × Size<sup>B</sup> × ∏(Cost Drivers)</div>
                                        <div class="mt-1">Schedule = C × Effort<sup>D</sup></div>
                                    </div>
                                    <div class="mt-2 text-xs text-gray-600">
                                        Dengan A={{ $coefficients['A'] }}, B={{ $coefficients['B'] }}, C={{ $coefficients['C'] }}, D={{ $coefficients['D'] }}
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            @endif

            <!-- Function Point Breakdown Detail -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Function Point Components</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">{{ $project->external_inputs ?? 0 }}</div>
                            <div class="text-sm text-gray-600">External Inputs</div>
                            <div class="text-xs text-gray-500">Form input, transaksi</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">{{ $project->external_outputs ?? 0 }}</div>
                            <div class="text-sm text-gray-600">External Outputs</div>
                            <div class="text-xs text-gray-500">Laporan, dokumen</div>
                        </div>
                        <div class="text-center p-4 bg-yellow-50 rounded-lg">
                            <div class="text-2xl font-bold text-yellow-600">{{ $project->external_inquiries ?? 0 }}</div>
                            <div class="text-sm text-gray-600">External Inquiries</div>
                            <div class="text-xs text-gray-500">Query, pencarian</div>
                        </div>
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600">{{ $project->internal_files ?? 0 }}</div>
                            <div class="text-sm text-gray-600">Internal Files</div>
                            <div class="text-xs text-gray-500">Database internal</div>
                        </div>
                        <div class="text-center p-4 bg-red-50 rounded-lg">
                            <div class="text-2xl font-bold text-red-600">{{ $project->external_files ?? 0 }}</div>
                            <div class="text-sm text-gray-600">External Files</div>
                            <div class="text-xs text-gray-500">API, web service</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Project Size Summary Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Ukuran Project</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">{{ number_format($project->function_points ?? 0, 1) }}</div>
                            <div class="text-sm font-medium text-gray-700">Function Points</div>
                            <div class="text-xs text-gray-500">Ukuran fungsional</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">{{ number_format($project->total_sloc ?? 0) }}</div>
                            <div class="text-sm font-medium text-gray-700">SLOC</div>
                            <div class="text-xs text-gray-500">Source Lines of Code</div>
                        </div>
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600">{{ number_format($project->kloc, 2) }}</div>
                            <div class="text-sm font-medium text-gray-700">KLOC</div>
                            <div class="text-xs text-gray-500">Kilo Lines of Code</div>
                        </div>
                        <div class="text-center p-4 bg-yellow-50 rounded-lg">
                            @php
                                $languageFactors = [
                                    'java' => 53, 'csharp' => 54, 'cpp' => 55, 'python' => 27,
                                    'javascript' => 47, 'php' => 36, 'ruby' => 25, 'swift' => 75,
                                    'kotlin' => 50, 'go' => 45
                                ];
                                $factor = $languageFactors[$project->programming_language] ?? 0;
                            @endphp
                            <div class="text-2xl font-bold text-yellow-600">{{ $factor }}</div>
                            <div class="text-sm font-medium text-gray-700">SLOC/FP Ratio</div>
                            <div class="text-xs text-gray-500">{{ ucfirst($project->programming_language) }}</div>
                        </div>
                    </div>
                    
                    <!-- Conversion Explanation -->
                    <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Konversi:</span>
                            <div class="flex items-center space-x-2">
                                <span class="text-blue-600 font-medium">{{ number_format($project->function_points ?? 0, 1) }} FP</span>
                                <span class="text-gray-400">×</span>
                                <span class="text-yellow-600 font-medium">{{ $factor }}</span>
                                <span class="text-gray-400">=</span>
                                <span class="text-green-600 font-medium">{{ number_format($project->total_sloc ?? 0) }} SLOC</span>
                                <span class="text-gray-400">=</span>
                                <span class="text-purple-600 font-medium">{{ number_format($project->kloc, 2) }} KLOC</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                <!-- Effort Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900">Total Usaha</h4>
                                <p class="text-3xl font-bold text-blue-600">{{ number_format($project->calculated_effort, 1) }}</p>
                                <p class="text-sm text-gray-500">Person-Months</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Schedule Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900">Waktu Pengembangan</h4>
                                <p class="text-3xl font-bold text-green-600">{{ number_format($project->calculated_schedule, 1) }}</p>
                                <p class="text-sm text-gray-500">Bulan</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personnel Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900">Jumlah Personal</h4>
                                <p class="text-3xl font-bold text-purple-600">{{ number_format($project->calculated_personnel, 1) }}</p>
                                <p class="text-sm text-gray-500">Orang</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced COCOMO Parameters & Impact Analysis -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">COCOMO Parameters & Impact Analysis</h3>
                    
                    <!-- Impact Summary Cards -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        @php
                            $totalScaleFactors = count($scaleFactors);
                            $highRiskFactors = 0;
                            $lowRiskFactors = 0;
                            
                            foreach($scaleFactors as $rating) {
                                if(in_array($rating, ['VL', 'L'])) $highRiskFactors++;
                                if(in_array($rating, ['H', 'VH'])) $lowRiskFactors++;
                            }
                            
                            $totalCostDrivers = count($costDrivers);
                            $increasingEffort = 0;
                            $decreasingEffort = 0;
                            
                            foreach($costDrivers as $rating) {
                                if(in_array($rating, ['H', 'VH', 'XH'])) $increasingEffort++;
                                if(in_array($rating, ['VL', 'L'])) $decreasingEffort++;
                            }
                        @endphp
                        
                        <div class="text-center p-4 bg-red-50 rounded-lg border border-red-200">
                            <div class="text-2xl font-bold text-red-600">{{ $highRiskFactors }}</div>
                            <div class="text-sm text-red-700">High Risk Factors</div>
                            <div class="text-xs text-red-600 mt-1">Need attention</div>
                        </div>
                        <div class="text-center p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                            <div class="text-2xl font-bold text-yellow-600">{{ $increasingEffort }}</div>
                            <div class="text-sm text-yellow-700">Effort Increasers</div>
                            <div class="text-xs text-yellow-600 mt-1">Cost drivers</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                            <div class="text-2xl font-bold text-green-600">{{ $lowRiskFactors }}</div>
                            <div class="text-sm text-green-700">Low Risk Factors</div>
                            <div class="text-xs text-green-600 mt-1">Favorable conditions</div>
                        </div>
                        <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <div class="text-2xl font-bold text-blue-600">{{ $decreasingEffort }}</div>
                            <div class="text-sm text-blue-700">Effort Reducers</div>
                            <div class="text-xs text-blue-600 mt-1">Positive factors</div>
                        </div>
                    </div>
                    
                    <!-- Tabs for Parameters -->
                    <div class="border-b border-gray-200 mb-6">
                        <nav class="-mb-px flex space-x-8">
                            <button onclick="showParameterTab('scale-factors')" id="scale-tab" 
                                    class="parameter-tab-button border-blue-500 text-blue-600 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                                Scale Factors ({{ $totalScaleFactors }})
                            </button>
                            <button onclick="showParameterTab('cost-drivers')" id="cost-tab" 
                                    class="parameter-tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                                Cost Drivers ({{ $totalCostDrivers }})
                            </button>
                        </nav>
                    </div>

                    <!-- Scale Factors Tab -->
                    <div id="scale-factors-tab" class="parameter-tab-content">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            @foreach(App\Enums\ScaleFactor::cases() as $factor)
                                @php
                                    $rating = $scaleFactors[$factor->value] ?? 'N';
                                    $impact = match($rating) {
                                        'VL' => ['level' => 'High Risk', 'color' => 'red', 'desc' => 'Significantly increases complexity'],
                                        'L' => ['level' => 'Medium Risk', 'color' => 'orange', 'desc' => 'Moderately increases effort'],
                                        'N' => ['level' => 'Balanced', 'color' => 'gray', 'desc' => 'Standard industry average'],
                                        'H' => ['level' => 'Low Risk', 'color' => 'blue', 'desc' => 'Favorable conditions'],
                                        'VH' => ['level' => 'Very Low Risk', 'color' => 'green', 'desc' => 'Optimal conditions'],
                                        default => ['level' => 'Balanced', 'color' => 'gray', 'desc' => 'Standard conditions']
                                    };
                                    $ratingText = match($rating) {
                                        'VL' => 'Very Low', 'L' => 'Low', 'N' => 'Nominal',
                                        'H' => 'High', 'VH' => 'Very High', default => 'Nominal'
                                    };
                                @endphp
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-all duration-200 hover:border-blue-300">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex-1">
                                            <div class="flex items-center mb-2">
                                                <h4 class="font-semibold text-gray-900 ml-2">{{ $factor->label() }}</h4>
                                            </div>
                                            <p class="text-sm text-gray-600 mb-2">{{ $impact['desc'] }}</p>
                                        </div>
                                        <div class="text-right ml-4">
                                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium
                                                {{ $impact['color'] === 'red' ? 'bg-red-100 text-red-800' :
                                                   ($impact['color'] === 'orange' ? 'bg-orange-100 text-orange-800' :
                                                   ($impact['color'] === 'gray' ? 'bg-gray-100 text-gray-800' :
                                                   ($impact['color'] === 'blue' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'))) }}">
                                                {{ $ratingText }}
                                            </span>
                                            <div class="text-xs text-gray-500 mt-1">{{ $impact['level'] }}</div>
                                        </div>
                                    </div>
                                    
                                    <!-- Rating Scale Visual -->
                                    <div class="flex items-center space-x-1">
                                        @foreach(['VL', 'L', 'N', 'H', 'VH'] as $level)
                                            <div class="flex-1 h-2 rounded 
                                                {{ $level === $rating ? 
                                                   ($impact['color'] === 'red' ? 'bg-red-500' :
                                                    ($impact['color'] === 'orange' ? 'bg-orange-500' :
                                                    ($impact['color'] === 'gray' ? 'bg-gray-500' :
                                                    ($impact['color'] === 'blue' ? 'bg-blue-500' : 'bg-green-500')))) : 'bg-gray-200' }}">
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="flex justify-between text-xs text-gray-400 mt-1">
                                        <span>VL</span><span>L</span><span>N</span><span>H</span><span>VH</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Cost Drivers Tab -->
                    <div id="cost-drivers-tab" class="parameter-tab-content hidden">
                        <!-- Category-based grouping -->
                        <div class="space-y-6">
                            @foreach([
                                'Product' => ['RELY', 'DATA', 'CPLX', 'RUSE', 'DOCU'], 
                                'Platform' => ['TIME', 'STOR', 'PVOL'], 
                                'Personnel' => ['ACAP', 'PCAP', 'AEXP', 'LTEX', 'PCON'], 
                                'Project' => ['TOOL', 'SITE', 'SCED']
                            ] as $category => $driverCodes)
                                <div>
                                    <h4 class="text-md font-semibold text-gray-800 mb-3 flex items-center">
                                        @if($category === 'Product')
                                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                            </svg>
                                        @elseif($category === 'Platform')
                                            <svg class="w-5 h-5 mr-2 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd"/>
                                            </svg>
                                        @elseif($category === 'Personnel')
                                            <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 mr-2 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.532 1.532 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                                            </svg>
                                        @endif
                                        {{ $category }} Factors
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                        @foreach($driverCodes as $driverCode)
                                            @php
                                                $driver = collect(App\Enums\CostDriver::cases())->first(fn($d) => $d->value === $driverCode);
                                                if (!$driver) continue;
                                                
                                                $rating = $costDrivers[$driver->value] ?? 'N';
                                                $ratingText = match($rating) {
                                                    'VL' => 'Very Low', 'L' => 'Low', 'N' => 'Nominal',
                                                    'H' => 'High', 'VH' => 'Very High', 'XH' => 'Extra High',
                                                    default => 'Nominal'
                                                };
                                                
                                                // Simplified impact calculation (you may want to use actual multiplier values)
                                                $impact = in_array($rating, ['H', 'VH', 'XH']) ? 'Increases Effort' : 
                                                         (in_array($rating, ['VL', 'L']) ? 'Reduces Effort' : 'Neutral');
                                                $impactColor = in_array($rating, ['H', 'VH', 'XH']) ? 'red' : 
                                                              (in_array($rating, ['VL', 'L']) ? 'green' : 'gray');
                                            @endphp
                                            <div class="border border-gray-200 rounded-lg p-3 hover:shadow-sm transition-all duration-200 hover:border-blue-300">
                                                <div class="flex items-center justify-between mb-2">
                                                    <h5 class="font-medium text-sm text-gray-900">{{ $driver->label() }}</h5>
                                                    <span class="text-xs font-mono text-gray-500">{{ $driverCode }}</span>
                                                </div>
                                                <div class="flex items-center justify-between">
                                                    <span class="inline-flex px-2 py-1 rounded text-xs font-medium
                                                        {{ $impactColor === 'red' ? 'bg-red-100 text-red-800' :
                                                           ($impactColor === 'green' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                                        {{ $ratingText }}
                                                    </span>
                                                    <div class="text-xs text-gray-500">{{ $impact }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <script>
                function showParameterTab(tabName) {
                    // Hide all parameter tabs
                    document.querySelectorAll('.parameter-tab-content').forEach(tab => tab.classList.add('hidden'));
                    document.querySelectorAll('.parameter-tab-button').forEach(btn => {
                        btn.classList.remove('border-blue-500', 'text-blue-600');
                        btn.classList.add('border-transparent', 'text-gray-500');
                    });
                    
                    // Show selected tab
                    document.getElementById(tabName + '-tab').classList.remove('hidden');
                    const activeButton = document.getElementById(tabName.split('-')[0] + '-tab');
                    if (activeButton) {
                        activeButton.classList.add('border-blue-500', 'text-blue-600');
                        activeButton.classList.remove('border-transparent', 'text-gray-500');
                    }
                }
            </script>

            <!-- Enhanced Project Analysis & Insights -->
            <div class="mt-4 mb-4 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Project Analysis & Strategic Insights</h3>
                    
                    @php
                        $avgTeamSize = $project->calculated_personnel;
                        $schedule = $project->calculated_schedule;
                        $effort = $project->calculated_effort;
                        $complexity = $project->complexity_factor ?? 'medium';
                        $kloc = $project->kloc;
                        
                        // Calculate project characteristics
                        $projectSize = $kloc < 2 ? 'small' : ($kloc < 8 ? 'medium' : ($kloc < 32 ? 'large' : 'very_large'));
                        $scheduleIntensity = $schedule < 3 ? 'aggressive' : ($schedule < 8 ? 'balanced' : ($schedule > 18 ? 'extended' : 'standard'));
                        $teamEfficiency = $avgTeamSize <= 3 ? 'high' : ($avgTeamSize <= 7 ? 'medium' : 'complex');
                        
                        // Risk factors assessment
                        $risks = [];
                        $opportunities = [];
                        
                        if ($scheduleIntensity === 'aggressive') $risks[] = 'Timeline sangat ketat - risiko burnout tinggi';
                        if ($scheduleIntensity === 'extended') $risks[] = 'Timeline terlalu panjang - risiko scope creep';
                        if ($avgTeamSize > 8) $risks[] = 'Tim besar - kompleksitas komunikasi tinggi';
                        if ($complexity === 'high') $risks[] = 'Kompleksitas teknis tinggi - butuh expertise';
                        if ($kloc > 20) $risks[] = 'Project besar - butuh manajemen formal';
                        
                        if ($teamEfficiency === 'high') $opportunities[] = 'Tim kecil - komunikasi efisien';
                        if ($scheduleIntensity === 'balanced') $opportunities[] = 'Timeline realistis - kualitas terjaga';
                        if ($projectSize === 'small') $opportunities[] = 'Project kecil - fleksibilitas tinggi';
                        
                        // Calculate productivity metrics
                        $locPerPersonMonth = $effort > 0 ? round(($kloc * 1000) / $effort, 0) : 0;
                        $industryAverage = 2000; // LOC per person-month industry average
                        $productivityLevel = $locPerPersonMonth > $industryAverage * 1.2 ? 'high' : 
                                           ($locPerPersonMonth < $industryAverage * 0.8 ? 'low' : 'average');
                    @endphp
                    
                    <!-- Project Overview Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <div class="text-sm font-medium text-blue-700 mb-1">Project Scale</div>
                            <div class="text-lg font-bold text-blue-600">
                                {{ ucfirst(str_replace('_', ' ', $projectSize)) }}
                            </div>
                            <div class="text-xs text-blue-600">{{ number_format($kloc, 1) }} KLOC</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                            <div class="text-sm font-medium text-green-700 mb-1">Schedule Type</div>
                            <div class="text-lg font-bold text-green-600">
                                {{ ucfirst($scheduleIntensity) }}
                            </div>
                            <div class="text-xs text-green-600">{{ number_format($schedule, 1) }} months</div>
                        </div>
                        <div class="text-center p-4 bg-purple-50 rounded-lg border border-purple-200">
                            <div class="text-sm font-medium text-purple-700 mb-1">Team Profile</div>
                            <div class="text-lg font-bold text-purple-600">
                                {{ ucfirst($teamEfficiency) }} Efficiency
                            </div>
                            <div class="text-xs text-purple-600">{{ number_format($avgTeamSize, 1) }} people</div>
                        </div>
                        <div class="text-center p-4 bg-orange-50 rounded-lg border border-orange-200">
                            <div class="text-sm font-medium text-orange-700 mb-1">Productivity</div>
                            <div class="text-lg font-bold text-orange-600">
                                {{ ucfirst($productivityLevel) }}
                            </div>
                            <div class="text-xs text-orange-600">{{ number_format($locPerPersonMonth) }} LOC/PM</div>
                        </div>
                    </div>
                    
                    <!-- Development Timeline & Milestones -->
                    <div class="bg-gray-50 border border-gray-200 p-4 rounded-lg">
                        <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            Development Timeline & Milestones
                        </h4>
                        @php
                            $totalSchedule = $project->calculated_schedule;
                            
                            // Adjust phase distribution based on project characteristics
                            if ($complexity === 'high' || $projectSize === 'large') {
                                $analysisPhase = round($totalSchedule * 0.20, 1);
                                $designPhase = round($totalSchedule * 0.30, 1);
                                $codingPhase = round($totalSchedule * 0.35, 1);
                                $testingPhase = round($totalSchedule * 0.15, 1);
                            } elseif ($projectSize === 'small') {
                                $analysisPhase = round($totalSchedule * 0.10, 1);
                                $designPhase = round($totalSchedule * 0.20, 1);
                                $codingPhase = round($totalSchedule * 0.50, 1);
                                $testingPhase = round($totalSchedule * 0.20, 1);
                            } else {
                                $analysisPhase = round($totalSchedule * 0.15, 1);
                                $designPhase = round($totalSchedule * 0.25, 1);
                                $codingPhase = round($totalSchedule * 0.40, 1);
                                $testingPhase = round($totalSchedule * 0.20, 1);
                            }
                        @endphp
                        
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700">Analysis & Planning:</span>
                                <span class="font-medium text-gray-800">{{ $analysisPhase }} months</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700">Design & Architecture:</span>
                                <span class="font-medium text-gray-800">{{ $designPhase }} months</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700">Implementation:</span>
                                <span class="font-medium text-gray-800">{{ $codingPhase }} months</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700">Testing & Deployment:</span>
                                <span class="font-medium text-gray-800">{{ $testingPhase }} months</span>
                            </div>
                            <div class="flex justify-between border-t border-gray-300 pt-2 mt-2">
                                <span class="font-semibold text-gray-700">Total Duration:</span>
                                <span class="font-bold text-gray-600">{{ number_format($totalSchedule, 1) }} months</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team & Schedule Adjustment Calculator (Collapsible) -->
            <div class="mt-4 mb-4 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 border-b border-gray-200">
                        <button onclick="toggleCalculator()" class="w-full flex items-center justify-between text-left">
                            <div class="flex items-center">
                                <h3 class="text-lg font-semibold text-gray-900">Hitung Penyesuaian Tim & Waktu</h3>
                            </div>
                            <svg id="calculator-chevron" class="w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <p class="text-sm text-gray-600 mt-1">Sesuaikan estimasi berdasarkan jumlah programmer atau waktu yang tersedia untuk Project Anda.</p>
                    </div>
                    
                    <div id="calculator-content" class="hidden">
                        <div class="p-6">
                            <!-- Calculator Header -->
                            <div class="mb-6 text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full mb-3">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-600">
                                    Estimasi awal: <span class="font-semibold text-blue-600">{{ number_format($project->calculated_effort, 1) }} person-months</span>
                                </p>
                            </div>

                            <!-- Calculator Options -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <!-- Resource-Based Planning -->
                                <div class="border border-gray-200 rounded-lg p-6 hover:border-blue-300 transition-colors">
                                    <div class="flex items-center mb-4">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                        </div>
                                        <h4 class="text-lg font-semibold text-gray-900">Resource-Based Planning</h4>
                                    </div>
                                    
                                    <p class="text-sm text-gray-600 mb-4">
                                        Calculate project duration based on available team size
                                    </p>
                                    
                                    <div class="space-y-3">
                                        <label class="block text-sm font-medium text-gray-700">
                                            Team Size (Developers)
                                        </label>
                                        <div class="relative">
                                            <input 
                                                type="number" 
                                                id="input-personnel" 
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-lg font-medium"
                                                min="1" 
                                                max="50"
                                                placeholder="e.g., 5"
                                                oninput="calculateFromPersonnel()"
                                            >
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        
                                        <div id="result-schedule" class="min-h-[60px] p-4 bg-green-50 border border-green-200 rounded-lg transition-all duration-200"></div>
                                    </div>
                                </div>
                                
                                <!-- Time-Based Planning -->
                                <div class="border border-gray-200 rounded-lg p-6 hover:border-blue-300 transition-colors">
                                    <div class="flex items-center mb-4">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <h4 class="text-lg font-semibold text-gray-900">Time-Based Planning</h4>
                                    </div>
                                    
                                    <p class="text-sm text-gray-600 mb-4">
                                        Calculate required team size based on project timeline
                                    </p>
                                    
                                    <div class="space-y-3">
                                        <label class="block text-sm font-medium text-gray-700">
                                            Available Timeline (Months)
                                        </label>
                                        <div class="relative">
                                            <input 
                                                type="number" 
                                                id="input-schedule" 
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-lg font-medium"
                                                min="0.5" 
                                                max="60"
                                                step="0.5" 
                                                placeholder="e.g., 6.0"
                                                oninput="calculateFromSchedule()"
                                            >
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        
                                        <div id="result-personnel" class="min-h-[60px] p-4 bg-blue-50 border border-blue-200 rounded-lg transition-all duration-200"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Information Footer -->
                            <div class="mt-8 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div class="text-sm text-gray-600">
                                        <p class="font-medium mb-1">Calculation Method</p>
                                        <p class="mb-2">
                                            <strong>Duration:</strong> Total Effort ÷ Team Size = Project Duration<br>
                                            <strong>Team Size:</strong> Total Effort ÷ Available Time = Required Team Size
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            <strong>Note:</strong> These calculations are estimates based on {{ number_format($project->calculated_effort, 1) }} person-months of effort. 
                                            Actual results may vary due to team communication overhead, coordination complexity, and project-specific factors.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    function toggleCalculator() {
                        const content = document.getElementById('calculator-content');
                        const chevron = document.getElementById('calculator-chevron');
                        
                        if (content.classList.contains('hidden')) {
                            content.classList.remove('hidden');
                            chevron.style.transform = 'rotate(180deg)';
                        } else {
                            content.classList.add('hidden');
                            chevron.style.transform = 'rotate(0deg)';
                        }
                    }
                    
                    function calculateFromPersonnel() {
                        const effort = {{ $project->calculated_effort }};
                        const personnel = parseFloat(document.getElementById('input-personnel').value);
                        const resultDiv = document.getElementById('result-schedule');
                        
                        if (personnel && personnel > 0) {
                            const schedule = (effort / personnel).toFixed(1);
                            const efficiency = personnel <= 3 ? 'High' : personnel <= 7 ? 'Medium' : 'Lower';
                            const efficiencyColor = personnel <= 3 ? 'text-green-600' : personnel <= 7 ? 'text-blue-600' : 'text-yellow-600';
                            
                            resultDiv.innerHTML = `
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700">Estimated Duration</span>
                                    <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-800">Result</span>
                                </div>
                                <div class="text-2xl font-bold text-green-600 mb-1">${schedule} months</div>
                                <div class="text-sm text-gray-600">
                                    <div class="flex items-center justify-between">
                                        <span>Team Size: ${personnel} developers</span>
                                        <span class="text-xs ${efficiencyColor} font-medium">${efficiency} Efficiency</span>
                                    </div>
                                </div>
                            `;
                            
                            // Clear the other input and result
                            document.getElementById('input-schedule').value = '';
                            document.getElementById('result-personnel').innerHTML = '';
                        } else {
                            resultDiv.innerHTML = `
                                <div class="flex items-center justify-center h-12 text-gray-400">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-sm">Enter team size to calculate duration</span>
                                </div>
                            `;
                        }
                    }
                    
                    function calculateFromSchedule() {
                        const effort = {{ $project->calculated_effort }};
                        const schedule = parseFloat(document.getElementById('input-schedule').value);
                        const resultDiv = document.getElementById('result-personnel');
                        
                        if (schedule && schedule > 0) {
                            const personnel = Math.ceil(effort / schedule);
                            const timeframe = schedule <= 3 ? 'Aggressive' : schedule <= 8 ? 'Balanced' : 'Conservative';
                            const timeframeColor = schedule <= 3 ? 'text-red-600' : schedule <= 8 ? 'text-blue-600' : 'text-green-600';
                            
                            resultDiv.innerHTML = `
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700">Required Team Size</span>
                                    <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-800">Result</span>
                                </div>
                                <div class="text-2xl font-bold text-blue-600 mb-1">${personnel} developers</div>
                                <div class="text-sm text-gray-600">
                                    <div class="flex items-center justify-between">
                                        <span>Timeline: ${schedule} months</span>
                                        <span class="text-xs ${timeframeColor} font-medium">${timeframe} Schedule</span>
                                    </div>
                                </div>
                            `;
                            
                            // Clear the other input and result
                            document.getElementById('input-personnel').value = '';
                            document.getElementById('result-schedule').innerHTML = '';
                        } else {
                            resultDiv.innerHTML = `
                                <div class="flex items-center justify-center h-12 text-gray-400">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <span class="text-sm">Enter timeline to calculate team size</span>
                                </div>
                            `;
                        }
                    }
                    
                    // Initialize empty states
                    document.addEventListener('DOMContentLoaded', function() {
                        calculateFromPersonnel();
                        calculateFromSchedule();
                    });
                </script>
            
            <!-- Actual Data & Accuracy Section -->
            @if($project->actual_effort || $project->actual_schedule || $project->actual_personnel || $project->status !== 'planning')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-2">
                    <div class="p-4 pb-1">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-900">Data Aktual & Akurasi</h3>
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $project->status_badge_color }}">
                                {{ ucfirst(str_replace('_', ' ', $project->status ?? 'planning')) }}
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($project->actual_effort && $project->actual_schedule && $project->actual_personnel)
                            <!-- Comparison Table -->
                            <div class="overflow-x-auto mb-4">
                                <table class="w-full border-collapse border border-gray-300">
                                    <thead>
                                        <tr class="bg-gray-50">
                                            <th class="border border-gray-300 px-4 py-2 text-left font-semibold">Metrik</th>
                                            <th class="border border-gray-300 px-4 py-2 text-center font-semibold">Estimasi</th>
                                            <th class="border border-gray-300 px-4 py-2 text-center font-semibold">Aktual</th>
                                            <th class="border border-gray-300 px-4 py-2 text-center font-semibold">Akurasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="border border-gray-300 px-4 py-2 font-medium">Effort (Person-Months)</td>
                                            <td class="border border-gray-300 px-4 py-2 text-center">{{ number_format($project->calculated_effort, 1) }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-center">{{ number_format($project->actual_effort, 1) }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-center">
                                                @if($project->effort_accuracy)
                                                    <div class="flex flex-col items-center">
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                            {{ $project->effort_accuracy <= 0.25 ? 'bg-green-500 text-white' : 
                                                               ($project->effort_accuracy <= 0.50 ? 'bg-blue-500 text-white' : 
                                                               ($project->effort_accuracy <= 0.75 ? 'bg-yellow-500 text-white' : 'bg-red-500 text-white')) }}">
                                                            {{ number_format($project->effort_accuracy, 3) }}
                                                        </span>
                                                        <span class="text-xs mt-1 
                                                            {{ $project->effort_accuracy <= 0.25 ? 'text-green-600' : 
                                                               ($project->effort_accuracy <= 0.50 ? 'text-blue-600' : 
                                                               ($project->effort_accuracy <= 0.75 ? 'text-yellow-600' : 'text-red-600')) }}">
                                                            @if($project->effort_accuracy <= 0.25)
                                                                Excellent
                                                            @elseif($project->effort_accuracy <= 0.50)
                                                                Good
                                                            @elseif($project->effort_accuracy <= 0.75)
                                                                Fair
                                                            @else
                                                                Poor
                                                            @endif
                                                        </span>
                                                    </div>
                                                @else
                                                    <span class="text-gray-400">N/A</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="border border-gray-300 px-4 py-2 font-medium">Schedule (Bulan)</td>
                                            <td class="border border-gray-300 px-4 py-2 text-center">{{ number_format($project->calculated_schedule, 1) }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-center">{{ number_format($project->actual_schedule, 1) }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-center">
                                                @if($project->schedule_accuracy)
                                                    <div class="flex flex-col items-center">
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                            {{ $project->schedule_accuracy <= 0.25 ? 'bg-green-500 text-white' : 
                                                               ($project->schedule_accuracy <= 0.50 ? 'bg-blue-500 text-white' : 
                                                               ($project->schedule_accuracy <= 0.75 ? 'bg-yellow-500 text-white' : 'bg-red-500 text-white')) }}">
                                                            {{ number_format($project->schedule_accuracy, 3) }}
                                                        </span>
                                                        <span class="text-xs mt-1 
                                                            {{ $project->schedule_accuracy <= 0.25 ? 'text-green-600' : 
                                                               ($project->schedule_accuracy <= 0.50 ? 'text-blue-600' : 
                                                               ($project->schedule_accuracy <= 0.75 ? 'text-yellow-600' : 'text-red-600')) }}">
                                                            @if($project->schedule_accuracy <= 0.25)
                                                                Excellent
                                                            @elseif($project->schedule_accuracy <= 0.50)
                                                                Good
                                                            @elseif($project->schedule_accuracy <= 0.75)
                                                                Fair
                                                            @else
                                                                Poor
                                                            @endif
                                                        </span>
                                                    </div>
                                                @else
                                                    <span class="text-gray-400">N/A</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="border border-gray-300 px-4 py-2 font-medium">Personnel (Orang)</td>
                                            <td class="border border-gray-300 px-4 py-2 text-center">{{ number_format($project->calculated_personnel, 0) }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-center">{{ number_format($project->actual_personnel, 0) }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-center">
                                                @if($project->personnel_accuracy)
                                                    <div class="flex flex-col items-center">
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                            {{ $project->personnel_accuracy <= 0.25 ? 'bg-green-500 text-white' : 
                                                               ($project->personnel_accuracy <= 0.50 ? 'bg-blue-500 text-white' : 
                                                               ($project->personnel_accuracy <= 0.75 ? 'bg-yellow-500 text-white' : 'bg-red-500 text-white')) }}">
                                                            {{ number_format($project->personnel_accuracy, 3) }}
                                                        </span>
                                                        <span class="text-xs mt-1 
                                                            {{ $project->personnel_accuracy <= 0.25 ? 'text-green-600' : 
                                                               ($project->personnel_accuracy <= 0.50 ? 'text-blue-600' : 
                                                               ($project->personnel_accuracy <= 0.75 ? 'text-yellow-600' : 'text-red-600')) }}">
                                                            @if($project->personnel_accuracy <= 0.25)
                                                                Excellent
                                                            @elseif($project->personnel_accuracy <= 0.50)
                                                                Good
                                                            @elseif($project->personnel_accuracy <= 0.75)
                                                                Fair
                                                            @else
                                                                Poor
                                                            @endif
                                                        </span>
                                                    </div>
                                                @else
                                                    <span class="text-gray-400">N/A</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @if($project->actual_sloc && $project->total_sloc)
                                            <tr>
                                                <td class="border border-gray-300 px-4 py-2 font-medium">SLOC</td>
                                                <td class="border border-gray-300 px-4 py-2 text-center">{{ number_format($project->total_sloc) }}</td>
                                                <td class="border border-gray-300 px-4 py-2 text-center">{{ number_format($project->actual_sloc) }}</td>
                                                <td class="border border-gray-300 px-4 py-2 text-center">
                                                    @if($project->sloc_accuracy)
                                                        <div class="flex flex-col items-center">
                                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                                {{ $project->sloc_accuracy <= 0.25 ? 'bg-green-500 text-white' : 
                                                                   ($project->sloc_accuracy <= 0.50 ? 'bg-blue-500 text-white' : 
                                                                   ($project->sloc_accuracy <= 0.75 ? 'bg-yellow-500 text-white' : 'bg-red-500 text-white')) }}">
                                                                {{ number_format($project->sloc_accuracy, 3) }}
                                                            </span>
                                                            <span class="text-xs mt-1 
                                                                {{ $project->sloc_accuracy <= 0.25 ? 'text-green-600' : 
                                                                   ($project->sloc_accuracy <= 0.50 ? 'text-blue-600' : 
                                                                   ($project->sloc_accuracy <= 0.75 ? 'text-yellow-600' : 'text-red-600')) }}">
                                                                @if($project->sloc_accuracy <= 0.25)
                                                                    Excellent
                                                                @elseif($project->sloc_accuracy <= 0.50)
                                                                    Good
                                                                @elseif($project->sloc_accuracy <= 0.75)
                                                                    Fair
                                                                @else
                                                                    Poor
                                                                @endif
                                                            </span>
                                                        </div>
                                                    @else
                                                        <span class="text-gray-400">N/A</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            <!-- Overall Accuracy -->
                            @if($project->overall_accuracy)
                                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-lg font-semibold text-gray-900">MMRE Keseluruhan:</span>
                                        <span class="inline-flex px-3 py-1 text-lg font-bold rounded-full 
                                            {{ $project->overall_accuracy <= 0.25 ? 'bg-green-500 text-white' : 
                                               ($project->overall_accuracy <= 0.50 ? 'bg-blue-500 text-white' : 
                                               ($project->overall_accuracy <= 0.75 ? 'bg-yellow-500 text-white' : 'bg-red-500 text-white')) }}">
                                            {{ number_format($project->overall_accuracy, 3) }}
                                        </span>
                                    </div>
                                    
                                    <!-- MMRE Interpretation -->
                                    <div class="mt-3 p-3 border-l-4 
                                        {{ $project->overall_accuracy <= 0.25 ? 'border-green-500 bg-green-50' : 
                                           ($project->overall_accuracy <= 0.50 ? 'border-blue-500 bg-blue-50' : 
                                           ($project->overall_accuracy <= 0.75 ? 'border-yellow-500 bg-yellow-50' : 'border-red-500 bg-red-50')) }}">
                                        <p class="text-sm font-medium 
                                            {{ $project->overall_accuracy <= 0.25 ? 'text-green-800' : 
                                               ($project->overall_accuracy <= 0.50 ? 'text-blue-800' : 
                                               ($project->overall_accuracy <= 0.75 ? 'text-yellow-800' : 'text-red-800')) }}">
                                            Interpretasi:
                                            @if($project->overall_accuracy <= 0.25)
                                                <strong>Excellent (≤ 0.25)</strong> - Estimasi sangat akurat, model COCOMO bekerja dengan baik untuk Project ini.
                                            @elseif($project->overall_accuracy <= 0.50)
                                                <strong>Good (0.25 - 0.50)</strong> - Estimasi cukup baik, dapat diterima untuk kebanyakan Project software.
                                            @elseif($project->overall_accuracy <= 0.75)
                                                <strong>Fair (0.50 - 0.75)</strong> - Estimasi kurang akurat, perlu perbaikan model atau kalibrasi parameter.
                                            @else
                                                <strong>Poor (> 0.75)</strong> - Estimasi tidak akurat, kemungkinan ada masalah data atau model tidak cocok untuk Project ini.
                                            @endif
                                        </p>
                                        
                                        <!-- Additional Context -->
                                        <div class="mt-2 text-xs 
                                            {{ $project->overall_accuracy <= 0.25 ? 'text-green-700' : 
                                               ($project->overall_accuracy <= 0.50 ? 'text-blue-700' : 
                                               ($project->overall_accuracy <= 0.75 ? 'text-yellow-700' : 'text-red-700')) }}">
                                            @if($project->overall_accuracy <= 0.25)
                                                <strong>Rekomendasi:</strong> Model dapat digunakan dengan confidence tinggi untuk Project serupa.
                                            @elseif($project->overall_accuracy <= 0.50)
                                                <strong>Rekomendasi:</strong> Tambahkan buffer 10-20% pada estimasi untuk Project serupa.
                                            @elseif($project->overall_accuracy <= 0.75)
                                                <strong>Rekomendasi:</strong> Review dan kalibrasi parameter COCOMO, pertimbangkan faktor lokal yang belum diakomodasi.
                                            @else
                                                <strong>Rekomendasi:</strong> Investigasi penyebab error tinggi - cek data input, scope change, atau gunakan metode estimasi alternatif.
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- MMRE Interpretation Guide -->
                            @if($project->overall_accuracy)
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                                        <div class="flex items-center mb-2">
                                            <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                                            <span class="text-sm font-semibold text-green-800">Excellent (≤ 0.25)</span>
                                        </div>
                                        <p class="text-xs text-green-700">Error ≤ 25%. Estimasi sangat akurat, model bekerja optimal.</p>
                                    </div>
                                    <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                        <div class="flex items-center mb-2">
                                            <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                                            <span class="text-sm font-semibold text-blue-800">Good (0.25 - 0.50)</span>
                                        </div>
                                        <p class="text-xs text-blue-700">Error 25-50%. Estimasi dapat diterima untuk mayoritas Project.</p>
                                    </div>
                                    <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                        <div class="flex items-center mb-2">
                                            <span class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></span>
                                            <span class="text-sm font-semibold text-yellow-800">Fair (0.50 - 0.75)</span>
                                        </div>
                                        <p class="text-xs text-yellow-700">Error 50-75%. Perlu perbaikan model atau kalibrasi.</p>
                                    </div>
                                    <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                                        <div class="flex items-center mb-2">
                                            <span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span>
                                            <span class="text-sm font-semibold text-red-800">Poor (> 0.75)</span>
                                        </div>
                                        <p class="text-xs text-red-700">Error > 75%. Model tidak cocok atau ada masalah data.</p>
                                    </div>
                                </div>
                                <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                                    <p class="text-sm text-gray-700">
                                        <strong>Formula:</strong> MRE = |Actual - Estimated| / Actual. 
                                        Semakin kecil nilai MRE, semakin akurat estimasi. 
                                        MMRE adalah rata-rata MRE dari semua Project.
                                    </p>
                                </div>
                            @endif

                            <!-- Notes -->
                            @if($project->actual_notes)
                                <h4 class="text-md font-semibold text-gray-800 mt-4 mb-1">Notes</h4>
                                <div class="bg-white border rounded-lg p-4">
                                    <p class="text-sm text-gray-700">{{ $project->actual_notes }}</p>
                                </div>
                            @endif
                        @else
                            <!-- Call to Action for Data Input -->
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Data aktual belum tersedia</h3>
                                <p class="mt-1 text-sm text-gray-500">Input data aktual setelah Project selesai untuk mengukur akurasi estimasi.</p>
                                <div class="mt-6">
                                    <a href="{{ route('cocomo.actual-data-form', $project->id) }}" 
                                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                        </svg>
                                        Input Data Aktual
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="mt-4 flex flex-col sm:flex-row gap-4">
                
                <a href="{{ route('cocomo.edit', $project->id) }}" 
                    class="inline-flex items-center justify-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    {{ __('Edit Project') }}
                </a>

                <a href="{{ route('cocomo.actual-data-form', $project->id) }}" 
                    class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('Input Actual Data Project') }}
                </a>

                <a href="{{ route('cocomo.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('New Software Cost Estimation') }}
                </a>
                
                <button onclick="window.print()" 
                    class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                    </svg>
                    {{ __('Print') }}
                </button>
            </div>
        </div>
    </div>
</x-app-layout>