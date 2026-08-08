<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light dark">
    <title>API Documentation - {{ config('app.name', 'Pelican') }}</title>

    <link rel="icon" href="{{ config('app.favicon', '/pelican.ico') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet">

    @vite('resources/css/app.css')

    <style>
        body { font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', sans-serif; }
    </style>

    {{-- Respect the Panel's dark mode setting (shared 'theme' key in localStorage), matching the
         default used by resources/views/vendor/scramble/docs.blade.php. --}}
    <script>
        const theme = localStorage.getItem('theme') === 'system'
            ? (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light')
            : localStorage.getItem('theme') ?? 'dark';

        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body class="flex min-h-screen items-center justify-center bg-zinc-50 p-4 antialiased dark:bg-zinc-950">
    <div class="w-full max-w-md rounded-2xl bg-white p-8 text-center shadow-sm ring-1 ring-zinc-950/5 dark:bg-zinc-900 dark:ring-white/10">
        @if(config('app.logo'))
            <img src="{{ config('app.logo') }}" alt="{{ config('app.name', 'Pelican') }}" class="mx-auto mb-4 h-10 w-auto">
        @endif

        <h1 class="text-xl font-semibold text-zinc-950 dark:text-white">API Documentation</h1>
        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Browse the {{ config('app.name', 'Pelican') }} API reference.</p>

        <div class="mt-6 flex flex-col gap-3">
            <a
                href="/docs/api/application"
                class="flex items-center justify-center gap-2 rounded-lg bg-blue-50 px-4 py-2.5 text-sm font-medium text-blue-600 ring-1 ring-blue-600/10 transition hover:bg-blue-100 dark:bg-blue-500/10 dark:text-blue-400 dark:ring-blue-400/20 dark:hover:bg-blue-500/20"
            >
                <x-tabler-book class="h-5 w-5 shrink-0" />
                Application API
            </a>

            <a
                href="/docs/api/client"
                class="flex items-center justify-center gap-2 rounded-lg bg-emerald-50 px-4 py-2.5 text-sm font-medium text-emerald-600 ring-1 ring-emerald-600/10 transition hover:bg-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-400 dark:ring-emerald-400/20 dark:hover:bg-emerald-500/20"
            >
                <x-tabler-terminal-2 class="h-5 w-5 shrink-0" />
                Client API
            </a>
        </div>

        @guest
            <div class="mt-6 flex items-center justify-center gap-2 text-sm text-amber-600 dark:text-amber-400">
                <x-tabler-alert-triangle class="h-4 w-4 shrink-0" />
                <span>You need to be logged in to view the API docs.</span>
            </div>
        @endguest
    </div>
</body>
</html>
