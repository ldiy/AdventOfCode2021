<?php

include_once "../tools.php";

const TEST = false;
//const TEST = true;

function parse_input(&$template): array
{
    $filename = "input.txt";
    if (TEST)
        $filename = "test_input.txt";
    $lines = lines($filename);
    $template = str_split($lines[0]);
    $parsed = [];
    for ($i = 2; $i < count($lines); $i++) {
        $a = explode(' -> ',$lines[$i]);
        $parsed[$a[0]] = $a[1];
    }
    return $parsed;
}

function calc_polymer($rules, $template, $steps){
    $polymer = [];
    $letter_count = [];

    for($i = 0; $i < count($template) - 1; $i++) {
        $key = $template[$i] . $template[$i+1];
        if(array_key_exists($key, $polymer))
            $polymer[$key]++;
        else
            $polymer[$key] = 1;
    }

    for($i = 0; $i < count($template); $i++) {
        if (array_key_exists($template[$i], $letter_count))
            $letter_count[$template[$i]] += 1;
        else
            $letter_count[$template[$i]] = 1;
    }

    for ($i = 0; $i < $steps; $i++) {
        $new_polymer = $polymer;
        foreach ($polymer as $pair => $count) {
            if ($count > 0) {
                $between = $rules[$pair];
                $new_keys = [$pair[0] . $between, $between . $pair[1]];
                foreach ($new_keys as $new_key) {
                    if (array_key_exists($new_key, $new_polymer))
                        $new_polymer[$new_key] += $count;
                    else
                        $new_polymer[$new_key] = $count;
                }
                $new_polymer[$pair]-=$count;

                if (array_key_exists($between, $letter_count))
                    $letter_count[$between] += $count;
                else
                    $letter_count[$between] = $count;

            }
        }
        $polymer = $new_polymer;
    }
    return max($letter_count)  - min($letter_count);
}

$template = [];
$parsed = parse_input($template);
echo "Part 1: " . calc_polymer($parsed, $template, 10) . PHP_EOL;
echo "Part 2: " . calc_polymer($parsed, $template, 40) . PHP_EOL;