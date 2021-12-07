<?php

include_once "../tools.php";

const TEST = false;
//const TEST = true;

function parse_input(): array
{
    $filename = "input.txt";
    if (TEST)
        $filename = "test_input.txt";

    return explode(',', lines($filename)[0]);
}

function part1($positions): int
{
    sort($positions);
    $pos = $positions[floor((floor(count($positions) / 2) + ceil (count($positions) / 2)) / 2)];
    $fuel = 0;
    foreach ($positions as $position)
        $fuel += abs($position - $pos);

    return $fuel;
}

function part2($positions): int
{
    $pos_floor = floor(array_sum($positions) / count($positions));
    $fuel_floor = 0;
    $fuel_ceil = 0;
    foreach ($positions as $position) {
        $d = abs($position-$pos_floor);
        $fuel_floor += $d*($d+1)/2;
        $d = abs($position-$pos_floor - 1);
        $fuel_ceil += $d*($d+1)/2;
    }

    return min($fuel_ceil, $fuel_floor);
}

$parsed = parse_input();
echo "Part 1: " . part1($parsed) . PHP_EOL;
echo "Part 2: " . part2($parsed) . PHP_EOL;