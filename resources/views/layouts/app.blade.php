<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PJL Connect') }} — @yield('title', 'Dashboard')</title>

    <!-- Fonts: Inter (primary per UX spec) -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 font-sans text-gray-900 antialiased"
      x-data="{ sidebarOpen: false }"
      x-on:keydown.escape.window="sidebarOpen = false">

    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen"
         x-transition:enter="transition-opacity duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-black/50 lg:hidden"
         x-on:click="sidebarOpen = false"
         x-cloak>
    </div>

    <!-- Sidebar -->
    <aside class="fixed top-0 left-0 z-50 h-full w-64 -translate-x-full transform bg-deep-teal transition-transform duration-300 ease-in-out lg:translate-x-0"
           x-bind:class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
        @include('partials.sidebar')
    </aside>

    <!-- Main Content Wrapper -->
    <div class="lg:ml-64">
        <!-- Header -->
        @include('partials.header')

        <!-- Page Content -->
        <main class="p-4 sm:p-6 lg:p-8">
            @yield('content')
        </main>

        <!-- Footer -->
        @include('partials.footer')
    </div>

    @livewireScripts
</body>
</html>
