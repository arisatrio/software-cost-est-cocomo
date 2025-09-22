<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Input Data Aktual Proyek') }}
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
            <div class="flex space-x-3 mb-2">
                <a href="{{ route('cocomo.show', $project->id) }}" 
                    class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <!-- make arrow icon left -->
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    {{ __('Back to Result') }}
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2]]]">{{ $project->name }}</h3>
                    <p class="text-sm text-gray-600">
                        Masukkan data aktual setelah proyek selesai untuk mengukur akurasi estimasi COCOMO.
                        Data ini akan membantu meningkatkan akurasi model di masa depan.
                    </p>
                </div>

                <form action="{{ route('cocomo.update-actual', $project->id) }}" method="POST" class="p-6">
                    @csrf
                    @method('PATCH')

                    <!-- Project Status -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Status Proyek</label>
                        <select name="status" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('status') border-red-500 @enderror" required>
                            <option value="planning" {{ ($project->status ?? 'planning') == 'planning' ? 'selected' : '' }}>Perencanaan</option>
                            <option value="in_progress" {{ $project->status == 'in_progress' ? 'selected' : '' }}>Sedang Berlangsung</option>
                            <option value="completed" {{ $project->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="cancelled" {{ $project->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Actual Dates -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Mulai Aktual</label>
                            <input type="date" name="actual_start_date" 
                                   value="{{ $project->actual_start_date?->format('Y-m-d') ?? old('actual_start_date') }}"
                                   class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('actual_start_date') border-red-500 @enderror">
                            @error('actual_start_date')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Selesai Aktual</label>
                            <input type="date" name="actual_end_date" 
                                   value="{{ $project->actual_end_date?->format('Y-m-d') ?? old('actual_end_date') }}"
                                   class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('actual_end_date') border-red-500 @enderror">
                            @error('actual_end_date')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Comparison Card -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-4">
                        <h4 class="text-md font-semibold text-gray-800 mb-4">Perbandingan Estimasi vs Aktual</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Schedule -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    Durasi Aktual (Bulan)
                                </label>
                                <div class="mb-2 p-2 bg-green-100 rounded text-sm">
                                    <span class="font-medium">Estimasi:</span> {{ number_format($project->calculated_schedule ?? 0, 1) }} Bulan
                                </div>
                                <input type="number" step="0.1" name="actual_schedule" id="actual_schedule"
                                       value="{{ $project->actual_schedule ?? old('actual_schedule') }}"
                                       class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight bg-gray-100 focus:outline-none focus:shadow-outline @error('actual_schedule') border-red-500 @enderror" 
                                       placeholder="Contoh: 8.5" readonly>
                                @error('actual_schedule')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Personnel -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    Tim Aktual (Orang)
                                </label>
                                <div class="mb-2 p-2 bg-purple-100 rounded text-sm">
                                    <span class="font-medium">Estimasi:</span> {{ number_format($project->calculated_personnel ?? 0, 0) }} Orang
                                </div>
                                <input type="number" name="actual_personnel" id="actual_personnel"
                                       value="{{ $project->actual_personnel ?? old('actual_personnel') }}"
                                       class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('actual_personnel') border-red-500 @enderror" 
                                       placeholder="Contoh: 5" onchange="calculateEffort()">
                                @error('actual_personnel')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Auto-calculated Effort -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    Effort Aktual (Person-Months)
                                    <span class="text-xs font-normal text-gray-500">(Otomatis)</span>
                                </label>
                                <div class="mb-2 p-2 bg-blue-100 rounded text-sm">
                                    <span class="font-medium">Estimasi:</span> {{ number_format($project->calculated_effort ?? 0, 1) }} PM
                                </div>
                                <div class="relative">
                                    <input type="number" step="0.1" name="actual_effort" id="actual_effort"
                                           value="{{ $project->actual_effort ?? old('actual_effort') }}"
                                           class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight bg-gray-100 focus:outline-none focus:shadow-outline @error('actual_effort') border-red-500 @enderror" 
                                           readonly>
                                    <div class="absolute right-2 top-2 text-xs text-gray-500">
                                        <span id="calculation_display">Durasi × Tim</span>
                                    </div>
                                </div>
                                <div class="text-xs text-gray-600 mt-1">
                                    <strong>Formula:</strong> Durasi (bulan) × Jumlah Tim (orang)
                                </div>
                                @error('actual_effort')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            
                        </div>

                        <!-- SLOC Row -->
                        <div class="mt-2">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- SLOC -->
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">
                                        SLOC Aktual
                                    </label>
                                    <div class="mb-2 p-2 bg-yellow-100 rounded text-sm">
                                        <span class="font-medium">Estimasi:</span> {{ number_format($project->total_sloc ?? 0) }} SLOC
                                    </div>
                                    <input type="number" name="actual_sloc" 
                                           value="{{ $project->actual_sloc ?? old('actual_sloc') }}"
                                           class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('actual_sloc') border-red-500 @enderror" 
                                           placeholder="Contoh: 12500">
                                    @error('actual_sloc')
                                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Calculation Summary -->
                                {{-- <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">
                                        Ringkasan Perhitungan
                                    </label>
                                    <div class="bg-gray-100 rounded-lg p-4 space-y-2">
                                        <div class="flex justify-between text-sm">
                                            <span>Durasi:</span>
                                            <span id="summary_schedule" class="font-medium">{{ number_format($project->actual_schedule ?? 0, 1) }} bulan</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span>Tim:</span>
                                            <span id="summary_personnel" class="font-medium">{{ $project->actual_personnel ?? 0 }} orang</span>
                                        </div>
                                        <div class="flex justify-between text-sm border-t pt-2">
                                            <span class="font-semibold">Total Effort:</span>
                                            <span id="summary_effort" class="font-bold text-blue-600">{{ number_format($project->actual_effort ?? 0, 1) }} PM</span>
                                        </div>
                                        <div class="text-xs text-gray-600 mt-2">
                                            <strong>Contoh:</strong> 4 bulan × 3 orang = 12 person-months<br>
                                            <strong>Catatan:</strong> Formula ini mengasumsikan semua anggota tim bekerja full-time selama durasi proyek.
                                        </div>
                                    </div>
                                </div> --}}
                                
                            </div>
                        </div>
                    </div>

                    
                    <div class="mt-2">
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Catatan & Pembelajaran</label>
                            <textarea name="actual_notes" rows="4" 
                                    class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('actual_notes') border-red-500 @enderror"
                                    placeholder="Jelaskan faktor-faktor yang mempengaruhi perbedaan antara estimasi dan aktual, pembelajaran yang didapat, atau tantangan yang dihadapi selama proyek...">{{ $project->actual_notes ?? old('actual_notes') }}</textarea>
                            @error('actual_notes')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                            class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Save
                        </button>
                    </div>

                    <!-- JavaScript for Auto Calculation -->
                    <script>
                        function calculateDurationFromDates() {
                            const startInput = document.getElementsByName('actual_start_date')[0];
                            const endInput = document.getElementsByName('actual_end_date')[0];
                            const scheduleInput = document.getElementById('actual_schedule');
                            if (!startInput || !endInput || !scheduleInput) return;

                            const startDate = new Date(startInput.value);
                            const endDate = new Date(endInput.value);
                            if (startInput.value && endInput.value && endDate > startDate) {
                                // Calculate months difference (with decimals)
                                const msPerMonth = 1000 * 60 * 60 * 24 * 30.4375;
                                const diffMs = endDate - startDate;
                                const diffMonths = diffMs / msPerMonth;
                                scheduleInput.value = diffMonths.toFixed(1);
                                calculateEffort();
                            }
                        }

                        function calculateEffort() {
                            const schedule = parseFloat(document.getElementById('actual_schedule').value) || 0;
                            const personnel = parseInt(document.getElementById('actual_personnel').value) || 0;
                            const effort = schedule * personnel;
                            document.getElementById('actual_effort').value = effort.toFixed(1);
                            if (schedule > 0 && personnel > 0) {
                                document.getElementById('calculation_display').textContent = `${schedule} × ${personnel} = ${effort.toFixed(1)}`;
                            } else {
                                document.getElementById('calculation_display').textContent = 'Durasi × Tim';
                            }
                            // Show warning for unusual values
                            showValidationWarnings(schedule, personnel, effort);
                        }

                        function showValidationWarnings(schedule, personnel, effort) {
                            const warningDiv = document.getElementById('calculation_warnings') || createWarningDiv();
                            let warnings = [];
                            if (schedule > 24) warnings.push('⚠️ Durasi >24 bulan terlihat tidak wajar untuk proyek mahasiswa');
                            if (personnel > 10) warnings.push('⚠️ Tim >10 orang terlihat terlalu besar untuk proyek akademik');
                            if (effort > 100) warnings.push('⚠️ Effort >100 PM terlihat terlalu besar untuk konteks akademik');
                            if (schedule > 0 && schedule < 0.5) warnings.push('⚠️ Durasi <0.5 bulan terlihat terlalu singkat');
                            if (warnings.length > 0) {
                                warningDiv.innerHTML = warnings.map(w => `<div class=\"text-xs text-amber-600\">${w}</div>`).join('');
                                warningDiv.style.display = 'block';
                            } else {
                                warningDiv.style.display = 'none';
                            }
                        }

                        function createWarningDiv() {
                            const div = document.createElement('div');
                            div.id = 'calculation_warnings';
                            div.className = 'mt-2 p-2 bg-amber-50 border border-amber-200 rounded';
                            document.getElementById('actual_effort').parentNode.parentNode.appendChild(div);
                            return div;
                        }

                        document.addEventListener('DOMContentLoaded', function() {
                            calculateEffort();
                            document.getElementById('actual_schedule').addEventListener('input', calculateEffort);
                            document.getElementById('actual_personnel').addEventListener('input', calculateEffort);
                            // Add listeners for date changes
                            const startInput = document.getElementsByName('actual_start_date')[0];
                            const endInput = document.getElementsByName('actual_end_date')[0];
                            if (startInput && endInput) {
                                startInput.addEventListener('change', calculateDurationFromDates);
                                endInput.addEventListener('change', calculateDurationFromDates);
                            }
                        });
                    </script>
                    </div>

                    <!-- Current Accuracy Display (if available) -->
                    @if($project->overall_mre)
                        <div class="bg-blue-50 rounded-lg p-6 mb-6">
                            <h4 class="text-md font-semibold text-gray-800 mb-4">Akurasi Saat Ini (MMRE)</h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @if($project->effort_mre)
                                    <div class="text-center">
                                        <div class="text-2xl font-bold 
                                            @if($project->effort_mre <= 0.25) text-green-600
                                            @elseif($project->effort_mre <= 0.50) text-blue-600
                                            @elseif($project->effort_mre <= 0.75) text-yellow-600
                                            @else text-red-600
                                            @endif
                                        ">{{ number_format($project->effort_mre, 3) }}</div>
                                        <div class="text-sm text-gray-600">Effort MRE</div>
                                    </div>
                                @endif
                                @if($project->schedule_mre)
                                    <div class="text-center">
                                        <div class="text-2xl font-bold 
                                            @if($project->schedule_mre <= 0.25) text-green-600
                                            @elseif($project->schedule_mre <= 0.50) text-blue-600
                                            @elseif($project->schedule_mre <= 0.75) text-yellow-600
                                            @else text-red-600
                                            @endif
                                        ">{{ number_format($project->schedule_mre, 3) }}</div>
                                        <div class="text-sm text-gray-600">Schedule MRE</div>
                                    </div>
                                @endif
                                @if($project->personnel_mre)
                                    <div class="text-center">
                                        <div class="text-2xl font-bold 
                                            @if($project->personnel_mre <= 0.25) text-green-600
                                            @elseif($project->personnel_mre <= 0.50) text-blue-600
                                            @elseif($project->personnel_mre <= 0.75) text-yellow-600
                                            @else text-red-600
                                            @endif
                                        ">{{ number_format($project->personnel_mre, 3) }}</div>
                                        <div class="text-sm text-gray-600">Personnel MRE</div>
                                    </div>
                                @endif
                                @if($project->overall_mre)
                                    <div class="text-center">
                                        <div class="text-2xl font-bold 
                                            @if($project->overall_mre <= 0.25) text-green-600
                                            @elseif($project->overall_mre <= 0.50) text-blue-600
                                            @elseif($project->overall_mre <= 0.75) text-yellow-600
                                            @else text-red-600
                                            @endif
                                        ">{{ number_format($project->overall_mre, 3) }}</div>
                                        <div class="text-sm text-gray-600">Overall MMRE</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                </form>
            </div>
        </div>
    </div>
</x-app-layout>