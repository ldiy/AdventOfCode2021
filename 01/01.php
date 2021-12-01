<?php
include_once "../tools.php";

const TEST = false;
//const TEST = true;

function parse_input(): array
{
    $filename = "input.txt";
    if (TEST)
        $filename = "test_input.txt";
    return lines($filename);
}

function part1(): int
{
    $result = 0;
    $input = parse_input();
    $prev = 0;
    foreach ($input as $line) {
        if( $line > $prev)
            $result++;
        $prev = $line;
    }

    return $result-1;
}

function part2(): int
{
    $result = 0;
    $input = parse_input();
    $prev = 0;
    for ($i = 0; $i < sizeof($input) - 2; $i++) {
        $sum = array_sum(array_slice($input, $i, 3));
        if($sum > $prev)
            $result++;
        $prev = $sum;
    }
    return $result - 1;
}

echo "Part1: " . part1() . PHP_EOL;
echo "Part2: " . part2() . PHP_EOL;