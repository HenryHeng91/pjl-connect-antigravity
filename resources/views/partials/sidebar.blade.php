{{-- PJL Connect Sidebar Navigation --}}
{{-- Based on TailAdmin layout, customized with PJL brand colors --}}

<div class="flex h-full flex-col">
    {{-- Logo Section --}}
    <div class="flex h-16 items-center justify-center border-b border-white/10 px-6">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-sky-blue text-xs font-bold text-white">
                PJL
            </div>
            <span class="text-lg font-bold tracking-tight text-white">PJL Connect</span>
        </a>
    </div>

    {{-- Navigation Menu --}}
    <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
           class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-sidebar-active text-white' : 'text-white/80 hover:bg-sidebar-hover hover:text-white' }}">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        {{-- Section Divider: Operations --}}
        <div class="px-3 pb-1 pt-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-white/40">Operations</p>
        </div>

        {{-- Jobs (Epic 3) --}}
        <a href="#"
           class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-white/80 transition-colors hover:bg-sidebar-hover hover:text-white">
            {{-- Roles: ADM, OPSM, OPS --}}
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Jobs
        </a>

        {{-- Bookings (Epic 2) --}}
        <a href="#"
           class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-white/80 transition-colors hover:bg-sidebar-hover hover:text-white">
            {{-- Roles: ADM, OPSM, OPS --}}
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            Bookings
        </a>

        {{-- Tracking (Epic 5) --}}
        <a href="#"
           class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-white/80 transition-colors hover:bg-sidebar-hover hover:text-white">
            {{-- Roles: ADM, OPSM, OPS --}}
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Tracking
        </a>

        {{-- Customers (Epic 1) --}}
        <a href="#"
           class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-white/80 transition-colors hover:bg-sidebar-hover hover:text-white">
            {{-- Roles: ADM, OPSM, OPS --}}
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            Customers
        </a>

        {{-- Section Divider: Compliance & Finance --}}
        <div class="px-3 pb-1 pt-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-white/40">Compliance & Finance</p>
        </div>

        {{-- Compliance (Epic 6) --}}
        <a href="#"
           class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-white/80 transition-colors hover:bg-sidebar-hover hover:text-white">
            {{-- Roles: ADM, OPSM, BRK --}}
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Compliance
        </a>

        {{-- Financial (Epic 7) --}}
        <a href="#"
           class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-white/80 transition-colors hover:bg-sidebar-hover hover:text-white">
            {{-- Roles: ADM, OPSM, ACCT --}}
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Financial
        </a>

        {{-- Section Divider: Administration --}}
        <div class="px-3 pb-1 pt-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-white/40">Administration</p>
        </div>

        {{-- Admin (Epic 8) --}}
        <a href="#"
           class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-white/80 transition-colors hover:bg-sidebar-hover hover:text-white">
            {{-- Roles: SADM, ADM --}}
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Admin
        </a>
    </nav>

    {{-- Sidebar Footer --}}
    <div class="border-t border-white/10 p-4">
        <div class="flex items-center gap-3">
            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-sky-blue text-xs font-bold text-white">
                U
            </div>
            <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-medium text-white">User</p>
                <p class="truncate text-xs text-white/50">Placeholder</p>
            </div>
        </div>
    </div>
</div>
