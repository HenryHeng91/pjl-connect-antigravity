{{-- PJL Connect Header --}}

<header class="sticky top-0 z-30 border-b border-gray-200 bg-white">
    <div class="flex h-16 items-center justify-between px-4 sm:px-6 lg:px-8">
        {{-- Left: Hamburger (mobile) + Page Title --}}
        <div class="flex items-center gap-4">
            {{-- Mobile Hamburger Toggle --}}
            <button x-on:click="sidebarOpen = !sidebarOpen"
                    class="rounded-lg p-2 text-gray-600 hover:bg-gray-100 hover:text-deep-teal lg:hidden"
                    aria-label="Toggle sidebar">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Page Title --}}
            <h1 class="text-lg font-semibold text-gray-900">
                @yield('page-title', 'Dashboard')
            </h1>
        </div>

        {{-- Right: Actions --}}
        <div class="flex items-center gap-3">
            {{-- Sky Blue accent indicator --}}
            <div class="hidden h-2 w-2 rounded-full bg-sky-blue sm:block" title="System Online"></div>

            {{-- Notification Bell Placeholder --}}
            <button class="relative rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-deep-teal"
                    aria-label="Notifications">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </button>

            {{-- User Avatar Placeholder --}}
            <button class="flex items-center gap-2 rounded-lg p-1.5 hover:bg-gray-100">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-deep-teal text-xs font-bold text-white">
                    U
                </div>
                <span class="hidden text-sm font-medium text-gray-700 sm:block">User</span>
            </button>
        </div>
    </div>

    {{-- Sky Blue accent line --}}
    <div class="h-0.5 bg-gradient-to-r from-deep-teal via-sky-blue to-deep-teal"></div>
</header>
