<?php

include_once "../tools.php";

const TEST = false;
//const TEST = true;

function parse_input(): array
{
    $filename = "input.txt";
    if (TEST)
        $filename = "test_input.txt";
    $lines = lines($filename);

    $parsed = [];
    foreach ($lines as $line)
        array_push($parsed,regex_groups("/([a-z]+) x=(-*[0-9]+)..(-*[0-9]+),y=(-*[0-9]+)..(-*[0-9]+),z=(-*[0-9]+)..(-*[0-9]+)/", $line));
    return $parsed;
}

function cubes($x_min, $x_max, $y_min, $y_max, $z_min, $z_max) {
    $cubes = [];
    for ($z = $z_min; $z <= $z_max; $z++)
        for ($y = $y_min; $y <= $y_max; $y++)
            for ($x = $x_min; $x <= $x_max; $x++)
                $cubes[] = $x . ',' . $y . ',' . $z;
    return $cubes;
}

function part1($steps): int
{
    $cubes_on = [];
    foreach ($steps as $step) {
        if($step[1] < -50)
            $step[1] = -50;
        if($step[2] > 50)
            $step[2] = 50;
        if($step[3] < -50)
            $step[3] = -50;
        if($step[4] > 50)
            $step[4] = 50;
        if($step[5] < -50)
            $step[5] = -50;
        if($step[6] > 50)
            $step[6] = 50;

        $cubes = cubes($step[1], $step[2], $step[3], $step[4], $step[5], $step[6]);
            foreach ($cubes as $cube) {
                if ($step[0] == "on")
                    $cubes_on[$cube] = 1;
                else
                    $cubes_on[$cube] = 0;
            }
        }
    return array_sum($cubes_on);
}

function intersect(int $x1_min, int $x1_max, int  $y1_min, int $y1_max, int $z1_min, int $z1_max, int $x2_min, int $x2_max, int $y2_min, int $y2_max, int $z2_min, int $z2_max) : ?array
{
    if(!(($x1_min >= $x2_min && $x1_min <= $x2_max) || ($x1_max >= $x2_min && $x1_max <= $x2_max) || ($x1_min <= $x2_min && $x2_min <= $x1_max) || ($x1_min <= $x2_max && $x2_max <= $x1_max))){
        return NULL;
    }
    if(!(($y1_min >= $y2_min && $y1_min <= $y2_max) || ($y1_max >= $y2_min && $y1_max <= $y2_max) || ($y1_min <= $y2_min && $y2_min <= $y1_max) || ($y1_min <= $y2_max && $y2_max <= $y1_max))){
        return NULL;
    }
    if(!(($z1_min >= $z2_min && $z1_min <= $z2_max) || ($z1_max >= $z2_min && $z1_max <= $z2_max) || ($z1_min <= $z2_min && $z2_min <= $z1_max) || ($z1_min <= $z2_max && $z2_max <= $z1_max))){
        return NULL;
    }
    $new_x_min = max($x1_min, $x2_min);
    $new_x_max = min($x1_max, $x2_max);

    $new_y_min = max($y1_min, $y2_min);
    $new_y_max = min($y1_max, $y2_max);

    $new_z_min = max($z1_min, $z2_min);
    $new_z_max = min($z1_max, $z2_max);

    return [$new_x_min,$new_x_max,$new_y_min,$new_y_max,$new_z_min,$new_z_max];
}

function part2($steps): int
{
    $ons = [];
    $offs =[];
    foreach ($steps as $step) {
        $temp_offs = [];
        $temp_ons = [];
        foreach ($ons as $on) {
            $a = intersect($on[0], $on[1], $on[2], $on[3], $on[4], $on[5], $step[1], $step[2], $step[3], $step[4], $step[5], $step[6]);
            if($a != NULL)
                $temp_offs[] = $a;
        }

        foreach ($offs as $off) {
            $a =  intersect($off[0], $off[1], $off[2], $off[3], $off[4], $off[5], $step[1], $step[2], $step[3], $step[4], $step[5], $step[6]);
            if($a != NULL)
                $temp_ons[] = $a;
        }

        foreach ($temp_offs as $temp_off)
            $offs[] = $temp_off;

        foreach ($temp_ons as $temp_on)
            $ons[] = $temp_on;

        if($step[0] == "on")
            $ons[] = [$step[1], $step[2], $step[3], $step[4], $step[5], $step[6]];
    }
    $res = 0;
    foreach ($ons as $on)
        $res += ($on[1] - $on[0] + 1) * ($on[3] - $on[2] + 1) *   ($on[5] - $on[4] + 1);
    foreach ($offs as $off)
        $res -= ($off[1] - $off[0] + 1) * ($off[3] - $off[2] + 1) *   ($off[5] - $off[4] + 1);

    return $res;
}

$parsed = parse_input();
echo "Part 1: " . part1($parsed) . PHP_EOL;
echo "Part 2: " . part2($parsed) . PHP_EOL;