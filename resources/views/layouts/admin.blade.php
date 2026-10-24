<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="bg-slate-900 text-slate-100 w-64 flex-shrink-0 flex flex-col">
            <div class="px-6 py-4 border-b border-slate-800">
                <h1 class="text-xl font-bold tracking-tight">{{ config('app.name') }}</h1>
                <p class="text-xs text-slate-400 mt-1">Panneau d'administration</p>
            </div>

            <nav class="flex-1 px-2 py-4 space-y-1 text-sm">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-md transition
                    {{ request()->routeIs('admin.dashboard') ? 'bg-slate-800 text-white' : 'text-slate-200 hover:bg-slate-800/70' }}">
                    <span class="w-4 h-4 rounded bg-emerald-400/80 inline-block"></span>
                    <span>Dashboard</span>
                </a>

                <p class="px-3 pt-4 text-xs font-semibold text-slate-500 uppercase tracking-wide">Contenu</p>

                <a href="{{ route('admin.projects.index') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-md transition
                    {{ request()->routeIs('admin.projects.*') ? 'bg-slate-800 text-white' : 'text-slate-200 hover:bg-slate-800/70' }}">
                    <span class="w-1.5 h-6 rounded-full bg-blue-400/80 inline-block"></span>
                    <span>Projets</span>
                </a>

                <a href="{{ route('admin.categories.index') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-md transition
                    {{ request()->routeIs('admin.categories.*') ? 'bg-slate-800 text-white' : 'text-slate-200 hover:bg-slate-800/70' }}">
                    <span class="w-1.5 h-6 rounded-full bg-sky-400/80 inline-block"></span>
                    <span>Catégories</span>
                </a>

                <a href="{{ route('admin.skills.index') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-md transition
                    {{ request()->routeIs('admin.skills.*') ? 'bg-slate-800 text-white' : 'text-slate-200 hover:bg-slate-800/70' }}">
                    <span class="w-1.5 h-6 rounded-full bg-amber-400/80 inline-block"></span>
                    <span>Compétences</span>
                </a>

                <a href="{{ route('admin.settings.edit') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-md transition
                    {{ request()->routeIs('admin.settings.*') ? 'bg-slate-800 text-white' : 'text-slate-200 hover:bg-slate-800/70' }}">
                    <span class="w-1.5 h-6 rounded-full bg-violet-400/80 inline-block"></span>
                    <span>Paramètres du site</span>
                </a>

                <p class="px-3 pt-4 text-xs font-semibold text-slate-500 uppercase tracking-wide">Site public</p>

                <a href="{{ route('home') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-md text-slate-200 hover:bg-slate-800/70 transition">
                    <span class="w-4 h-4 rounded-full bg-amber-400/80 inline-block"></span>
                    <span>Voir le site</span>
                </a>
            </nav>

            <div class="px-4 py-4 border-t border-slate-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-between px-3 py-2 text-xs text-slate-300 rounded-md hover:bg-slate-800/80 transition">
                        <span>Déconnexion</span>
                        <span class="text-[10px] uppercase tracking-wide text-slate-500">Admin</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-slate-100">
                <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Espace d'administration</h2>
                        <p class="text-xs text-slate-500 mt-1">Gérez les contenus affichés sur le site public.</p>
                    </div>

                    @auth
                        <div class="flex items-center gap-3">
                            <div class="text-right">
                                <p class="text-xs font-medium text-slate-800">{{ auth()->user()->name ?? 'Admin' }}</p>
                                <p class="text-[11px] text-slate-500">Connecté</p>
                            </div>
                            <div
                                class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center text-xs font-semibold text-white">
                                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                            </div>
                        </div>
                    @endauth
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1">
                <div class="max-w-6xl mx-auto px-6 py-6">
                    @if (session('success'))
                        <div
                            class="mb-4 rounded-md bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-800">
                            {{ session('success') }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>

</html>
