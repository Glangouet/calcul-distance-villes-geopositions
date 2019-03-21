<?php
/**
 * Created by Guillaume Langouet - Atos Montpellier.
 * User: Guillaume Langouet
 * Email: guillaume@dialo-home.com
 * Date: 18/03/2019
 * Time: 09:15
 */


// Tableau donnée de base
$cities = [
    [0, 'PARIS', 'Paris', 48.857257, 2.344311],
    [1, 'MARSEILLE', 'Marseille', 43.2958, 5.366067],
    [2, 'LYON', 'Lyon', 45.762006, 4.832372],
    [3, 'TOULOUSE', 'Toulouse', 43.606148, 1.435585],
    [4, 'NICE', 'Nice', 43.712104, 7.255905],
    [5, 'NANTES', 'Nantes', 47.219316, -1.556968],
    [6, 'MONTPELLIER', 'Montpellier', 43.610498, 3.877936],
    [7, 'STRASBOURG', 'Strasbourg', 48.574935, 7.748352],
    [8, 'BORDEAUX', 'Bordeaux', 44.837663, -0.577904],
    [9, 'LILLE', 'Lille', 50.62974, 3.054711],
    [10, 'RENNES', 'Rennes', 48.117241, -1.677809],
    [11, 'REIMS', 'Reims', 49.257329, 4.034491],
    [12, 'LEHAVRE', 'Le Havre', 49.493329, 0.103028],
    [13, 'SAINT-ETIENNE', 'Saint-Etienne', 45.439955, 4.389153],
    [14, 'TOULON', 'Toulon', 43.124326, 5.927245],
    [15, 'GRENOBLE', 'Grenoble', 45.18887, 5.727048],
    [17, 'NIMES', 'Nimes', 43.833463, 4.351784],
    [18, 'ANGERS', 'Angers', 47.471287, -0.551617],
    [19, 'VILLEURBANNE', 'Villeurbanne', 45.771987, 4.89017]
];


// Je recupere un tableau plus maléable
$newCitiesTable = recreateTable($cities);

// Je déclare le tableau final qui contiendra les villes et leur tableau roadmap
$finalTable = [];

// Je boucle mon nouveau tableau
foreach ($newCitiesTable as $index => $city) {

    // finalTable[NomDeLaVille] contiendra le tableau de villes à parcourir dans l'ordre
    $finalTable[$index] = check($newCitiesTable, $city);
}

// Affichage du tableau en console
var_dump($finalTable); exit;



/**
 *
 * Fonction recréant un tableau plus maléable.
 *
 * Retourne un tableau $newCitiesTable['NomDeVille'] = ['Nom de la ville', 'latitude', 'longitude']
 *
 * @param array $cities
 * @return array
 */
function recreateTable(array $cities) {
    $newCitiesTable = [];
    foreach ($cities as $index => $city) {
        $newCitiesTable[$city[1]] = [$city[1], $city[3], $city[4]];
    }

    return $newCitiesTable;
}


/**
 *
 * Fonction récursive créant le circuit de ville à parcourir
 *
 * @param array $cities
 * @param array $c
 * @param array $finalTable
 * @return array
 */
function check(array $cities, array $c, array $finalTable = []) {

    // Supression de la ville donné dans le tableau afin de ne pas la prendre en compte
    $cities = refreshTable($cities, $c);

    // Condition d'arrêt quand il n'y a plus de ville dans le tableau
    if (count($cities) > 0) {

        // Déclaration du tableau qui contiendra les distances
        $newTable = [];

        // Boucle sur le tableau de ville
        foreach ($cities as $index => $city) {

            // On ajoute à $newTable['NomDeVille'] = la distance entre celle-ci et la ville donnée à la function
            $newTable[$city[0]] = distance($c[1], $c[2], $city[1], $city[2]);
        }

        // Tri le nouveau tableau de la plus proche des villes à la plus loin
        asort($newTable);

        // Récupération de la ville la plus proche
        $nearCityName = key($newTable);
        $nearCity = $cities[$nearCityName];

        // Ajout de la ville dans le tableau final avec la distance en KM
        $finalTable[$nearCityName] = $newTable[$nearCityName];

        // Appelle récursif de la fonction en passant en paramêtre le tableau de ville,
        // la nouvelle ville de départ et le tableau final à renvoyer.
        return check($cities, $nearCity, $finalTable);
    }

    // Retourne le tableau final
    return $finalTable;
}


/**
 *
 * Supprime du tableau de ville la ville donnée
 *
 * @param array $cities
 * @param array $c
 * @return array
 */
function refreshTable(array $cities, array $c) {
    foreach ($cities as $index => $city) {
        if ($index === $c[0]) {
            unset($cities[$index]);
        }
    }

    return $cities;
}


/**
 *
 * Retourne la distance en metre ou kilometre (si $unit = 'k') entre deux latitude et longitude fournies
 * (vol d'oiseau)
 *
 */
function distance($lat1, $lng1, $lat2, $lng2, $unit = 'k') {
    $earth_radius = 6378137;   // Terre = sphère de 6378km de rayon
    $rlo1 = deg2rad($lng1);
    $rla1 = deg2rad($lat1);
    $rlo2 = deg2rad($lng2);
    $rla2 = deg2rad($lat2);
    $dlo = ($rlo2 - $rlo1) / 2;
    $dla = ($rla2 - $rla1) / 2;
    $a = (sin($dla) * sin($dla)) + cos($rla1) * cos($rla2) * (sin($dlo) * sin($dlo));
    $d = 2 * atan2(sqrt($a), sqrt(1 - $a));
    //
    $meter = ($earth_radius * $d);
    if ($unit === 'k') {
        return $meter / 1000;
    }

    return $meter;
}




