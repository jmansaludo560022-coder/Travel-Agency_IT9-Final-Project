{{-- 
    Reusable package form fields for inclusions, exclusions, and itinerary.
    Props: $package (optional, for edit mode), $inputClass, $labelClass
--}}

@php
    $inputClass = $inputClass ?? 'w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition text-sm';
    $labelClass = $labelClass ?? 'block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5';

    $commonInclusions = [
        'Round-trip airfare',
        'Hotel accommodation',
        'Daily breakfast',
        'Airport transfers',
        'Tour guide',
        'City tour',
        'Entrance fees',
        'Travel insurance',
        'Visa assistance',
        'Welcome dinner',
    ];

    $commonExclusions = [
        'Personal expenses',
        'Lunch and dinner',
        'Optional excursions',
        'Alcoholic beverages',
        'Visa fees',
        'Tips and gratuities',
        'Travel insurance',
        'Laundry services',
        'Phone calls',
        'Medical expenses',
    ];

    // Parse existing values (newline-separated text)
    $existingInclusions = isset($package) && $package->inclusions
        ? array_map('trim', explode("\n", $package->inclusions))
        : [];
    $existingExclusions = isset($package) && $package->exclusions
        ? array_map('trim', explode("\n", $package->exclusions))
        : [];
    $existingItinerary = isset($package) && $package->itinerary
        ? array_map('trim', explode("\n", $package->itinerary))
        : [];
@endphp

{{-- ── INCLUSIONS ─────────────────────────────────────────── --}}
<div>
    <label class="{{ $labelClass }}">Inclusions</label>
    <div class="border border-gray-300 dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-800/50 space-y-2">
        <div class="grid grid-cols-2 gap-2">
            @foreach($commonInclusions as $item)
            <label class="flex items-center gap-2 cursor-pointer group">
                <input type="checkbox" name="inclusions_check[]" value="{{ $item }}"
                    {{ in_array($item, $existingInclusions) ? 'checked' : '' }}
                    class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 text-sky-500 focus:ring-sky-500">
                <span class="text-sm text-gray-700 dark:text-gray-300 group-hover:text-sky-600 dark:group-hover:text-sky-400 transition">{{ $item }}</span>
            </label>
            @endforeach
        </div>

        {{-- Other / custom --}}
        <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
            <label class="flex items-center gap-2 cursor-pointer mb-2">
                <input type="checkbox" id="inclusions_other_check"
                    {{ collect($existingInclusions)->diff($commonInclusions)->isNotEmpty() ? 'checked' : '' }}
                    onchange="document.getElementById('inclusions_other_input').classList.toggle('hidden', !this.checked)"
                    class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 text-sky-500 focus:ring-sky-500">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Other (specify)</span>
            </label>
            <textarea id="inclusions_other_input" name="inclusions_other"
                rows="2" placeholder="Enter additional inclusions, one per line..."
                class="{{ $inputClass }} {{ collect($existingInclusions)->diff($commonInclusions)->isNotEmpty() ? '' : 'hidden' }}">{{ collect($existingInclusions)->diff($commonInclusions)->implode("\n") }}</textarea>
        </div>
    </div>
    {{-- Hidden field to store final value --}}
    <input type="hidden" name="inclusions" id="inclusions_hidden" value="{{ old('inclusions', isset($package) ? $package->inclusions : '') }}">
</div>

{{-- ── EXCLUSIONS ─────────────────────────────────────────── --}}
<div>
    <label class="{{ $labelClass }}">Exclusions</label>
    <div class="border border-gray-300 dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-800/50 space-y-2">
        <div class="grid grid-cols-2 gap-2">
            @foreach($commonExclusions as $item)
            <label class="flex items-center gap-2 cursor-pointer group">
                <input type="checkbox" name="exclusions_check[]" value="{{ $item }}"
                    {{ in_array($item, $existingExclusions) ? 'checked' : '' }}
                    class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 text-sky-500 focus:ring-sky-500">
                <span class="text-sm text-gray-700 dark:text-gray-300 group-hover:text-sky-600 dark:group-hover:text-sky-400 transition">{{ $item }}</span>
            </label>
            @endforeach
        </div>

        {{-- Other / custom --}}
        <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
            <label class="flex items-center gap-2 cursor-pointer mb-2">
                <input type="checkbox" id="exclusions_other_check"
                    {{ collect($existingExclusions)->diff($commonExclusions)->isNotEmpty() ? 'checked' : '' }}
                    onchange="document.getElementById('exclusions_other_input').classList.toggle('hidden', !this.checked)"
                    class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 text-sky-500 focus:ring-sky-500">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Other (specify)</span>
            </label>
            <textarea id="exclusions_other_input" name="exclusions_other"
                rows="2" placeholder="Enter additional exclusions, one per line..."
                class="{{ $inputClass }} {{ collect($existingExclusions)->diff($commonExclusions)->isNotEmpty() ? '' : 'hidden' }}">{{ collect($existingExclusions)->diff($commonExclusions)->implode("\n") }}</textarea>
        </div>
    </div>
    <input type="hidden" name="exclusions" id="exclusions_hidden" value="{{ old('exclusions', isset($package) ? $package->exclusions : '') }}">
</div>

{{-- ── ITINERARY ───────────────────────────────────────────── --}}
<div>
    <label class="{{ $labelClass }}">Itinerary</label>
    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
        Days are auto-generated from your start and end dates. Fill in the activity for each day.
    </p>
    <div id="itinerary-container" class="space-y-3">
        {{-- Populated by JS based on dates --}}
    </div>
    <input type="hidden" name="itinerary" id="itinerary_hidden" value="{{ old('itinerary', isset($package) ? $package->itinerary : '') }}">
    <p id="itinerary-placeholder" class="text-sm text-gray-400 dark:text-gray-500 italic py-4 text-center border border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
        Select start and end dates above to generate itinerary days.
    </p>
</div>

<script>
// ── Build itinerary from dates ──────────────────────────────
function buildItinerary() {
    const startInput = document.querySelector('[name="start_date"]');
    const endInput   = document.querySelector('[name="end_date"]');
    const container  = document.getElementById('itinerary-container');
    const placeholder = document.getElementById('itinerary-placeholder');
    const hidden     = document.getElementById('itinerary_hidden');

    if (!startInput || !endInput || !startInput.value || !endInput.value) {
        container.innerHTML = '';
        placeholder.classList.remove('hidden');
        return;
    }

    const start = new Date(startInput.value);
    const end   = new Date(endInput.value);
    if (end <= start) { container.innerHTML = ''; placeholder.classList.remove('hidden'); return; }

    placeholder.classList.add('hidden');

    // Parse existing itinerary lines
    const existingLines = (hidden.value || '').split('\n').map(l => l.replace(/^Day \d+: ?/, '').trim());

    const days = Math.round((end - start) / (1000 * 60 * 60 * 24)) + 1;
    container.innerHTML = '';

    for (let i = 0; i < days; i++) {
        const date = new Date(start);
        date.setDate(start.getDate() + i);
        const dateStr = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        const existing = existingLines[i] || '';

        const div = document.createElement('div');
        div.className = 'flex items-start gap-3 p-3 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800';
        div.innerHTML = `
            <div class="flex-shrink-0 w-20 text-center">
                <span class="block text-xs font-bold text-sky-600 dark:text-sky-400">Day ${i + 1}</span>
                <span class="block text-xs text-gray-400 mt-0.5">${dateStr}</span>
            </div>
            <input type="text" data-day="${i}"
                value="${existing.replace(/"/g, '&quot;')}"
                placeholder="Describe Day ${i + 1} activities..."
                oninput="syncItinerary()"
                class="flex-1 px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition">
        `;
        container.appendChild(div);
    }
}

function syncItinerary() {
    const inputs = document.querySelectorAll('#itinerary-container input[data-day]');
    const lines = Array.from(inputs).map((inp, i) => `Day ${i + 1}: ${inp.value}`);
    document.getElementById('itinerary_hidden').value = lines.join('\n');
}

function syncInclusionsExclusions() {
    // Inclusions
    const checked = Array.from(document.querySelectorAll('[name="inclusions_check[]"]:checked')).map(c => c.value);
    const other = document.getElementById('inclusions_other_input').value.trim();
    const otherLines = other ? other.split('\n').map(l => l.trim()).filter(Boolean) : [];
    document.getElementById('inclusions_hidden').value = [...checked, ...otherLines].join('\n');

    // Exclusions
    const excChecked = Array.from(document.querySelectorAll('[name="exclusions_check[]"]:checked')).map(c => c.value);
    const excOther = document.getElementById('exclusions_other_input').value.trim();
    const excOtherLines = excOther ? excOther.split('\n').map(l => l.trim()).filter(Boolean) : [];
    document.getElementById('exclusions_hidden').value = [...excChecked, ...excOtherLines].join('\n');
}

// Attach listeners
document.addEventListener('DOMContentLoaded', function () {
    // Date change → rebuild itinerary
    const startInput = document.querySelector('[name="start_date"]');
    const endInput   = document.querySelector('[name="end_date"]');
    if (startInput) startInput.addEventListener('change', buildItinerary);
    if (endInput)   endInput.addEventListener('change', buildItinerary);

    // Checkbox change → sync hidden fields
    document.querySelectorAll('[name="inclusions_check[]"], [name="exclusions_check[]"]').forEach(cb => {
        cb.addEventListener('change', syncInclusionsExclusions);
    });
    document.getElementById('inclusions_other_input')?.addEventListener('input', syncInclusionsExclusions);
    document.getElementById('exclusions_other_input')?.addEventListener('input', syncInclusionsExclusions);

    // Build on load (for edit mode)
    buildItinerary();
    syncInclusionsExclusions();

    // Sync before form submit
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function () {
            syncItinerary();
            syncInclusionsExclusions();
        });
    }
});
</script>
