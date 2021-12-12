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
        [$cave1, $cave2] = explode('-',$line);

        if(array_key_exists($cave1, $parsed))
            array_push($parsed[$cave1], $cave2);
        else
            $parsed[$cave1] = [$cave2];

        if(array_key_exists($cave2, $parsed))
            array_push($parsed[$cave2], $cave1);
        else
            $parsed[$cave2] = [$cave1];
    }
    return $parsed;
}

function find_paths(&$web, $node, $visited=[]): int
{
    if ($node == 'end')
        return 1;

    if ($node == strtolower($node)) {
        if (array_key_exists($node, $visited))
            return 0;
        $visited[$node] = 1;
    }

    $adj_nodes = $web[$node];
    $number_of_paths = 0;
    foreach ($adj_nodes as $adj_node) {
        $number_of_paths += find_paths($web, $adj_node, $visited);
    }
    return $number_of_paths;
}


function find_paths_2(&$web, $node, $visited=[], $visited_twice=false): int
{
    if ($node == 'end')
        return 1;

    if ($node == strtolower($node)) {
        $already_visited = array_key_exists($node, $visited);

        if (($visited_twice && $already_visited) || ($already_visited && $node == 'start'))
                return 0;
        elseif ($already_visited)
            $visited_twice = true;

        $visited[$node] = 1;
    }

    $adj_nodes = $web[$node];
    $number_of_paths = 0;
    foreach ($adj_nodes as $adj_node) {
        $number_of_paths += find_paths_2($web, $adj_node, $visited, $visited_twice);
    }

    return $number_of_paths;
}

function part1($web): int
{
    return find_paths($web, 'start');
}

function part2($web): int
{
    return find_paths_2($web, 'start');
}

$parsed = parse_input();
echo "Part 1: " . part1($parsed) . PHP_EOL;
echo "Part 2: " . part2($parsed) . PHP_EOL;