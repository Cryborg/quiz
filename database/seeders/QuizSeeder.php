<?php

namespace Database\Seeders;

use App\Models\Theme;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        // Créer des thèmes
        $themes = [
            [
                'name' => 'Cuisine',
                'description' => 'Questions sur la cuisine et la gastronomie',
            ],
            [
                'name' => 'Cinéma',
                'description' => 'Questions sur les films et le cinéma',
            ],
            [
                'name' => 'Sport',
                'description' => 'Questions sur les sports et les compétitions',
            ],
            [
                'name' => 'Géographie',
                'description' => 'Questions sur les pays, villes et géographie',
            ],
            [
                'name' => 'Histoire',
                'description' => 'Questions sur l\'histoire mondiale',
            ],
        ];

        foreach ($themes as $themeData) {
            $theme = Theme::create($themeData);

            // Questions pour le thème Cuisine
            if ($theme->name === 'Cuisine') {
                $question = Question::create([
                    'theme_id' => $theme->id,
                    'question' => 'Qu\'est-ce qu\'on trouve dans un frigo ?',
                    'penalty_answer' => 'Glace', // Piège : c'est au congélateur
                ]);

                $answers = [
                    ['answer' => 'Lait', 'points' => 1],
                    ['answer' => 'Yaourt', 'points' => 1],
                    ['answer' => 'Beurre', 'points' => 2],
                    ['answer' => 'Œufs', 'points' => 2],
                    ['answer' => 'Fromage', 'points' => 2],
                    ['answer' => 'Légumes', 'points' => 3],
                    ['answer' => 'Condiments', 'points' => 4],
                    ['answer' => 'Restes', 'points' => 4],
                    ['answer' => 'Médicaments', 'points' => 5],
                ];

                foreach ($answers as $answerData) {
                    Answer::create([
                        'question_id' => $question->id,
                        'answer' => $answerData['answer'],
                        'points' => $answerData['points'],
                    ]);
                }
            }

            // Questions pour le thème Cinéma
            if ($theme->name === 'Cinéma') {
                $question = Question::create([
                    'theme_id' => $theme->id,
                    'question' => 'Que peut-on voir dans un film de science-fiction ?',
                    'penalty_answer' => 'Dinosaures', // Piège : c'est plutôt fantastique
                ]);

                $answers = [
                    ['answer' => 'Vaisseaux spatiaux', 'points' => 1],
                    ['answer' => 'Robots', 'points' => 1],
                    ['answer' => 'Aliens', 'points' => 2],
                    ['answer' => 'Lasers', 'points' => 2],
                    ['answer' => 'Voyage temporel', 'points' => 3],
                    ['answer' => 'Téléportation', 'points' => 4],
                    ['answer' => 'Intelligence artificielle', 'points' => 4],
                    ['answer' => 'Cyberpunk', 'points' => 5],
                    ['answer' => 'Dystopie', 'points' => 5],
                ];

                foreach ($answers as $answerData) {
                    Answer::create([
                        'question_id' => $question->id,
                        'answer' => $answerData['answer'],
                        'points' => $answerData['points'],
                    ]);
                }
            }

            // Questions pour le thème Sport
            if ($theme->name === 'Sport') {
                $question = Question::create([
                    'theme_id' => $theme->id,
                    'question' => 'Quels sports se jouent avec un ballon ?',
                    'penalty_answer' => 'Tennis', // Piège : c'est une balle
                ]);

                $answers = [
                    ['answer' => 'Football', 'points' => 1],
                    ['answer' => 'Basketball', 'points' => 1],
                    ['answer' => 'Volleyball', 'points' => 2],
                    ['answer' => 'Rugby', 'points' => 2],
                    ['answer' => 'Handball', 'points' => 3],
                    ['answer' => 'Water-polo', 'points' => 4],
                    ['answer' => 'Football américain', 'points' => 4],
                    ['answer' => 'Netball', 'points' => 5],
                    ['answer' => 'Tchoukball', 'points' => 5],
                ];

                foreach ($answers as $answerData) {
                    Answer::create([
                        'question_id' => $question->id,
                        'answer' => $answerData['answer'],
                        'points' => $answerData['points'],
                    ]);
                }
            }
        }
    }
}