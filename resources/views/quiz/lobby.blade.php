<!DOCTYPE html>
<html lang="fr" id="app">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lobby - {{ $session->name }} - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 min-h-screen transition-colors duration-300">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <header class="text-center mb-8">
            <div class="flex justify-between items-center mb-6">
                <a href="{{ route('home') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 text-lg font-medium">
                    <i class="fas fa-home mr-2"></i>Menu principal
                </a>
                <button onclick="toggleTheme()" 
                        class="p-3 rounded-full bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-300">
                    <i class="fas fa-moon dark:hidden text-gray-600"></i>
                    <i class="fas fa-sun hidden dark:inline text-yellow-400"></i>
                </button>
            </div>
            <h1 class="text-4xl font-bold text-indigo-800 dark:text-indigo-300 mb-4">
                <i class="fas fa-gamepad mr-3"></i>{{ $session->name }}
            </h1>
            <div class="inline-flex items-center px-4 py-2 bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 rounded-full font-medium">
                <i class="fas fa-clock mr-2"></i>En attente de lancement
            </div>
        </header>

        <div class="max-w-2xl mx-auto">
            <!-- Liste des joueurs -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">
                    <i class="fas fa-users mr-2 text-green-600 dark:text-green-400"></i>Joueurs Inscrits
                </h2>
                
                <div class="space-y-3">
                    @foreach($session->players as $index => $player)
                        <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex-shrink-0 w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold text-lg">
                                {{ $index + 1 }}
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="font-semibold text-gray-800 dark:text-gray-100">{{ $player->name }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Prêt à jouer</p>
                            </div>
                            <div class="text-green-600 dark:text-green-400">
                                <i class="fas fa-check-circle text-xl"></i>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Statistiques rapides -->
                <div class="mt-6 p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg">
                    <h3 class="font-semibold text-indigo-800 dark:text-indigo-300 mb-2">
                        <i class="fas fa-chart-bar mr-2"></i>Aperçu du Quiz
                    </h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ count($session->selected_themes) }}</div>
                            <div class="text-gray-600 dark:text-gray-300">Thèmes</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $session->themes->sum('questions_count') }}</div>
                            <div class="text-gray-600 dark:text-gray-300">Questions</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Règles du jeu -->
        <div class="max-w-4xl mx-auto mt-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">
                    <i class="fas fa-question-circle mr-2 text-purple-600 dark:text-purple-400"></i>Rappel des Règles
                </h2>
                
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="bg-blue-100 dark:bg-blue-900 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-microphone text-2xl text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-2">Réponses orales</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm">Les joueurs donnent leurs réponses à voix haute</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-green-100 dark:bg-green-900 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-check text-2xl text-green-600 dark:text-green-400"></i>
                        </div>
                        <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-2">9 bonnes réponses</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm">Chaque question a 9 réponses correctes (1-5 points)</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-red-100 dark:bg-red-900 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-exclamation-triangle text-2xl text-red-600 dark:text-red-400"></i>
                        </div>
                        <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-2">1 réponse piège</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm">Attention à la réponse piège (-5 points !)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bouton de lancement -->
        <div class="text-center mt-8">
            <form action="{{ route('game.start', $session) }}" method="POST">
                @csrf
                <button type="submit" 
                        class="px-12 py-4 bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-600 text-white rounded-xl font-bold text-xl transition-colors shadow-lg hover:shadow-xl">
                    <i class="fas fa-play mr-3"></i>Lancer la Partie !
                </button>
            </form>
            <p class="text-gray-600 dark:text-gray-400 text-sm mt-4">
                Assurez-vous que tous les joueurs sont prêts avant de commencer
            </p>
        </div>
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

        // Initialize theme on page load
        initTheme();
    </script>
</body>
</html>