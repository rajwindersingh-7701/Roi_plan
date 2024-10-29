<?php

if (!function_exists('pr')) {

    function pr($array, $die = false) {
        echo'<pre>';
        print_r($array);
        echo'</pre>';
        if ($die)
            die();
    }

}

if (!function_exists('calculate_rank')) {

function calculate_rank($directs) {
    if($directs >= 100)
        $rank = 'Diamond';
    elseif($directs >= 50)
        $rank = 'Emerald';
    elseif($directs >= 25)
        $rank = 'Topaz';
    elseif($directs >= 20)
        $rank = 'Pearl';
    elseif($directs >= 15)
        $rank = 'Gold';
    elseif($directs >= 10)
        $rank = 'Silver';
    elseif($directs >= 5)
        $rank = 'Star';
    else
        $rank = 'Associate';
    
    return $rank;
}
}