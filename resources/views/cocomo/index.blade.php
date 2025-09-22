<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Projects List Dashboard') }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($projects->count() > 0)
                <!-- Portfolio Summary Dashboard -->
                <div class="mb-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @php
                        $totalProjects = $projects->total();
                        $totalEffort = $projects->sum('calculated_effort');
                        $avgSchedule = $projects->avg('calculated_schedule');
                        $totalKLOC = $projects->sum('kloc');
                        
                        $completedProjects = $projects->where('status', '!=', 'planning')->count();
                        $completionRate = $totalProjects > 0 ? ($completedProjects / $totalProjects) * 100 : 0;
                        
                        // Calculate project size distribution
                        $smallProjects = $projects->where('kloc', '<', 2)->count();
                        $mediumProjects = $projects->whereBetween('kloc', [2, 8])->count();
                        $largeProjects = $projects->where('kloc', '>', 8)->count();
                        
                        // Most used language
                        $languageStats = $projects->groupBy('programming_language');
                        $popularLanguage = $languageStats->sortByDesc(function($group) { 
                            return $group->count(); 
                        })->keys()->first();
                    @endphp
                    
                    <!-- Total Projects Card -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-semibold text-gray-900">{{ number_format($totalProjects) }}</h4>
                                    <p class="text-sm text-gray-500">Total Projects</p>
                                    <p class="text-xs text-blue-600 mt-1">{{ $completedProjects }} Completed</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Effort Card -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-semibold text-gray-900">{{ number_format($totalEffort, 1) }}</h4>
                                    <p class="text-sm text-gray-500">Total Effort</p>
                                    <p class="text-xs text-green-600 mt-1">Person-Months</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Average Schedule Card -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-semibold text-gray-900">{{ number_format($avgSchedule, 1) }}</h4>
                                    <p class="text-sm text-gray-500">Avg. Duration</p>
                                    <p class="text-xs text-purple-600 mt-1">Months</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Code Size Card -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-semibold text-gray-900">{{ number_format($totalKLOC, 1) }}</h4>
                                    <p class="text-sm text-gray-500">Total Code Size</p>
                                    <p class="text-xs text-yellow-600 mt-1">KLOC</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Projects List -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-900">Projects Overview</h3>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-500">{{ $projects->total() }} projects total</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <!-- Projects Grid -->
                        <div class="grid grid-cols-1 lg:grid-cols-3 xl:grid-cols-3 gap-6">
                            @foreach($projects as $project)
                                <div class="bg-white border border-gray-200 rounded-lg shadow hover:shadow-lg transition-all duration-200 hover:border-blue-300">
                                    <!-- Project Header -->
                                    <div class="p-6 pb-1 border-b border-gray-100">
                                        <div class="flex justify-between items-start mb-3">
                                            <div class="flex-1">
                                                <h4 class="text-lg font-semibold text-gray-900 truncate mb-1">
                                                    {{ $project->name ?: 'Untitled Project' }}
                                                </h4>
                                                <div class="flex items-center space-x-2">
                                                    @php
                                                        $projectSize = $project->kloc < 2 ? 'small' : ($project->kloc < 8 ? 'medium' : 'large');
                                                        $sizeColor = $projectSize === 'small' ? 'green' : ($projectSize === 'medium' ? 'blue' : 'red');
                                                        $statusColor = 'gray';
                                                        if ($project->status === 'completed') $statusColor = 'green';
                                                        elseif ($project->status === 'in_progress') $statusColor = 'blue';
                                                        elseif ($project->status === 'on_hold') $statusColor = 'yellow';
                                                    @endphp
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $sizeColor }}-100 text-{{ $sizeColor }}-800">
                                                        {{ ucfirst($projectSize) }} Project
                                                    </span>
                                                    @if($project->status)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800">
                                                            {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="text-right ml-4">
                                                <span class="text-xs text-gray-500 whitespace-nowrap">
                                                    {{ $project->created_at->format('d M Y') }}
                                                </span>
                                                @if($project->updated_at != $project->created_at)
                                                    <div class="text-xs text-gray-400 mt-1">
                                                        Updated {{ $project->updated_at->diffForHumans() }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Project Technical Details -->
                                    <div class="p-6 pb-1">
                                        <div class="space-y-3 mb-4">
                                            @if($project->programming_language)
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-600 flex items-center">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                                        </svg>
                                                        Programming Language:
                                                    </span>
                                                    <span class="font-bold capitalize text-black">{{ $project->programming_language }}</span>
                                                </div>
                                            @endif
                                            
                                            @if($project->function_points > 0)
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-600 flex items-center">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                                        </svg>
                                                        Function Points:
                                                    </span>
                                                    <span class="font-bold text-black">{{ number_format($project->function_points, 1) }}</span>
                                                </div>
                                            @endif
                                            
                                            @if($project->total_sloc > 0)
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-600 flex items-center">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                                                        </svg>
                                                        Total Lines of Code (SLOC):
                                                    </span>
                                                    <span class="font-bold text-black">{{ number_format($project->total_sloc) }}</span>
                                                </div>
                                            @endif

                                            @if($project->complexity_factor)
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-600 flex items-center">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                                        </svg>
                                                        Complexity:
                                                    </span>
                                                    <span class="font-bold capitalize text-black">{{ $project->complexity_factor }}</span>
                                                </div>
                                            @endif

                                            @if($project->calculated_effort)
                                                <div class="flex justify-between text-sm pt-2 border-t border-gray-100">
                                                    <span class="text-gray-600 flex items-center">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                        </svg>
                                                        Estimated Effort:
                                                    </span>
                                                    <span class="font-bold text-black">{{ number_format($project->calculated_effort, 1) }} PM</span>
                                                </div>
                                            @endif

                                            @if($project->calculated_schedule)
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-600 flex items-center">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        Schedule:
                                                    </span>
                                                    <span class="font-bold text-black">{{ number_format($project->calculated_schedule, 1) }} months</span>
                                                </div>
                                            @endif

                                            @if($project->calculated_personnel)
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-600 flex items-center">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                        </svg>
                                                        Team Size:
                                                    </span>
                                                    <span class="font-bold text-black">{{ number_format($project->calculated_personnel, 0) }} people</span>
                                                </div>
                                            @endif

                                            <!-- Actual Data Section -->
                                            <div class="pt-3 border-t border-gray-200">
                                                
                                                <div class="flex justify-between text-sm mb-2">
                                                    <span class="text-gray-600 flex items-center">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        Actual Effort:
                                                    </span>
                                                    <span class="font-bold text-black">
                                                        @if($project->status === 'completed' && isset($project->actual_effort) && $project->actual_effort > 0)
                                                            {{ number_format($project->actual_effort, 1) }} PM
                                                        @else
                                                            -
                                                        @endif
                                                    </span>
                                                </div>

                                                <div class="flex justify-between text-sm mb-2">
                                                    <span class="text-gray-600 flex items-center">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        Actual Duration:
                                                    </span>
                                                    <span class="font-bold text-black">
                                                        @if($project->status === 'completed' && isset($project->actual_schedule) && $project->actual_schedule > 0)
                                                            {{ number_format($project->actual_schedule, 1) }} months
                                                        @else
                                                            -
                                                        @endif
                                                    </span>
                                                </div>

                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-600 flex items-center">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        Actual Team Size:
                                                    </span>
                                                    <span class="font-bold text-black">
                                                        @if($project->status === 'completed' && isset($project->actual_personnel) && $project->actual_personnel > 0)
                                                            {{ number_format($project->actual_personnel, 0) }} people
                                                        @else
                                                            -
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="px-6 pb-6">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('cocomo.show', $project->id) }}" 
                                               class="flex-1 inline-flex justify-center items-center px-3 py-2 bg-blue-600 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-wider hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                View Details
                                            </a>
                                            <button class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-8 border-t border-gray-200 pt-6">
                            {{ $projects->links() }}
                        </div>
                    </div>
                </div>

            @else
                <!-- Enhanced Empty State -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="text-center py-16">
                        <div class="mx-auto h-24 w-24 text-gray-400 mb-4">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-full h-full">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-medium text-gray-900 mb-2">No Projects Yet</h3>
                        <p class="text-gray-500 mb-8 max-w-md mx-auto">
                            Start building your project portfolio by creating your first COCOMO estimation project. 
                            Track effort, schedule, and team requirements with professional accuracy.
                        </p>
                        
                        <!-- Getting Started Actions -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-2xl mx-auto mb-8">
                            <div class="p-4 border border-gray-200 rounded-lg">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                </div>
                                <h4 class="font-medium text-gray-900 mb-1">Create Project</h4>
                                <p class="text-xs text-gray-500">Start with function points or direct KLOC input</p>
                            </div>
                            <div class="p-4 border border-gray-200 rounded-lg">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <h4 class="font-medium text-gray-900 mb-1">Configure Parameters</h4>
                                <p class="text-xs text-gray-500">Set scale factors and cost drivers</p>
                            </div>
                            <div class="p-4 border border-gray-200 rounded-lg">
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h4 class="font-medium text-gray-900 mb-1">Get Estimates</h4>
                                <p class="text-xs text-gray-500">View detailed effort and schedule analysis</p>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <a href="{{ route('dashboard') }}" 
                               class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Create Your First Project
                            </a>
                            <div class="text-xs text-gray-400">
                                <p>COCOMO II model • Professional estimation • Comprehensive analysis</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>