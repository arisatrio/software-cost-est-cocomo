<div class="mb-4">
    <label for="{{ $name }}" class="block text-gray-700 text-sm font-bold mb-2">
        {{ $label }}
        @if($description)
            <p class="text-gray-500 text-xs mt-1">{{ $description }}</p>
        @endif
    </label>
    <select name="{{ $name }}" id="{{ $name }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        <option value="">Pilih rating...</option>
        @foreach($options as $option)
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
            <option value="{{ $charValue }}">{{ $displayText }}</option>
        @endforeach
    </select>
</div>