<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Overall Statistics -->
            <div class="mb-4">
                <div class="grid grid-cols-1 md:grid-cols-5 lg:grid-cols-4 gap-6">
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">MMRE Effort</h3>
                                <p class="text-3xl font-bold text-blue-600">{{ number_format($avgEffortAccuracy, 3) }}</p>
                                <p class="text-sm text-gray-500">{{ $completedProjects }} Project selesai</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">MMRE Schedule</h3>
                                <p class="text-3xl font-bold text-green-600">{{ number_format($avgScheduleAccuracy, 3) }}</p>
                                <p class="text-sm text-gray-500">Rata-rata jadwal</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">MMRE Personnel</h3>
                                <p class="text-3xl font-bold text-purple-600">{{ number_format($avgPersonnelAccuracy, 3) }}</p>
                                <p class="text-sm text-gray-500">Rata-rata tim</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">MMRE Keseluruhan</h3>
                                <p class="text-3xl font-bold text-red-600">{{ number_format($avgOverallAccuracy, 3) }}</p>
                                <p class="text-sm text-gray-500">Model performance</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Project Accuracy Table -->
            <div class="bg-white shadow-sm rounded-lg mb-4">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Accuration Result per Project</h2>
                    <p class="mt-1 text-sm text-gray-600">MMRE (Mean Magnitude of Relative Error)</p>
                </div>
                
                @if($allProjects->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Effort</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Schedule</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Personnel</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Overall</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($allProjects as $project)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    <a href="{{ route('cocomo.show', $project->id) }}" class="text-blue-600 hover:underline">
                                                        {{ $project->name }}
                                                    </a>
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    Created {{ $project->created_at->format('d M Y') }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $project->status_badge_color }}">
                                                {{ ucfirst(str_replace('_', ' ', $project->status ?? 'planning')) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if($project->effort_accuracy)
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                    {{ $project->effort_accuracy <= 0.25 ? 'bg-green-500 text-white' : 
                                                       ($project->effort_accuracy <= 0.50 ? 'bg-blue-500 text-white' : 
                                                       ($project->effort_accuracy <= 0.75 ? 'bg-yellow-500 text-white' : 'bg-red-500 text-white')) }}">
                                                    {{ number_format($project->effort_accuracy, 3) }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-xs">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if($project->schedule_accuracy)
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                    {{ $project->schedule_accuracy <= 0.25 ? 'bg-green-500 text-white' : 
                                                       ($project->schedule_accuracy <= 0.50 ? 'bg-blue-500 text-white' : 
                                                       ($project->schedule_accuracy <= 0.75 ? 'bg-yellow-500 text-white' : 'bg-red-500 text-white')) }}">
                                                    {{ number_format($project->schedule_accuracy, 3) }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-xs">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if($project->personnel_accuracy)
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                    {{ $project->personnel_accuracy <= 0.25 ? 'bg-green-500 text-white' : 
                                                       ($project->personnel_accuracy <= 0.50 ? 'bg-blue-500 text-white' : 
                                                       ($project->personnel_accuracy <= 0.75 ? 'bg-yellow-500 text-white' : 'bg-red-500 text-white')) }}">
                                                    {{ number_format($project->personnel_accuracy, 3) }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-xs">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if($project->overall_accuracy)
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                    {{ $project->overall_accuracy <= 0.25 ? 'bg-green-500 text-white' : 
                                                       ($project->overall_accuracy <= 0.50 ? 'bg-blue-500 text-white' : 
                                                       ($project->overall_accuracy <= 0.75 ? 'bg-yellow-500 text-white' : 'bg-red-500 text-white')) }}">
                                                    {{ number_format($project->overall_accuracy, 3) }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-xs">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                            <div class="flex space-x-2 justify-center">
                                                <a href="{{ route('cocomo.show', $project->id) }}" 
                                                   class="text-blue-600 hover:text-blue-900 font-medium">
                                                    Detail
                                                </a>
                                                @if($project->status !== 'completed' || !$project->actual_effort)
                                                    <span class="text-gray-300">|</span>
                                                    <a href="{{ route('cocomo.actual-data-form', $project->id) }}" 
                                                       class="text-green-600 hover:text-green-900 font-medium">
                                                        Input Data
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada data akurasi</h3>
                        <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat estimasi dan menambahkan data aktual setelah Project selesai.</p>
                        <div class="mt-6">
                            <a href="{{ route('cocomo.create') }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Buat Estimasi Pertama
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Performance Guide -->
                @if($completedProjects > 0)
                    <div class="bg-white rounded-lg shadow mb-8 p-6">
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
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>