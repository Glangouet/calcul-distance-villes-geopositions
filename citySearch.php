<?php
/**
 * Created by Guillaume Langouet - Atos Montpellier.
 * User: Guillaume Langouet
 * Email: guillaume@dialo-home.com
 * Date: 18/03/2019
 * Time: 09:15
 */

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

function recreateTable($cities) {
    $newCitiesTable = [];
    foreach ($cities as $index => $city) {
        $newCitiesTable[$city[1]] = [$city[1], $city[3], $city[4]];
    }

    return $newCitiesTable;
}


$newCitiesTable = recreateTable($cities);
$finalTable = [];
foreach ($newCitiesTable as $index => $city) {
    $finalTable[$index] = check($newCitiesTable, $city);
}

var_dump($finalTable); exit;




// retourne tableau avec index name et addition geoloc
function getNewTable($cities) {
    $table = [];
    foreach ($cities as $c) {
        $table[$c[1]] = $c[3] + $c[4];
    }

    return $table;
}

function getFirstCityName($cities) {
    foreach ($cities as $index => $c){
        return $index;
    }

    return null;
}

function getCompleteCity($cities, $nearCityName) {
    foreach ($cities as $index => $city) {
        if ($index === $nearCityName) {
            return $city;
        }
    }

    return null;
}

// retourne la plus proche ville a visiter
function check($cities, $c, $finalTable = []) {
    if (count($cities) > 0) {
        $newTable = [];
        foreach ($cities as $index => $city) {
            if ($index !== $c[0]) {
                $newTable[$city[0]] = Misc::distance($c[1], $c[2], $city[1], $city[2]);
            }
        }
        asort($newTable);

        $nearCityName = getFirstCityName($newTable);
        $newStartCity = getCompleteCity($cities, $nearCityName);
        $cities = refreshTable($cities, $newStartCity);

        $finalTable[] = $newStartCity;


        return check($cities, $newStartCity, $finalTable);
    }

    return $finalTable;
}



function refreshTable($cities, $c) {
    foreach ($cities as $index => $city) {
        if ($index === $c[0]) {
            unset($cities[$index]);
        }
    }

    return $cities;
}




class Misc {
    /**
     * Retourne la distance en metre ou kilometre (si $unit = 'k') entre deux latitude et longitude fournit
     */
    public static function distance($lat1, $lng1, $lat2, $lng2, $unit = 'k') {
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
        if ($unit == 'k') {
            return $meter / 1000;
        }
        return $meter;
    }
}



