<button
    id="theme-toggle"
    onclick="toggleTheme()"
    title="Toggle dark/light mode"
    class="w-9 h-9 rounded-lg flex items-center justify-center transition
        bg-gray-100 hover:bg-gray-200 text-gray-600
        dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-gray-300
        border border-gray-200 dark:border-gray-700">

    <!-- Sun icon (shown in dark mode) -->
    <svg id="icon-sun" class="w-4 h-4 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
    </svg>

    <!-- Moon icon (shown in light mode) -->
    <svg id="icon-moon" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
    </svg>
</button>
