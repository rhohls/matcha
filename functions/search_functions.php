<?php

function getDistance($latitude1, $longitude1, $latitude2, $longitude2 )
{
    $earth_radius = 6371;
    $dLat = deg2rad( $latitude2 - $latitude1 );
    $dLon = deg2rad( $longitude2 - $longitude1 );
    $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);
    $c = 2 * asin(sqrt($a));
    $d = $earth_radius * $c;
    return $d;
}

function profileSearch($pdo, $sex_search, $age_search, $fame_search, $name_search)
{
    $query = "SELECT *, FLOOR(ABS(DATEDIFF(NOW(), `birthdate`)/365)) AS 'age' FROM `users` WHERE `complete`=1 AND ";
    
    $query .= $sex_search;
    $query .= " AND " . $age_search;
    $query .= " AND " . $fame_search;
    if ($name_search)
        $query .= " AND " . $name_search;

    var_dump($query);
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $results = $stmt->fetchAll();
    return $results;
}

function matchingTags($pdo, $user_id, $res_user_id){
    $query = "SELECT * FROM `user_tag` WHERE user_id=$user_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $user_tags = $stmt->fetchAll();
    
    $query = "SELECT * FROM `user_tag` WHERE user_id=$res_user_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $match_tags = $stmt->fetchAll();
    
    $count = 0;
    foreach ($user_tags as $ut){
        foreach ($match_tags as $mt){
            if ($ut['tag_id'] == $mt['tag_id'])
                $count += 1;
        }
    }
    
    return($count);
}

function inDistance($res_user, $location_gap){
    if ($res_user['distance'] <= $location_gap)
        return True;
    return False;
}

function matchTags($res_user, $min_tags){
    if ($res_user['tags_matching'] >= $min_tags)
        return True;
    return False;
}