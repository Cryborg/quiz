<!DOCTYPE html>
<html lang="fr" id="app">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Quiz Multijoueurs</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 min-h-screen transition-colors duration-300">
    <div class="container mx-auto px-2 sm:px-4 py-4 sm:py-8">
        <!-- Header -->
        <header class="text-center mb-8 sm:mb-12">
            <div class="flex flex-col sm:flex-row justify-between items-center sm:items-start mb-6 sm:mb-8 space-y-4 sm:space-y-0">
                <div class="sm:w-16"></div> <!-- Spacer pour desktop -->
                <div class="text-center">
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-indigo-800 dark:text-indigo-300 mb-2 sm:mb-4">
                        <i class="fas fa-brain mr-2 sm:mr-3"></i>{{ config('app.name') }}
                    </h1>
                    <p class="text-base sm:text-lg lg:text-xl text-gray-600 dark:text-gray-300 mb-4 sm:mb-6 px-4">Quiz oral pour 2 joueurs qui alternent les rôles</p>
                </div>
                <!-- Theme Toggle -->
                <button onclick="toggleTheme()" 
                        class="p-2 sm:p-3 rounded-full bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-300">
                    <i class="fas fa-moon dark:hidden text-gray-600"></i>
                    <i class="fas fa-sun hidden dark:inline text-yellow-400"></i>
                </button>
            </div>
            <div class="flex flex-col sm:flex-row justify-center gap-3 sm:gap-4">
                <a href="/admin" class="bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-600 text-white px-4 py-2 sm:px-6 sm:py-3 rounded-lg font-semibold transition duration-300 text-sm sm:text-base">
                    <i class="fas fa-cog mr-1 sm:mr-2"></i>Administration
                </a>
                <a href="{{ route('game.create') }}" class="bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-600 text-white px-4 py-2 sm:px-6 sm:py-3 rounded-lg font-semibold transition duration-300 text-sm sm:text-base">
                    <i class="fas fa-play mr-1 sm:mr-2"></i>Nouvelle Partie
                </a>
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-8">
            <!-- Thèmes disponibles -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 sm:p-6">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-gray-100 mb-4">
                    <i class="fas fa-tags mr-2 text-blue-600 dark:text-blue-400"></i>Thèmes Disponibles
                </h2>
                @if($themes->count() > 0)
                    <div class="space-y-3">
                        @foreach($themes as $theme)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                                <div>
                                    <h3 class="font-semibold text-gray-800 dark:text-gray-100">{{ $theme->name }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $theme->description }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-1 rounded-full text-sm font-medium">
                                        {{ $theme->questions_count }} questions
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-exclamation-circle text-4xl text-gray-400 dark:text-gray-500 mb-4"></i>
                        <p class="text-gray-600 dark:text-gray-300">Aucun thème disponible</p>
                        <a href="/admin" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium mt-2 inline-block">
                            Créer des thèmes dans l'administration
                        </a>
                    </div>
                @endif
            </div>

            <!-- Sessions récentes -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-4">
                    <i class="fas fa-history mr-2 text-green-600 dark:text-green-400"></i>Sessions Récentes
                </h2>
                @if($recentSessions->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentSessions as $session)
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="font-semibold text-gray-800 dark:text-gray-100">{{ $session->name }}</h3>
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        @if($session->status === 'waiting') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                                        @elseif($session->status === 'playing') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                        @else bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200 @endif">
                                        {{ ucfirst($session->status) }}
                                    </span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $session->created_at->diffForHumans() }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-gamepad text-4xl text-gray-400 dark:text-gray-500 mb-4"></i>
                        <p class="text-gray-600 dark:text-gray-300">Aucune session récente</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Créez votre première partie !</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Comment jouer -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mt-8">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-4">
                <i class="fas fa-question-circle mr-2 text-purple-600 dark:text-purple-400"></i>Comment jouer ?
            </h2>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="bg-blue-100 dark:bg-blue-900 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-eye text-2xl text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-2">1. Maître du jeu</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">Un joueur lit la question et coche les bonnes réponses</p>
                </div>
                <div class="text-center">
                    <div class="bg-green-100 dark:bg-green-900 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-microphone text-2xl text-green-600 dark:text-green-400"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-2">2. Répondeur actif</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">L'autre joueur donne 9 réponses orales en 60 secondes</p>
                </div>
                <div class="text-center">
                    <div class="bg-orange-100 dark:bg-orange-900 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-sync-alt text-2xl text-orange-600 dark:text-orange-400"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-2">3. Alternance</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">Les rôles s'inversent à chaque nouvelle question</p>
                </div>
            </div>
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-400 dark:border-yellow-500 p-4 mt-6">
                <div class="flex">
                    <i class="fas fa-lightbulb text-yellow-600 dark:text-yellow-400 mt-1 mr-3"></i>
                    <div>
                        <h4 class="font-semibold text-yellow-800 dark:text-yellow-200 mb-1">Règles du jeu</h4>
                        <p class="text-yellow-700 dark:text-yellow-300 text-sm">
                            Chaque question a <strong>9 bonnes réponses</strong> (1-5 points) et <strong>1 réponse piège</strong> (-5 points).
                            <strong>Timer de 60 secondes</strong> par manche, avec pause/reprise possible.
                            Le timer redémarre automatiquement quand une réponse est cochée pendant la pause.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="text-center mt-12 text-gray-600 dark:text-gray-400">
            <p class="mb-2">
                <i class="fas fa-code mr-1"></i>
                Développé avec ❤️ par <strong class="text-gray-800 dark:text-gray-200">Franck</strong>
            </p>
            <p class="text-sm">
                Laravel {{ app()->version() }} • Filament • Fait avec passion
            </p>
        </footer>
    </div>

    <script>
        // Theme management
        function initTheme() {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }

        function toggleTheme() {
            const isDark = document.documentElement.classList.contains('dark');
            
            if (isDark) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }

        // Removed createSession function as button now links directly to game.create route

        // Initialize theme on page load
        initTheme();
    </script>
</body>
</html>