<div class="bg-white rounded-lg shadow-sm p-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-8">Frequently Asked Questions</h1>

    @forelse($faqs as $category => $items)
        <div class="mb-8">
            @if($category)
                <h2 class="text-lg font-semibold text-indigo-700 mb-4 pb-2 border-b border-indigo-100">
                    {{ $category }}
                </h2>
            @endif

            <div class="space-y-3">
                @foreach($items as $faq)
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <button type="button" onclick="toggleFaq({{ $faq->id }})"
                            class="w-full text-left px-5 py-4 flex items-center justify-between bg-gray-50 hover:bg-gray-100 transition">
                            <span class="font-medium text-gray-800 text-sm">{{ $faq->question }}</span>
                            <span id="icon-{{ $faq->id }}" class="text-gray-400 text-xl leading-none ml-4">+</span>
                        </button>
                        <div id="answer-{{ $faq->id }}" class="hidden px-5 py-4 text-sm text-gray-700 leading-relaxed border-t border-gray-100">
                            {{ $faq->answer }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <p class="text-gray-500 text-center py-8">No FAQs available at this time.</p>
    @endforelse
</div>

<script>
function toggleFaq(id) {
    const answer = document.getElementById('answer-' + id);
    const icon   = document.getElementById('icon-' + id);
    answer.classList.toggle('hidden');
    icon.textContent = answer.classList.contains('hidden') ? '+' : '−';
}
</script>
