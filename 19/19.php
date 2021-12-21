<?php

include_once "../tools.php";

//const TEST = false;
const TEST = true;


function parse_input(): array
{
    $filename = "input.txt";
    if (TEST)
        $filename = "test_input.txt";
    $lines = lines($filename);

    $scanners = [];
    for ($i = 1; $i < count($lines); $i++) {
        $beacons = [];
        while($i < count($lines) && $lines[$i] != ""){
            $beacon = explode(',', $lines[$i]);
            $beacons[] = ['x' => $beacon[0], 'y' => $beacon[1], 'z' => $beacon[2]];
            $i++;
        }
        $scanners[] = $beacons;
        $i++;
    }
    return $scanners;
}

function orientations($beacon) : array {
    $orientations = [];
    for($x=-1; $x<=1; $x+=2){
        for($y=-1; $y<=1; $y+=2){
            for($z=-1; $z<=1; $z+=2){
                $orientations[] = ['x' => $x * $beacon['x'], 'y' => $y * $beacon['y'], 'z' => $z * $beacon['z']];
                $orientations[] = ['x' => $x * $beacon['x'], 'y' => $y * $beacon['z'], 'z' => $z * $beacon['y']];
                $orientations[] = ['x' => $x * $beacon['y'], 'y' => $y * $beacon['x'], 'z' => $z * $beacon['z']];
                $orientations[] = ['x' => $x * $beacon['y'], 'y' => $y * $beacon['z'], 'z' => $z * $beacon['x']];
                $orientations[] = ['x' => $x * $beacon['z'], 'y' => $y * $beacon['x'], 'z' => $z * $beacon['y']];
                $orientations[] = ['x' => $x * $beacon['z'], 'y' => $y * $beacon['y'], 'z' => $z * $beacon['x']];
            }
        }
    }
    return $orientations;
}

function intersect_scanners_count($scanner1, $scanner2){
    $i =0 ;
    foreach ($scanner1 as $beacon1) {
        foreach ($scanner2 as $beacon2) {
            if ($beacon1['x'] == $beacon2['x'] && $beacon1['y'] == $beacon2['y'] && $beacon1['z'] == $beacon2['z']) {
                $i++;
                if($i >= 12) return true;
            }
        }
    }
    return false;
}

function check($scanner1, $scanner2, &$scanner_to_org = [], &$scanner_pos=[])
{
    $scanner2_orientations = [];
    foreach ($scanner2 as $beacon) {
        $or = orientations($beacon);
        foreach ($or as $key=>$ori) {
            if(array_key_exists($key, $scanner2_orientations))
                array_push($scanner2_orientations[$key], $ori);
            else
                $scanner2_orientations[$key] = [$ori];
        }
    }
    foreach ($scanner2 as $beacon2) {
        $beacon2_orientations = orientations($beacon2);
        foreach ($scanner1 as $beacon1) {
            foreach ($beacon2_orientations as $key=>$beacon2_orientation) {
                $x_dif = $beacon1['x'] - $beacon2_orientation['x'];
                $y_dif = $beacon1['y'] - $beacon2_orientation['y'];
                $z_dif = $beacon1['z'] - $beacon2_orientation['z'];

                $scanner_add = $scanner2_orientations[$key];
                for ($i = 0; $i < count($scanner_add); $i++) {
                    $scanner_add[$i]['x'] += $x_dif;
                    $scanner_add[$i]['y'] += $y_dif;
                    $scanner_add[$i]['z'] += $z_dif;
                }

                if(intersect_scanners_count($scanner1, $scanner_add)){
                   $scanner_to_org = $scanner_add;
                   $scanner_pos = ['x' => $x_dif, 'y' => $y_dif,  'z' => $z_dif];
                   return true;
                }
            }
        }
    }
    return false;
}

function part1($scanners): int
{
    $scanners_rel_to_org = [$scanners[0]];
    $scanners_pos = [];
    unset($scanners[0]);

    for ($i = 0; $i < count($scanners_rel_to_org); $i++) {
        echo 'ok' . PHP_EOL;
        foreach ($scanners as $j=>$scanner) {
            $scanner_rel_to_org = [];
            $scanner_pos = [];
            $c = check($scanners_rel_to_org[$i], $scanner, $scanner_rel_to_org, $scanner_pos);
            if($c){
                array_push($scanners_rel_to_org, $scanner_rel_to_org);
                array_push($scanners_pos, $scanner_pos);
                unset($scanners[$j]);
            }
        }
    }

    $points = [];
    foreach ($scanners_rel_to_org as $scanner) {
        foreach ($scanner as $beacon) {
            $key = $beacon['x']. ',' . $beacon['y'] . ',' . $beacon['z'];
            $points[$key] = 1;
        }
    }
    $max_distance = 0;
    foreach ($scanners_pos as $k1=>$scanner_pos1) {
        foreach ($scanners_pos as $k2=>$scanner_pos2) {
            if($k1 != $k2){
                $n = abs($scanner_pos1['x'] - $scanner_pos2['x']) + abs($scanner_pos1['y'] - $scanner_pos2['y']) + abs($scanner_pos1['z'] - $scanner_pos2['z']);
                $max_distance = max($max_distance,$n);
            }
        }
    }
    echo 'Part 2: ' . $max_distance . PHP_EOL;
    return count($points);
}

$parsed = parse_input();
echo "Part 1: " . part1($parsed) . PHP_EOL;