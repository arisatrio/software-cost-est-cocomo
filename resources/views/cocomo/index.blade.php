<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Project List') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if($projects->count() > 0)
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                Project List
                            </h3>
                        </div>

                        <!-- Projects Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($projects as $project)
                                <div class="bg-white border border-gray-200 rounded-lg shadow hover:shadow-md transition-shadow duration-200">
                                    <div class="p-6">
                                        <div class="flex justify-between items-start mb-4">
                                            <h4 class="text-lg font-semibold text-gray-900 truncate">
                                                {{ $project->name ?: 'Proyek Tanpa Nama' }}
                                            </h4>
                                            <span class="text-xs text-gray-500 whitespace-nowrap ml-2">
                                                {{ $project->created_at->format('d M Y') }}
                                            </span>
                                        </div>

                                        <div class="space-y-2 mb-4">
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-600">KLOC:</span>
                                                <span class="font-medium">{{ number_format($project->kloc, 2) }}</span>
                                            </div>
                                            
                                            @if($project->programming_language)
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-600">Bahasa:</span>
                                                    <span class="font-medium capitalize">{{ $project->programming_language }}</span>
                                                </div>
                                            @endif
                                            
                                            @if($project->calculated_effort)
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-600">Effort:</span>
                                                    <span class="font-medium">{{ number_format($project->calculated_effort, 2) }} Person-Months</span>
                                                </div>
                                            @endif
                                            
                                            @if($project->calculated_schedule)
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-600">Waktu:</span>
                                                    <span class="font-medium">{{ number_format($project->calculated_schedule, 2) }} Bulan</span>
                                                </div>
                                            @endif
                                            
                                            @if($project->calculated_personnel)
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-600">Tim:</span>
                                                    <span class="font-medium">{{ number_format($project->calculated_personnel, 0) }} Orang</span>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Function Points Info -->
                                        @if($project->function_points > 0)
                                            <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-600">Function Points:</span>
                                                    <span class="font-medium">{{ number_format($project->function_points, 2) }}</span>
                                                </div>
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-600">Total SLOC:</span>
                                                    <span class="font-medium">{{ number_format($project->total_sloc) }}</span>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- View Details Button -->
                                        <a href="{{ route('cocomo.show', $project->id) }}" 
                                           class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-8">
                            {{ $projects->links() }}
                        </div>

                    @else
                        <!-- Empty State -->
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada proyek</h3>
                            <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat proyek estimasi COCOMO pertama Anda.</p>
                            <div class="mt-6">
                                <a href="{{ route('dashboard') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                    Buat Proyek Baru
                                </a>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>