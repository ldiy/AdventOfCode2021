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
    foreach ($lines as $line) {
        array_push($parsed,str_split($line));
    }
    return $parsed;
}

function check_neighbours(&$map,$x,$y): bool
{
    if($y != 0 && $map[$y-1][$x] <= $map[$y][$x])
       return false;
    elseif ($y != count($map)-1 && $map[$y+1][$x] <= $map[$y][$x])
        return false;

    if($x != 0 && $map[$y][$x-1] <= $map[$y][$x])
        return false;
    elseif ($x != count($map[0])-1 && $map[$y][$x+1] <= $map[$y][$x])
        return false;

    return true;
}

function calc_basin(&$map, $x, $y) : int
{
    if($map[$y][$x] == 9 || $map[$y][$x] == 'X')
        return 0;

    $size = 1;
    $map[$y][$x] = 'X';

    if($y != 0)
        $size += calc_basin($map, $x, $y-1);
    if ($y != count($map) - 1)
        $size += calc_basin($map, $x, $y+1);

    if($x != 0)
        $size += calc_basin($map, $x - 1, $y);
    if ($x != count($map[0]) - 1)
        $size += calc_basin($map, $x + 1, $y);

    return $size;
}

function part1($map): int
{
    $result = 0;
    for ($y = 0; $y < count($map); $y++)
        for ($x = 0; $x < count($map[0]); $x++)
            if(check_neighbours($map, $x, $y))
                $result += $map[$y][$x] + 1;

    return $result;
}

function part2($map): int
{
    $sizes = [];
    for ($y = 0; $y < count($map); $y++) {
        for ($x = 0; $x < count($map[0]); $x++) {
            $size = calc_basin($map, $x, $y);
            if($size != 0) {
                array_push($sizes, $size);
            }
        }
    }
    rsort($sizes);
    return $sizes[0] * $sizes[1] * $sizes[2];
}

$parsed = parse_input();
echo "Part 1: " . part1($parsed) . PHP_EOL;
echo "Part 2: " . part2($parsed) . PHP_EOL;