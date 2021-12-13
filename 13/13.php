<?php

include_once "../tools.php";

const TEST = false;
//const TEST = true;

function parse_input(&$points, &$folds)
{
    $filename = "input.txt";
    if (TEST)
        $filename = "test_input.txt";
    $lines = lines($filename);

    $points_done = false;
    foreach ($lines as $line) {
        if($line == '') {
            $points_done = true;
        }
        elseif($points_done){
            $f = regex_groups('/fold along ([xy])=([0-9]+)/',$line);
            array_push($folds, $f);
        } else {
            $points[$line] = explode(',', $line);
        }
    }

}

function fold_x(&$points, $x_line)
{
    $temp_points =[];
    foreach ($points as $key=>$point) {
        [$x,$y] = $point;
        if ($x > $x_line)
            $temp_points[2 * $x_line - $x . ',' . $y] = [2 * $x_line - $x, $y];
        else
            $temp_points[$key] = $point;
    }
    $points = $temp_points;

}

function fold_y(&$points, $y_line)
{
    $temp_points =[];
    foreach ($points as $key=>$point) {
        [$x,$y] = $point;
        if ($y > $y_line)
            $temp_points[$x . ',' . 2 * $y_line - $y] = [$x, 2 *  $y_line -  $y];
        else
            $temp_points[$key] = $point;
    }
    $points = $temp_points;

}

function part1($points, $folds): int
{
    [$dir, $val] = $folds[0];
    if($dir == 'x')
        fold_x($points, $val);
    else
        fold_y($points, $val);

    return count($points);
}

function print_points($points) {
    for($y = 0; $y < 6; $y++){
        for($x = 0; $x < 50; $x++){
            if (array_key_exists($x . ',' . $y, $points))
                echo '█';
            else
                echo ' ';
        }
        echo PHP_EOL;
    }
}

function part2($points, $folds) : void
{
    foreach ($folds as $fold) {
        [$dir, $val] = $fold;
        if($dir == 'x')
            fold_x($points, $val);
        else
            fold_y($points, $val);
    }
    print_points($points);
}

$points = [];
$folds = [];
parse_input($points, $folds);
echo "Part 1: " . part1($points, $folds) . PHP_EOL;
echo 'Part2:' . PHP_EOL;
part2($points, $folds);