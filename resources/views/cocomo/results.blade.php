<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Hasil Estimasi COCOMO') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Create Estimation') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Project Summary Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Proyek</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700">Nama Proyek</label>
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
                            <label class="block text-sm font-bold text-gray-700">Ukuran Proyek (KLOC)</label>
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
                            <label class="block text-sm font-bold text-gray-700">ID Proyek</label>
                            <p class="mt-1 text-sm text-gray-900">#{{ $project->id }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Function Point Breakdown Detail -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
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
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Ukuran Proyek</h3>
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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
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

            <!-- Detailed Results -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Scale Factors -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Faktor Skala</h3>
                        <div class="space-y-3">
                            @foreach(App\Enums\ScaleFactor::cases() as $factor)
                                @php
                                    $rating = $scaleFactors[$factor->value] ?? 'N';
                                    $ratingText = match($rating) {
                                        'VL' => 'Sangat Rendah',
                                        'L' => 'Rendah', 
                                        'N' => 'Normal',
                                        'H' => 'Tinggi',
                                        'VH' => 'Sangat Tinggi',
                                        default => 'Normal'
                                    };
                                    $ratingColor = match($rating) {
                                        'VL' => 'bg-red-500 text-white',
                                        'L' => 'bg-orange-500 text-white',
                                        'N' => 'bg-gray-500 text-white',
                                        'H' => 'bg-blue-500 text-white',
                                        'VH' => 'bg-green-500 text-white',
                                        default => 'bg-gray-500 text-white'
                                    };
                                @endphp
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $factor->label() }}</p>
                                        <p class="text-sm text-gray-500">{{ $factor->value }}</p>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $ratingColor }}">
                                        {{ $ratingText }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Cost Drivers -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Penggerak Biaya</h3>
                        <div class="space-y-3 max-h-96 overflow-y-auto">
                            @foreach(App\Enums\CostDriver::cases() as $driver)
                                @php
                                    $rating = $costDrivers[$driver->value] ?? 'N';
                                    $ratingText = match($rating) {
                                        'VL' => 'Sangat Rendah',
                                        'L' => 'Rendah',
                                        'N' => 'Normal', 
                                        'H' => 'Tinggi',
                                        'VH' => 'Sangat Tinggi',
                                        'XH' => 'Ekstra Tinggi',
                                        default => 'Normal'
                                    };
                                    $ratingColor = match($rating) {
                                        'VL' => 'bg-red-500 text-white',
                                        'L' => 'bg-orange-500 text-white',
                                        'N' => 'bg-gray-500 text-white',
                                        'H' => 'bg-blue-500 text-white',
                                        'VH' => 'bg-green-500 text-white',
                                        'XH' => 'bg-purple-500 text-white',
                                        default => 'bg-gray-500 text-white'
                                    };
                                @endphp
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $driver->label() }}</p>
                                        <p class="text-sm text-gray-500">{{ $driver->value }}</p>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $ratingColor }}">
                                        {{ $ratingText }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calculation Details -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Interpretasi Hasil</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Rekomendasi Tim</h4>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                @php
                                    $avgTeamSize = $project->calculated_personnel;
                                    $teamRecommendation = '';
                                    if ($avgTeamSize < 2) {
                                        $teamRecommendation = 'Proyek dapat dikerjakan oleh 1-2 developer dengan supervisi minimal.';
                                    } elseif ($avgTeamSize < 5) {
                                        $teamRecommendation = 'Tim kecil (3-5 orang) dengan struktur sederhana sudah cukup.';
                                    } elseif ($avgTeamSize < 10) {
                                        $teamRecommendation = 'Tim menengah (6-10 orang) dengan pembagian peran yang jelas.';
                                    } else {
                                        $teamRecommendation = 'Tim besar (10+ orang) memerlukan manajemen proyek yang ketat dan struktur hierarkis.';
                                    }
                                @endphp
                                <p class="text-sm text-gray-700">{{ $teamRecommendation }}</p>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Fase Pengembangan</h4>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                @php
                                    $totalSchedule = $project->calculated_schedule;
                                    $analysisPhase = round($totalSchedule * 0.15, 1);
                                    $designPhase = round($totalSchedule * 0.25, 1);
                                    $codingPhase = round($totalSchedule * 0.40, 1);
                                    $testingPhase = round($totalSchedule * 0.20, 1);
                                @endphp
                                <div class="space-y-1 text-sm text-gray-700">
                                    <div class="flex justify-between">
                                        <span>Analisis & Perencanaan:</span>
                                        <span class="font-medium">{{ $analysisPhase }} bulan</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Desain:</span>
                                        <span class="font-medium">{{ $designPhase }} bulan</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Coding:</span>
                                        <span class="font-medium">{{ $codingPhase }} bulan</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Testing & Deployment:</span>
                                        <span class="font-medium">{{ $testingPhase }} bulan</span>
                                    </div>
                                    <div class="flex justify-between border-t border-gray-300 pt-2 mt-2">
                                        <span class="font-semibold">Total Durasi:</span>
                                        <span class="font-bold text-green-600">{{ number_format($totalSchedule, 1) }} bulan</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex flex-col sm:flex-row gap-4">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('Estimasi Baru') }}
                </a>
                
                <button onclick="window.print()" class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                    </svg>
                    {{ __('Cetak Hasil') }}
                </button>
            </div>
        </div>
    </div>
</x-app-layout>