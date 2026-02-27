{{-- PJL Connect Footer --}}

<footer class="border-t border-gray-200 px-4 py-4 sm:px-6 lg:px-8">
    <div class="flex flex-col items-center justify-between gap-2 text-sm text-gray-500 sm:flex-row">
        <p>&copy; {{ date('Y') }} PJL Connect. All rights reserved.</p>
        @if(app()->environment('local'))
            <p class="text-xs text-gray-400">Powered by Laravel {{ app()->version() }}</p>
        @endif
    </div>
</footer>
