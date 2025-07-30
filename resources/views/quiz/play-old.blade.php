<!DOCTYPE html>
<html lang="fr" id="app">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partie en cours - {{ $session->name }} - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 min-h-screen transition-colors duration-300">
    <div class="container mx-auto px-4 py-6">
        <!-- Header avec scores -->
        <header class="mb-6">
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center space-x-4">
                    <h1 class="text-2xl font-bold text-indigo-800 dark:text-indigo-300">
                        {{ $session->name }}
                    </h1>
                    <button onclick="toggleTheme()" 
                            class="p-2 rounded-full bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-300">
                        <i class="fas fa-moon dark:hidden text-gray-600 text-sm"></i>
                        <i class="fas fa-sun hidden dark:inline text-yellow-400 text-sm"></i>
                    </button>
                </div>
                <div class="flex space-x-2">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Question</span>
                    <span id="current-question" class="text-sm font-bold text-indigo-600 dark:text-indigo-400">1</span>
                    <span class="text-sm text-gray-600 dark:text-gray-400">sur {{ $questions->count() }}</span>
                </div>
            </div>
            
            <!-- Scores des joueurs -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="grid grid-cols-2 md:grid-cols-{{ min($session->players->count(), 6) }} gap-3">
                    @foreach($session->players as $player)
                        <div class="text-center p-2 bg-gray-50 dark:bg-gray-700 rounded player-score" data-player-id="{{ $player->id }}">
                            <div class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $player->name }}</div>
                            <div class="text-lg font-bold text-indigo-600 dark:text-indigo-400 score">{{ $player->score }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </header>

        <!-- Interface de jeu -->
        <div class="max-w-6xl mx-auto">
            <!-- Question actuelle -->
            <div id="question-container" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-2" id="question-text">
                        Cliquez sur "Question suivante" pour commencer
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300" id="question-theme"></p>
                </div>

                <!-- Réponses -->
                <div id="answers-container" class="hidden">
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-3 mb-6" id="answers-grid">
                        <!-- Les réponses seront ajoutées ici par JavaScript -->
                    </div>
                    
                    <!-- Réponse piège -->
                    <div class="bg-red-50 dark:bg-red-900/20 border-2 border-red-200 dark:border-red-800 rounded-lg p-4 mb-6">
                        <h3 class="text-lg font-bold text-red-800 dark:text-red-300 mb-2">
                            <i class="fas fa-exclamation-triangle mr-2"></i>Réponse Piège (-5 points)
                        </h3>
                        <div id="penalty-answer" class="text-red-700 dark:text-red-400 font-medium">
                            <!-- La réponse piège sera ajoutée ici -->
                        </div>
                    </div>
                </div>

                <!-- Boutons de contrôle -->
                <div class="flex justify-center space-x-4">
                    <button id="show-question-btn" 
                            onclick="showNextQuestion()" 
                            class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-600 text-white rounded-lg font-semibold transition-colors">
                        <i class="fas fa-play mr-2"></i>Question suivante
                    </button>
                    
                    <button id="show-answers-btn" 
                            onclick="showAnswers()" 
                            class="hidden px-6 py-3 bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-600 text-white rounded-lg font-semibold transition-colors">
                        <i class="fas fa-eye mr-2"></i>Révéler les réponses
                    </button>
                    
                    <button id="next-question-btn" 
                            onclick="nextQuestion()" 
                            class="hidden px-6 py-3 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white rounded-lg font-semibold transition-colors">
                        <i class="fas fa-forward mr-2"></i>Question suivante
                    </button>
                    
                    <form id="finish-game-form" action="{{ route('game.finish', $session) }}" method="POST" class="hidden">
                        @csrf
                        <button type="submit"
                                class="px-6 py-3 bg-purple-600 hover:bg-purple-700 dark:bg-purple-700 dark:hover:bg-purple-600 text-white rounded-lg font-semibold transition-colors">
                            <i class="fas fa-flag-checkered mr-2"></i>Terminer la partie
                        </button>
                    </form>
                </div>
            </div>

            <!-- Sélection du joueur (modal) -->
            <div id="player-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 max-w-md w-full mx-4">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-4">
                        Qui a donné cette réponse ?
                    </h3>
                    <div id="selected-answer" class="mb-4 p-3 bg-gray-100 dark:bg-gray-700 rounded-lg">
                        <!-- La réponse sélectionnée sera affichée ici -->
                    </div>
                    <div class="grid grid-cols-2 gap-3" id="player-selection">
                        @foreach($session->players as $player)
                            <button type="button" 
                                    class="p-3 bg-indigo-100 dark:bg-indigo-900 hover:bg-indigo-200 dark:hover:bg-indigo-800 text-indigo-800 dark:text-indigo-200 rounded-lg font-medium transition-colors"
                                    onclick="assignAnswer({{ $player->id }}, '{{ $player->name }}')">
                                {{ $player->name }}
                            </button>
                        @endforeach
                    </div>
                    <button type="button" 
                            onclick="closePlayerModal()" 
                            class="mt-4 w-full px-4 py-2 bg-gray-600 hover:bg-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 text-white rounded-lg font-medium transition-colors">
                        Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const questions = @json($questions->values());
        let currentQuestionIndex = -1;
        let currentQuestion = null;
        let selectedAnswer = null;
        let sessionId = {{ $session->id }};

        // Debug initial
        console.log('Questions chargées au début:', questions);
        console.log('Nombre total de questions:', questions.length);
        if (questions.length > 0) {
            console.log('Première question:', questions[0]);
            console.log('Réponses de la première question:', questions[0].answers);
        }

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

        function showNextQuestion() {
            currentQuestionIndex++;
            
            console.log('Questions disponibles:', questions.length);
            console.log('Index actuel:', currentQuestionIndex);
            
            if (currentQuestionIndex >= questions.length) {
                document.getElementById('finish-game-form').classList.remove('hidden');
                document.getElementById('show-question-btn').classList.add('hidden');
                return;
            }
            
            currentQuestion = questions[currentQuestionIndex];
            console.log('Question chargée:', currentQuestion);
            
            // Mettre à jour l'interface
            document.getElementById('current-question').textContent = currentQuestionIndex + 1;
            document.getElementById('question-text').textContent = currentQuestion.question;
            document.getElementById('question-theme').textContent = `Thème: ${currentQuestion.theme.name}`;
            
            // Cacher les réponses et afficher le bouton pour les révéler
            document.getElementById('answers-container').classList.add('hidden');
            document.getElementById('show-question-btn').classList.add('hidden');
            document.getElementById('show-answers-btn').classList.remove('hidden');
            document.getElementById('next-question-btn').classList.add('hidden');
        }

        function showAnswers() {
            if (!currentQuestion) {
                console.error('Aucune question actuelle');
                return;
            }
            
            console.log('=== AFFICHAGE DES RÉPONSES ===');
            console.log('Question actuelle:', currentQuestion);
            console.log('Réponses brutes:', currentQuestion.answers);
            console.log('Nombre de réponses:', currentQuestion.answers ? currentQuestion.answers.length : 0);
            
            const answersGrid = document.getElementById('answers-grid');
            const penaltyAnswer = document.getElementById('penalty-answer');
            const answersContainer = document.getElementById('answers-container');
            
            console.log('Elements DOM:');
            console.log('- answersGrid:', answersGrid);
            console.log('- penaltyAnswer:', penaltyAnswer);
            console.log('- answersContainer:', answersContainer);
            
            // Vider le grid
            answersGrid.innerHTML = '';
            
            // Vérifier que les réponses existent
            if (!currentQuestion.answers || currentQuestion.answers.length === 0) {
                console.error('PROBLÈME: Aucune réponse trouvée');
                answersGrid.innerHTML = '<div class="col-span-full text-center text-red-600 p-4 bg-red-100 rounded-lg">Aucune réponse trouvée pour cette question</div>';
                answersContainer.classList.remove('hidden');
                return;
            }
            
            console.log('Tri des réponses...');
            // Trier les réponses par points croissants
            const sortedAnswers = [...currentQuestion.answers].sort((a, b) => a.points - b.points);
            console.log('Réponses triées:', sortedAnswers);
            
            // Ajouter les bonnes réponses
            console.log('Ajout des réponses au DOM...');
            sortedAnswers.forEach((answer, index) => {
                console.log(`Ajout réponse ${index + 1}:`, answer);
                
                const answerDiv = document.createElement('div');
                answerDiv.className = `p-3 bg-green-100 dark:bg-green-900 hover:bg-green-200 dark:hover:bg-green-800 text-green-800 dark:text-green-200 rounded-lg cursor-pointer transition-colors border-2 border-transparent hover:border-green-300 dark:hover:border-green-600`;
                answerDiv.innerHTML = `
                    <div class="font-medium">${answer.answer}</div>
                    <div class="text-sm opacity-75">${answer.points} point${answer.points > 1 ? 's' : ''}</div>
                `;
                answerDiv.onclick = () => selectAnswer(answer.id, answer.answer, answer.points);
                answersGrid.appendChild(answerDiv);
                
                console.log(`Réponse ${index + 1} ajoutée au DOM`);
            });
            
            // Ajouter la réponse piège
            console.log('Ajout de la réponse piège:', currentQuestion.penalty_answer);
            if (currentQuestion.penalty_answer) {
                penaltyAnswer.innerHTML = `
                    <div class="cursor-pointer hover:bg-red-100 dark:hover:bg-red-800 p-2 rounded transition-colors" onclick="selectAnswer('penalty', '${currentQuestion.penalty_answer}', -5)">
                        <div class="font-medium">${currentQuestion.penalty_answer}</div>
                        <div class="text-sm opacity-75">-5 points</div>
                    </div>
                `;
                console.log('Réponse piège ajoutée');
            } else {
                penaltyAnswer.innerHTML = '<div class="text-center text-red-600">Réponse piège manquante</div>';
                console.log('PROBLÈME: Réponse piège manquante');
            }
            
            // Afficher les réponses
            console.log('Affichage du container de réponses...');
            answersContainer.classList.remove('hidden');
            document.getElementById('show-answers-btn').classList.add('hidden');
            document.getElementById('next-question-btn').classList.remove('hidden');
            
            console.log('État final du DOM:');
            console.log('- answersGrid.children.length:', answersGrid.children.length);
            console.log('- answersContainer.classList:', answersContainer.classList.toString());
            console.log('=== FIN AFFICHAGE RÉPONSES ===');
        }

        function selectAnswer(answerId, answerText, points) {
            selectedAnswer = { id: answerId, text: answerText, points: points };
            
            // Afficher le modal de sélection du joueur
            document.getElementById('selected-answer').innerHTML = `
                <div class="font-medium">${answerText}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">${points > 0 ? '+' : ''}${points} point${Math.abs(points) > 1 ? 's' : ''}</div>
            `;
            document.getElementById('player-modal').classList.remove('hidden');
            document.getElementById('player-modal').classList.add('flex');
        }

        function closePlayerModal() {
            document.getElementById('player-modal').classList.add('hidden');
            document.getElementById('player-modal').classList.remove('flex');
            selectedAnswer = null;
        }

        function assignAnswer(playerId, playerName) {
            if (!selectedAnswer) return;
            
            // Préparer les données pour l'API
            const data = {
                player_id: playerId,
                question_id: currentQuestion.id,
                answer_id: selectedAnswer.id === 'penalty' ? null : selectedAnswer.id,
                points: selectedAnswer.points
            };
            
            // Si c'est une réponse piège, créer une réponse temporaire
            if (selectedAnswer.id === 'penalty') {
                data.penalty = true;
            }
            
            // Envoyer à l'API
            fetch(`/game/${sessionId}/score`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    // Mettre à jour le score du joueur
                    updatePlayerScore(playerId, result.new_score);
                    
                    // Afficher une notification
                    showNotification(`${playerName} gagne ${selectedAnswer.points > 0 ? '+' : ''}${selectedAnswer.points} point${Math.abs(selectedAnswer.points) > 1 ? 's' : ''} !`);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showNotification('Erreur lors de la mise à jour du score', 'error');
            });
            
            closePlayerModal();
        }

        function updatePlayerScore(playerId, newScore) {
            const playerScoreElement = document.querySelector(`.player-score[data-player-id="${playerId}"] .score`);
            if (playerScoreElement) {
                playerScoreElement.textContent = newScore;
                
                // Animation
                playerScoreElement.classList.add('text-green-600', 'font-bold');
                setTimeout(() => {
                    playerScoreElement.classList.remove('text-green-600');
                }, 1000);
            }
        }

        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg font-medium z-50 transition-opacity ${type === 'error' ? 'bg-red-600 text-white' : 'bg-green-600 text-white'}`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 2000);
        }

        function nextQuestion() {
            showNextQuestion();
        }

        // Initialize theme on page load
        initTheme();
    </script>
</body>
</html>