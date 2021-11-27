<?php
include_once "../tools.php";

const TEST = false;

function parse_input()
{
    $filename = "input.txt";
    if (TEST)
        $filename = "test_input.txt";
    $lines = lines($filename);

    $parsed = [];
    foreach ($lines as $line) {

    }
    return $parsed;
}

function part1()
{
    $result = 0;
    $input = parse_input();

    return $result;
}

function part2()
{
    $result = 0;
    $input = parse_input();

    return $result;
}

echo "Part1: " . part1() . PHP_EOL;
echo "Part2: " . part2() . PHP_EOL;