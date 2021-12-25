<?php


include_once "../tools.php";

const TEST = false;
//const TEST = true;

$amphipods = ['A', 'B', 'C', 'D'];
$room_hallway_entry_map = [2, 4, 6, 8];
$cost_ = ['A' => 1, 'B' => 10, 'C' => 100, 'D' => 1000];

function move($rooms, $hallway = [], $cost = 0, &$min = 1000000, $room_size = 2)
{
    global $amphipods;
    global $room_hallway_entry_map;
    global $cost_;

    if ($cost > $min)
        return 0;

    $done = true;
    for ($i = 0; $i < 4; $i++) {
        for ($j = 0; $j < $room_size; $j++) {
            if($rooms[$i][$j] != $amphipods[$i])
                $done = false;
        }
    }
    if ($done) {
        if ($cost < $min)
            echo $cost . PHP_EOL;
        $min = min($cost, $min);
        return 0;
    }

    foreach ($rooms as $room_n => $room) {

        if($room[0] == 0) continue;

        // Check if the amphipods are already in their dest room
        $move = false;
        for ($i = 0; $i < $room_size; $i++) {
            if($room[$i] != $amphipods[$room_n] && $room[$i] != 0)
                $move = true;
        }
        if(!$move)
            continue;

        // Take the amphipod in the upper deck
        $deck = $room_size - 1;
        $step_start = 1;
        while ($room[$deck] == 0) {
            $deck--;
            $step_start++;
        }

        // Move right
        $steps = $step_start;
        for ($i = $room_hallway_entry_map[$room_n]; $i < 11; $i++) {
            if ($i == 2 || $i == 4 || $i == 6 || $i == 8) {
                $i++;
                $steps++;
            }
            if ($hallway[$i] != 0)
                break;

            $new_rooms = $rooms;
            $new_rooms[$room_n][$deck] = 0;
            $new_hallway = $hallway;
            $new_hallway[$i] = $room[$deck];
            move($new_rooms, $new_hallway, $cost + $steps * $cost_[$room[$deck]], $min, $room_size);

            $steps++;
        }

        // Move left
        $steps = $step_start;
        for ($i = $room_hallway_entry_map[$room_n]; $i >= 0; $i--) {
            if ($i == 2 || $i == 4 || $i == 6 || $i == 8) {
                $i--;
                $steps++;
            }
            if ($hallway[$i] != 0)
                break;

            $new_rooms = $rooms;
            $new_rooms[$room_n][$deck] = 0;
            $new_hallway = $hallway;
            $new_hallway[$i] = $room[$deck];
            move($new_rooms, $new_hallway, $cost + $steps * $cost_[$room[$deck]], $min, $room_size);
            $steps++;
        }

    }
    foreach ($hallway as $pos => $amphipod) {
        if ($amphipod != 0) {
            $ok1 = false;

            $nr = 0;
            $deck = -1;
            foreach ($amphipods as $room_nr => $pod) {
                if ($amphipod == $pod){
                    $ok1 = true;
                    $nr = $room_nr;
                    for ($i = 0; $i < $room_size; $i++) {
                        if($rooms[$room_nr][$i] != 0 && $rooms[$room_nr][$i] != $pod)
                            $ok1 = false;
                        if($rooms[$room_nr][$i] == $pod)
                            $deck = $i;
                    }
                }
            }
            $deck++;

            $ok2 = true;
            $a = range($pos, $room_hallway_entry_map[$nr]);
            foreach ($a as $p) {
                if ($p != $pos && $hallway[$p] != 0)
                    $ok2 = false;
            }

            if ($ok1 && $ok2) {
                $steps = abs($pos - $room_hallway_entry_map[$nr]) + 1;
                $new_hallway = $hallway;
                $new_rooms = $rooms;
                $new_hallway[$pos] = 0;

                $new_rooms[$nr][$deck] = $amphipod;
                $steps += $room_size - 1 - $deck;

                move($new_rooms, $new_hallway, $cost + $steps * $cost_[$amphipod], $min, $room_size);
            }
        }
    }
    return 0;
}

function part1($room): int
{
    $hallway = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,];
    $min = 100000;
    move($room, $hallway, 0, $min);
    return $min;
}

function part2($room): int
{
    $hallway = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
    $min = 1000000;
    move($room, $hallway, 0, $min, 4);
    return $min;

}

if (TEST) {
    $room = [['A', 'B'], ['D', 'C'], ['C', 'B'], ['A', 'D']];
    $room2 = [['A', 'D', 'D', 'B'], ['D', 'B', 'C', 'C'], ['C', 'A', 'B', 'B'], ['A', 'C', 'A', 'D']];
}
else {
    $room = [['B', 'C'], ['D', 'B'], ['A', 'D'], ['C', 'A']];
    $room2 = [['B', 'D', 'D', 'C'], ['D', 'B', 'C', 'B'], ['A', 'A', 'B', 'D'], ['C', 'C', 'A', 'A']];
}

$parsed = parse_input();
echo "Part 1: " . part1($room) . PHP_EOL;
echo "Part 2: " . part2($room2) . PHP_EOL;