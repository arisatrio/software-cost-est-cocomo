<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create Software Cost Estimation') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <h3 class="p-3 text-gray-900 font-semibold text-lg">
                    Form Create Estimation
                </h3>
                <hr>
                <div class="p-6 text-gray-900">

                    <form action="{{ route('cocomo.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="project_name" class="block text-gray-700 text-sm font-bold mb-2">
                                Nama Proyek
                            </label>
                            <input type="text" name="project_name" id="project_name" 
                                   class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('project_name') border-red-500 @enderror" 
                                   placeholder="Masukkan nama proyek" value="{{ old('project_name') }}" required>
                            @error('project_name')
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
                                    <option value="java" {{ old('programming_language') == 'java' ? 'selected' : '' }}>Java</option>
                                    <option value="csharp" {{ old('programming_language') == 'csharp' ? 'selected' : '' }}>C#</option>
                                    <option value="cpp" {{ old('programming_language') == 'cpp' ? 'selected' : '' }}>C++</option>
                                    <option value="python" {{ old('programming_language') == 'python' ? 'selected' : '' }}>Python</option>
                                    <option value="javascript" {{ old('programming_language') == 'javascript' ? 'selected' : '' }}>JavaScript</option>
                                    <option value="php" {{ old('programming_language') == 'php' ? 'selected' : '' }}>PHP</option>
                                    <option value="ruby" {{ old('programming_language') == 'ruby' ? 'selected' : '' }}>Ruby</option>
                                    <option value="swift" {{ old('programming_language') == 'swift' ? 'selected' : '' }}>Swift</option>
                                    <option value="kotlin" {{ old('programming_language') == 'kotlin' ? 'selected' : '' }}>Kotlin</option>
                                    <option value="go" {{ old('programming_language') == 'go' ? 'selected' : '' }}>Go</option>
                                </select>
                                @error('programming_language')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">Bahasa pemrograman yang akan digunakan untuk mengembangkan aplikasi</p>
                            </div>

                            <!-- Question 1: Input Data & Transaksi (External Inputs) -->
                            <div class="mb-6 p-4 bg-white rounded-lg border">
                                <h4 class="font-semibold text-gray-800 mb-3">1. Input Data & Transaksi</h4>
                                <p class="text-sm text-gray-600 mb-4">Seberapa banyak dan seberapa rumit formulir atau layar untuk memasukkan data ke dalam aplikasi (misalnya, formulir pendaftaran, input pesanan, entry data master, dll)?</p>
                                <div class="space-y-2">
                                    <label class="flex items-start space-x-3 cursor-pointer">
                                        <input type="radio" name="q_inputs" value="low" class="mt-1" {{ old('q_inputs') == 'low' ? 'checked' : '' }} required>
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-800">(A) Minimalis</div>
                                            <div class="text-sm text-gray-600">Hanya ada beberapa formulir sederhana (1-5 form). Misalnya: login, register, contact form</div>
                                        </div>
                                    </label>
                                    <label class="flex items-start space-x-3 cursor-pointer">
                                        <input type="radio" name="q_inputs" value="medium" class="mt-1" {{ old('q_inputs') == 'medium' ? 'checked' : '' }}>
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-800">(B) Standar</div>
                                            <div class="text-sm text-gray-600">Ada beberapa formulir yang lebih kompleks (6-15 form). Misalnya: form transaksi, input produk, manajemen user</div>
                                        </div>
                                    </label>
                                    <label class="flex items-start space-x-3 cursor-pointer">
                                        <input type="radio" name="q_inputs" value="high" class="mt-1" {{ old('q_inputs') == 'high' ? 'checked' : '' }}>
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-800">(C) Kompleks</div>
                                            <div class="text-sm text-gray-600">Banyak formulir dengan logika dan validasi yang rumit (>15 form). Misalnya: ERP, workflow approval, multi-step forms</div>
                                        </div>
                                    </label>
                                </div>
                                @error('q_inputs')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Question 2: Laporan & Output (External Outputs) -->
                            <div class="mb-6 p-4 bg-white rounded-lg border">
                                <h4 class="font-semibold text-gray-800 mb-3">2. Laporan & Output</h4>
                                <p class="text-sm text-gray-600 mb-4">Seberapa banyak laporan, dokumen, atau file yang akan dihasilkan oleh aplikasi (misalnya, laporan penjualan, invoice, dashboard, export file, dll)?</p>
                                <div class="space-y-2">
                                    <label class="flex items-start space-x-3 cursor-pointer">
                                        <input type="radio" name="q_outputs" value="low" class="mt-1" {{ old('q_outputs') == 'low' ? 'checked' : '' }} required>
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-800">(A) Sedikit</div>
                                            <div class="text-sm text-gray-600">Hanya laporan basic (1-3 jenis). Misalnya: list data, simple report, basic export</div>
                                        </div>
                                    </label>
                                    <label class="flex items-start space-x-3 cursor-pointer">
                                        <input type="radio" name="q_outputs" value="medium" class="mt-1" {{ old('q_outputs') == 'medium' ? 'checked' : '' }}>
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-800">(B) Sedang</div>
                                            <div class="text-sm text-gray-600">Beberapa laporan dengan format berbeda (4-10 jenis). Misalnya: dashboard, grafik, multiple export format</div>
                                        </div>
                                    </label>
                                    <label class="flex items-start space-x-3 cursor-pointer">
                                        <input type="radio" name="q_outputs" value="high" class="mt-1" {{ old('q_outputs') == 'high' ? 'checked' : '' }}>
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-800">(C) Banyak</div>
                                            <div class="text-sm text-gray-600">Laporan kompleks dengan berbagai format dan filter (>10 jenis). Misalnya: advanced analytics, custom reports, scheduled reports</div>
                                        </div>
                                    </label>
                                </div>
                                @error('q_outputs')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Question 3: Pencarian & Query (External Inquiries) -->
                            <div class="mb-6 p-4 bg-white rounded-lg border">
                                <h4 class="font-semibold text-gray-800 mb-3">3. Pencarian & Query</h4>
                                <p class="text-sm text-gray-600 mb-4">Seberapa banyak fitur pencarian, filter, atau query yang tersedia dalam aplikasi (misalnya, search bar, filter data, lookup, dll)?</p>
                                <div class="space-y-2">
                                    <label class="flex items-start space-x-3 cursor-pointer">
                                        <input type="radio" name="q_inquiries" value="low" class="mt-1" {{ old('q_inquiries') == 'low' ? 'checked' : '' }} required>
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-800">(A) Basic</div>
                                            <div class="text-sm text-gray-600">Search sederhana (1-3 fitur). Misalnya: search bar, simple filter</div>
                                        </div>
                                    </label>
                                    <label class="flex items-start space-x-3 cursor-pointer">
                                        <input type="radio" name="q_inquiries" value="medium" class="mt-1" {{ old('q_inquiries') == 'medium' ? 'checked' : '' }}>
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-800">(B) Menengah</div>
                                            <div class="text-sm text-gray-600">Pencarian dengan beberapa kriteria (4-8 fitur). Misalnya: advanced search, multiple filters, sorting</div>
                                        </div>
                                    </label>
                                    <label class="flex items-start space-x-3 cursor-pointer">
                                        <input type="radio" name="q_inquiries" value="high" class="mt-1" {{ old('q_inquiries') == 'high' ? 'checked' : '' }}>
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-800">(C) Advanced</div>
                                            <div class="text-sm text-gray-600">Sistem pencarian yang kompleks (>8 fitur). Misalnya: faceted search, real-time search, complex queries</div>
                                        </div>
                                    </label>
                                </div>
                                @error('q_inquiries')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Question 4: Penyimpanan Data (Internal Files) -->
                            <div class="mb-6 p-4 bg-white rounded-lg border">
                                <h4 class="font-semibold text-gray-800 mb-3">4. Penyimpanan Data</h4>
                                <p class="text-sm text-gray-600 mb-4">Seberapa banyak dan kompleks data yang akan disimpan dalam aplikasi (misalnya, tabel database, master data, user data, dll)?</p>
                                <div class="space-y-2">
                                    <label class="flex items-start space-x-3 cursor-pointer">
                                        <input type="radio" name="q_internal_files" value="low" class="mt-1" {{ old('q_internal_files') == 'low' ? 'checked' : '' }} required>
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-800">(A) Sederhana</div>
                                            <div class="text-sm text-gray-600">Data sederhana (1-5 tabel). Misalnya: user data, basic content management</div>
                                        </div>
                                    </label>
                                    <label class="flex items-start space-x-3 cursor-pointer">
                                        <input type="radio" name="q_internal_files" value="medium" class="mt-1" {{ old('q_internal_files') == 'medium' ? 'checked' : '' }}>
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-800">(B) Menengah</div>
                                            <div class="text-sm text-gray-600">Struktur data yang cukup kompleks (6-15 tabel). Misalnya: e-commerce, inventory management</div>
                                        </div>
                                    </label>
                                    <label class="flex items-start space-x-3 cursor-pointer">
                                        <input type="radio" name="q_internal_files" value="high" class="mt-1" {{ old('q_internal_files') == 'high' ? 'checked' : '' }}>
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-800">(C) Kompleks</div>
                                            <div class="text-sm text-gray-600">Database dengan banyak tabel dan relasi (>15 tabel). Misalnya: ERP, CRM, financial system</div>
                                        </div>
                                    </label>
                                </div>
                                @error('q_internal_files')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Question 5: Integrasi Eksternal (External Files) -->
                            <div class="mb-6 p-4 bg-white rounded-lg border">
                                <h4 class="font-semibold text-gray-800 mb-3">5. Integrasi Eksternal</h4>
                                <p class="text-sm text-gray-600 mb-4">Apakah aplikasi akan terhubung dengan sistem lain atau layanan eksternal (misalnya, API payment, social media, email service, dll)?</p>
                                <div class="space-y-2">
                                    <label class="flex items-start space-x-3 cursor-pointer">
                                        <input type="radio" name="q_external_files" value="low" class="mt-1" {{ old('q_external_files') == 'low' ? 'checked' : '' }} required>
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-800">(A) Tidak ada / Minimal</div>
                                            <div class="text-sm text-gray-600">Aplikasi standalone atau integrasi minimal (0-1 layanan). Misalnya: hanya email notification</div>
                                        </div>
                                    </label>
                                    <label class="flex items-start space-x-3 cursor-pointer">
                                        <input type="radio" name="q_external_files" value="medium" class="mt-1" {{ old('q_external_files') == 'medium' ? 'checked' : '' }}>
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-800">(B) Beberapa Integrasi</div>
                                            <div class="text-sm text-gray-600">Integrasi dengan beberapa layanan (2-5 layanan). Misalnya: payment gateway, social login, SMS</div>
                                        </div>
                                    </label>
                                    <label class="flex items-start space-x-3 cursor-pointer">
                                        <input type="radio" name="q_external_files" value="high" class="mt-1" {{ old('q_external_files') == 'high' ? 'checked' : '' }}>
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-800">(C) Banyak Integrasi</div>
                                            <div class="text-sm text-gray-600">Integrasi ekstensif (>5 layanan). Misalnya: multiple APIs, third-party services, microservices</div>
                                        </div>
                                    </label>
                                </div>
                                @error('q_external_files')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Complexity Factor -->
                            <div class="mb-6 p-4 bg-white rounded-lg border">
                                <h4 class="font-semibold text-gray-800 mb-3">6. Tingkat Kompleksitas Keseluruhan</h4>
                                <p class="text-sm text-gray-600 mb-4">Bagaimana Anda menilai tingkat kompleksitas keseluruhan dari aplikasi yang akan dikembangkan?</p>
                                <div class="space-y-2">
                                    <label class="flex items-start space-x-3 cursor-pointer">
                                        <input type="radio" name="complexity_factor" value="simple" class="mt-1" {{ old('complexity_factor') == 'simple' ? 'checked' : '' }} required>
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-800">(A) Sederhana</div>
                                            <div class="text-sm text-gray-600">Aplikasi standar dengan logika bisnis yang mudah dipahami</div>
                                        </div>
                                    </label>
                                    <label class="flex items-start space-x-3 cursor-pointer">
                                        <input type="radio" name="complexity_factor" value="average" class="mt-1" {{ old('complexity_factor') == 'average' ? 'checked' : '' }}>
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-800">(B) Menengah</div>
                                            <div class="text-sm text-gray-600">Aplikasi dengan beberapa aturan bisnis dan workflow yang cukup kompleks</div>
                                        </div>
                                    </label>
                                    <label class="flex items-start space-x-3 cursor-pointer">
                                        <input type="radio" name="complexity_factor" value="complex" class="mt-1" {{ old('complexity_factor') == 'complex' ? 'checked' : '' }}>
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-800">(C) Kompleks</div>
                                            <div class="text-sm text-gray-600">Aplikasi dengan logika bisnis yang sangat kompleks, algoritma khusus, atau real-time processing</div>
                                        </div>
                                    </label>
                                </div>
                                @error('complexity_factor')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Function Point Summary -->
                            <div class="mt-4 p-3 bg-blue-50 rounded">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-700">Estimasi Function Point:</span>
                                    <span id="fp-total" class="text-lg font-bold text-blue-600">0 FP</span>
                                </div>
                                <div class="flex justify-between items-center mt-1">
                                    <span class="text-sm font-medium text-gray-700">Estimasi SLOC:</span>
                                    <span id="sloc-total" class="text-lg font-bold text-green-600">0 SLOC</span>
                                </div>
                                <div class="flex justify-between items-center mt-1">
                                    <span class="text-sm font-medium text-gray-700">Estimasi KLOC:</span>
                                    <span id="kloc-total" class="text-lg font-bold text-purple-600">0 KLOC</span>
                                </div>
                            </div>
                        </div>

                        <h2 class="text-xl font-bold">Faktor Skala</h2>
                        @foreach($scaleFactors as $factor)
                            <x-cocomo-question
                                name="{{ $factor->value }}"
                                label="{{ $factor->label() }}"
                                :options="$factor->options()"
                                description="{{ $factor->description() }}"
                            />
                        @endforeach

                        <h2 class="text-xl font-bold mt-8">Penggerak Biaya</h2>
                        @foreach($costDrivers as $driver)
                            <x-cocomo-question
                                name="{{ $driver->value }}"
                                label="{{ $driver->label() }}"
                                :options="$driver->options()"
                                description="{{ $driver->description() }}"
                            />
                        @endforeach

                        <!-- Hidden Fields for Backend Processing -->
                        <input type="hidden" name="kloc" id="hidden_kloc" value="0">
                        <input type="hidden" name="function_points" id="hidden_function_points" value="0">
                        <input type="hidden" name="total_sloc" id="hidden_total_sloc" value="0">
                        <input type="hidden" name="external_inputs" id="hidden_external_inputs" value="0">
                        <input type="hidden" name="external_outputs" id="hidden_external_outputs" value="0">
                        <input type="hidden" name="external_inquiries" id="hidden_external_inquiries" value="0">
                        <input type="hidden" name="internal_files" id="hidden_internal_files" value="0">
                        <input type="hidden" name="external_files" id="hidden_external_files" value="0">

                        <div class="mt-6">
                            <button type="submit"
                                class="inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ __('Hitung Estimasi') }}
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
            'simple': 10,
            'average': 14,
            'complex': 16
        };

        // Mapping kuisioner ke nilai Function Point
        const questionMapping = {
            'q_inputs': {
                'low': 3,      // 1-5 form
                'medium': 10,  // 6-15 form  
                'high': 20     // >15 form
            },
            'q_outputs': {
                'low': 2,      // 1-3 jenis
                'medium': 7,   // 4-10 jenis
                'high': 15     // >10 jenis
            },
            'q_inquiries': {
                'low': 2,      // 1-3 fitur
                'medium': 6,   // 4-8 fitur
                'high': 12     // >8 fitur
            },
            'q_internal_files': {
                'low': 2,      // 1-5 tabel
                'medium': 8,   // 6-15 tabel
                'high': 18     // >15 tabel
            },
            'q_external_files': {
                'low': 0,      // 0-1 layanan
                'medium': 3,   // 2-5 layanan
                'high': 8      // >5 layanan
            }
        };

        function calculateFunctionPoints() {
            // Get answers from questionnaire
            const inputs = getQuestionValue('q_inputs');
            const outputs = getQuestionValue('q_outputs');
            const inquiries = getQuestionValue('q_inquiries');
            const internalFiles = getQuestionValue('q_internal_files');
            const externalFiles = getQuestionValue('q_external_files');
            
            const complexity = getSelectedValue('complexity_factor');
            const language = document.getElementById('programming_language').value;

            // Calculate unadjusted function points using questionnaire values
            const unadjustedFP = (inputs * fpWeights.external_inputs) +
                                 (outputs * fpWeights.external_outputs) +
                                 (inquiries * fpWeights.external_inquiries) +
                                 (internalFiles * fpWeights.internal_files) +
                                 (externalFiles * fpWeights.external_files);

            // Apply complexity adjustment
            const complexityFactor = complexityMultipliers[complexity] / 10;
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
            document.getElementById('hidden_external_inputs').value = inputs;
            document.getElementById('hidden_external_outputs').value = outputs;
            document.getElementById('hidden_external_inquiries').value = inquiries;
            document.getElementById('hidden_internal_files').value = internalFiles;
            document.getElementById('hidden_external_files').value = externalFiles;

            return {
                functionPoints: adjustedFP,
                sloc: sloc,
                kloc: sloc / 1000,
                // Hidden values for backend
                external_inputs: inputs,
                external_outputs: outputs,
                external_inquiries: inquiries,
                internal_files: internalFiles,
                external_files: externalFiles
            };
        }

        function getQuestionValue(questionName) {
            const selectedOption = getSelectedValue(questionName);
            return questionMapping[questionName] ? (questionMapping[questionName][selectedOption] || 0) : 0;
        }

        function getSelectedValue(name) {
            const selected = document.querySelector(`input[name="${name}"]:checked`);
            return selected ? selected.value : '';
        }

        // Add event listeners to all input fields
        document.addEventListener('DOMContentLoaded', function() {
            const questionNames = ['q_inputs', 'q_outputs', 'q_inquiries', 'q_internal_files', 'q_external_files', 'complexity_factor'];
            
            questionNames.forEach(questionName => {
                const inputs = document.querySelectorAll(`input[name="${questionName}"]`);
                inputs.forEach(input => {
                    input.addEventListener('change', calculateFunctionPoints);
                });
            });

            const languageSelect = document.getElementById('programming_language');
            if (languageSelect) {
                languageSelect.addEventListener('change', calculateFunctionPoints);
            }

            // Initial calculation
            calculateFunctionPoints();
        });
    </script>
</x-app-layout>
