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
        $a = explode(' ',$line);
        array_push($parsed,$a);
    }
    return $parsed;
}

function part1($instructions): int
{
    $hpos = 0;
    $depth = 0;
    foreach ($instructions as $instruction) {
        if ($instruction[0] == "forward")
            $hpos += $instruction[1];
        elseif ($instruction[0] == "down")
            $depth += $instruction[1];
        elseif ($instruction[0] == "up")
            $depth -= $instruction[1];
    }
    return $hpos * $depth;
}

function part2($instructions): int
{
    $hpos = 0;
    $depth = 0;
    $aim = 0;
    foreach ($instructions as $instruction) {
        if ($instruction[0] == "forward") {
            $hpos += $instruction[1];
            $depth += $instruction[1] * $aim;
        }
        elseif ($instruction[0] == "down")
            $aim += $instruction[1];
        elseif ($instruction[0] == "up")
            $aim -= $instruction[1];
    }
    return $hpos * $depth;
}

$parsed = parse_input();
echo "Part 1: " . part1($parsed) . PHP_EOL;
echo "Part 2: " . part2($parsed) . PHP_EOL;