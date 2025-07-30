<?php

namespace Database\Seeders;

use App\Models\Theme;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Database\Seeder;

class QuizDataSeeder extends Seeder
{
    public function run(): void
    {
        // Charger les données du fichier JavaScript original
        $questionsData = $this->getQuestionsData();
        
        $themes = [];
        
        foreach ($questionsData as $questionData) {
            $themeName = $questionData['theme'];
            
            // Créer le thème s'il n'existe pas déjà
            if (!isset($themes[$themeName])) {
                $theme = Theme::updateOrCreate(
                    ['name' => $themeName],
                    [
                        'description' => $this->getThemeDescription($themeName),
                        'is_active' => true
                    ]
                );
                $themes[$themeName] = $theme;
            }
            
            $theme = $themes[$themeName];
            
            // Créer la question
            $question = Question::updateOrCreate(
                [
                    'theme_id' => $theme->id,
                    'question' => $questionData['question']
                ],
                [
                    'penalty_answer' => $questionData['penalty_answer'],
                    'is_active' => true
                ]
            );
            
            // Supprimer les anciennes réponses pour cette question
            $question->answers()->delete();
            
            // Créer les réponses
            foreach ($questionData['answers'] as $answerData) {
                Answer::create([
                    'question_id' => $question->id,
                    'answer' => $answerData['answer'],
                    'points' => $answerData['points']
                ]);
            }
        }
        
        $this->command->info('Import terminé : ' . count($themes) . ' thèmes et ' . count($questionsData) . ' questions importées.');
    }
    
    private function getThemeDescription(string $themeName): string
    {
        $descriptions = [
            'Cuisine' => 'Questions sur la cuisine, la gastronomie et l\'art culinaire',
            'Star Wars' => 'L\'univers complet de la saga Star Wars',
            'Dessins animés 80-90' => 'Les dessins animés cultes des années 80 et 90',
            'Heavy Metal' => 'L\'univers du metal et de la musique heavy',
            'Jeux Nintendo' => 'Les jeux et franchises iconiques de Nintendo',
            'Jeux PlayStation' => 'L\'écosystème PlayStation et ses exclusivités',
            'Personnages de jeux vidéo' => 'Les héros légendaires du jeu vidéo',
            'Jeux de combat' => 'Les grands classiques du fighting game',
            'Jeux PC' => 'Les incontournables du gaming sur PC',
            'Consoles de jeux' => 'L\'histoire des consoles de jeux vidéo',
            'Jeux mobiles' => 'Les hits du gaming mobile',
            'Studios de jeux vidéo' => 'Les créateurs derrière nos jeux favoris',
            'Jeux de sport' => 'Les simulations sportives virtuelles',
            'Jeux rétro' => 'Les classiques intemporels de l\'arcade'
        ];
        
        return $descriptions[$themeName] ?? "Questions sur le thème $themeName";
    }
    
    private function getQuestionsData(): array
    {
        return [
            // CUISINE - 10 questions
            [
                'theme' => 'Cuisine',
                'question' => "Qu'est-ce qu'on trouve dans un frigo ?",
                'penalty_answer' => 'Glace',
                'answers' => [
                    ['answer' => 'Lait', 'points' => 1],
                    ['answer' => 'Beurre', 'points' => 1],
                    ['answer' => 'Fromage', 'points' => 2],
                    ['answer' => 'Légumes', 'points' => 2],
                    ['answer' => 'Yaourt', 'points' => 3],
                    ['answer' => 'Sauce', 'points' => 3],
                    ['answer' => 'Restes', 'points' => 4],
                    ['answer' => 'Cornichons', 'points' => 4],
                    ['answer' => 'Condiments', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Cuisine',
                'question' => 'Citez des épices utilisées en cuisine',
                'penalty_answer' => 'Sucre',
                'answers' => [
                    ['answer' => 'Sel', 'points' => 1],
                    ['answer' => 'Poivre', 'points' => 1],
                    ['answer' => 'Paprika', 'points' => 2],
                    ['answer' => 'Curry', 'points' => 2],
                    ['answer' => 'Cumin', 'points' => 3],
                    ['answer' => 'Coriandre', 'points' => 3],
                    ['answer' => 'Safran', 'points' => 4],
                    ['answer' => 'Cardamome', 'points' => 4],
                    ['answer' => 'Sumac', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Cuisine',
                'question' => 'Citez des fromages français',
                'penalty_answer' => 'Mozzarella',
                'answers' => [
                    ['answer' => 'Camembert', 'points' => 1],
                    ['answer' => 'Brie', 'points' => 1],
                    ['answer' => 'Roquefort', 'points' => 2],
                    ['answer' => 'Comté', 'points' => 2],
                    ['answer' => 'Reblochon', 'points' => 3],
                    ['answer' => 'Munster', 'points' => 3],
                    ['answer' => 'Beaufort', 'points' => 4],
                    ['answer' => 'Crottin de Chavignol', 'points' => 4],
                    ['answer' => 'Picodon', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Cuisine',
                'question' => 'Citez des ustensiles de cuisine',
                'penalty_answer' => 'Assiette',
                'answers' => [
                    ['answer' => 'Couteau', 'points' => 1],
                    ['answer' => 'Casserole', 'points' => 1],
                    ['answer' => 'Louche', 'points' => 2],
                    ['answer' => 'Spatule', 'points' => 2],
                    ['answer' => 'Fouet', 'points' => 3],
                    ['answer' => 'Écumoire', 'points' => 3],
                    ['answer' => 'Mandoline', 'points' => 4],
                    ['answer' => 'Chinois', 'points' => 4],
                    ['answer' => 'Canneleur', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Cuisine',
                'question' => 'Citez des plats italiens',
                'penalty_answer' => 'Paella',
                'answers' => [
                    ['answer' => 'Pizza', 'points' => 1],
                    ['answer' => 'Pâtes', 'points' => 1],
                    ['answer' => 'Lasagnes', 'points' => 2],
                    ['answer' => 'Risotto', 'points' => 2],
                    ['answer' => 'Tiramisu', 'points' => 3],
                    ['answer' => 'Osso buco', 'points' => 3],
                    ['answer' => 'Carpaccio', 'points' => 4],
                    ['answer' => 'Saltimbocca', 'points' => 4],
                    ['answer' => 'Vitello tonnato', 'points' => 5]
                ]
            ],
            
            // STAR WARS - 10 questions  
            [
                'theme' => 'Star Wars',
                'question' => 'Citez des personnages de Star Wars',
                'penalty_answer' => 'Gandalf',
                'answers' => [
                    ['answer' => 'Luke Skywalker', 'points' => 1],
                    ['answer' => 'Dark Vador', 'points' => 1],
                    ['answer' => 'Leia Organa', 'points' => 2],
                    ['answer' => 'Han Solo', 'points' => 2],
                    ['answer' => 'Chewbacca', 'points' => 3],
                    ['answer' => 'Yoda', 'points' => 3],
                    ['answer' => 'Boba Fett', 'points' => 4],
                    ['answer' => 'Jabba le Hutt', 'points' => 4],
                    ['answer' => 'Mace Windu', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Star Wars',
                'question' => 'Citez des planètes de Star Wars',
                'penalty_answer' => 'Pandora',
                'answers' => [
                    ['answer' => 'Tatooine', 'points' => 1],
                    ['answer' => 'Coruscant', 'points' => 1],
                    ['answer' => 'Naboo', 'points' => 2],
                    ['answer' => 'Endor', 'points' => 2],
                    ['answer' => 'Hoth', 'points' => 3],
                    ['answer' => 'Dagobah', 'points' => 3],
                    ['answer' => 'Kamino', 'points' => 4],
                    ['answer' => 'Mustafar', 'points' => 4],
                    ['answer' => 'Scarif', 'points' => 5]
                ]
            ],
            
            // DESSINS ANIMÉS 80-90 - 10 questions
            [
                'theme' => 'Dessins animés 80-90',
                'question' => 'Citez des dessins animés des années 80-90',
                'penalty_answer' => 'Naruto',
                'answers' => [
                    ['answer' => 'Les Tortues Ninja', 'points' => 1],
                    ['answer' => 'Dragon Ball', 'points' => 1],
                    ['answer' => 'Les Chevaliers du Zodiaque', 'points' => 2],
                    ['answer' => 'Goldorak', 'points' => 2],
                    ['answer' => 'Inspecteur Gadget', 'points' => 3],
                    ['answer' => 'Denver le dernier dinosaure', 'points' => 3],
                    ['answer' => 'Les Mystérieuses Cités d\'or', 'points' => 4],
                    ['answer' => 'Ulysse 31', 'points' => 4],
                    ['answer' => 'Jayce et les Conquérants de la lumière', 'points' => 5]
                ]
            ],
            
            // HEAVY METAL - Quelques questions représentatives
            [
                'theme' => 'Heavy Metal',
                'question' => 'Citez des groupes de Heavy Metal célèbres',
                'penalty_answer' => 'Nirvana',
                'answers' => [
                    ['answer' => 'Metallica', 'points' => 1],
                    ['answer' => 'Iron Maiden', 'points' => 1],
                    ['answer' => 'AC/DC', 'points' => 2],
                    ['answer' => 'Black Sabbath', 'points' => 2],
                    ['answer' => 'Judas Priest', 'points' => 3],
                    ['answer' => 'Megadeth', 'points' => 3],
                    ['answer' => 'Slayer', 'points' => 4],
                    ['answer' => 'Motörhead', 'points' => 4],
                    ['answer' => 'Anthrax', 'points' => 5]
                ]
            ],
            
            // JEUX NINTENDO - Quelques questions représentatives
            [
                'theme' => 'Jeux Nintendo',
                'question' => 'Citez des jeux Mario',
                'penalty_answer' => 'Sonic Mania',
                'answers' => [
                    ['answer' => 'Super Mario Bros', 'points' => 1],
                    ['answer' => 'Mario Kart', 'points' => 1],
                    ['answer' => 'Mario Odyssey', 'points' => 2],
                    ['answer' => 'Mario Galaxy', 'points' => 2],
                    ['answer' => 'Paper Mario', 'points' => 3],
                    ['answer' => 'Mario Party', 'points' => 3],
                    ['answer' => 'Mario Strikers', 'points' => 4],
                    ['answer' => 'Mario RPG', 'points' => 4],
                    ['answer' => 'Mario Sunshine', 'points' => 5]
                ]
            ],
            
            // JEUX PLAYSTATION - Quelques questions représentatives
            [
                'theme' => 'Jeux PlayStation',
                'question' => 'Citez des exclusivités PlayStation',
                'penalty_answer' => 'Halo',
                'answers' => [
                    ['answer' => 'The Last of Us', 'points' => 1],
                    ['answer' => 'God of War', 'points' => 1],
                    ['answer' => 'Spider-Man', 'points' => 2],
                    ['answer' => 'Uncharted', 'points' => 2],
                    ['answer' => 'Horizon Zero Dawn', 'points' => 3],
                    ['answer' => 'Gran Turismo', 'points' => 3],
                    ['answer' => 'Bloodborne', 'points' => 4],
                    ['answer' => 'Ratchet & Clank', 'points' => 4],
                    ['answer' => 'Shadow of the Colossus', 'points' => 5]
                ]
            ]
        ];
    }
}