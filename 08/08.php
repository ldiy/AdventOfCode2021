<?php

include_once "../tools.php";

const TEST = false;
//const TEST = true;

function sort_strings_in_array(&$array) : void
{
    for ($i = 0; $i < count($array); $i++) {
        $temp = str_split($array[$i]);
        sort($temp);
        $array[$i] = implode('', $temp);
    }
}

function parse_input(): array
{
    $filename = "input.txt";
    if (TEST)
        $filename = "test_input.txt";
    $lines = lines($filename);

    $parsed = [];
    foreach ($lines as $line) {
        $a = explode(' | ',$line);
        $digits = explode(' ', $a[0]);
        $output = explode(' ', $a[1]);

        sort_strings_in_array($digits);
        sort_strings_in_array($output);

        array_push($parsed,[$digits, $output]);
    }

    return $parsed;
}

function contains_all_letters($subject, $letters): bool
{
    $ok = true;
    foreach (str_split($letters) as $letter) {
        if (substr_count($subject, $letter) == 0)
            $ok = false;
    }
    return $ok;
}

function part1($displays): int
{
    $result = 0;
    foreach ($displays as $display) {
        foreach ($display[1] as $segment) {
            if (strlen($segment) == 2 || strlen($segment) == 3 || strlen($segment) == 4 || strlen($segment) == 7 )
                $result++;
        }
    }
    return $result;
}

function part2($displays): int
{
    $result = 0;
    foreach ($displays as $display) {
        $map = [];

        // 1 4 7 8
        foreach ($display[0] as $key => $segment) {
            if (strlen($segment) == 2) {
                $map[1] = $segment;
                unset($display[0][$key]);
            }
            elseif (strlen($segment) == 3) {
                $map[7] = $segment;
                unset($display[0][$key]);
            }
            elseif (strlen($segment) == 4) {
                $map[4] = $segment;
                unset($display[0][$key]);
            }
            elseif (strlen($segment) == 7) {
                $map[8] = $segment;
                unset($display[0][$key]);
            }
        }

        // 3 6
        foreach ($display[0] as $key => $segment) {
            if (strlen($segment) == 6 && contains_all_letters($segment, $map[7]) == false) {
                $map[6] = $segment;
                unset($display[0][$key]);
            }

            if (strlen($segment) == 5 && contains_all_letters($segment, $map[7])) {
                $map[3] = $segment;
                unset($display[0][$key]);
            }
        }

        // 5 9
        foreach ($display[0] as $key => $segment) {
            if (strlen($segment) == 6 && contains_all_letters($segment, $map[3])) {
                $map[9] = $segment;
                unset($display[0][$key]);
            }

            if (strlen($segment) == 5 && contains_all_letters($map[6], $segment)) {
                $map[5] = $segment;
                unset($display[0][$key]);
            }
        }

        // 0 2
        foreach ($display[0] as $segment) {
            if (strlen($segment) == 5)
                $map[2] = $segment;

            if (strlen($segment) == 6)
                $map[0] = $segment;
        }

        $value = "";
        foreach ($display[1] as $segment)
            $value .= array_search($segment, $map);
        $result += (int)$value;

    }
    return $result;
}

$parsed = parse_input();
echo "Part 1: " . part1($parsed) . PHP_EOL;
echo "Part 2: " . part2($parsed) . PHP_EOL;