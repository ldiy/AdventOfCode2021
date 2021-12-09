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

function part1($map, &$low_points): int
{
    $result = 0;
    $low_points = [];
    for ($y = 0; $y < count($map); $y++)
        for ($x = 0; $x < count($map[0]); $x++)
            if(check_neighbours($map, $x, $y)) {
                array_push($low_points, [$x, $y]);
                $result += $map[$y][$x] + 1;
            }

    return $result;
}

function part2($map, &$low_points): int
{
    $sizes = [];
    $queue = new \Ds\Queue();
    foreach ($low_points as $low_point) {
        $queue->push([$low_point[0],$low_point[1]]);
        $queue->allocate(256);
        $size = 0;
        while(!$queue->isEmpty()) {
            [$x,$y] = $queue->pop();
            if ($map[$y][$x] == 9 || $map[$y][$x] == 'X')
                continue;

            $size++;
            $map[$y][$x] = 'X';

            if ($y != 0)
                $queue->push([$x, $y - 1]);
            if ($y != count($map) - 1)
                $queue->push([$x, $y + 1]);

            if ($x != 0)
                $queue->push([$x - 1, $y]);
            if ($x != count($map[0]) - 1)
                $queue->push([$x + 1, $y]);
        }

        array_push($sizes,$size);
    }
    rsort($sizes);
    return $sizes[0] * $sizes[1] * $sizes[2];
}

$parsed = parse_input();
$low_points = [];
echo "Part 1: " . part1($parsed, $low_points) . PHP_EOL;
echo "Part 2: " . part2($parsed, $low_points) . PHP_EOL;