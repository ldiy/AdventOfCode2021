<?php

include_once "../tools.php";

const TEST = false;
//const TEST = true;

class Number{
    public int $val;
    public Number $left;
    public Number $right;
    public Number $parent;
    public bool $is_regular = false;
    public bool $has_parent = false;
    public bool $removed = false;

    public function __construct() {

    }

    public function set_left(Number $left) {
        $this->left = $left;
        $this->left->set_parent($this);
    }

    public function set_right(Number $right) {
        $this->right = $right;
        $this->right->set_parent($this);
    }

    public function set_regular(int $number) {
        $this->is_regular = true;
        $this->val = $number;
    }

    public function set_parent(Number &$number) {
        $this->parent = &$number;
        $this->has_parent = true;
    }

    public function is_regular() : bool
    {
        return $this->is_regular;
    }

    public function get_depth() :int
    {
        $i = 0;
        $temp = $this;
        while ($temp->has_parent){
            $temp = $temp->parent;
            $i++;
        }
        return $i;
    }

    public function str() {
        if($this->is_regular)
            return $this->val;
        else{
            return "[" . $this->left->str() . "," . $this->right->str() . "]";
        }

    }

}

function to_number_obj($number) : Number
{
    $number_obj = new Number();
    if (gettype($number) ==  "integer") {
        $number_obj->set_regular($number);
    }else {
        $number_obj->set_left(to_number_obj($number[0]));
        $number_obj->set_right(to_number_obj($number[1]));
    }

    return $number_obj;
}

function parse_input(): array
{
    $filename = "input.txt";
    if (TEST)
        $filename = "test_input.txt";
    $lines = lines($filename);

    $arr = [];
    foreach ($lines as $line) {
        $a = [];
        eval("\$a = " . $line .";");
        array_push($arr, to_number_obj($a));
    }
    return $arr;
}

function parse_input_array(): array
{
    $filename = "input.txt";
    if (TEST)
        $filename = "test_input.txt";
    $lines = lines($filename);

    $arr = [];
    foreach ($lines as $line) {
        $a = [];
        eval("\$a = " . $line .";");
        array_push($arr, $a);
    }
    return $arr;
}

function explode_f(Number &$number) {
    $tmp = $number;
    while ($tmp->has_parent) {
        if ($tmp->parent->left !== $tmp) {
            $tmp = $tmp->parent->left;
            if(!$tmp->is_regular) {
                while (!$tmp->right->is_regular) {
                    $tmp = $tmp->right;
                }
                $tmp = $tmp->right;
            }
            $tmp->val += $number->left->val;
            break;
        }
        $tmp = $tmp->parent;
    }

    $tmp = $number;
    while ($tmp->has_parent){
        if ($tmp->parent->right !== $tmp) {
            $tmp = $tmp->parent->right;
            if(!$tmp->is_regular) {
                while (!$tmp->left->is_regular) {
                    $tmp = $tmp->left;
                }
                $tmp = $tmp->left;
            }
            $tmp->val += $number->right->val;
            break;
        }
        $tmp = $tmp->parent;
    }
    $number->set_regular(0);

    $number->left->removed = true;
    $number->right->removed = true;
}

function split(Number &$number) {
    $number->is_regular = false;
    $left = new Number();
    $right = new Number();
    $left->set_regular(floor($number->val / 2));
    $right->set_regular(ceil($number->val / 2));
    $number->set_left($left);
    $number->set_right($right);
}

function reduce_expl(Number &$number) : bool {
    if($number->get_depth() == 4 && !$number->is_regular) {
        explode_f($number);
        return true;
    }

    if(!$number->is_regular()) {
        if (reduce_expl($number->left)) return true;
        if (reduce_expl($number->right)) return true;
    }

    return false;
}

function reduce_split(Number &$number) : bool {
    if($number->is_regular() && $number->val >= 10){
        split($number);
        return true;
    }

    if(!$number->is_regular()) {
        if (reduce_split($number->left)) return true;
        if (reduce_split($number->right)) return true;
    }

    return false;
}

function reduce(Number &$number){
    $c = true;
    while ($c) {
        while (reduce_expl($number));
        $c = reduce_split($number);
    }
}

function magnitude(&$number){
    if($number->is_regular)
        return $number->val;

    $a = 0;
    $a += 3* magnitude($number->left);
    $a += 2* magnitude($number->right);
    return $a;
}

function part1($numbers): int
{
    $sum = $numbers[0];
    for ($i = 1; $i < count($numbers); $i++) {
        $new_sum = new Number();
        $new_sum->set_left($sum);
        $new_sum->set_right($numbers[$i]);
        $sum = $new_sum;
        reduce($sum);
    }
    return magnitude($sum);
}

function sum(Number $number1, Number $number2){
    $sum = new Number();
    $sum->set_left($number1);
    $sum->set_right($number2);
    reduce($sum);
    return magnitude($sum);
}

function part2($numbers): int
{
    $max = 0;
    foreach ($numbers as $key1=>$number1)
        foreach ($numbers as $key2=>$number2)
            if($key1 != $key2)
                $max = max($max, sum(to_number_obj($number1), to_number_obj($number2)));

    return $max;
}

echo "Part 1: " . part1(parse_input()) . PHP_EOL;
echo "Part 2: " . part2(parse_input_array()) . PHP_EOL;