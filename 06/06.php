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

    return explode(',', $lines[0]);
}

function calc_growth($timers, $day_count) : int
{
    $days = [0,0,0,0,0,0,0,0,0];
    foreach ($timers as $timer) {
        $days[$timer]++;
    }

    for($y = 0; $y < $day_count; $y++) {
        $days_new = [0,0,0,0,0,0,0,0,0];
        for ($i = 8; $i >= 0; $i--) {
            if ($i == 0) {
                $days_new[6] += $days[0];
                $days_new[8] += $days[0];
            } else {
                $days_new[$i - 1] += $days[$i];
            }

        }
        $days = $days_new;
    }

    return array_sum($days);
}

function part1($timers): int
{
    return calc_growth($timers, 80);
}

function part2($timers): int
{
    return calc_growth($timers, 256);
}

$parsed = parse_input();
echo "Part 1: " . part1($parsed) . PHP_EOL;
echo "Part 2: " . part2($parsed) . PHP_EOL;