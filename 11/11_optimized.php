<?php
$lines = file("input.txt", FILE_IGNORE_NEW_LINES);

$octopuses = [];
foreach ($lines as $line) {
    array_push($octopuses, str_split($line));
}

$steps = 0;
$flashes_tot = 0;
$flashes = 0;
$part1 = 0;
$part2 = 0;
while ($part1 == 0 || $part2 == 0) {
    // Check part 1
    if ($steps == 100)
        $part1 = $flashes_tot;

    $flashes = 0;

    // Increment
    for ($y = 0; $y < 10; $y++)
        for ($x = 0; $x < 10; $x++)
            $octopuses[$y][$x]++;

    // Flashes
    $queue = [];
    for ($y = 0; $y < 10; $y++) {
        for ($x = 0; $x < 10; $x++) {
            if ($octopuses[$y][$x] > 9) {
                $octopuses[$y][$x] = 'X';
                $flashes++;
                $neighbours = [[$x,$y-1], [$x+1,$y-1], [$x+1,$y], [$x+1,$y+1], [$x,$y+1], [$x-1,$y+1], [$x-1,$y], [$x-1,$y-1]];
                foreach ($neighbours as $neighbour) {
                    if ($neighbour[0] >=0 && $neighbour[0] < 10 && $neighbour[1] >= 0 && $neighbour[1] < 10 && $octopuses[$neighbour[1]][$neighbour[0]] != 'X')
                        array_push($queue, $neighbour);
                }
            }
        }
    }

    for ($i = 0; $i < count($queue); $i++) {
        [$x, $y] = $queue[$i];
        if ($octopuses[$y][$x] != 'X') {
            $octopuses[$y][$x]++;
            if ($octopuses[$y][$x] > 9) {
                $octopuses[$y][$x] = 'X';
                $flashes++;

                $neighbours = [[$x,$y-1], [$x+1,$y-1], [$x+1,$y], [$x+1,$y+1], [$x,$y+1], [$x-1,$y+1], [$x-1,$y], [$x-1,$y-1]];
                foreach ($neighbours as $neighbour) {
                    if ($neighbour[0] >=0 && $neighbour[0] < 10 && $neighbour[1] >= 0 && $neighbour[1] < 10 && $octopuses[$neighbour[1]][$neighbour[0]] != 'X')
                        array_push($queue, $neighbour);
                }
            }
        }
    }

    // Reset energy
    for ($y = 0; $y < 10; $y++)
        for ($x = 0; $x < 10; $x++)
            if ($octopuses[$y][$x] == 'X')
                $octopuses[$y][$x] = 0;

    $steps++;

    // Check part 2
    if ($flashes == 100)
        $part2 = $steps;

    $flashes_tot += $flashes;
}

echo "Part 1: " . $part1 . PHP_EOL;
echo "Part 2: " . $part2 . PHP_EOL;