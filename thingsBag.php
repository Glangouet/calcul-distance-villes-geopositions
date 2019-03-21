<?php
/**
 * Created by Guillaume Langouet - Atos Montpellier.
 * User: Guillaume Langouet
 * Email: guillaume@dialo-home.com
 * Date: 18/03/2019
 * Time: 08:48
 */


$things = [ 'ordinateur' => 4, 'disque' => 5, 'television' => 10, 'bidule' => 4, 'assietes' => 3, 'bd' => 2 ];
$newThings = getMostThings(20, $things, []);
foreach ($newThings as $key => $th) {
    printf("objet: %s, poids: %d \n", $key, $th);
}

function getMostThings($capacity, $things, $newThings) {
    if (count($things) > 0) {
        $key = array_search(min($things), $things);
        if ($things[$key] <= $capacity) {
            $newThings[$key] = $things[$key];
            $capacity -= $things[$key];
            unset($things[$key]);
            return getMostThings($capacity, $things, $newThings);
        }

        return  $newThings;
    }

    return  $newThings;
}
