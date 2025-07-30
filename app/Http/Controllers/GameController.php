<?php

namespace App\Http\Controllers;

use App\Models\GameSession;
use App\Models\Player;
use App\Models\Question;
use App\Models\Theme;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function createSession()
    {
        return view('quiz.create-session');
    }

    public function storeSession(Request $request)
    {
        $request->validate([
            'session_name' => 'nullable|string|max:255',
            'min_players' => 'required|integer|min:2|max:2',
            'max_players' => 'required|integer|min:2|max:2',
            'players' => 'required|array|size:2',
            'players.*' => 'required|string|max:100',
        ]);

        // Vérifier que les 2 joueurs sont fournis
        if (count($request->players) !== 2) {
            return back()->withErrors(['players' => 'Exactement 2 joueurs sont requis.']);
        }

        // Générer le nom de session automatiquement si non fourni
        $sessionName = $request->session_name;
        if (empty($sessionName)) {
            $player1 = trim($request->players[0]);
            $player2 = trim($request->players[1]);
            $dateTime = now()->setTimezone('Europe/Paris')->format('d/m/Y H:i');
            $sessionName = "{$player1} contre {$player2} - {$dateTime}";
        }

        // Récupérer tous les thèmes actifs pour la sélection automatique
        $allThemes = Theme::where('is_active', true)
            ->withCount('questions')
            ->having('questions_count', '>', 0)
            ->pluck('id')
            ->toArray();

        if (empty($allThemes)) {
            return back()->withErrors(['themes' => 'Aucun thème avec des questions n\'est disponible. Veuillez créer des thèmes dans l\'administration.']);
        }

        // Créer la session
        $session = GameSession::create([
            'name' => $sessionName,
            'min_players' => 2,
            'max_players' => 2,
            'status' => 'waiting',
            'selected_themes' => $allThemes, // Tous les thèmes disponibles
        ]);

        // Créer les joueurs
        foreach ($request->players as $playerName) {
            if (!empty(trim($playerName))) {
                Player::create([
                    'game_session_id' => $session->id,
                    'name' => trim($playerName),
                    'score' => 0,
                ]);
            }
        }

        return redirect()->route('game.lobby', $session->id);
    }

    public function lobby(GameSession $session)
    {
        $session->load('players');
        
        return view('quiz.lobby', compact('session'));
    }

    public function startGame(GameSession $session)
    {
        $session->update([
            'status' => 'playing',
            'started_at' => now(),
        ]);

        return redirect()->route('game.play', $session->id);
    }

    public function play(GameSession $session)
    {
        if ($session->status !== 'playing') {
            return redirect()->route('game.lobby', $session->id);
        }

        $session->load('players');
        
        // Récupérer toutes les questions des thèmes sélectionnés
        $questions = Question::whereIn('theme_id', $session->selected_themes)
            ->where('is_active', true)
            ->with(['answers', 'theme'])
            ->get();

        // Mélanger les questions
        $questions = $questions->shuffle();

        return view('quiz.play', compact('session', 'questions'));
    }

    public function updateScore(Request $request, GameSession $session)
    {
        $request->validate([
            'player_id' => 'required|exists:players,id',
            'question_id' => 'required|exists:questions,id',
            'answer_id' => 'nullable|exists:answers,id',
            'penalty' => 'nullable|boolean',
            'points' => 'required|integer',
        ]);

        $player = Player::findOrFail($request->player_id);
        $question = Question::with('answers')->findOrFail($request->question_id);

        // Vérifier que le joueur appartient à la session
        if ($player->game_session_id !== $session->id) {
            return response()->json(['error' => 'Joueur non autorisé'], 403);
        }

        $points = $request->points;

        // Si ce n'est pas une réponse piège, vérifier que la réponse existe
        if (!$request->penalty && $request->answer_id) {
            $answer = $question->answers()->findOrFail($request->answer_id);
            $points = $answer->points;
        }

        // Ajouter les points
        $player->increment('score', $points);

        // Marquer la question comme répondue pour ce joueur
        $answeredQuestions = $player->answered_questions ?? [];
        if (!in_array($request->question_id, $answeredQuestions)) {
            $answeredQuestions[] = (int) $request->question_id;
            $player->update(['answered_questions' => $answeredQuestions]);
        }

        return response()->json([
            'success' => true,
            'new_score' => $player->score,
            'points_earned' => $points,
        ]);
    }

    public function finishGame(GameSession $session)
    {
        $session->update([
            'status' => 'finished',
            'finished_at' => now(),
        ]);

        return redirect()->route('game.results', $session->id);
    }

    public function results(GameSession $session)
    {
        if ($session->status !== 'finished') {
            return redirect()->route('game.play', $session->id);
        }

        $session->load(['players' => function($query) {
            $query->orderBy('score', 'desc');
        }]);

        return view('quiz.results', compact('session'));
    }
}
