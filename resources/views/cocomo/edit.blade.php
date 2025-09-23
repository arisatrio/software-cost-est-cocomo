<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Project Estimation') }} - {{ $project->name }}
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

            <!-- Edit Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <h3 class="p-3 text-gray-900 font-semibold text-lg">
                    Form Edit Estimation
                </h3>
                <hr>
                <div class="p-6 text-gray-900">

                    <form action="{{ route('cocomo.update', $project->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Project Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">
                                Nama Proyek
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $project->name) }}" 
                                   class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" 
                                   placeholder="Masukkan nama proyek" required>
                            @error('name')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Function Point Analysis -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Analisis Kompleksitas Aplikasi</h3>
                            <p class="text-sm text-gray-600 mb-6">Jawab pertanyaan berikut untuk memperkirakan ukuran dan kompleksitas aplikasi yang akan dikembangkan</p>
                            
                            <!-- Programming Language -->
                            <div class="mb-6">
                                <label for="programming_language" class="block text-gray-700 text-sm font-bold mb-2">
                                    Bahasa Pemrograman Utama
                                </label>
                                <select name="programming_language" id="programming_language" 
                                        class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('programming_language') border-red-500 @enderror" required>
                                    <option value="">Pilih bahasa pemrograman...</option>
                                    <option value="java" {{ old('programming_language', $project->programming_language) == 'java' ? 'selected' : '' }}>Java</option>
                                    <option value="csharp" {{ old('programming_language', $project->programming_language) == 'csharp' ? 'selected' : '' }}>C#</option>
                                    <option value="cpp" {{ old('programming_language', $project->programming_language) == 'cpp' ? 'selected' : '' }}>C++</option>
                                    <option value="python" {{ old('programming_language', $project->programming_language) == 'python' ? 'selected' : '' }}>Python</option>
                                    <option value="javascript" {{ old('programming_language', $project->programming_language) == 'javascript' ? 'selected' : '' }}>JavaScript</option>
                                    <option value="php" {{ old('programming_language', $project->programming_language) == 'php' ? 'selected' : '' }}>PHP</option>
                                    <option value="ruby" {{ old('programming_language', $project->programming_language) == 'ruby' ? 'selected' : '' }}>Ruby</option>
                                    <option value="swift" {{ old('programming_language', $project->programming_language) == 'swift' ? 'selected' : '' }}>Swift</option>
                                    <option value="kotlin" {{ old('programming_language', $project->programming_language) == 'kotlin' ? 'selected' : '' }}>Kotlin</option>
                                    <option value="go" {{ old('programming_language', $project->programming_language) == 'go' ? 'selected' : '' }}>Go</option>
                                </select>
                                @error('programming_language')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">Bahasa pemrograman yang akan digunakan untuk mengembangkan aplikasi</p>
                            </div>

                            <!-- Question 1: Input Data & Transaksi (External Inputs) -->
                            <div class="mb-6 p-4 bg-white rounded-lg border">
                                <h4 class="font-semibold text-gray-800 mb-3">1. Input Data & Transaksi</h4>
                                <p class="text-sm text-gray-600 mb-4">Berapa jumlah External Inputs dalam aplikasi (formulir input, transaksi, data yang dimasukkan user)?</p>
                                <input type="number" name="external_inputs" id="external_inputs" 
                                       value="{{ old('external_inputs', $project->external_inputs) }}" min="0" step="1"
                                       class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('external_inputs') border-red-500 @enderror">
                                @error('external_inputs')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">Contoh: form pendaftaran, input pesanan, entry data master</p>
                            </div>

                            <!-- Question 2: Laporan & Output (External Outputs) -->
                            <div class="mb-6 p-4 bg-white rounded-lg border">
                                <h4 class="font-semibold text-gray-800 mb-3">2. Laporan & Output</h4>
                                <p class="text-sm text-gray-600 mb-4">Berapa jumlah External Outputs yang dihasilkan aplikasi (laporan, dokumen, file yang dihasilkan)?</p>
                                <input type="number" name="external_outputs" id="external_outputs" 
                                       value="{{ old('external_outputs', $project->external_outputs) }}" min="0" step="1"
                                       class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('external_outputs') border-red-500 @enderror">
                                @error('external_outputs')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">Contoh: laporan penjualan, invoice, dashboard, export file</p>
                            </div>

                            <!-- Question 3: Pencarian & Query (External Inquiries) -->
                            <div class="mb-6 p-4 bg-white rounded-lg border">
                                <h4 class="font-semibold text-gray-800 mb-3">3. Pencarian & Query</h4>
                                <p class="text-sm text-gray-600 mb-4">Berapa jumlah External Inquiries dalam aplikasi (fitur pencarian, filter, query)?</p>
                                <input type="number" name="external_inquiries" id="external_inquiries" 
                                       value="{{ old('external_inquiries', $project->external_inquiries) }}" min="0" step="1"
                                       class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('external_inquiries') border-red-500 @enderror">
                                @error('external_inquiries')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">Contoh: search bar, filter data, lookup</p>
                            </div>

                            <!-- Question 4: Penyimpanan Data (Internal Files) -->
                            <div class="mb-6 p-4 bg-white rounded-lg border">
                                <h4 class="font-semibold text-gray-800 mb-3">4. Penyimpanan Data</h4>
                                <p class="text-sm text-gray-600 mb-4">Berapa jumlah Internal Files yang digunakan aplikasi (tabel database, master data, user data)?</p>
                                <input type="number" name="internal_files" id="internal_files" 
                                       value="{{ old('internal_files', $project->internal_files) }}" min="0" step="1"
                                       class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('internal_files') border-red-500 @enderror">
                                @error('internal_files')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">Contoh: tabel database, file data internal</p>
                            </div>

                            <!-- Question 5: Integrasi Eksternal (External Files) -->
                            <div class="mb-6 p-4 bg-white rounded-lg border">
                                <h4 class="font-semibold text-gray-800 mb-3">5. Integrasi Eksternal</h4>
                                <p class="text-sm text-gray-600 mb-4">Berapa jumlah External Files yang diakses aplikasi (API eksternal, web service, file sharing)?</p>
                                <input type="number" name="external_files" id="external_files" 
                                       value="{{ old('external_files', $project->external_files) }}" min="0" step="1"
                                       class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('external_files') border-red-500 @enderror">
                                @error('external_files')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">Contoh: API payment, social media, email service</p>
                            </div>

                            <!-- Complexity Factor -->
                            <div class="mb-6 p-4 bg-white rounded-lg border">
                                <h4 class="font-semibold text-gray-800 mb-3">6. Tingkat Kompleksitas Keseluruhan</h4>
                                <p class="text-sm text-gray-600 mb-4">Bagaimana Anda menilai tingkat kompleksitas keseluruhan dari aplikasi yang akan dikembangkan?</p>
                                <div class="space-y-2">
                                    <label class="flex items-start space-x-3 cursor-pointer">
                                        <input type="radio" name="complexity_factor" value="low" class="mt-1" {{ old('complexity_factor', $project->complexity_factor) == 'low' ? 'checked' : '' }} required>
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-800">Sederhana</div>
                                            <div class="text-sm text-gray-600">Aplikasi dengan fitur dasar, tidak banyak integrasi</div>
                                        </div>
                                    </label>
                                    <label class="flex items-start space-x-3 cursor-pointer">
                                        <input type="radio" name="complexity_factor" value="medium" class="mt-1" {{ old('complexity_factor', $project->complexity_factor) == 'medium' ? 'checked' : '' }}>
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-800">Sedang</div>
                                            <div class="text-sm text-gray-600">Aplikasi dengan beberapa modul dan integrasi</div>
                                        </div>
                                    </label>
                                    <label class="flex items-start space-x-3 cursor-pointer">
                                        <input type="radio" name="complexity_factor" value="high" class="mt-1" {{ old('complexity_factor', $project->complexity_factor) == 'high' ? 'checked' : '' }}>
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-800">Kompleks</div>
                                            <div class="text-sm text-gray-600">Aplikasi dengan banyak modul, fitur advanced, dan integrasi kompleks</div>
                                        </div>
                                    </label>
                                </div>
                                @error('complexity_factor')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Project Category (COCOMO II) -->
                            <div class="mb-6 p-4 bg-white rounded-lg border">
                                <h4 class="font-semibold text-gray-800 mb-3">7. Kategori Proyek COCOMO II</h4>
                                <p class="text-sm text-gray-600 mb-4">Pilih kategori proyek yang paling sesuai dengan karakteristik proyek Anda:</p>
                                <div class="space-y-3">
                                    @foreach(App\Enums\ProjectCategory::cases() as $category)
                                        <label class="flex items-start space-x-3 cursor-pointer p-3 border rounded-lg hover:bg-gray-50">
                                            <input type="radio" name="project_category" value="{{ $category->value }}" class="mt-1" 
                                                   {{ old('project_category', $project->project_category?->value) == $category->value ? 'checked' : '' }} required>
                                            <div class="flex-1">
                                                <div class="font-medium text-gray-800 flex items-center">
                                                    {{ $category->label() }}
                                                    <span class="ml-2 px-2 py-1 text-xs rounded-full {{ $category->colorClass() }}">
                                                        {{ ucfirst($category->value) }}
                                                    </span>
                                                </div>
                                                <div class="text-sm text-gray-600 mt-1">{{ $category->description() }}</div>
                                                <div class="text-xs text-gray-500 mt-2">
                                                    <strong>Karakteristik:</strong>
                                                    <ul class="mt-1 space-y-1">
                                                        @foreach($category->characteristics() as $characteristic)
                                                        <li>• {{ $characteristic }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                @error('project_category')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Function Point Summary -->
                            <div class="mt-4 p-3 bg-blue-50 rounded">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-700">Estimasi Function Point:</span>
                                    <span id="fp-total" class="text-lg font-bold text-blue-600">{{ $project->function_points ?? 0 }} FP</span>
                                </div>
                                <div class="flex justify-between items-center mt-1">
                                    <span class="text-sm font-medium text-gray-700">Estimasi SLOC:</span>
                                    <span id="sloc-total" class="text-lg font-bold text-green-600">{{ $project->total_sloc ?? 0 }} SLOC</span>
                                </div>
                                <div class="flex justify-between items-center mt-1">
                                    <span class="text-sm font-medium text-gray-700">Estimasi KLOC:</span>
                                    <span id="kloc-total" class="text-lg font-bold text-purple-600">{{ $project->kloc ?? 0 }} KLOC</span>
                                </div>
                            </div>
                        </div>

                        <h2 class="text-xl font-bold">Faktor Skala</h2>
                        @foreach(App\Enums\ScaleFactor::cases() as $factor)
                            <div class="mb-4">
                                <label for="scale_factors_{{ $factor->value }}" class="block text-gray-700 text-sm font-bold mb-2">
                                    {{ $factor->label() }}
                                    @if($factor->description())
                                        <p class="text-gray-500 text-xs mt-1">{{ $factor->description() }}</p>
                                    @endif
                                </label>
                                <select name="scale_factors[{{ $factor->value }}]" id="scale_factors_{{ $factor->value }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                    <option value="">Pilih rating...</option>
                                    @foreach($factor->options() as $option)
                                        @php
                                            $charValue = match($option->name) {
                                                'VeryLow' => 'VL',
                                                'Low' => 'L',
                                                'Nominal' => 'N',
                                                'High' => 'H',
                                                'VeryHigh' => 'VH',
                                                default => 'N'
                                            };
                                            $displayText = match($option->name) {
                                                'VeryLow' => 'Very Low',
                                                'Low' => 'Low',
                                                'Nominal' => 'Nominal',
                                                'High' => 'High',
                                                'VeryHigh' => 'Very High',
                                                default => 'Nominal'
                                            };
                                        @endphp
                                        <option value="{{ $charValue }}" {{ old("scale_factors.{$factor->value}", $scaleFactors[$factor->value] ?? 'N') == $charValue ? 'selected' : '' }}>
                                            {{ $displayText }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endforeach

                        <h2 class="text-xl font-bold mt-8">Penggerak Biaya</h2>
                        @foreach(App\Enums\CostDriver::cases() as $driver)
                            <div class="mb-4">
                                <label for="cost_drivers_{{ $driver->value }}" class="block text-gray-700 text-sm font-bold mb-2">
                                    {{ $driver->label() }}
                                    @if($driver->description())
                                        <p class="text-gray-500 text-xs mt-1">{{ $driver->description() }}</p>
                                    @endif
                                </label>
                                <select name="cost_drivers[{{ $driver->value }}]" id="cost_drivers_{{ $driver->value }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                    <option value="">Pilih rating...</option>
                                    @foreach($driver->options() as $option)
                                        @php
                                            $charValue = match($option->name) {
                                                'VeryLow' => 'VL',
                                                'Low' => 'L',
                                                'Nominal' => 'N',
                                                'High' => 'H',
                                                'VeryHigh' => 'VH',
                                                'ExtraHigh' => 'XH',
                                                default => 'N'
                                            };
                                            $displayText = match($option->name) {
                                                'VeryLow' => 'Very Low',
                                                'Low' => 'Low',
                                                'Nominal' => 'Nominal',
                                                'High' => 'High',
                                                'VeryHigh' => 'Very High',
                                                'ExtraHigh' => 'Extra High',
                                                default => 'Nominal'
                                            };
                                        @endphp
                                        <option value="{{ $charValue }}" {{ old("cost_drivers.{$driver->value}", $costDrivers[$driver->value] ?? 'N') == $charValue ? 'selected' : '' }}>
                                            {{ $displayText }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endforeach

                        <!-- Hidden Fields for Backend Processing -->
                        <input type="hidden" name="kloc" id="hidden_kloc" value="{{ $project->kloc ?? 0 }}">
                        <input type="hidden" name="function_points" id="hidden_function_points" value="{{ $project->function_points ?? 0 }}">
                        <input type="hidden" name="total_sloc" id="hidden_total_sloc" value="{{ $project->total_sloc ?? 0 }}">

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 justify-end mt-6">
                            <a href="{{ route('cocomo.results', $project->id) }}" 
                               class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                {{ __('Update & Recalculate') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Function Point Calculator Script -->
    <script>
        // Language-specific SLOC per Function Point conversion rates
        const languageRates = {
            'java': 53,
            'csharp': 54,
            'cpp': 55,
            'python': 27,
            'javascript': 47,
            'php': 36,
            'ruby': 25,
            'swift': 75,
            'kotlin': 50,
            'go': 45
        };

        // Function Point weights
        const fpWeights = {
            external_inputs: 4,
            external_outputs: 5,
            external_inquiries: 4,
            internal_files: 10,
            external_files: 7
        };

        // Complexity multipliers
        const complexityMultipliers = {
            'low': 0.85,
            'medium': 1.0,
            'high': 1.15
        };

        function calculateFunctionPoints() {
            // Get values from input fields
            const inputs = parseInt(document.getElementById('external_inputs').value) || 0;
            const outputs = parseInt(document.getElementById('external_outputs').value) || 0;
            const inquiries = parseInt(document.getElementById('external_inquiries').value) || 0;
            const internalFiles = parseInt(document.getElementById('internal_files').value) || 0;
            const externalFiles = parseInt(document.getElementById('external_files').value) || 0;
            
            const complexity = getSelectedValue('complexity_factor');
            const language = document.getElementById('programming_language').value;

            // Calculate unadjusted function points using input values
            const unadjustedFP = (inputs * fpWeights.external_inputs) +
                                 (outputs * fpWeights.external_outputs) +
                                 (inquiries * fpWeights.external_inquiries) +
                                 (internalFiles * fpWeights.internal_files) +
                                 (externalFiles * fpWeights.external_files);

            // Apply complexity adjustment
            const complexityFactor = complexityMultipliers[complexity] || 1.0;
            const adjustedFP = unadjustedFP * complexityFactor;

            // Convert to SLOC
            let sloc = 0;
            let kloc = 0;
            if (language && languageRates[language]) {
                sloc = Math.round(adjustedFP * languageRates[language]);
                kloc = sloc / 1000;
            }

            // Update display
            document.getElementById('fp-total').textContent = Math.round(adjustedFP) + ' FP';
            document.getElementById('sloc-total').textContent = sloc.toLocaleString() + ' SLOC';
            document.getElementById('kloc-total').textContent = kloc.toFixed(2) + ' KLOC';

            // Update hidden fields
            document.getElementById('hidden_kloc').value = kloc;
            document.getElementById('hidden_function_points').value = adjustedFP;
            document.getElementById('hidden_total_sloc').value = sloc;

            return {
                functionPoints: adjustedFP,
                sloc: sloc,
                kloc: kloc
            };
        }

        function getSelectedValue(name) {
            const selected = document.querySelector(`input[name="${name}"]:checked`);
            return selected ? selected.value : '';
        }

        // Add event listeners to all input fields
        document.addEventListener('DOMContentLoaded', function() {
            // Function point input fields
            const fpInputs = ['external_inputs', 'external_outputs', 'external_inquiries', 'internal_files', 'external_files'];
            
            fpInputs.forEach(inputName => {
                const input = document.getElementById(inputName);
                if (input) {
                    input.addEventListener('input', calculateFunctionPoints);
                    input.addEventListener('change', calculateFunctionPoints);
                }
            });

            // Complexity factor radio buttons
            const complexityInputs = document.querySelectorAll('input[name="complexity_factor"]');
            complexityInputs.forEach(input => {
                input.addEventListener('change', calculateFunctionPoints);
            });

            // Programming language select
            const languageSelect = document.getElementById('programming_language');
            if (languageSelect) {
                languageSelect.addEventListener('change', calculateFunctionPoints);
            }

            // Initial calculation
            calculateFunctionPoints();
        });
    </script>
</x-app-layout>