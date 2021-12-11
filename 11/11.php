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
        array_push($parsed, str_split($line));
    }
    return $parsed;
}

function check_increment(&$octopuses, $coordinates) {
    foreach ($coordinates as $coordinate) {
        [$x,$y] = $coordinate;
        if($x >= 0 && $x < count($octopuses[0]) && $y >= 0 && $y < count($octopuses) && $octopuses[$y][$x] != 'X')
            $octopuses[$y][$x]++;
    }
}

function part1($octopuses): int
{
    $result = 0;
    for ($i = 0; $i < 100; $i++) {
        // Increment
        for ($y = 0; $y < count($octopuses); $y++)
            for ($x = 0; $x < count($octopuses[0]); $x++)
                $octopuses[$y][$x]++;

        // Flashes
        $found = true;
        while ($found) {
            $found = false;
            for ($y = 0; $y < count($octopuses); $y++) {
                for ($x = 0; $x < count($octopuses[0]); $x++) {
                    if ($octopuses[$y][$x] != 'X' && $octopuses[$y][$x] > 9) {
                        $found = true;
                        $octopuses[$y][$x] = 'X';
                        $result++;
                        check_increment($octopuses, [[$x,$y-1], [$x+1,$y-1], [$x+1,$y], [$x+1,$y+1], [$x,$y+1], [$x-1,$y+1], [$x-1,$y], [$x-1,$y-1],]);
                    }
                }
            }
        }

        // Reset energy
        for ($y = 0; $y < count($octopuses); $y++)
            for ($x = 0; $x < count($octopuses[0]); $x++)
                if ($octopuses[$y][$x] == 'X')
                    $octopuses[$y][$x] = 0;

    }
    return $result;
}

function part2($octopuses): int
{

    $result = 0;
    $flashes = 0;
    while($flashes != 100){
        $flashes = 0;
        $result++;

        // Increment
        for ($y = 0; $y < count($octopuses); $y++)
            for ($x = 0; $x < count($octopuses[0]); $x++)
                $octopuses[$y][$x]++;

        // Flashes
        $found = true;
        while ($found) {
            $found = false;
            for ($y = 0; $y < count($octopuses); $y++) {
                for ($x = 0; $x < count($octopuses[0]); $x++) {
                    if ($octopuses[$y][$x] != 'X' && $octopuses[$y][$x] > 9) {
                        $found = true;
                        $octopuses[$y][$x] = 'X';
                        $flashes++;
                        check_increment($octopuses, [[$x,$y-1], [$x+1,$y-1], [$x+1,$y], [$x+1,$y+1], [$x,$y+1], [$x-1,$y+1], [$x-1,$y], [$x-1,$y-1],]);
                    }
                }
            }
        }

        // Reset energy
        for ($y = 0; $y < count($octopuses); $y++)
            for ($x = 0; $x < count($octopuses[0]); $x++)
                if ($octopuses[$y][$x] == 'X')
                    $octopuses[$y][$x] = 0;
    }
    return $result;
}

$parsed = parse_input();
echo "Part 1: " . part1($parsed) . PHP_EOL;
echo "Part 2: " . part2($parsed) . PHP_EOL;