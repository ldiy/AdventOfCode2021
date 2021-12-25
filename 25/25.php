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
        array_push($parsed,str_split($line));

    return $parsed;
}

function empty_before_x(&$map, $x, $y) {
    if($x < count($map[0]) - 1)
        return $map[$y][$x+1] == '.';
    else
        return $map[$y][0] == '.';
}

function empty_before_y(&$map, $x, $y) {
    if($y < count($map) - 1)
        return $map[$y+1][$x] == '.';
    else
        return $map[0][$x] == '.';
}

function part1($map): int
{
    $step = 0;
    $moved = true;
    while ($moved){
        $moved = false;
        $new_map = $map;
        // >
        for ($y = 0; $y < count($map); $y++) {
            for ($x = 0; $x < count($map[0]); $x++) {
                if($map[$y][$x] == '>' && empty_before_x($map,$x,$y)){
                    $moved = true;
                    $new_map[$y][$x] = '.';
                    if($x < count($map[0]) - 1)
                        $new_map[$y][$x+1] = '>';
                    else
                        $new_map[$y][0] = '>';
                }
            }
        }

        $map = $new_map;

        // V
        for ($y = 0; $y < count($map); $y++) {
            for ($x = 0; $x < count($map[0]); $x++) {
                if($map[$y][$x] == 'v' && empty_before_y($map,$x,$y)){
                    $moved = true;
                    $new_map[$y][$x] = '.';
                    if($y < count($map) - 1)
                        $new_map[$y + 1][$x] = 'v';
                    else
                        $new_map[0][$x] = 'v';
                }
            }
        }
        $map = $new_map;
        $step++;
    }

    return $step;
}

$parsed = parse_input();
echo "Part 1: " . part1($parsed) . PHP_EOL;
