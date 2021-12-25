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
    $l = 0;

    for ($i = 0; $i < 14; $i++) {
        array_push($parsed, [explode(' ',$lines[$l + 4])[2], explode(' ',$lines[$l + 5])[2], explode(' ', $lines[$l+ 15])[2]]);
        $l += 18;
    }
    return $parsed;
}

function run(&$z, $w, $p1, $p2, $p3) {
    $x = 0;
    $y = 0;
    $x += $z;
    $x %= 26;
    $z = (int)($z/$p1);
    $x += $p2;
    $x = $x == $w;
    $x = $x == 0;
    $y = 25;
    $y *= $x;
    $y++;
    $z *= $y;
    $y = $w;
    $y += $p3;
    $y *= $x;
    $z += $y;
}


function to_str(&$vals): string
{
    $str = "";
    for ($i = 0; $i < 14; $i++) {
        $str .= $vals[$i]['digit'];
    }
    return $str;
}

function part1($instructions): string
{
    $vals =[];

    for ($i = -1; $i < 14; $i++) {
        $vals[$i] = ['z' => 0, 'digit' => 9];
    }
    $i = 0;
    while($i < 14){
        [$a,$b,$c] = [$instructions[$i][0], $instructions[$i][1], $instructions[$i][2]];
        $z = $vals[$i - 1]['z'];
        if($b < 0) {
            $digit = ($z % 26) + $b;
            if ($digit > 9 || $digit < 1){
                // Backtrack
                $i--;
                $vals[$i]['digit']--;
                continue;
            }
        }
        else{
            $digit = $vals[$i]['digit'];
            if($digit < 1) {
                $vals[$i]['digit'] = 9;
                // Backtrack
                $i--;
                $vals[$i]['digit']--;
                continue;
            }
        }

        $vals[$i]['digit'] = $digit;
        run($z,$digit,$a,$b,$c);
        $vals[$i]['z'] = $z;
        $i++;
    }
    return to_str($vals);
}

function part2($instructions): string
{
    $vals =[];

    for ($i = -1; $i < 14; $i++) {
        $vals[$i] = ['z' => 0, 'digit' => 1, 'type' => 0];
    }
    $i = 0;
    while($i < 14){
        [$a,$b,$c] = [$instructions[$i][0], $instructions[$i][1], $instructions[$i][2]];
        $z = $vals[$i - 1]['z'];
        if($b < 0) {
            $digit = ($z % 26) + $b;
            if ($digit > 9 || $digit < 1){
                // Backtrack
                $i--;
                while($vals[$i]['type']) $i--;
                $vals[$i]['digit']++;
                continue;
            }
            $vals[$i]['type'] = 1;
        }
        else{
            $digit = $vals[$i]['digit'];
            if($digit > 9) {
                $vals[$i]['digit'] = 1;
                // Backtrack
                $i--;
                while($vals[$i]['type']) $i--;
                $vals[$i]['digit']++;
                continue;
            }
            $vals[$i]['type'] = 0;
        }

        $vals[$i]['digit'] = $digit;
        run($z,$digit,$a,$b,$c);
        $vals[$i]['z'] = $z;
        $i++;
    }

    return to_str($vals);
}

$parsed = parse_input();
echo "Part 1: " . part1($parsed) . PHP_EOL;
echo "Part 2: " . part2($parsed) . PHP_EOL;