<!DOCTYPE html>
<html lang="fr" id="app">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une Partie - {{ config('app.name') }}</title>
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
        <header class="text-center mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-4 sm:mb-6 space-y-3 sm:space-y-0">
                <a href="{{ route('home') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 text-base sm:text-lg font-medium">
                    <i class="fas fa-home mr-2"></i>Menu principal
                </a>
                <button onclick="toggleTheme()" 
                        class="p-2 sm:p-3 rounded-full bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-300">
                    <i class="fas fa-moon dark:hidden text-gray-600"></i>
                    <i class="fas fa-sun hidden dark:inline text-yellow-400"></i>
                </button>
            </div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-indigo-800 dark:text-indigo-300 mb-2 sm:mb-4">
                <i class="fas fa-plus-circle mr-2 sm:mr-3"></i>Créer une Partie
            </h1>
            <p class="text-base sm:text-lg text-gray-600 dark:text-gray-300 px-4">Configurez votre session de quiz multijoueurs</p>
        </header>

        <!-- Formulaire de création -->
        <div class="max-w-4xl mx-auto">
            <form action="{{ route('game.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Les 2 Joueurs -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">
                        <i class="fas fa-users mr-2 text-purple-600 dark:text-purple-400"></i>Les 2 Joueurs
                    </h2>
                    
                    <div class="space-y-4">
                        <!-- Joueur 1 - Maître du jeu -->
                        <div class="flex items-center space-x-3 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-700">
                            <div class="flex-shrink-0 w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-lg">
                                <i class="fas fa-eye"></i>
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-blue-800 dark:text-blue-200 mb-1">
                                    Joueur 1 - Maître du jeu
                                </label>
                                <input type="text" 
                                       name="players[]" 
                                       value="{{ old('players.0') }}"
                                       placeholder="Nom du maître du jeu"
                                       class="w-full px-4 py-2 rounded-lg border border-blue-300 dark:border-blue-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500"
                                       required>
                                <p class="text-xs text-blue-600 dark:text-blue-300 mt-1">Voit les réponses et coche celles données par l'autre joueur</p>
                            </div>
                        </div>
                        
                        <!-- Joueur 2 - Répondeur -->
                        <div class="flex items-center space-x-3 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-700">
                            <div class="flex-shrink-0 w-12 h-12 bg-green-600 text-white rounded-full flex items-center justify-center font-bold text-lg">
                                <i class="fas fa-microphone"></i>
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-green-800 dark:text-green-200 mb-1">
                                    Joueur 2 - Répondeur
                                </label>
                                <input type="text" 
                                       name="players[]" 
                                       value="{{ old('players.1') }}"
                                       placeholder="Nom du répondeur"
                                       class="w-full px-4 py-2 rounded-lg border border-green-300 dark:border-green-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500"
                                       required>
                                <p class="text-xs text-green-600 dark:text-green-300 mt-1">Doit donner 9 réponses à l'oral sur le thème demandé</p>
                            </div>
                        </div>
                    </div>
                    
                    @error('players')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Nom de la partie (optionnel) -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">
                        <i class="fas fa-tag mr-2 text-blue-600 dark:text-blue-400"></i>Nom de la Partie <span class="text-sm font-normal text-gray-500 dark:text-gray-400">(optionnel)</span>
                    </h2>
                    
                    <div>
                        <label for="session_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nom personnalisé pour cette partie
                        </label>
                        <input type="text" 
                               id="session_name" 
                               name="session_name" 
                               value="{{ old('session_name') }}"
                               placeholder="Ex: Quiz du vendredi soir (laissez vide pour génération automatique)"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('session_name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            <i class="fas fa-info-circle mr-1"></i>
                            Si laissé vide, sera généré automatiquement : "Joueur1 contre Joueur2 - 31/07/2025 00:05"
                        </p>
                    </div>
                    
                    <!-- Champs cachés pour les limites de joueurs -->
                    <input type="hidden" name="min_players" value="2">
                    <input type="hidden" name="max_players" value="2">
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-center space-x-4">
                    <a href="{{ route('home') }}" 
                       class="px-8 py-3 bg-gray-600 hover:bg-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 text-white rounded-lg font-semibold transition-colors">
                        <i class="fas fa-times mr-2"></i>Annuler
                    </a>
                    <button type="submit" 
                            class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-600 text-white rounded-lg font-semibold transition-colors">
                        <i class="fas fa-play mr-2"></i>Créer la Partie
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Pas de JavaScript nécessaire pour les joueurs (fixé à 2 joueurs)

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