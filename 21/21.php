<?php

include_once "../tools.php";

const TEST = false;
//const TEST = true;

function part1($s1, $s2): int
{
    $score1 = 0;
    $score2 = 0;
    $s1--;
    $s2--;
    $dice = 0;
    $rolled = 0;
    while($score2 < 1000) {
        $s1 += ($dice++) % 100 + 3 + ($dice++) % 100 + ($dice++) % 100;
        $s1 %= 10;
        $score1 += $s1 + 1;
        $rolled += 3;
        if ($score1 >= 1000)
            break;
        $s2 += ($dice++) % 100 + 3 + ($dice++) % 100 + ($dice++) % 100;
        $s2 %= 10;
        $score2 += $s2 + 1;
        $rolled += 3;
    }


    return min($score1,$score2) * $rolled;
}
$count = [3=>1,4=>3,5=>6,6=>7,7=>6,8=>3,9=>1];


function d1($s1, $s2, $score1, $score2, $mul, &$wins1, &$wins2, &$lookup_table) {
    global $count;
    if($score1 >= 21) {
        $wins1 += $mul;
        return;
    }elseif($score2 >= 21){
        $wins2 += $mul;
        return;
    }

    foreach ($lookup_table[$s1] as $k1=>$pos1) {
        d2($pos1, $s2, $score1+$pos1, $score2, $mul*$count[$k1], $wins1, $wins2, $lookup_table);
    }
}

function d2($s1, $s2, $score1, $score2, $mul, &$wins1, &$wins2, &$lookup_table) {
    global $count;
    if($score1 >= 21) {
        $wins1 += $mul;
        return;
    }
    elseif($score2 >= 21){
        $wins2 += $mul;
        return;
    }

    foreach ($lookup_table[$s2] as $k2=>$pos2) {
         d1($s1, $pos2, $score1, $score2+$pos2, $mul*$count[$k2], $wins1, $wins2, $lookup_table);
    }
}

function part2($s1, $s2): int
{
    $lookup_table = [];
    for ($i = 1; $i <= 10; $i++) {
        $lookup_table[$i] = [];
        for ($j = 3; $j <= 9; $j++) {
            $lookup_table[$i][$j] = $i + $j;
            if ($lookup_table[$i][$j] > 10)
                $lookup_table[$i][$j] -= 10;
        }
    }
    $wins1 = 0;
    $wins2 = 0;
    d1($s1,$s2,0,0, 1, $wins1, $wins2, $lookup_table);

    return max($wins1, $wins2);
}

if (TEST) {
    $start_pos_1 = 4;
    $start_pos_2 = 8;
}else {
    $start_pos_1 = 9;
    $start_pos_2 = 4;
}
echo "Part 1: " . part1($start_pos_1, $start_pos_2) . PHP_EOL;
echo "Part 2: " . part2($start_pos_1, $start_pos_2) . PHP_EOL;