<?php

include_once "../tools.php";

const TEST = false;
//const TEST = true;

function parse_input(&$algorithm): array
{
    $filename = "input.txt";
    if (TEST)
        $filename = "test_input.txt";
    $lines = lines($filename);

    $algorithm = str_split(str_replace(['#', '.'], [1,0],$lines[0]));
    $img = [];
    for ($i = 2; $i < count($lines); $i++) {
        $img[] = str_split(str_replace(['#', '.'], [1, 0], $lines[$i]));
    }
    return $img;
}

function count_lit(&$img): int
{
    $i = 0;
    foreach ($img as $img_row) {
        foreach ($img_row as $pixel) {
            if ($pixel == 1)
                $i++;
        }
    }
    return $i;
}

function surrounding_to_int(&$img, $x, $y, $outside_val): int
{
    $val = 0;
    $pixels = [[$x-1, $y-1], [$x, $y-1], [$x+1, $y-1],[$x-1, $y], [$x, $y], [$x+1, $y], [$x-1, $y+1], [$x, $y+1], [$x+1, $y+1]];
    foreach ($pixels as $pixel) {
        if(array_key_exists($pixel[1], $img) && array_key_exists($pixel[0], $img[$pixel[1]])){
            $val = ($val << 1) | (int)$img[$pixel[1]][$pixel[0]];
        }
        else{
            $val = ($val << 1) | (int)$outside_val;
        }
    }
    return $val;
}

function enhance(&$img, &$algorithm, &$outside_val) {
    $enhanced = [];
    for ($y = array_key_first($img) - 3; $y < array_key_last($img) + 3; $y++) {
        $enhanced[$y] = [];
        for ($x = array_key_first($img[0]) - 3; $x < array_key_last($img[0]) + 3; $x++) {
            $enhanced[$y][$x] = $algorithm[surrounding_to_int($img, $x, $y, $outside_val)];
        }
    }

    $img = $enhanced;
    if($outside_val == 1)
        $outside_val = $algorithm[511];
    else
        $outside_val = $algorithm[0];
}

function part1($img, $algorithm): int
{
    $outside_val = 0;
    enhance($img,$algorithm, $outside_val);
    enhance($img,$algorithm, $outside_val);

    return count_lit($img);
}

function part2($img, $algorithm): int
{
    $outside_val = 0;
    for ($i = 0; $i < 50; $i++) {
        enhance($img,$algorithm, $outside_val);
    }
    return count_lit($img);
}

$algorithm = [];
$img = parse_input($algorithm);

echo "Part 1: " . part1($img, $algorithm) . PHP_EOL;
echo "Part 2: " . part2($img, $algorithm) . PHP_EOL;