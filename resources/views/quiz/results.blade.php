<!DOCTYPE html>
<html lang="fr" id="app">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats - {{ $session->name }} - {{ config('app.name') }}</title>
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
                <i class="fas fa-trophy mr-3"></i>Résultats
            </h1>
            <p class="text-xl text-gray-600 dark:text-gray-300">{{ $session->name }}</p>
            <div class="inline-flex items-center px-4 py-2 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full font-medium mt-4">
                <i class="fas fa-flag-checkered mr-2"></i>Partie terminée
            </div>
        </header>

        <!-- Podium des vainqueurs -->
        <div class="max-w-4xl mx-auto mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6 text-center">
                    <i class="fas fa-medal mr-2 text-yellow-600 dark:text-yellow-400"></i>Classement Final
                </h2>
                
                @if($session->players->count() > 0)
                    <div class="space-y-4">
                        @foreach($session->players as $index => $player)
                            <div class="flex items-center p-4 rounded-lg 
                                @if($index === 0) bg-gradient-to-r from-yellow-100 to-yellow-200 dark:from-yellow-900/30 dark:to-yellow-800/30 border-2 border-yellow-300 dark:border-yellow-600
                                @elseif($index === 1) bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 border-2 border-gray-300 dark:border-gray-500
                                @elseif($index === 2) bg-gradient-to-r from-orange-100 to-orange-200 dark:from-orange-900/30 dark:to-orange-800/30 border-2 border-orange-300 dark:border-orange-600
                                @else bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 @endif">
                                
                                <!-- Position et médaille -->
                                <div class="flex-shrink-0 w-16 h-16 flex items-center justify-center rounded-full font-bold text-xl
                                    @if($index === 0) bg-yellow-500 text-white
                                    @elseif($index === 1) bg-gray-400 text-white  
                                    @elseif($index === 2) bg-orange-500 text-white
                                    @else bg-indigo-600 text-white @endif">
                                    @if($index === 0)
                                        <i class="fas fa-crown"></i>
                                    @elseif($index === 1)
                                        <i class="fas fa-medal"></i>
                                    @elseif($index === 2)
                                        <i class="fas fa-award"></i>
                                    @else
                                        {{ $index + 1 }}
                                    @endif
                                </div>
                                
                                <!-- Informations du joueur -->
                                <div class="ml-6 flex-1">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="text-xl font-bold 
                                                @if($index === 0) text-yellow-800 dark:text-yellow-200
                                                @elseif($index === 1) text-gray-800 dark:text-gray-200
                                                @elseif($index === 2) text-orange-800 dark:text-orange-200
                                                @else text-gray-800 dark:text-gray-100 @endif">
                                                {{ $player->name }}
                                                @if($index === 0)
                                                    <span class="ml-2 text-lg">🏆</span>
                                                @endif
                                            </h3>
                                            <p class="text-sm 
                                                @if($index === 0) text-yellow-600 dark:text-yellow-300
                                                @elseif($index === 1) text-gray-600 dark:text-gray-300
                                                @elseif($index === 2) text-orange-600 dark:text-orange-300
                                                @else text-gray-600 dark:text-gray-400 @endif">
                                                @if($index === 0) Vainqueur !
                                                @elseif($index === 1) 2ème place
                                                @elseif($index === 2) 3ème place
                                                @else {{ $index + 1 }}ème place @endif
                                            </p>
                                        </div>
                                        
                                        <!-- Score -->
                                        <div class="text-right">
                                            <div class="text-3xl font-bold 
                                                @if($index === 0) text-yellow-600 dark:text-yellow-400
                                                @elseif($index === 1) text-gray-600 dark:text-gray-400
                                                @elseif($index === 2) text-orange-600 dark:text-orange-400
                                                @else text-indigo-600 dark:text-indigo-400 @endif">
                                                {{ $player->score }}
                                            </div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400">points</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-exclamation-circle text-4xl text-gray-400 dark:text-gray-500 mb-4"></i>
                        <p class="text-gray-600 dark:text-gray-300">Aucun joueur trouvé</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Statistiques de la partie -->
        <div class="max-w-4xl mx-auto mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">
                    <i class="fas fa-chart-bar mr-2 text-blue-600 dark:text-blue-400"></i>Statistiques de la Partie
                </h2>
                
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <div class="text-3xl font-bold text-green-600 dark:text-green-400 mb-2">
                            {{ $session->themes->sum('questions_count') }}
                        </div>
                        <div class="text-gray-600 dark:text-gray-300">Questions disponibles</div>
                    </div>
                    
                    <div class="text-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                        <div class="text-3xl font-bold text-purple-600 dark:text-purple-400 mb-2">
                            {{ count($session->selected_themes) }}
                        </div>
                        <div class="text-gray-600 dark:text-gray-300">Thèmes joués</div>
                    </div>
                    
                    <div class="text-center p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                        <div class="text-3xl font-bold text-yellow-600 dark:text-yellow-400 mb-2">
                            @if($session->started_at && $session->finished_at)
                                {{ $session->started_at->diffInMinutes($session->finished_at) }}
                            @else
                                -
                            @endif
                        </div>
                        <div class="text-gray-600 dark:text-gray-300">Minutes de jeu</div>
                    </div>
                </div>
                
                <!-- Détails des thèmes -->
                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-3">Thèmes joués :</h3>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($session->themes as $theme)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div>
                                    <h4 class="font-medium text-gray-800 dark:text-gray-100">{{ $theme->name }}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $theme->questions_count }} questions</p>
                                </div>
                                <div class="text-indigo-600 dark:text-indigo-400">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Score le plus élevé -->
        @if($session->players->count() > 0)
            @php $winner = $session->players->first(); @endphp
            <div class="max-w-2xl mx-auto mb-8">
                <div class="bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-xl shadow-lg p-6 text-center text-white">
                    <i class="fas fa-trophy text-4xl mb-4"></i>
                    <h2 class="text-2xl font-bold mb-2">Félicitations !</h2>
                    <p class="text-xl">
                        <strong>{{ $winner->name }}</strong> remporte la partie avec 
                        <strong>{{ $winner->score }} points</strong> !
                    </p>
                </div>
            </div>
        @endif

        <!-- Boutons d'action -->
        <div class="text-center space-y-4">
            <div class="flex justify-center space-x-4">
                <a href="{{ route('game.create') }}" 
                   class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-600 text-white rounded-lg font-semibold transition-colors">
                    <i class="fas fa-play mr-2"></i>Nouvelle Partie
                </a>
                
                <a href="{{ route('home') }}" 
                   class="px-8 py-3 bg-gray-600 hover:bg-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 text-white rounded-lg font-semibold transition-colors">
                    <i class="fas fa-home mr-2"></i>Accueil
                </a>
            </div>
            
            <button onclick="window.print()" 
                    class="inline-flex items-center px-6 py-2 bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-600 text-white rounded-lg font-medium transition-colors">
                <i class="fas fa-print mr-2"></i>Imprimer les résultats
            </button>
        </div>

        <!-- Informations de session -->
        <div class="text-center mt-8 text-gray-600 dark:text-gray-400 text-sm">
            <p>Partie créée le {{ $session->created_at->format('d/m/Y à H:i') }}</p>
            @if($session->started_at)
                <p>Démarrée le {{ $session->started_at->format('d/m/Y à H:i') }}</p>
            @endif
            @if($session->finished_at)
                <p>Terminée le {{ $session->finished_at->format('d/m/Y à H:i') }}</p>
            @endif
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

    <!-- Print styles -->
    <style>
        @media print {
            body {
                background: white !important;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</body>
</html>