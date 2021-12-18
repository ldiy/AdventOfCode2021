<?php

include_once "../tools.php";

const TEST = false;
//const TEST = true;

$filename = "input.txt";
if (TEST)
    $filename = "test_input.txt";
$line = lines($filename)[0];
$tmp = explode('x=', $line);
$tmp = explode(', y=', $tmp[1]);
[$xmin, $xmax] = explode('..', $tmp[0]);
[$ymin, $ymax] = explode('..', $tmp[1]);


$max_height = 0;
$count = 0;
$y_m = max(abs($ymin), abs($ymax));
for ($yvel = -1 * $y_m; $yvel < $y_m; $yvel++) {
    for ($xvel = 0; $xvel < $xmin + $xmax; $xvel++) {
        $xv = $xvel;
        $yv = $yvel;
        $x = 0;
        $y = 0;
        $max_height_t = 0;
        for ($i = 0; $i < $xmax - $ymin; $i++) {
            $x += $xv;
            $y += $yv;
            if($xv > 0)
                $xv--;
            elseif ($xv < 0)
                $xv++;
            $yv -= 1;
            $max_height_t = max($max_height_t, $y);
            if($x <= $xmax && $x >= $xmin && $y <= $ymax && $y >= $ymin){
                $max_height = max($max_height_t, $max_height);
                $count++;
                break;
            }
            if($x > $xmax)
                break;
        }
    }
}

echo "Part 1: " . $max_height . PHP_EOL;
echo "Part 2: " . $count . PHP_EOL;