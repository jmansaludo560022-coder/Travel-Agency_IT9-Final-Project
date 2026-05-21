const activityOptions = [
    'Hotel Check-in', 'Hotel Check-out',
    'Airport Pickup', 'Airport Drop-off',
    'City Tour', 'Island Hopping', 'Beach Visit',
    'Snorkeling', 'Diving', 'Surfing',
    'Hiking / Trekking', 'Mountain Climbing',
    'Cultural Tour', 'Museum Visit', 'Heritage Walk',
    'Shopping', 'Night Market Visit',
    'Boat Ride', 'River Cruise',
    'Breakfast at Hotel', 'Lunch at Restaurant', 'Dinner at Restaurant',
    'Welcome Dinner', 'Farewell Dinner',
    'Free Time / Leisure', 'Rest Day',
    'Spa & Wellness', 'Adventure Sports',
    'Wildlife Safari', 'Nature Walk',
    'Photography Tour', 'Cooking Class',
];

const existingItinerary = @json($existingItinerary ?? []);

function buildItinerary() {
    const startVal = document.getElementById('start_date').value;
    const endVal   = document.getElementById('end_date').value;
    const container = document.getElementById('itinerary-container');
    const placeholder = document.getElementById('itinerary-placeholder');

    if (!startVal || !endVal) {
        container.innerHTML = '<p class="text-sm text-gray-400 italic" id="itinerary-placeholder">No dates selected yet.</p>';
        return;
    }

    const start = new Date(startVal + 'T00:00:00');
    const end   = new Date(endVal   + 'T00:00:00');

    if (end < start) {
        container.innerHTML = '<p class="text-sm text-red-500 italic">End date must be on or after start date.</p>';
        return;
    }

    // Build existing map: date string -> array of activities
    const existingMap = {};
    (existingItinerary || []).forEach(function(day) {
        existingMap[day.date] = day.activities || [];
    });

    let html = '';
    let dayNum = 1;
    let current = new Date(start);

    while (current <= end) {
        const dateStr = current.toISOString().split('T')[0];
        const label   = current.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
        const checked = existingMap[dateStr] || [];
        const idx     = dayNum - 1;

        html += `
        <div class="border border-gray-200 rounded-lg overflow-hidden">
            <div class="bg-indigo-50 px-4 py-2 flex items-center gap-2">
                <span class="text-sm font-semibold text-indigo-700">Day ${dayNum}</span>
                <span class="text-sm text-gray-500">(${label})</span>
                <input type="hidden" name="itinerary[${idx}][date]" value="${dateStr}">
            </div>
            <div class="p-4 grid grid-cols-2 md:grid-cols-3 gap-2">
        `;

        activityOptions.forEach(function(activity) {
            const isChecked = checked.includes(activity) ? 'checked' : '';
            html += `
                <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                    <input type="checkbox" name="itinerary[${idx}][activities][]" value="${activity}" ${isChecked}
                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    ${activity}
                </label>
            `;
        });

        html += `</div></div>`;
        current.setDate(current.getDate() + 1);
        dayNum++;
    }

    container.innerHTML = html;
}

document.getElementById('start_date').addEventListener('change', buildItinerary);
document.getElementById('end_date').addEventListener('change', buildItinerary);

// Run on page load (for edit form with existing values)
document.addEventListener('DOMContentLoaded', buildItinerary);
