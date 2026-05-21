<div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-8">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">Frequently Asked Questions</h1>

    @forelse($faqs as $category => $items)
        <div class="mb-8">
            @if($category)
                <h2 class="text-sm font-semibold text-sky-600 dark:text-sky-400 uppercase tracking-wider mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                    {{ $category }}
                </h2>
            @endif

            <div class="space-y-2">
                @foreach($items as $faq)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                        <button type="button" onclick="toggleFaq({{ $faq->id }})"
                            class="w-full text-left px-5 py-4 flex items-center justify-between
                                bg-gray-50 dark:bg-gray-800/50 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                            <span class="font-medium text-gray-800 dark:text-gray-200 text-sm">{{ $faq->question }}</span>
                            <span id="icon-{{ $faq->id }}" class="text-gray-400 dark:text-gray-500 text-xl leading-none ml-4 flex-shrink-0">+</span>
                        </button>
                        <div id="answer-{{ $faq->id }}" class="hidden px-5 py-4 text-sm text-gray-600 dark:text-gray-400 leading-relaxed border-t border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900">
                            {{ $faq->answer }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <p class="text-gray-500 dark:text-gray-400 text-center py-8">No FAQs available at this time.</p>
    @endforelse
</div>

<script>
function toggleFaq(id) {
    var answer = document.getElementById('answer-' + id);
    var icon   = document.getElementById('icon-' + id);
    answer.classList.toggle('hidden');
    icon.textContent = answer.classList.contains('hidden') ? '+' : '−';
}
</script>
