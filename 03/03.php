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
        array_push($parsed, str_split($line));
    }
    return $parsed;
}

function most_common_bit($numbers , $i) : int
{
    $sum = 0;
    foreach ($numbers as $number) {
        $sum += $number[$i];
    }
    if ($sum == count($numbers) / 2)
        return 1;
    return round($sum / count($numbers));
}

function get_oxygen_generator_rating($numbers, $i) : array
{
    if(count($numbers) == 1)
        return $numbers[0];

    $mc = most_common_bit($numbers, $i);
    $new = [];
    foreach ($numbers as $number) {
        if($number[$i] == $mc)
            array_push($new, $number);
    }
    return get_oxygen_generator_rating($new, $i+1);
}

function get_CO2_scrubber_rating($numbers , $i) : array
{
    if(count($numbers) == 1)
        return $numbers[0];

    $lc = !most_common_bit($numbers, $i);
    $new = [];
    foreach ($numbers as $number) {
        if($number[$i] == $lc)
            array_push($new, $number);
    }
    return get_CO2_scrubber_rating($new, $i+1);
}

function bin_str_to_dec($str): int
{
    $dec = 0;
    for ($i = 0; $i < count($str); $i++) {
        $dec = ($dec << 1) | $str[$i];
    }
    return $dec;
}

function part1($numbers): int
{
    $result = 0;
    for ($i = 0; $i < count($numbers[0]); $i++) {
        $result = ($result << 1) | most_common_bit($numbers, $i);
    }
    return $result * ((~$result) & pow(2,count($numbers[0])) - 1);
}

function part2($numbers): int
{
    $oxygen_generator_rating = bin_str_to_dec(get_oxygen_generator_rating($numbers, 0));
    $CO2_scrubber_rating = bin_str_to_dec(get_CO2_scrubber_rating($numbers, 0));
    return $oxygen_generator_rating * $CO2_scrubber_rating;
}

$parsed = parse_input();
echo "Part 1: " . part1($parsed) . PHP_EOL;
echo "Part 2: " . part2($parsed) . PHP_EOL;