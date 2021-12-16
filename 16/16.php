<?php
include_once "../tools.php";

const TEST = false;
//const TEST = true;

function parse_input() : string
{
    $filename = "input.txt";
    if (TEST)
        $filename = "test_input.txt";
    $lines = lines($filename);
    $bin_str = "";
    $hex_digits = str_split($lines[0]);
    foreach ($hex_digits as $hex_digit) {
        $bin_s = base_convert($hex_digit, 16, 2);
        $bin_str .= substr("0000{$bin_s}", -4);
    }
    return $bin_str;
}

function parse($bin_str, &$versions=[], &$offset = 0) : void
{
    $version = (int)base_convert(substr($bin_str, $offset, 3),2,10);
    $offset += 3;
    $versions[] = $version;

    $type_id = (int)base_convert(substr($bin_str,$offset,3), 2 ,10);
    $offset += 3;

    if($type_id == 4) {
        // Literal value
        $end = false;
        while (!$end) {
            $end = substr($bin_str, $offset, 1) == 0;
            $offset += 5;
        }
    }else{
        // Operator
        $length_type_id = substr($bin_str,$offset,1);
        $offset += 1;

        if($length_type_id == 1){
            // Next 11 bits are a number that represents the number of sub-packets immediately contained
            $number_of_sub_packets = (int)base_convert(substr($bin_str, $offset,11), 2, 10);
            $offset += 11;
            for ($i = 0; $i < $number_of_sub_packets; $i++)
                    parse($bin_str, $versions, $offset);
        }else{
            // Next 15 bits are a number that represents the total length in bits of the sub-packets contained
            $length_in_bits = (int)base_convert(substr($bin_str,$offset,15), 2, 10);
            $offset += 15;
            $end = $offset + $length_in_bits;
            while ($offset < $end)
                    parse($bin_str, $versions, $offset);
        }
    }
}



function parse2($bin_str, &$offset = 0) : int
{
    $version = (int)base_convert(substr($bin_str, $offset, 3),2,10);
    $offset += 3;

    $type_id = (int)base_convert(substr($bin_str,$offset,3), 2 ,10);
    $offset += 3;

    if($type_id == 4) {
        // Literal value
        $val = "";
        $end = false;
        while (!$end) {
            $end = substr($bin_str, $offset, 1) == 0;
            $offset++;
            $val .= substr($bin_str, $offset, 4);
            $offset += 4;
        }
        return (int)base_convert($val, 2, 10);
    }else{
        // Operator
        $length_type_id = substr($bin_str,$offset,1);
        $offset += 1;
        $sub_packets = [];
        if($length_type_id == 1){
            // Next 11 bits are a number that represents the number of sub-packets immediately contained
            $number_of_sub_packets = (int)base_convert(substr($bin_str, $offset,11), 2, 10);
            $offset += 11;
            for ($i = 0; $i < $number_of_sub_packets; $i++)
                $sub_packets[] = parse2($bin_str, $offset);
        }
        else{
            // Next 15 bits are a number that represents the total length in bits of the sub-packets contained
            $length_in_bits = (int)base_convert(substr($bin_str,$offset,15), 2, 10);
            $offset += 15;
            $end = $offset + $length_in_bits;
            while ($offset < $end)
                $sub_packets[] = parse2($bin_str, $offset);
        }

        if($type_id == 0)
            return array_sum($sub_packets);
        elseif ($type_id == 1)
            return array_product($sub_packets);
        elseif ($type_id == 2)
            return min($sub_packets);
        elseif ($type_id == 3)
            return max($sub_packets);
        elseif ($type_id == 5)
            return (int)($sub_packets[0] > $sub_packets[1]);
        elseif ($type_id == 6)
            return (int)($sub_packets[0] < $sub_packets[1]);
        elseif ($type_id == 7)
            return (int)($sub_packets[0] == $sub_packets[1]);
    }
    return 0;
}

function part1($bin_str): int
{
    $versions = [];
    parse($bin_str,$versions);
    return array_sum($versions);
}

function part2($bin_str): int
{
    $offset = 0;
    return parse2($bin_str, $offset);
}

$parsed = parse_input();
echo "Part 1: " . part1($parsed) . PHP_EOL;
echo "Part 2: " . part2($parsed) . PHP_EOL;