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

function find_lowest_total_risk($start_x, $start_y, $stop_x, $stop_y, $map)
{
    $x_max = count($map[0]);
    $y_max = count($map);

    $risks = $map;
    for ($y = 0; $y < $y_max; $y++) {
        for ($x = 0; $x < $x_max; $x++) {
            $risks[$y][$x] = 100000;
        }
    }

    $map[$start_y][$start_x] = 0;
    $risks[$start_y][$start_x] = 0;

    $changed = true;
    while($changed) {
        $queue = [[$start_x,$start_y]];
        $visited = [];
        $changed = false;

        for ($i = 0; $i < count($queue); $i++) {
            [$x, $y] = $queue[$i];
            $key = $x . ',' . $y;
            if (array_key_exists($key, $visited))
                continue;
            $visited[$key] = 1;

            $adj_risks = [[$x + 1, $y], [$x, $y + 1], [$x - 1, $y], [$x, $y - 1]];
            foreach ($adj_risks as $adj_risk) {
                if ($adj_risk[0] < $x_max && $adj_risk[0] >= 0 && $adj_risk[1] < $y_max && $adj_risk[1] >= 0 ) {
                    $risk = $map[$adj_risk[1]][$adj_risk[0]] + $risks[$y][$x];
                    if ($risk < $risks[$adj_risk[1]][$adj_risk[0]]) {
                        $risks[$adj_risk[1]][$adj_risk[0]] = $risk;
                        $changed = true;
                    }
                    if(!(array_key_exists($adj_risk[0] . ',' . $adj_risk[1], $visited)))
                        array_push($queue, [$adj_risk[0], $adj_risk[1]]);
                }
            }


        }
    }
    return $risks[$stop_y][$stop_x];
}

function part1($map): int
{
    return find_lowest_total_risk(0,0, count($map[0]) - 1 , count($map) - 1, $map);
}

function part2($map): int
{
    $map2 = $map;
    $x_max = count($map[0]);
    $y_max = count($map);

    for ($y = 0; $y < $y_max; $y++) {
        for ($i = 0; $i < 5; $i++) {
            for ($x = 0; $x < $x_max; $x++) {
                $map2[$y][$x + $i * $x_max] = $map2[$y][$x] + $i;
                if($map2[$y][$x + $i * $x_max] > 9)
                    $map2[$y][$x + $i * $x_max] -= 9;
            }
        }
    }
    for ($i = 1; $i < 5; $i++) {
        for ($y = 0; $y < $y_max; $y++) {
            $temp = [];
            for ($x = 0; $x < $x_max * 5; $x++) {
                $a = $map2[$y][$x] + $i;
                if($a > 9)
                    $a -=9;
                array_push($temp, $a);
            }
            array_push($map2, $temp);
        }
    }
    return find_lowest_total_risk(0,0, count($map2[0]) - 1 , count($map2) - 1, $map2);
}

$map= parse_input();
echo "Part 1: " . part1($map) . PHP_EOL;
echo "Part 2: " . part2($map) . PHP_EOL;