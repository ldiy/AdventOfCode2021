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
        array_push($parsed, regex_groups('/([0-9]*),([0-9]*) -> ([0-9]*),([0-9]*)/', $line));
    }
    return $parsed;
}

function part1($input): int
{
    $map=[];
    foreach ($input as $line) {
        [$x1,$y1,$x2,$y2] = $line;
        if($x1 == $x2 || $y1 == $y2){
            foreach (range($y1, $y2) as $y) {
                foreach (range($x1, $x2) as $x) {
                    $key = $x . ',' . $y;
                    if(array_key_exists($key, $map))
                        $map[$key]++;
                    else
                        $map[$key] = 1;
                }
            }
        }
    }
    $sum = 0;
    for ($i = 2; $i <= max($map); $i++) {
        $sum += array_count_values($map)[$i];
    }

    return $sum;
}


function part2($input): int
{
    $map=[];
    foreach ($input as $line) {
        [$x1,$y1,$x2,$y2] = $line;

        if($x1 == $x2 || $y1 == $y2){
            foreach (range($y1, $y2) as $y) {
                foreach (range($x1, $x2) as $x) {
                    $key = $x . ',' . $y;
                    if(array_key_exists($key, $map))
                        $map[$key]++;
                    else
                        $map[$key] = 1;
                }
            }
        }
        else {
            $x_points = range($x1,$x2);
            $y_points = range($y1,$y2);
            for ($i = 0; $i < count($x_points); $i++) {
                $key = $x_points[$i] . ',' . $y_points[$i];
                if(array_key_exists($key, $map))
                    $map[$key]++;
                else
                    $map[$key] = 1;
            }
        }
    }
    $sum = 0;
    for ($i = 2; $i <= max($map); $i++) {
        $sum += array_count_values($map)[$i];
    }

    return $sum;
}

$parsed = parse_input();
echo "Part 1: " . part1($parsed) . PHP_EOL;
echo "Part 2: " . part2($parsed) . PHP_EOL;