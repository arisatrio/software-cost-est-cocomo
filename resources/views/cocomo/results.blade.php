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
            <div class="mt-4 mb-4 bg-white overflow-hidden shadow-sm sm:rounded-lg">
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
                                                <strong>Excellent (≤ 0.25)</strong> - Estimasi sangat akurat, model COCOMO bekerja dengan baik untuk proyek ini.
                                            @elseif($project->overall_accuracy <= 0.50)
                                                <strong>Good (0.25 - 0.50)</strong> - Estimasi cukup baik, dapat diterima untuk kebanyakan proyek software.
                                            @elseif($project->overall_accuracy <= 0.75)
                                                <strong>Fair (0.50 - 0.75)</strong> - Estimasi kurang akurat, perlu perbaikan model atau kalibrasi parameter.
                                            @else
                                                <strong>Poor (> 0.75)</strong> - Estimasi tidak akurat, kemungkinan ada masalah data atau model tidak cocok untuk proyek ini.
                                            @endif
                                        </p>
                                        
                                        <!-- Additional Context -->
                                        <div class="mt-2 text-xs 
                                            {{ $project->overall_accuracy <= 0.25 ? 'text-green-700' : 
                                               ($project->overall_accuracy <= 0.50 ? 'text-blue-700' : 
                                               ($project->overall_accuracy <= 0.75 ? 'text-yellow-700' : 'text-red-700')) }}">
                                            @if($project->overall_accuracy <= 0.25)
                                                <strong>Rekomendasi:</strong> Model dapat digunakan dengan confidence tinggi untuk proyek serupa.
                                            @elseif($project->overall_accuracy <= 0.50)
                                                <strong>Rekomendasi:</strong> Tambahkan buffer 10-20% pada estimasi untuk proyek serupa.
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
                                        <p class="text-xs text-blue-700">Error 25-50%. Estimasi dapat diterima untuk mayoritas proyek.</p>
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
                                        MMRE adalah rata-rata MRE dari semua proyek.
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
                                <p class="mt-1 text-sm text-gray-500">Input data aktual setelah proyek selesai untuk mengukur akurasi estimasi.</p>
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
                
                <button onclick="window.print()" class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                    </svg>
                    {{ __('Print') }}
                </button>
            </div>
        </div>
    </div>
</x-app-layout>