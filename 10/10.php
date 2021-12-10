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

$part2_points = ['(' => 1, '[' => 2, '{' => 3,'<' => 4];

$lines = parse_input();
$part1 = 0;
$scores = [];
foreach ($lines as $chunks) {
    $stack = [];
    $corrupted = false;
    foreach ($chunks as $chunk) {
        if ($chunk == '(' || $chunk == '{' || $chunk == '[' || $chunk == '<')
            array_push($stack, $chunk);
        else {
            $open = array_pop($stack);
            if ($open != '(' && $chunk == ')') {
                $part1 += 3;
                $corrupted = true;
                break;
            }
            elseif ($open != '[' && $chunk == ']') {
                $part1 += 57;
                $corrupted = true;
                break;
            }
            elseif ($open != '{' && $chunk == '}') {
                $part1 += 1197;
                $corrupted = true;
                break;
            }
            elseif ($open != '<' && $chunk == '>') {
                $part1 += 2537;
                $corrupted = true;
                break;
            }
        }
    }

    if (!$corrupted) {
        $score = 0;
        for ($i = count($stack) - 1; $i >= 0 ; $i--) {
            $score *= 5;
            $score += $part2_points[$stack[$i]];
        }
        array_push($scores, $score);
    }
}

sort($scores);
$part2 = $scores[floor(count($scores) / 2)];

echo "Part 1: " . $part1 . PHP_EOL;
echo "Part 2: " . $part2 . PHP_EOL;