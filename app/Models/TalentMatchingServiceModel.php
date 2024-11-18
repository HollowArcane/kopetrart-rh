<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TalentMatchingServiceModel extends Model {

    // Constructeur vide
    public function __construct() {
        // Aucune initialisation spécifique
    }

    public function comparerTalents($talentsRequis, $classificationCv) {
        $scores = [];

        // Pour chaque talent requis
        foreach ($talentsRequis as $talentRequis) {
            $idTalentRequis = $talentRequis->id_talent;
            $expMinRequis = $talentRequis->expmin;
            $expMaxRequis = $talentRequis->expmax;

            // Pour chaque talent dans la classification du CV
            foreach ($classificationCv as $classificationTalent) {
                // Comparer l'id du talent requis avec celui du talent dans la classification du CV
                if ($classificationTalent->id_talent == $idTalentRequis) {
                    // Récupérer l'expérience du talent dans le CV
                    $experienceCv = $classificationTalent->experience;

                    // Calculer la correspondance pour l'expérience
                    $expScore = $this->calculerScoreExperience($experienceCv, $expMinRequis, $expMaxRequis);

                    // Ajouter le score basé uniquement sur l'expérience
                    $scores[] = [
                        'id_talent' => $idTalentRequis,
                        'score' => $expScore // Ici, on ne calcule que l'expérience
                    ];

                    // Vous pouvez aussi quitter la boucle interne si un talent est trouvé
                    // break;
                }
            }
        }

        return $scores;
    }


    public function calculerScoreGeneral($scores, $ponderExp=null, $ponderEtude=null) {
        // Vérifier s'il y a des scores dans le tableau
        if (empty($scores)) {
            return 0; // Si aucun score, retourner 0
        }

        // Initialiser des variables pour totaliser les scores
        $totalScore = 0;
        $totalTalents = count($scores);

        // Additionner les scores individuels
        foreach ($scores as $scoreData) {
            // On suppose que chaque entrée de $scores contient 'id_talent' et 'score'
            $totalScore += $scoreData['score'];
        }

        // Calculer la moyenne des scores
        $scoreGeneral = $totalScore / $totalTalents;

        // Appliquer la pondération sur le score global
        $scoreGeneralPondere = $ponderExp * $scoreGeneral + $ponderEtude * (1 - $scoreGeneral);

        return $scoreGeneralPondere*100;
    }

    private static function calculerScoreExperience($experienceCv, $expMin, $expMax) {
        // Si l'expérience est dans la plage requise
        if ($experienceCv >= $expMin && $experienceCv <= $expMax) {
            return 1; // Score parfait
        } elseif ($experienceCv < $expMin) {
            return $experienceCv / $expMin; // Moins d'expérience, score inférieur
        } elseif ($experienceCv > $expMax) {
            return $expMax / $experienceCv; // Plus d'expérience que nécessaire
        }

        return 0; // Aucun score si les conditions ne sont pas remplies
    }
}
