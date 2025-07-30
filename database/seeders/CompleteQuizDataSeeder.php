<?php

namespace Database\Seeders;

use App\Models\Theme;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Database\Seeder;

class CompleteQuizDataSeeder extends Seeder
{
    public function run(): void
    {
        // Charger TOUTES les données du fichier JavaScript original
        $questionsData = $this->getAllQuestionsData();
        
        $themes = [];
        $questionCount = 0;
        
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
                $this->command->info("Thème créé : $themeName");
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
            
            $questionCount++;
        }
        
        $this->command->info("Import terminé : " . count($themes) . " thèmes et $questionCount questions importées.");
        
        // Afficher le résumé par thème
        foreach ($themes as $themeName => $theme) {
            $count = $theme->questions()->count();
            $this->command->info("- $themeName: $count questions");
        }
    }
    
    private function getThemeDescription(string $themeName): string
    {
        $descriptions = [
            'Cuisine' => 'Questions sur la cuisine, la gastronomie et l\'art culinaire français et international',
            'Star Wars' => 'L\'univers complet de la saga Star Wars : personnages, planètes, vaisseaux et plus',
            'Dessins animés 80-90' => 'Les dessins animés cultes des années 80 et 90 qui ont marqué une génération',
            'Heavy Metal' => 'L\'univers du metal et de la musique heavy : groupes, albums, festivals',
            'Jeux Nintendo' => 'Les jeux et franchises iconiques de Nintendo à travers les générations',
            'Jeux PlayStation' => 'L\'écosystème PlayStation et ses exclusivités légendaires',
            'Personnages de jeux vidéo' => 'Les héros légendaires qui ont marqué l\'histoire du jeu vidéo',
            'Jeux de combat' => 'Les grands classiques du fighting game et leurs mécaniques',
            'Jeux PC' => 'Les incontournables du gaming sur PC et leurs communautés',
            'Consoles de jeux' => 'L\'histoire des consoles de jeux vidéo et leur évolution',
            'Jeux mobiles' => 'Les hits du gaming mobile qui ont révolutionné le secteur',
            'Studios de jeux vidéo' => 'Les créateurs et développeurs derrière nos jeux favoris',
            'Jeux de sport' => 'Les simulations sportives virtuelles et leurs licences',
            'Jeux rétro' => 'Les classiques intemporels de l\'arcade et leur héritage'
        ];
        
        return $descriptions[$themeName] ?? "Questions sur le thème $themeName";
    }
    
    private function getAllQuestionsData(): array
    {
        return [
            // CUISINE - 10 questions complètes
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
            [
                'theme' => 'Cuisine',
                'question' => 'Citez des légumes verts',
                'penalty_answer' => 'Tomate verte',
                'answers' => [
                    ['answer' => 'Salade', 'points' => 1],
                    ['answer' => 'Épinards', 'points' => 1],
                    ['answer' => 'Haricots verts', 'points' => 2],
                    ['answer' => 'Brocoli', 'points' => 2],
                    ['answer' => 'Courgette', 'points' => 3],
                    ['answer' => 'Petits pois', 'points' => 3],
                    ['answer' => 'Blette', 'points' => 4],
                    ['answer' => 'Roquette', 'points' => 4],
                    ['answer' => 'Chou romanesco', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Cuisine',
                'question' => 'Citez des modes de cuisson',
                'penalty_answer' => 'Congeler',
                'answers' => [
                    ['answer' => 'Bouillir', 'points' => 1],
                    ['answer' => 'Frire', 'points' => 1],
                    ['answer' => 'Griller', 'points' => 2],
                    ['answer' => 'Rôtir', 'points' => 2],
                    ['answer' => 'Pocher', 'points' => 3],
                    ['answer' => 'Braiser', 'points' => 3],
                    ['answer' => 'Confire', 'points' => 4],
                    ['answer' => 'Flamber', 'points' => 4],
                    ['answer' => 'Sous-vide', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Cuisine',
                'question' => 'Citez des herbes aromatiques',
                'penalty_answer' => 'Origan',
                'answers' => [
                    ['answer' => 'Persil', 'points' => 1],
                    ['answer' => 'Basilic', 'points' => 1],
                    ['answer' => 'Thym', 'points' => 2],
                    ['answer' => 'Romarin', 'points' => 2],
                    ['answer' => 'Ciboulette', 'points' => 3],
                    ['answer' => 'Estragon', 'points' => 3],
                    ['answer' => 'Aneth', 'points' => 4],
                    ['answer' => 'Sarriette', 'points' => 4],
                    ['answer' => 'Livèche', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Cuisine',
                'question' => 'Citez des desserts français',
                'penalty_answer' => 'Tiramisu',
                'answers' => [
                    ['answer' => 'Crêpes', 'points' => 1],
                    ['answer' => 'Tarte aux pommes', 'points' => 1],
                    ['answer' => 'Éclair', 'points' => 2],
                    ['answer' => 'Millefeuille', 'points' => 2],
                    ['answer' => 'Paris-Brest', 'points' => 3],
                    ['answer' => 'Opéra', 'points' => 3],
                    ['answer' => 'Kouign-amann', 'points' => 4],
                    ['answer' => 'Canelé', 'points' => 4],
                    ['answer' => 'Pithiviers', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Cuisine',
                'question' => 'Citez des sauces classiques',
                'penalty_answer' => 'Ketchup',
                'answers' => [
                    ['answer' => 'Mayonnaise', 'points' => 1],
                    ['answer' => 'Vinaigrette', 'points' => 1],
                    ['answer' => 'Béchamel', 'points' => 2],
                    ['answer' => 'Hollandaise', 'points' => 2],
                    ['answer' => 'Béarnaise', 'points' => 3],
                    ['answer' => 'Aïoli', 'points' => 3],
                    ['answer' => 'Beurre blanc', 'points' => 4],
                    ['answer' => 'Sauce vierge', 'points' => 4],
                    ['answer' => 'Sauce Choron', 'points' => 5]
                ]
            ],

            // STAR WARS - 10 questions complètes
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
            [
                'theme' => 'Star Wars',
                'question' => 'Citez des vaisseaux de Star Wars',
                'penalty_answer' => 'Normandy',
                'answers' => [
                    ['answer' => 'Faucon Millénium', 'points' => 1],
                    ['answer' => 'X-Wing', 'points' => 1],
                    ['answer' => 'TIE Fighter', 'points' => 2],
                    ['answer' => 'Étoile de la Mort', 'points' => 2],
                    ['answer' => 'Destroyer Stellaire', 'points' => 3],
                    ['answer' => 'A-Wing', 'points' => 3],
                    ['answer' => 'Slave I', 'points' => 4],
                    ['answer' => 'Navette Lambda', 'points' => 4],
                    ['answer' => 'Naboo Starfighter', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Star Wars',
                'question' => 'Citez des Jedi célèbres',
                'penalty_answer' => 'Neo',
                'answers' => [
                    ['answer' => 'Yoda', 'points' => 1],
                    ['answer' => 'Obi-Wan Kenobi', 'points' => 1],
                    ['answer' => 'Luke Skywalker', 'points' => 2],
                    ['answer' => 'Anakin Skywalker', 'points' => 2],
                    ['answer' => 'Mace Windu', 'points' => 3],
                    ['answer' => 'Qui-Gon Jinn', 'points' => 3],
                    ['answer' => 'Ahsoka Tano', 'points' => 4],
                    ['answer' => 'Kit Fisto', 'points' => 4],
                    ['answer' => 'Plo Koon', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Star Wars',
                'question' => 'Citez des créatures de Star Wars',
                'penalty_answer' => 'Orc',
                'answers' => [
                    ['answer' => 'Wookie', 'points' => 1],
                    ['answer' => 'Ewok', 'points' => 1],
                    ['answer' => 'Jawa', 'points' => 2],
                    ['answer' => 'Tusken', 'points' => 2],
                    ['answer' => 'Bantha', 'points' => 3],
                    ['answer' => 'Rancor', 'points' => 3],
                    ['answer' => 'Sarlacc', 'points' => 4],
                    ['answer' => 'Wampa', 'points' => 4],
                    ['answer' => 'Purrgil', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Star Wars',
                'question' => 'Citez des armes de Star Wars',
                'penalty_answer' => 'Baguette magique',
                'answers' => [
                    ['answer' => 'Sabre laser', 'points' => 1],
                    ['answer' => 'Blaster', 'points' => 1],
                    ['answer' => 'Étoile de la Mort', 'points' => 2],
                    ['answer' => 'Pistolet DL-44', 'points' => 2],
                    ['answer' => 'Électrobâton', 'points' => 3],
                    ['answer' => 'Bowcaster', 'points' => 3],
                    ['answer' => 'Vibrolame', 'points' => 4],
                    ['answer' => 'Darksaber', 'points' => 4],
                    ['answer' => 'Pistolet ionique', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Star Wars',
                'question' => 'Citez des droïdes de Star Wars',
                'penalty_answer' => 'Terminator',
                'answers' => [
                    ['answer' => 'R2-D2', 'points' => 1],
                    ['answer' => 'C-3PO', 'points' => 1],
                    ['answer' => 'BB-8', 'points' => 2],
                    ['answer' => 'Droïde de combat', 'points' => 2],
                    ['answer' => 'IG-88', 'points' => 3],
                    ['answer' => 'K-2SO', 'points' => 3],
                    ['answer' => 'HK-47', 'points' => 4],
                    ['answer' => 'Chopper', 'points' => 4],
                    ['answer' => 'L3-37', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Star Wars',
                'question' => 'Citez des Sith ou méchants',
                'penalty_answer' => 'Thanos',
                'answers' => [
                    ['answer' => 'Dark Vador', 'points' => 1],
                    ['answer' => 'Empereur Palpatine', 'points' => 1],
                    ['answer' => 'Dark Maul', 'points' => 2],
                    ['answer' => 'Comte Dooku', 'points' => 2],
                    ['answer' => 'Kylo Ren', 'points' => 3],
                    ['answer' => 'General Grievous', 'points' => 3],
                    ['answer' => 'Asajj Ventress', 'points' => 4],
                    ['answer' => 'Dark Plagueis', 'points' => 4],
                    ['answer' => 'Snoke', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Star Wars',
                'question' => 'Citez des organisations de Star Wars',
                'penalty_answer' => 'Avengers',
                'answers' => [
                    ['answer' => 'Empire', 'points' => 1],
                    ['answer' => 'Rébellion', 'points' => 1],
                    ['answer' => 'République', 'points' => 2],
                    ['answer' => 'Premier Ordre', 'points' => 2],
                    ['answer' => 'Séparatistes', 'points' => 3],
                    ['answer' => 'Résistance', 'points' => 3],
                    ['answer' => 'Guilde des Chasseurs de Primes', 'points' => 4],
                    ['answer' => 'Syndicat Pyke', 'points' => 4],
                    ['answer' => 'Crimson Dawn', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Star Wars',
                'question' => 'Citez des lieux emblématiques',
                'penalty_answer' => 'Poudlard',
                'answers' => [
                    ['answer' => 'Cantina de Mos Eisley', 'points' => 1],
                    ['answer' => 'Temple Jedi', 'points' => 1],
                    ['answer' => 'Palais de Jabba', 'points' => 2],
                    ['answer' => 'Base Echo', 'points' => 2],
                    ['answer' => 'Cloud City', 'points' => 3],
                    ['answer' => 'Sénat Galactique', 'points' => 3],
                    ['answer' => 'Mines de Kessel', 'points' => 4],
                    ['answer' => 'Château de Vador', 'points' => 4],
                    ['answer' => 'Mortis', 'points' => 5]
                ]
            ],

            // DESSINS ANIMÉS 80-90 - 10 questions complètes
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
            [
                'theme' => 'Dessins animés 80-90',
                'question' => 'Citez des personnages des Tortues Ninja',
                'penalty_answer' => 'Garfield',
                'answers' => [
                    ['answer' => 'Leonardo', 'points' => 1],
                    ['answer' => 'Donatello', 'points' => 1],
                    ['answer' => 'Raphael', 'points' => 2],
                    ['answer' => 'Michelangelo', 'points' => 2],
                    ['answer' => 'Splinter', 'points' => 3],
                    ['answer' => 'Shredder', 'points' => 3],
                    ['answer' => 'April O\'Neil', 'points' => 4],
                    ['answer' => 'Bebop', 'points' => 4],
                    ['answer' => 'Rocksteady', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Dessins animés 80-90',
                'question' => 'Citez des dessins animés de robots',
                'penalty_answer' => 'Evangelion',
                'answers' => [
                    ['answer' => 'Goldorak', 'points' => 1],
                    ['answer' => 'Transformers', 'points' => 1],
                    ['answer' => 'Voltron', 'points' => 2],
                    ['answer' => 'Robotech', 'points' => 2],
                    ['answer' => 'Albator', 'points' => 3],
                    ['answer' => 'Mazinger Z', 'points' => 3],
                    ['answer' => 'Gundam', 'points' => 4],
                    ['answer' => 'Macross', 'points' => 4],
                    ['answer' => 'Patlabor', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Dessins animés 80-90',
                'question' => 'Citez des héros de dessins animés',
                'penalty_answer' => 'Dora',
                'answers' => [
                    ['answer' => 'He-Man', 'points' => 1],
                    ['answer' => 'She-Ra', 'points' => 1],
                    ['answer' => 'Musclor', 'points' => 2],
                    ['answer' => 'Lion-O', 'points' => 2],
                    ['answer' => 'Capitaine Flam', 'points' => 3],
                    ['answer' => 'Ken le survivant', 'points' => 3],
                    ['answer' => 'Cobra', 'points' => 4],
                    ['answer' => 'Nicky Larson', 'points' => 4],
                    ['answer' => 'Jeanne et Serge', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Dessins animés 80-90',
                'question' => 'Citez des dessins animés Disney de l\'époque',
                'penalty_answer' => 'La Reine des Neiges',
                'answers' => [
                    ['answer' => 'La Bande à Picsou', 'points' => 1],
                    ['answer' => 'Tic et Tac', 'points' => 1],
                    ['answer' => 'Super Baloo', 'points' => 2],
                    ['answer' => 'Myster Mask', 'points' => 2],
                    ['answer' => 'La Bande à Dingo', 'points' => 3],
                    ['answer' => 'Bonkers', 'points' => 3],
                    ['answer' => 'Couacs en vrac', 'points' => 4],
                    ['answer' => 'Les Gummi', 'points' => 4],
                    ['answer' => 'Dinosaures', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Dessins animés 80-90',
                'question' => 'Citez des méchants de dessins animés',
                'penalty_answer' => 'Freezer',
                'answers' => [
                    ['answer' => 'Skeletor', 'points' => 1],
                    ['answer' => 'Gargamel', 'points' => 1],
                    ['answer' => 'Dr Gang', 'points' => 2],
                    ['answer' => 'Mumm-Ra', 'points' => 2],
                    ['answer' => 'Le Docteur Mad', 'points' => 3],
                    ['answer' => 'Shredder', 'points' => 3],
                    ['answer' => 'Megatron', 'points' => 4],
                    ['answer' => 'Le Shérif de Nottingham', 'points' => 4],
                    ['answer' => 'Baron Ashura', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Dessins animés 80-90',
                'question' => 'Citez des génériques cultes',
                'penalty_answer' => 'One Piece',
                'answers' => [
                    ['answer' => 'Goldorak', 'points' => 1],
                    ['answer' => 'Dragon Ball', 'points' => 1],
                    ['answer' => 'Olive et Tom', 'points' => 2],
                    ['answer' => 'Les Chevaliers du Zodiaque', 'points' => 2],
                    ['answer' => 'Nicky Larson', 'points' => 3],
                    ['answer' => 'Ken le survivant', 'points' => 3],
                    ['answer' => 'Sherlock Holmes', 'points' => 4],
                    ['answer' => 'Les Entrechats', 'points' => 4],
                    ['answer' => 'Lucile amour et rock\'n\'roll', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Dessins animés 80-90',
                'question' => 'Citez des dessins animés d\'aventure',
                'penalty_answer' => 'One Punch Man',
                'answers' => [
                    ['answer' => 'Les Mystérieuses Cités d\'or', 'points' => 1],
                    ['answer' => 'Ulysse 31', 'points' => 1],
                    ['answer' => 'Tom Sawyer', 'points' => 2],
                    ['answer' => 'Il était une fois la vie', 'points' => 2],
                    ['answer' => 'Rémi sans famille', 'points' => 3],
                    ['answer' => 'Les Mondes engloutis', 'points' => 3],
                    ['answer' => 'Willy Fog', 'points' => 4],
                    ['answer' => 'Sherlock Holmes', 'points' => 4],
                    ['answer' => 'Les Minipouss', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Dessins animés 80-90',
                'question' => 'Citez des animaux héros',
                'penalty_answer' => 'Pikachu',
                'answers' => [
                    ['answer' => 'Scoubidou', 'points' => 1],
                    ['answer' => 'Les Schtroumpfs', 'points' => 1],
                    ['answer' => 'Maya l\'abeille', 'points' => 2],
                    ['answer' => 'Calimero', 'points' => 2],
                    ['answer' => 'Sport Billy', 'points' => 3],
                    ['answer' => 'Les Bisounours', 'points' => 3],
                    ['answer' => 'Les Entrechats', 'points' => 4],
                    ['answer' => 'Sibor', 'points' => 4],
                    ['answer' => 'Les Popples', 'points' => 5]
                ]
            ],

            // HEAVY METAL - 10 questions complètes
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
            [
                'theme' => 'Heavy Metal',
                'question' => 'Citez des chanteurs de metal',
                'penalty_answer' => 'Kurt Cobain',
                'answers' => [
                    ['answer' => 'Ozzy Osbourne', 'points' => 1],
                    ['answer' => 'Bruce Dickinson', 'points' => 1],
                    ['answer' => 'James Hetfield', 'points' => 2],
                    ['answer' => 'Rob Halford', 'points' => 2],
                    ['answer' => 'Ronnie James Dio', 'points' => 3],
                    ['answer' => 'Lemmy Kilmister', 'points' => 3],
                    ['answer' => 'Dave Mustaine', 'points' => 4],
                    ['answer' => 'Tom Araya', 'points' => 4],
                    ['answer' => 'Phil Anselmo', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Heavy Metal',
                'question' => 'Citez des albums mythiques du metal',
                'penalty_answer' => 'The Wall',
                'answers' => [
                    ['answer' => 'Master of Puppets', 'points' => 1],
                    ['answer' => 'The Number of the Beast', 'points' => 1],
                    ['answer' => 'Paranoid', 'points' => 2],
                    ['answer' => 'Back in Black', 'points' => 2],
                    ['answer' => 'Rust in Peace', 'points' => 3],
                    ['answer' => 'Reign in Blood', 'points' => 3],
                    ['answer' => 'Painkiller', 'points' => 4],
                    ['answer' => 'Ace of Spades', 'points' => 4],
                    ['answer' => 'Among the Living', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Heavy Metal',
                'question' => 'Citez des sous-genres du metal',
                'penalty_answer' => 'Reggae metal',
                'answers' => [
                    ['answer' => 'Thrash metal', 'points' => 1],
                    ['answer' => 'Death metal', 'points' => 1],
                    ['answer' => 'Black metal', 'points' => 2],
                    ['answer' => 'Power metal', 'points' => 2],
                    ['answer' => 'Doom metal', 'points' => 3],
                    ['answer' => 'Progressive metal', 'points' => 3],
                    ['answer' => 'Symphonic metal', 'points' => 4],
                    ['answer' => 'Folk metal', 'points' => 4],
                    ['answer' => 'Djent', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Heavy Metal',
                'question' => 'Citez des guitaristes légendaires',
                'penalty_answer' => 'Jimi Hendrix',
                'answers' => [
                    ['answer' => 'Tony Iommi', 'points' => 1],
                    ['answer' => 'Kirk Hammett', 'points' => 1],
                    ['answer' => 'Dave Murray', 'points' => 2],
                    ['answer' => 'Randy Rhoads', 'points' => 2],
                    ['answer' => 'Dimebag Darrell', 'points' => 3],
                    ['answer' => 'Zakk Wylde', 'points' => 3],
                    ['answer' => 'Marty Friedman', 'points' => 4],
                    ['answer' => 'Jeff Hanneman', 'points' => 4],
                    ['answer' => 'Alexi Laiho', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Heavy Metal',
                'question' => 'Citez des festivals de metal',
                'penalty_answer' => 'Coachella',
                'answers' => [
                    ['answer' => 'Hellfest', 'points' => 1],
                    ['answer' => 'Wacken', 'points' => 1],
                    ['answer' => 'Download', 'points' => 2],
                    ['answer' => 'Graspop', 'points' => 2],
                    ['answer' => 'Bloodstock', 'points' => 3],
                    ['answer' => 'Sweden Rock', 'points' => 3],
                    ['answer' => 'Tuska', 'points' => 4],
                    ['answer' => 'Brutal Assault', 'points' => 4],
                    ['answer' => 'Maryland Deathfest', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Heavy Metal',
                'question' => 'Citez des groupes nordiques',
                'penalty_answer' => 'Rammstein',
                'answers' => [
                    ['answer' => 'In Flames', 'points' => 1],
                    ['answer' => 'Children of Bodom', 'points' => 1],
                    ['answer' => 'Amon Amarth', 'points' => 2],
                    ['answer' => 'Opeth', 'points' => 2],
                    ['answer' => 'Dimmu Borgir', 'points' => 3],
                    ['answer' => 'Emperor', 'points' => 3],
                    ['answer' => 'Meshuggah', 'points' => 4],
                    ['answer' => 'Mayhem', 'points' => 4],
                    ['answer' => 'Bathory', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Heavy Metal',
                'question' => 'Citez des chansons emblématiques',
                'penalty_answer' => 'Stairway to Heaven',
                'answers' => [
                    ['answer' => 'Enter Sandman', 'points' => 1],
                    ['answer' => 'Run to the Hills', 'points' => 1],
                    ['answer' => 'Crazy Train', 'points' => 2],
                    ['answer' => 'Holy Diver', 'points' => 2],
                    ['answer' => 'Peace Sells', 'points' => 3],
                    ['answer' => 'Angel of Death', 'points' => 3],
                    ['answer' => 'Breaking the Law', 'points' => 4],
                    ['answer' => 'Ace of Spades', 'points' => 4],
                    ['answer' => 'Indians', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Heavy Metal',
                'question' => 'Citez des labels de metal',
                'penalty_answer' => 'Sony Music',
                'answers' => [
                    ['answer' => 'Nuclear Blast', 'points' => 1],
                    ['answer' => 'Century Media', 'points' => 1],
                    ['answer' => 'Metal Blade', 'points' => 2],
                    ['answer' => 'Roadrunner', 'points' => 2],
                    ['answer' => 'Earache', 'points' => 3],
                    ['answer' => 'Season of Mist', 'points' => 3],
                    ['answer' => 'Relapse', 'points' => 4],
                    ['answer' => 'Peaceville', 'points' => 4],
                    ['answer' => 'Profound Lore', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Heavy Metal',
                'question' => 'Citez des mascottes de groupes',
                'penalty_answer' => 'Mickey Mouse',
                'answers' => [
                    ['answer' => 'Eddie (Iron Maiden)', 'points' => 1],
                    ['answer' => 'Vic Rattlehead (Megadeth)', 'points' => 1],
                    ['answer' => 'Snaggletooth (Motörhead)', 'points' => 2],
                    ['answer' => 'Chaly (Overkill)', 'points' => 2],
                    ['answer' => 'Jack Owen (Cannibal Corpse)', 'points' => 3],
                    ['answer' => 'Knarrenheinz (Sodom)', 'points' => 3],
                    ['answer' => 'Not Man (Anthrax)', 'points' => 4],
                    ['answer' => 'Roy (Helloween)', 'points' => 4],
                    ['answer' => 'Orgasmatron (Motörhead)', 'points' => 5]
                ]
            ],

            // JEUX NINTENDO - 10 questions complètes
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
            [
                'theme' => 'Jeux Nintendo',
                'question' => 'Citez des jeux Zelda',
                'penalty_answer' => 'Final Fantasy',
                'answers' => [
                    ['answer' => 'Breath of the Wild', 'points' => 1],
                    ['answer' => 'Ocarina of Time', 'points' => 1],
                    ['answer' => 'Majora\'s Mask', 'points' => 2],
                    ['answer' => 'Twilight Princess', 'points' => 2],
                    ['answer' => 'Wind Waker', 'points' => 3],
                    ['answer' => 'A Link to the Past', 'points' => 3],
                    ['answer' => 'Skyward Sword', 'points' => 4],
                    ['answer' => 'Link\'s Awakening', 'points' => 4],
                    ['answer' => 'Tears of the Kingdom', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Jeux Nintendo',
                'question' => 'Citez des personnages Nintendo',
                'penalty_answer' => 'Master Chief',
                'answers' => [
                    ['answer' => 'Mario', 'points' => 1],
                    ['answer' => 'Link', 'points' => 1],
                    ['answer' => 'Pikachu', 'points' => 2],
                    ['answer' => 'Samus', 'points' => 2],
                    ['answer' => 'Donkey Kong', 'points' => 3],
                    ['answer' => 'Yoshi', 'points' => 3],
                    ['answer' => 'Kirby', 'points' => 4],
                    ['answer' => 'Fox McCloud', 'points' => 4],
                    ['answer' => 'Captain Olimar', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Jeux Nintendo',
                'question' => 'Citez des consoles Nintendo',
                'penalty_answer' => 'PlayStation',
                'answers' => [
                    ['answer' => 'Nintendo Switch', 'points' => 1],
                    ['answer' => 'Game Boy', 'points' => 1],
                    ['answer' => 'Nintendo DS', 'points' => 2],
                    ['answer' => 'Nintendo Wii', 'points' => 2],
                    ['answer' => 'Super Nintendo', 'points' => 3],
                    ['answer' => 'Nintendo 64', 'points' => 3],
                    ['answer' => 'GameCube', 'points' => 4],
                    ['answer' => 'Game Boy Advance', 'points' => 4],
                    ['answer' => 'Virtual Boy', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Jeux Nintendo',
                'question' => 'Citez des jeux Pokémon',
                'penalty_answer' => 'Digimon',
                'answers' => [
                    ['answer' => 'Pokémon Rouge', 'points' => 1],
                    ['answer' => 'Pokémon Go', 'points' => 1],
                    ['answer' => 'Pokémon Épée', 'points' => 2],
                    ['answer' => 'Pokémon Diamant', 'points' => 2],
                    ['answer' => 'Pokémon Platine', 'points' => 3],
                    ['answer' => 'Pokémon Emeraude', 'points' => 3],
                    ['answer' => 'Pokémon Cristal', 'points' => 4],
                    ['answer' => 'Pokémon Ranger', 'points' => 4],
                    ['answer' => 'Pokémon Snap', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Jeux Nintendo',
                'question' => 'Citez des jeux Metroid',
                'penalty_answer' => 'Mega Man',
                'answers' => [
                    ['answer' => 'Metroid Prime', 'points' => 1],
                    ['answer' => 'Super Metroid', 'points' => 1],
                    ['answer' => 'Metroid Dread', 'points' => 2],
                    ['answer' => 'Metroid Fusion', 'points' => 2],
                    ['answer' => 'Metroid Zero Mission', 'points' => 3],
                    ['answer' => 'Metroid Prime 2', 'points' => 3],
                    ['answer' => 'Metroid Prime 3', 'points' => 4],
                    ['answer' => 'Metroid Other M', 'points' => 4],
                    ['answer' => 'Metroid Prime Hunters', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Jeux Nintendo',
                'question' => 'Citez des jeux multijoueurs Nintendo',
                'penalty_answer' => 'Call of Duty',
                'answers' => [
                    ['answer' => 'Mario Kart', 'points' => 1],
                    ['answer' => 'Super Smash Bros', 'points' => 1],
                    ['answer' => 'Mario Party', 'points' => 2],
                    ['answer' => 'Splatoon', 'points' => 2],
                    ['answer' => 'Arms', 'points' => 3],
                    ['answer' => 'Mario Tennis', 'points' => 3],
                    ['answer' => 'Wii Sports', 'points' => 4],
                    ['answer' => 'Clubhouse Games', 'points' => 4],
                    ['answer' => 'Nintendo Land', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Jeux Nintendo',
                'question' => 'Citez des jeux d\'aventure Nintendo',
                'penalty_answer' => 'Uncharted',
                'answers' => [
                    ['answer' => 'Zelda', 'points' => 1],
                    ['answer' => 'Metroid', 'points' => 1],
                    ['answer' => 'Xenoblade', 'points' => 2],
                    ['answer' => 'Fire Emblem', 'points' => 2],
                    ['answer' => 'Pikmin', 'points' => 3],
                    ['answer' => 'Star Fox', 'points' => 3],
                    ['answer' => 'F-Zero', 'points' => 4],
                    ['answer' => 'Kid Icarus', 'points' => 4],
                    ['answer' => 'Eternal Darkness', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Jeux Nintendo',
                'question' => 'Citez des jeux de plateforme Nintendo',
                'penalty_answer' => 'Sonic',
                'answers' => [
                    ['answer' => 'Super Mario', 'points' => 1],
                    ['answer' => 'Donkey Kong', 'points' => 1],
                    ['answer' => 'Kirby', 'points' => 2],
                    ['answer' => 'Yoshi', 'points' => 2],
                    ['answer' => 'Rayman', 'points' => 3],
                    ['answer' => 'Shovel Knight', 'points' => 3],
                    ['answer' => 'Celeste', 'points' => 4],
                    ['answer' => 'Hollow Knight', 'points' => 4],
                    ['answer' => 'A Hat in Time', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Jeux Nintendo',
                'question' => 'Citez des exclusivités Nintendo récentes',
                'penalty_answer' => 'The Last of Us',
                'answers' => [
                    ['answer' => 'Breath of the Wild', 'points' => 1],
                    ['answer' => 'Mario Odyssey', 'points' => 1],
                    ['answer' => 'Animal Crossing', 'points' => 2],
                    ['answer' => 'Splatoon 3', 'points' => 2],
                    ['answer' => 'Metroid Dread', 'points' => 3],
                    ['answer' => 'Kirby et le monde oublié', 'points' => 3],
                    ['answer' => 'Xenoblade Chronicles 3', 'points' => 4],
                    ['answer' => 'Bayonetta 3', 'points' => 4],
                    ['answer' => 'Triangle Strategy', 'points' => 5]
                ]
            ],

            // JEUX PLAYSTATION - 10 questions complètes
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
            ],
            [
                'theme' => 'Jeux PlayStation',
                'question' => 'Citez des jeux PlayStation 1 cultes',
                'penalty_answer' => 'Super Mario 64',
                'answers' => [
                    ['answer' => 'Final Fantasy VII', 'points' => 1],
                    ['answer' => 'Metal Gear Solid', 'points' => 1],
                    ['answer' => 'Resident Evil', 'points' => 2],
                    ['answer' => 'Crash Bandicoot', 'points' => 2],
                    ['answer' => 'Spyro', 'points' => 3],
                    ['answer' => 'Tekken 3', 'points' => 3],
                    ['answer' => 'Silent Hill', 'points' => 4],
                    ['answer' => 'Castlevania Symphony', 'points' => 4],
                    ['answer' => 'Xenogears', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Jeux PlayStation',
                'question' => 'Citez des jeux PlayStation 2 iconiques',
                'penalty_answer' => 'Zelda Wind Waker',
                'answers' => [
                    ['answer' => 'Grand Theft Auto', 'points' => 1],
                    ['answer' => 'God of War', 'points' => 1],
                    ['answer' => 'Shadow of the Colossus', 'points' => 2],
                    ['answer' => 'Final Fantasy X', 'points' => 2],
                    ['answer' => 'Ico', 'points' => 3],
                    ['answer' => 'Okami', 'points' => 3],
                    ['answer' => 'Katamari Damacy', 'points' => 4],
                    ['answer' => 'Persona 4', 'points' => 4],
                    ['answer' => 'We Love Katamari', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Jeux PlayStation',
                'question' => 'Citez des séries PlayStation',
                'penalty_answer' => 'Mario',
                'answers' => [
                    ['answer' => 'God of War', 'points' => 1],
                    ['answer' => 'Uncharted', 'points' => 1],
                    ['answer' => 'Gran Turismo', 'points' => 2],
                    ['answer' => 'Ratchet & Clank', 'points' => 2],
                    ['answer' => 'LittleBigPlanet', 'points' => 3],
                    ['answer' => 'Killzone', 'points' => 3],
                    ['answer' => 'Resistance', 'points' => 4],
                    ['answer' => 'Infamous', 'points' => 4],
                    ['answer' => 'Jak and Daxter', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Jeux PlayStation',
                'question' => 'Citez des jeux PlayStation 5',
                'penalty_answer' => 'Forza Horizon',
                'answers' => [
                    ['answer' => 'Demon\'s Souls', 'points' => 1],
                    ['answer' => 'Spider-Man Miles Morales', 'points' => 1],
                    ['answer' => 'Ratchet & Clank Rift Apart', 'points' => 2],
                    ['answer' => 'Returnal', 'points' => 2],
                    ['answer' => 'Horizon Forbidden West', 'points' => 3],
                    ['answer' => 'God of War Ragnarök', 'points' => 3],
                    ['answer' => 'Gran Turismo 7', 'points' => 4],
                    ['answer' => 'Ghostwire Tokyo', 'points' => 4],
                    ['answer' => 'Destruction AllStars', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Jeux PlayStation',
                'question' => 'Citez des jeux de course PlayStation',
                'penalty_answer' => 'Mario Kart',
                'answers' => [
                    ['answer' => 'Gran Turismo', 'points' => 1],
                    ['answer' => 'Need for Speed', 'points' => 1],
                    ['answer' => 'Wipeout', 'points' => 2],
                    ['answer' => 'Ridge Racer', 'points' => 2],
                    ['answer' => 'Burnout', 'points' => 3],
                    ['answer' => 'Midnight Club', 'points' => 3],
                    ['answer' => 'ATV Offroad Fury', 'points' => 4],
                    ['answer' => 'Syphon Filter', 'points' => 4],
                    ['answer' => 'Auto Modellista', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Jeux PlayStation',
                'question' => 'Citez des RPG PlayStation',
                'penalty_answer' => 'The Elder Scrolls',
                'answers' => [
                    ['answer' => 'Final Fantasy', 'points' => 1],
                    ['answer' => 'Persona', 'points' => 1],
                    ['answer' => 'Dragon Quest', 'points' => 2],
                    ['answer' => 'Tales of', 'points' => 2],
                    ['answer' => 'Ni No Kuni', 'points' => 3],
                    ['answer' => 'Chrono Cross', 'points' => 3],
                    ['answer' => 'Legend of Dragoon', 'points' => 4],
                    ['answer' => 'Valkyria Chronicles', 'points' => 4],
                    ['answer' => 'Arc the Lad', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Jeux PlayStation',
                'question' => 'Citez des jeux d\'horreur PlayStation',
                'penalty_answer' => 'Dead Space',
                'answers' => [
                    ['answer' => 'Resident Evil', 'points' => 1],
                    ['answer' => 'Silent Hill', 'points' => 1],
                    ['answer' => 'Until Dawn', 'points' => 2],
                    ['answer' => 'The Dark Pictures', 'points' => 2],
                    ['answer' => 'Days Gone', 'points' => 3],
                    ['answer' => 'Alien Isolation', 'points' => 3],
                    ['answer' => 'Outlast', 'points' => 4],
                    ['answer' => 'Amnesia', 'points' => 4],
                    ['answer' => 'Soma', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Jeux PlayStation',
                'question' => 'Citez des jeux multijoueurs PlayStation',
                'penalty_answer' => 'Mario Party',
                'answers' => [
                    ['answer' => 'Call of Duty', 'points' => 1],
                    ['answer' => 'FIFA', 'points' => 1],
                    ['answer' => 'Crash Team Racing', 'points' => 2],
                    ['answer' => 'Tekken', 'points' => 2],
                    ['answer' => 'LittleBigPlanet', 'points' => 3],
                    ['answer' => 'Rocket League', 'points' => 3],
                    ['answer' => 'Fall Guys', 'points' => 4],
                    ['answer' => 'Twisted Metal', 'points' => 4],
                    ['answer' => 'Sackboy', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Jeux PlayStation',
                'question' => 'Citez des studios PlayStation',
                'penalty_answer' => 'Nintendo',
                'answers' => [
                    ['answer' => 'Naughty Dog', 'points' => 1],
                    ['answer' => 'Santa Monica Studio', 'points' => 1],
                    ['answer' => 'Guerrilla Games', 'points' => 2],
                    ['answer' => 'Insomniac Games', 'points' => 2],
                    ['answer' => 'Sucker Punch', 'points' => 3],
                    ['answer' => 'Media Molecule', 'points' => 3],
                    ['answer' => 'Team Ico', 'points' => 4],
                    ['answer' => 'Bend Studio', 'points' => 4],
                    ['answer' => 'Housemarque', 'points' => 5]
                ]
            ],

            // THÈMES SUPPLÉMENTAIRES AVEC QUELQUES QUESTIONS CHACUN
            [
                'theme' => 'Personnages de jeux vidéo',
                'question' => 'Citez des personnages iconiques',
                'penalty_answer' => 'Mickey Mouse',
                'answers' => [
                    ['answer' => 'Mario', 'points' => 1],
                    ['answer' => 'Sonic', 'points' => 1],
                    ['answer' => 'Link', 'points' => 2],
                    ['answer' => 'Pikachu', 'points' => 2],
                    ['answer' => 'Lara Croft', 'points' => 3],
                    ['answer' => 'Master Chief', 'points' => 3],
                    ['answer' => 'Kratos', 'points' => 4],
                    ['answer' => 'Samus Aran', 'points' => 4],
                    ['answer' => 'Gordon Freeman', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Jeux de combat',
                'question' => 'Citez des jeux de combat',
                'penalty_answer' => 'FIFA',
                'answers' => [
                    ['answer' => 'Street Fighter', 'points' => 1],
                    ['answer' => 'Mortal Kombat', 'points' => 1],
                    ['answer' => 'Tekken', 'points' => 2],
                    ['answer' => 'Super Smash Bros', 'points' => 2],
                    ['answer' => 'Soul Calibur', 'points' => 3],
                    ['answer' => 'King of Fighters', 'points' => 3],
                    ['answer' => 'Guilty Gear', 'points' => 4],
                    ['answer' => 'BlazBlue', 'points' => 4],
                    ['answer' => 'Virtua Fighter', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Jeux PC',
                'question' => 'Citez des jeux PC cultes',
                'penalty_answer' => 'Zelda',
                'answers' => [
                    ['answer' => 'Counter-Strike', 'points' => 1],
                    ['answer' => 'World of Warcraft', 'points' => 1],
                    ['answer' => 'League of Legends', 'points' => 2],
                    ['answer' => 'Minecraft', 'points' => 2],
                    ['answer' => 'Half-Life', 'points' => 3],
                    ['answer' => 'StarCraft', 'points' => 3],
                    ['answer' => 'Civilization', 'points' => 4],
                    ['answer' => 'DOOM', 'points' => 4],
                    ['answer' => 'Baldur\'s Gate', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Consoles de jeux',
                'question' => 'Citez des consoles de salon',
                'penalty_answer' => 'Game Boy',
                'answers' => [
                    ['answer' => 'PlayStation', 'points' => 1],
                    ['answer' => 'Xbox', 'points' => 1],
                    ['answer' => 'Nintendo Switch', 'points' => 2],
                    ['answer' => 'Super Nintendo', 'points' => 2],
                    ['answer' => 'Mega Drive', 'points' => 3],
                    ['answer' => 'Nintendo 64', 'points' => 3],
                    ['answer' => 'Dreamcast', 'points' => 4],
                    ['answer' => 'GameCube', 'points' => 4],
                    ['answer' => 'Neo Geo', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Jeux mobiles',
                'question' => 'Citez des jeux mobiles populaires',
                'penalty_answer' => 'Fortnite',
                'answers' => [
                    ['answer' => 'Candy Crush', 'points' => 1],
                    ['answer' => 'Angry Birds', 'points' => 1],
                    ['answer' => 'Clash of Clans', 'points' => 2],
                    ['answer' => 'Pokémon GO', 'points' => 2],
                    ['answer' => 'Among Us', 'points' => 3],
                    ['answer' => 'Subway Surfers', 'points' => 3],
                    ['answer' => 'Monument Valley', 'points' => 4],
                    ['answer' => 'Alto\'s Adventure', 'points' => 4],
                    ['answer' => 'Threes', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Studios de jeux vidéo',
                'question' => 'Citez des studios célèbres',
                'penalty_answer' => 'Pixar',
                'answers' => [
                    ['answer' => 'Nintendo', 'points' => 1],
                    ['answer' => 'Ubisoft', 'points' => 1],
                    ['answer' => 'Rockstar', 'points' => 2],
                    ['answer' => 'Blizzard', 'points' => 2],
                    ['answer' => 'Bethesda', 'points' => 3],
                    ['answer' => 'CD Projekt', 'points' => 3],
                    ['answer' => 'FromSoftware', 'points' => 4],
                    ['answer' => 'Naughty Dog', 'points' => 4],
                    ['answer' => 'Remedy', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Jeux de sport',
                'question' => 'Citez des séries de jeux de sport',
                'penalty_answer' => 'Mario Kart',
                'answers' => [
                    ['answer' => 'FIFA', 'points' => 1],
                    ['answer' => 'NBA 2K', 'points' => 1],
                    ['answer' => 'Madden NFL', 'points' => 2],
                    ['answer' => 'PES', 'points' => 2],
                    ['answer' => 'NHL', 'points' => 3],
                    ['answer' => 'WWE 2K', 'points' => 3],
                    ['answer' => 'Tony Hawk', 'points' => 4],
                    ['answer' => 'SSX', 'points' => 4],
                    ['answer' => 'Virtua Tennis', 'points' => 5]
                ]
            ],
            [
                'theme' => 'Jeux rétro',
                'question' => 'Citez des jeux d\'arcade classiques',
                'penalty_answer' => 'Fortnite',
                'answers' => [
                    ['answer' => 'Pac-Man', 'points' => 1],
                    ['answer' => 'Space Invaders', 'points' => 1],
                    ['answer' => 'Donkey Kong', 'points' => 2],
                    ['answer' => 'Tetris', 'points' => 2],
                    ['answer' => 'Galaga', 'points' => 3],
                    ['answer' => 'Street Fighter II', 'points' => 3],
                    ['answer' => 'Bubble Bobble', 'points' => 4],
                    ['answer' => 'Metal Slug', 'points' => 4],
                    ['answer' => 'R-Type', 'points' => 5]
                ]
            ]
        ];
    }
}