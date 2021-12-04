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

    $parsed = [[],[]];
    $parsed[0] = explode(',', $lines[0]);

    for ($i = 2; $i < count($lines); $i++) {
        $board = [];
        while(array_key_exists($i, $lines) && $lines[$i] != "")
            array_push($board, preg_split('/\s+/', $lines[$i++], -1, PREG_SPLIT_NO_EMPTY));
        array_push($parsed[1], $board);
    }
    return $parsed;
}

function check_h(array $board): bool
{
    foreach ($board as $line) {
        $cnt = array_count_values($line);
        if(array_key_exists('X', $cnt) && $cnt['X'] == count($line))
            return true;
    }
    return false;
}

function check_v(array $board): bool
{
    for ($i = 0; $i < count($board[0]); $i++) {
        $ok = true;
        for($y = 0; $y < count($board); $y++)
            if($board[$y][$i] != 'X')
                $ok = false;
        if($ok)
            return true;
    }
    return false;
}

function mark_boards(array $boards, int $b, int $number): array
{
    for ($y = 0; $y < count($boards[$b]); $y++)
        for ($x = 0; $x < count($boards[$b][$y]); $x++)
            if ($boards[$b][$y][$x] == $number)
                $boards[$b][$y][$x] = 'X';
    return $boards;
}

function calc_score(array $boards, int $number): int
{
    $sum = 0;
    for ($i = 0; $i < count($boards); $i++)
        for ($x = 0; $x < count($boards[$i]); $x++)
            if ($boards[$i][$x] != 'X')
                $sum += $boards[$i][$x];
    return $sum * $number;
}


function part1(array $input): int
{
    [$numbers, $boards] = $input;
    foreach ($numbers as $number) {
        for ($b = 0; $b < count($boards); $b++) {
            $boards = mark_boards($boards, $b, $number);
            if(check_v($boards[$b]) || check_h($boards[$b]))
                return calc_score($boards[$b], $number);
        }
    }
    return 0;
}

function part2(array $input): int
{
    [$numbers, $boards] = $input;
    $won = [];
    foreach ($numbers as $number) {
        for ($b = 0; $b < count($boards); $b++) {
            $boards = mark_boards($boards, $b, $number);
            if(check_v($boards[$b]) || check_h($boards[$b])) {
                array_push($won, $b);
                if(count(array_count_values($won)) == count($boards))
                    return calc_score($boards[$b], $number);
            }
        }
    }
    return 0;
}

$parsed = parse_input();
echo "Part 1: " . part1($parsed) . PHP_EOL;
echo "Part 2: " . part2($parsed) . PHP_EOL;