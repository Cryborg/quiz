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
    <div class="container mx-auto px-2 sm:px-4 py-3 sm:py-6">
        <!-- Header avec scores -->
        <header class="mb-4 sm:mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 space-y-3 sm:space-y-0">
                <div class="flex flex-wrap items-center gap-2 sm:gap-4">
                    <a href="{{ route('home') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium">
                        <i class="fas fa-home mr-1 sm:mr-2"></i><span class="text-sm sm:text-base">Menu</span>
                    </a>
                    <h1 class="text-lg sm:text-2xl font-bold text-indigo-800 dark:text-indigo-300 break-words">
                        {{ $session->name }}
                    </h1>
                    <button onclick="toggleTheme()" 
                            class="p-2 rounded-full bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-300">
                        <i class="fas fa-moon dark:hidden text-gray-600 text-sm"></i>
                        <i class="fas fa-sun hidden dark:inline text-yellow-400 text-sm"></i>
                    </button>
                </div>
                <div class="flex items-center space-x-1 sm:space-x-2 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-lg">
                    <span class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">Question</span>
                    <span id="current-question" class="text-xs sm:text-sm font-bold text-indigo-600 dark:text-indigo-400">1</span>
                    <span class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">sur {{ $questions->count() }}</span>
                </div>
            </div>
            
            <!-- Scores des 2 joueurs -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-2 sm:p-3">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-4">
                    @foreach($session->players as $index => $player)
                        <div class="flex items-center justify-between p-2 sm:p-3 rounded-lg player-score {{ $index == 0 ? 'bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700' : 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700' }}" data-player-id="{{ $player->id }}">
                            <div class="flex items-center">
                                <i class="fas {{ $index == 0 ? 'fa-eye text-blue-600 dark:text-blue-400' : 'fa-microphone text-green-600 dark:text-green-400' }} text-base sm:text-lg mr-2"></i>
                                <div>
                                    <div class="text-sm sm:text-base font-bold text-gray-800 dark:text-gray-100">{{ $player->name }}</div>
                                    <div class="text-xs sm:text-sm text-gray-600 dark:text-gray-300">
                                        {{ $index == 0 ? 'Maître du jeu' : 'Répondeur' }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="text-lg sm:text-xl font-bold {{ $index == 0 ? 'text-blue-600 dark:text-blue-400' : 'text-green-600 dark:text-green-400' }} score">{{ $player->score }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">pts</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </header>

        <!-- Interface de jeu -->
        <div class="max-w-6xl mx-auto">
            <!-- Question actuelle -->
            <div id="question-container" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-3 sm:p-4 mb-4">
                <div class="text-center mb-4">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-gray-100 mb-1 px-2" id="question-text">
                        {{ $session->players[1]->name }}, préparez-vous à répondre !
                    </h2>
                    <p class="text-base sm:text-lg text-gray-600 dark:text-gray-300 px-2" id="question-theme">{{ $session->players[0]->name }} sera votre maître du jeu</p>
                </div>


                <!-- Réponses à cocher -->
                <div id="answers-container" class="hidden">
                    <div class="max-w-3xl mx-auto mb-4">
                        <h3 class="text-sm sm:text-base font-semibold text-gray-800 dark:text-gray-100 mb-3 text-center">
                            <i class="fas fa-list mr-2"></i>Réponses à cocher (ordre alphabétique)
                        </h3>
                        <div class="space-y-1 sm:space-y-1.5" id="answers-grid">
                            <!-- Les réponses seront ajoutées ici par JavaScript -->
                        </div>
                    </div>

                    <!-- Timer de la manche -->
                    <div id="timer-container" class="hidden bg-gradient-to-r from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20 border-2 border-orange-200 dark:border-orange-700 rounded-lg p-2 sm:p-3 mb-4">
                        <div class="flex flex-col sm:flex-row items-center justify-between mb-2 space-y-2 sm:space-y-0">
                            <span class="text-orange-800 dark:text-orange-200 font-semibold text-sm sm:text-base">
                                <i class="fas fa-clock mr-2"></i>Temps restant :
                            </span>
                            <span id="timer-display" class="text-2xl sm:text-3xl font-bold text-red-600 dark:text-red-400">60</span>
                            <span class="text-orange-800 dark:text-orange-200 text-sm sm:text-base">secondes</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2 sm:h-3">
                            <div id="timer-bar" class="bg-gradient-to-r from-green-500 to-orange-500 h-2 sm:h-3 rounded-full transition-all duration-1000" style="width: 100%"></div>
                        </div>
                        <div class="text-center mt-2">
                            <button id="pause-timer-btn" 
                                    onclick="toggleTimer()" 
                                    class="px-3 py-2 sm:px-4 sm:py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg font-medium transition-colors text-sm sm:text-base">
                                <i class="fas fa-pause mr-1 sm:mr-2"></i>Mettre en pause
                            </button>
                        </div>
                    </div>

                    <!-- Compteur de réponses -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-2 sm:p-3 mb-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700 dark:text-gray-300 text-sm sm:text-base">Réponses trouvées :</span>
                            <span id="found-count" class="text-xl sm:text-2xl font-bold text-indigo-600 dark:text-indigo-400">0</span>
                            <span class="text-gray-700 dark:text-gray-300 text-sm sm:text-base">/ 9</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2 mt-2">
                            <div id="progress-bar" class="bg-indigo-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                    </div>
                </div>

                <!-- Boutons de contrôle -->
                <div class="flex flex-wrap justify-center gap-2 sm:gap-4">
                    <button id="show-question-btn" 
                            onclick="showNextQuestion()" 
                            class="px-4 py-2 sm:px-6 sm:py-3 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-600 text-white rounded-lg font-semibold transition-colors text-sm sm:text-base">
                        <i class="fas fa-play mr-1 sm:mr-2"></i><span class="hidden sm:inline">Question suivante</span><span class="sm:hidden">Suivante</span>
                    </button>
                    
                    <button id="show-answers-btn" 
                            onclick="startRound()" 
                            class="hidden px-4 py-2 sm:px-6 sm:py-3 bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-600 text-white rounded-lg font-semibold transition-colors text-sm sm:text-base">
                        <i class="fas fa-play-circle mr-1 sm:mr-2"></i><span class="hidden sm:inline">Démarrer la manche</span><span class="sm:hidden">Démarrer</span>
                    </button>
                    
                    <button id="next-question-btn" 
                            onclick="nextQuestion()" 
                            class="hidden px-4 py-2 sm:px-6 sm:py-3 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white rounded-lg font-semibold transition-colors text-sm sm:text-base">
                        <i class="fas fa-forward mr-1 sm:mr-2"></i><span class="hidden sm:inline">Question suivante</span><span class="sm:hidden">Suivante</span>
                    </button>
                    
                    <form id="finish-game-form" action="{{ route('game.finish', $session) }}" method="POST" class="hidden">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 sm:px-6 sm:py-3 bg-purple-600 hover:bg-purple-700 dark:bg-purple-700 dark:hover:bg-purple-600 text-white rounded-lg font-semibold transition-colors text-sm sm:text-base">
                            <i class="fas fa-flag-checkered mr-1 sm:mr-2"></i><span class="hidden sm:inline">Terminer la partie</span><span class="sm:hidden">Terminer</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const allQuestions = @json($questions->values());
        const selectedThemes = @json($session->selected_themes);
        let questions = [];
        let currentQuestionIndex = -1;
        let currentQuestion = null;
        let foundAnswers = 0;
        let sessionId = {{ $session->id }};
        let player1Id = {{ $session->players[0]->id }};
        let player2Id = {{ $session->players[1]->id }};
        let player1Name = "{{ $session->players[0]->name }}";
        let player2Name = "{{ $session->players[1]->name }}";
        
        // Alternance des rôles : true = Joueur 1 pose, Joueur 2 répond
        let player1IsMaster = true;
        
        // Sélection automatique des questions par thème
        function generateQuestionSequence() {
            console.log('=== GÉNÉRATION DE LA SÉQUENCE DE QUESTIONS ===');
            console.log('Thèmes sélectionnés:', selectedThemes);
            console.log('Total questions disponibles:', allQuestions.length);
            
            // Créer une copie des questions pour éviter de modifier l'original
            const availableQuestions = [...allQuestions];
            const usedQuestionIds = new Set(); // Suivi des questions déjà utilisées
            
            // Grouper les questions par thème (copies)
            const questionsByTheme = {};
            selectedThemes.forEach(themeId => {
                questionsByTheme[themeId] = availableQuestions.filter(q => q.theme_id === themeId);
            });
            
            console.log('Questions par thème:', Object.fromEntries(
                Object.entries(questionsByTheme).map(([themeId, questions]) => [
                    themeId, questions.length + ' questions'
                ])
            ));
            
            // Créer une séquence alternée par thème
            const sequence = [];
            const maxQuestions = 30; // Limite élargie
            let themeIndex = 0;
            let consecutiveEmptyThemes = 0;
            
            for (let i = 0; i < maxQuestions; i++) {
                const currentThemeId = selectedThemes[themeIndex % selectedThemes.length];
                const themeQuestions = questionsByTheme[currentThemeId];
                
                // Filtrer les questions déjà utilisées pour ce thème
                const availableThemeQuestions = themeQuestions.filter(q => !usedQuestionIds.has(q.id));
                
                if (availableThemeQuestions.length > 0) {
                    // Prendre une question aléatoire de ce thème
                    const randomIndex = Math.floor(Math.random() * availableThemeQuestions.length);
                    const selectedQuestion = availableThemeQuestions[randomIndex];
                    
                    // Marquer comme utilisée
                    usedQuestionIds.add(selectedQuestion.id);
                    sequence.push(selectedQuestion);
                    
                    console.log(`Question ${i + 1}: "${selectedQuestion.question}" (Thème: ${selectedQuestion.theme.name})`);
                    consecutiveEmptyThemes = 0;
                } else {
                    console.log(`Thème ${currentThemeId} épuisé, passage au suivant`);
                    consecutiveEmptyThemes++;
                }
                
                themeIndex++;
                
                // Arrêter si tous les thèmes sont épuisés (on a fait un tour complet sans trouver de questions)
                if (consecutiveEmptyThemes >= selectedThemes.length) {
                    console.log('Tous les thèmes sont épuisés');
                    break;
                }
            }
            
            console.log(`Séquence générée: ${sequence.length} questions uniques`);
            console.log('IDs des questions utilisées:', Array.from(usedQuestionIds));
            return sequence;
        }
        
        // Générer la séquence au chargement
        questions = generateQuestionSequence();
        
        // Variables du timer
        let timeLeft = 60;
        let timerInterval = null;
        let roundActive = false;
        let timerPaused = false;

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
            
            if (currentQuestionIndex >= questions.length) {
                document.getElementById('finish-game-form').classList.remove('hidden');
                document.getElementById('show-question-btn').classList.add('hidden');
                return;
            }
            
            currentQuestion = questions[currentQuestionIndex];
            foundAnswers = 0;
            
            // Alterner les rôles à chaque question
            if (currentQuestionIndex > 0) {
                player1IsMaster = !player1IsMaster;
            }
            
            // Déterminer qui est maître et qui répond
            const masterName = player1IsMaster ? player1Name : player2Name;
            const responderName = player1IsMaster ? player2Name : player1Name;
            
            // Mettre à jour l'interface
            document.getElementById('current-question').textContent = currentQuestionIndex + 1;
            document.getElementById('question-text').textContent = currentQuestion.question;
            document.getElementById('question-theme').textContent = `Thème: ${currentQuestion.theme.name}`;
            
            // Mettre à jour les indicateurs visuels
            updateRoleIndicators(masterName, responderName);
            
            // Cacher les réponses et afficher le bouton pour démarrer la manche
            document.getElementById('answers-container').classList.add('hidden');
            document.getElementById('timer-container').classList.add('hidden');
            document.getElementById('show-question-btn').classList.add('hidden');
            document.getElementById('show-answers-btn').classList.remove('hidden');
            document.getElementById('next-question-btn').classList.add('hidden');
            
            // Réinitialiser le timer
            resetTimer();
        }

        function updateRoleIndicators(masterName, responderName) {
            // Mettre à jour les indicateurs visuels dans les scores
            const player1Score = document.querySelector(`[data-player-id="${player1Id}"]`);
            const player2Score = document.querySelector(`[data-player-id="${player2Id}"]`);
            
            if (player1IsMaster) {
                // Joueur 1 = Maître, Joueur 2 = Répondeur
                player1Score.querySelector('i').className = 'fas fa-eye text-blue-600 dark:text-blue-400 text-xl mr-2';
                player1Score.querySelector('.text-sm').innerHTML = '<strong>Maître du jeu</strong><br><span class="text-xs opacity-75">Coche les réponses</span>';
                player1Score.classList.remove('bg-green-50', 'dark:bg-green-900/20', 'border-green-200', 'dark:border-green-700', 'animate-pulse');
                player1Score.classList.add('bg-blue-50', 'dark:bg-blue-900/20', 'border-2', 'border-blue-300', 'dark:border-blue-600');
                
                player2Score.querySelector('i').className = 'fas fa-microphone text-green-600 dark:text-green-400 text-xl mr-2';
                player2Score.querySelector('.text-sm').innerHTML = '<strong>À ton tour !</strong><br><span class="text-xs opacity-75">Donne 9 réponses</span>';
                player2Score.classList.remove('bg-blue-50', 'dark:bg-blue-900/20', 'border-blue-200', 'dark:border-blue-700');
                player2Score.classList.add('bg-green-50', 'dark:bg-green-900/20', 'border-2', 'border-green-300', 'dark:border-green-600', 'animate-pulse');
            } else {
                // Joueur 2 = Maître, Joueur 1 = Répondeur
                player2Score.querySelector('i').className = 'fas fa-eye text-blue-600 dark:text-blue-400 text-xl mr-2';
                player2Score.querySelector('.text-sm').innerHTML = '<strong>Maître du jeu</strong><br><span class="text-xs opacity-75">Coche les réponses</span>';
                player2Score.classList.remove('bg-green-50', 'dark:bg-green-900/20', 'border-green-200', 'dark:border-green-700', 'animate-pulse');
                player2Score.classList.add('bg-blue-50', 'dark:bg-blue-900/20', 'border-2', 'border-blue-300', 'dark:border-blue-600');
                
                player1Score.querySelector('i').className = 'fas fa-microphone text-green-600 dark:text-green-400 text-xl mr-2';
                player1Score.querySelector('.text-sm').innerHTML = '<strong>À ton tour !</strong><br><span class="text-xs opacity-75">Donne 9 réponses</span>';
                player1Score.classList.remove('bg-blue-50', 'dark:bg-blue-900/20', 'border-blue-200', 'dark:border-blue-700');
                player1Score.classList.add('bg-green-50', 'dark:bg-green-900/20', 'border-2', 'border-green-300', 'dark:border-green-600', 'animate-pulse');
            }
        }


        function startRound() {
            if (!currentQuestion) {
                console.error('Aucune question actuelle');
                return;
            }
            
            console.log('=== DÉBUT DE LA MANCHE ===');
            console.log('Question actuelle:', currentQuestion);
            console.log('Réponses disponibles:', currentQuestion.answers);
            console.log('Nombre de réponses:', currentQuestion.answers ? currentQuestion.answers.length : 0);
            
            const answersGrid = document.getElementById('answers-grid');
            
            console.log('Éléments DOM trouvés:');
            console.log('- answersGrid:', answersGrid);
            
            // Vider le grid
            answersGrid.innerHTML = '';
            
            // Vérifier si les réponses existent
            if (!currentQuestion.answers || currentQuestion.answers.length === 0) {
                console.error('ERREUR: Aucune réponse trouvée !');
                answersGrid.innerHTML = '<div class="text-red-600 p-4 bg-red-100 rounded">ERREUR: Aucune réponse trouvée pour cette question</div>';
                return;
            }
            
            console.log('Tri des réponses par ordre alphabétique...');
            // Trier les réponses par ordre alphabétique
            const sortedAnswers = [...currentQuestion.answers].sort((a, b) => 
                a.answer.toLowerCase().localeCompare(b.answer.toLowerCase())
            );
            console.log('Réponses triées:', sortedAnswers);
            
            // Ajouter les bonnes réponses (une seule colonne)
            sortedAnswers.forEach((answer, index) => {
                const answerDiv = document.createElement('div');
                answerDiv.className = `answer-item flex items-center justify-between p-2.5 bg-gray-100 dark:bg-gray-700 hover:bg-green-200 dark:hover:bg-green-800 text-gray-800 dark:text-gray-200 rounded-lg cursor-pointer transition-all duration-200 border-2 border-transparent`;
                answerDiv.innerHTML = `
                    <div class="flex items-center flex-1">
                        <div class="w-7 h-7 bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 rounded-full flex items-center justify-center text-sm font-medium mr-3">
                            ${index + 1}
                        </div>
                        <div class="font-medium text-base">${answer.answer}</div>
                    </div>
                    <div class="flex items-center">
                        <div class="text-sm opacity-75 mr-3">${answer.points}pt</div>
                        <div class="w-5 h-5 border-2 border-gray-300 dark:border-gray-500 rounded checkbox-icon"></div>
                    </div>
                    <div class="hidden found-indicator">
                        <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-lg"></i>
                    </div>
                `;
                answerDiv.onclick = () => selectAnswer(answer.id, answer.answer, answer.points, answerDiv);
                answersGrid.appendChild(answerDiv);
            });
            
            // Ajouter la réponse piège en bas de la liste
            const penaltyDiv = document.createElement('div');
            penaltyDiv.className = `penalty-item flex items-center justify-between p-2.5 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200 rounded-lg cursor-pointer transition-all duration-200 border-2 border-red-300 dark:border-red-600 mt-3`;
            penaltyDiv.innerHTML = `
                <div class="flex items-center flex-1">
                    <div class="w-7 h-7 bg-red-600 text-white rounded-full flex items-center justify-center text-sm font-medium mr-3">
                        <i class="fas fa-exclamation-triangle text-xs"></i>
                    </div>
                    <div class="font-medium text-base">${currentQuestion.penalty_answer}</div>
                </div>
                <div class="flex items-center">
                    <div class="text-sm opacity-75 mr-3">-5pt</div>
                    <div class="w-5 h-5 border-2 border-red-400 dark:border-red-500 rounded checkbox-icon"></div>
                </div>
                <div class="hidden penalty-indicator">
                    <i class="fas fa-times-circle text-red-600 dark:text-red-400 text-lg"></i>
                </div>
            `;
            penaltyDiv.onclick = () => selectPenalty(penaltyDiv);
            answersGrid.appendChild(penaltyDiv);
            
            console.log('Affichage final...');
            // Afficher les réponses, le timer et mettre à jour les boutons
            document.getElementById('answers-container').classList.remove('hidden');
            document.getElementById('timer-container').classList.remove('hidden');
            document.getElementById('show-answers-btn').classList.add('hidden');
            document.getElementById('next-question-btn').classList.remove('hidden');
            
            console.log('Container affiché, nombre d\'éléments dans answersGrid:', answersGrid.children.length);
            console.log('Classes du container:', document.getElementById('answers-container').className);
            
            // Réinitialiser le compteur et démarrer le timer
            updateProgress();
            startTimer();
            
            console.log('=== MANCHE DÉMARRÉE ===');
        }

        function selectAnswer(answerId, answerText, points, element) {
            if (element.classList.contains('found')) return; // Déjà trouvé
            
            console.log('Cocher réponse - Timer état:', { roundActive, timerPaused, timerInterval: !!timerInterval });
            
            // Si le timer était en pause, le redémarrer automatiquement
            if (roundActive && timerPaused) {
                console.log('Timer en pause, redémarrage automatique...');
                timerPaused = false;
                updatePauseButton();
                showNotification('▶️ Timer redémarré automatiquement');
            }
            
            // Marquer comme trouvé visuellement
            element.classList.add('found', 'bg-green-100', 'dark:bg-green-900', 'border-green-300', 'dark:border-green-600');
            element.classList.remove('bg-gray-100', 'dark:bg-gray-700', 'hover:bg-green-200', 'dark:hover:bg-green-800');
            
            // Remplacer la checkbox par un check
            const checkboxIcon = element.querySelector('.checkbox-icon');
            const foundIndicator = element.querySelector('.found-indicator');
            checkboxIcon.classList.add('hidden');
            foundIndicator.classList.remove('hidden');
            
            element.style.cursor = 'default';
            
            foundAnswers++;
            updateProgress();
            
            // Déterminer qui reçoit les points (le répondeur)
            const responderId = player1IsMaster ? player2Id : player1Id;
            const responderName = player1IsMaster ? player2Name : player1Name;
            
            // Envoyer le score au serveur
            updateScore(responderId, answerId, points);
            
            showNotification(`+${points} point${points > 1 ? 's' : ''} pour ${responderName} : "${answerText}" !`);
            
            // Vérifier si toutes les bonnes réponses ont été trouvées
            if (foundAnswers === 9) {
                console.log('Toutes les réponses trouvées ! Passage automatique à la question suivante...');
                showNotification('🎉 Parfait ! Toutes les réponses trouvées !', 'success');
                
                // Arrêter le timer
                if (timerInterval) {
                    clearInterval(timerInterval);
                    timerInterval = null;
                }
                roundActive = false;
                timerPaused = false;
                
                // Passer à la question suivante après un délai
                setTimeout(() => {
                    nextQuestion();
                }, 2000); // 2 secondes pour laisser le temps de voir la notification
            }
            
            console.log('Après cocher réponse - Timer état:', { roundActive, timerPaused, timerInterval: !!timerInterval });
        }

        function selectPenalty(element) {
            if (element.classList.contains('penalty-selected')) return; // Déjà sélectionné
            
            // Marquer comme sélectionné visuellement
            element.classList.add('penalty-selected', 'bg-red-200', 'dark:bg-red-800');
            element.classList.remove('hover:bg-red-200', 'dark:hover:bg-red-800');
            
            // Remplacer la checkbox par un X
            const checkboxIcon = element.querySelector('.checkbox-icon');
            const penaltyIndicator = element.querySelector('.penalty-indicator');
            checkboxIcon.classList.add('hidden');
            penaltyIndicator.classList.remove('hidden');
            
            element.style.cursor = 'default';
            
            // Déterminer qui perd les points (le répondeur)
            const responderId = player1IsMaster ? player2Id : player1Id;
            const responderName = player1IsMaster ? player2Name : player1Name;
            
            // Marquer la pénalité
            updateScore(responderId, null, -5, true);
            showNotification(`-5 points pour ${responderName} : réponse piège "${currentQuestion.penalty_answer}" !`, 'error');
        }

        function updateProgress() {
            document.getElementById('found-count').textContent = foundAnswers;
            const percentage = (foundAnswers / 9) * 100;
            document.getElementById('progress-bar').style.width = percentage + '%';
            
            // Changer la couleur selon le progrès
            const progressBar = document.getElementById('progress-bar');
            if (percentage === 100) {
                progressBar.className = 'bg-green-600 h-2 rounded-full transition-all duration-300';
            } else if (percentage >= 50) {
                progressBar.className = 'bg-yellow-600 h-2 rounded-full transition-all duration-300';
            } else {
                progressBar.className = 'bg-indigo-600 h-2 rounded-full transition-all duration-300';
            }
        }

        function updateScore(playerId, answerId, points, isPenalty = false) {
            const data = {
                player_id: playerId,
                question_id: currentQuestion.id,
                answer_id: answerId,
                points: points,
                penalty: isPenalty
            };
            
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
                    updatePlayerScore(playerId, result.new_score);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showNotification('Erreur lors de la mise à jour du score', 'error');
            });
        }

        function updatePlayerScore(playerId, newScore) {
            const playerScoreElement = document.querySelector(`.player-score[data-player-id="${playerId}"] .score`);
            if (playerScoreElement) {
                playerScoreElement.textContent = newScore;
                
                // Animation
                playerScoreElement.classList.add('scale-110');
                setTimeout(() => {
                    playerScoreElement.classList.remove('scale-110');
                }, 200);
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
            }, 2500);
        }

        function nextQuestion() {
            if (timerInterval) {
                clearInterval(timerInterval);
                timerInterval = null;
            }
            roundActive = false;
            timerPaused = false;
            showNextQuestion();
        }

        // Fonctions du timer
        function resetTimer() {
            timeLeft = 60;
            roundActive = false;
            timerPaused = false;
            if (timerInterval) {
                clearInterval(timerInterval);
                timerInterval = null;
            }
            updateTimerDisplay();
            updatePauseButton();
        }

        function startTimer() {
            if (roundActive && !timerPaused) return; // Déjà démarré
            
            roundActive = true;
            timerPaused = false;
            
            if (timeLeft <= 0) timeLeft = 60; // Reset si nécessaire
            
            timerInterval = setInterval(() => {
                if (!timerPaused) {
                    timeLeft--;
                    updateTimerDisplay();
                    
                    if (timeLeft <= 0) {
                        clearInterval(timerInterval);
                        timerInterval = null;
                        roundActive = false;
                        showNotification('⏰ Temps écoulé ! La manche est terminée.', 'error');
                    }
                }
            }, 1000);
            
            updatePauseButton();
            
            const responderName = player1IsMaster ? player2Name : player1Name;
            showNotification(`⏱️ Timer démarré ! ${responderName} a ${timeLeft} secondes pour répondre.`);
        }

        function toggleTimer() {
            if (!roundActive) return; // Pas de timer actif
            
            console.log('toggleTimer appelé - État avant:', { roundActive, timerPaused, timerInterval: !!timerInterval });
            
            timerPaused = !timerPaused;
            updatePauseButton();
            
            if (timerPaused) {
                showNotification('⏸️ Timer en pause');
                console.log('Timer mis en pause');
            } else {
                showNotification('▶️ Timer repris');
                console.log('Timer repris');
            }
            
            console.log('toggleTimer terminé - État après:', { roundActive, timerPaused, timerInterval: !!timerInterval });
        }

        function updatePauseButton() {
            const pauseBtn = document.getElementById('pause-timer-btn');
            const timerBar = document.getElementById('timer-bar');
            const timerDisplay = document.getElementById('timer-display');
            
            if (!pauseBtn) return;
            
            if (timerPaused) {
                pauseBtn.innerHTML = '<i class="fas fa-play mr-2"></i>Reprendre la partie';
                pauseBtn.className = 'px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors';
                
                // Indiquer visuellement que le timer est en pause
                if (timerBar) {
                    timerBar.style.animationPlayState = 'paused';
                    timerBar.classList.add('opacity-75');
                }
                if (timerDisplay) {
                    timerDisplay.classList.add('animate-pulse');
                }
            } else {
                pauseBtn.innerHTML = '<i class="fas fa-pause mr-2"></i>Mettre en pause';
                pauseBtn.className = 'px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg font-medium transition-colors';
                
                // Reprendre l'animation normale
                if (timerBar) {
                    timerBar.style.animationPlayState = 'running';
                    timerBar.classList.remove('opacity-75');
                }
                if (timerDisplay) {
                    timerDisplay.classList.remove('animate-pulse');
                }
            }
        }

        function updateTimerDisplay() {
            const timerDisplay = document.getElementById('timer-display');
            const timerBar = document.getElementById('timer-bar');
            
            timerDisplay.textContent = timeLeft;
            
            // Calculer le pourcentage restant
            const percentage = (timeLeft / 60) * 100;
            timerBar.style.width = percentage + '%';
            
            // Changer la couleur selon le temps restant
            let baseClasses = 'text-3xl font-bold';
            if (timeLeft <= 10) {
                timerBar.className = 'bg-red-600 h-3 rounded-full transition-all duration-1000';
                timerDisplay.className = baseClasses + ' text-red-600 dark:text-red-400';
                if (!timerPaused) timerDisplay.classList.add('animate-pulse');
            } else if (timeLeft <= 30) {
                timerBar.className = 'bg-orange-500 h-3 rounded-full transition-all duration-1000';
                timerDisplay.className = baseClasses + ' text-orange-600 dark:text-orange-400';
            } else {
                timerBar.className = 'bg-gradient-to-r from-green-500 to-yellow-500 h-3 rounded-full transition-all duration-1000';
                timerDisplay.className = baseClasses + ' text-green-600 dark:text-green-400';
            }
            
            // Ajouter l'animation de pause si nécessaire
            if (timerPaused) {
                timerDisplay.classList.add('animate-pulse');
                timerBar.classList.add('opacity-75');
            }
        }

        // Initialize theme on page load
        initTheme();
    </script>
</body>
</html>